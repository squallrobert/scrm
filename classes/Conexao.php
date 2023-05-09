<?php

/**
 * @author Squall Robert
 * @copyright 2011
 */

class Conexao extends PDO
{


    private $usuario_banco = USERDB;
    private $senha_banco = PASSDB;
    private $servidore_banco = HOSTDB;
    private $base_dados = DATABASE;
    private $porta_banco = PORTDB;



    public  $erro;
    public $stmt;
    public function __construct()
    {
        try
        {
            $pdo = parent::__construct("mysql:host={$this->servidore_banco};port={$this->porta_banco};dbname={$this->base_dados}", "$this->usuario_banco", "$this->senha_banco");
            parent::exec("SET CHARACTER SET utf8");
            // mostra erros
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            parent::setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
            parent::setAttribute(PDO::ATTR_ORACLE_NULLS,PDO::NULL_EMPTY_STRING);
            parent::setAttribute(PDO::ATTR_TIMEOUT, 10 );

            // esconde erros
            //parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $this->stmt = $pdo;

            $this->erro = parent::errorInfo();
            $pdo = null;
            return $this->stmt;
        }
        catch(PDOException $e)
        {
            echo 'Error: '.$e->getMessage();
			return false;
        }
    }
    function __destruct() {
        $this->stmt = NULL;
    }

    public static function PrepararDataBDIdFuso($data, $fuso_horario = null, $formatoSaida = "Y-m-d H:i:s" )
    {
        list($data, $hora) = explode(" ", $data);
        $data = explode("/", $data);

        if(isset($fuso_horario))
        {
            if( is_numeric($fuso_horario) )
                $fuso_horario = FusoHorario::Editar($fuso_horario);

            $hora = explode(":", $hora);
            $data = date($formatoSaida, mktime($hora[0] - $fuso_horario['multiplicador'] * $fuso_horario['gmt_hora'], $hora[1] - $fuso_horario['multiplicador'] * $fuso_horario['gmt_minuto'], $hora[2], $data[1], $data[0], $data[2]));
        }
        else
        {
            $data = $data[2]."-".$data[1]."-".$data[0]." ".$hora;
        }

        return $data;
    }
    
    public static function PrepararDataBD($data, $fuso_horario = "America/Bahia", $formatoSaida = "Y-m-d H:i:s" )
    {
    	if ($data == "") {
    		return "";
		}

		if (strpos($data, "-") !== false) {
			return $data;
		}

		list($data, $hora) = explode(" ", $data);
		$data = explode("/", $data);
		$data = $data[2]."-".$data[1]."-".$data[0]." ".$hora;
        if ($fuso_horario == NULL || $fuso_horario == "") {
			return $data;
		}

        $datetime = new DateTime($data, new DateTimeZone($fuso_horario));
		$datetime->setTimezone(new DateTimeZone("America/Bahia"));
		return $datetime->format($formatoSaida);
    }
    public static function DataHoraGMTPHP()
    {
        return gmdate("d/m/Y H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));
    }
    public static function DataHoraGMTDB()
    {
        return gmdate("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y')));
    }
    public static function PrepararDataPHP( $data, $fuso_horario = "America/Bahia", $formatoSaida = "d/m/Y H:i:s" )
    {
    	if ($data == "") {
    		return "";
		}

		if (strpos($data, "/") !== false) {
			return $data;
		}

		if ($data == "") {
			return "";
		}

		list($auxData, $auxHora) = explode(" ", $data);

		if ($auxHora == "") {
			$data = explode("-", $data);
			$data = $data[2]."/".$data[1]."/".$data[0];

			if ($auxHora != "") {
				$data .= " " . $auxHora;
			}
			return $data;
		}
		if ($fuso_horario == NULL || $fuso_horario == "" || $fuso_horario == 23) {
			$fuso_horario = "America/Bahia";
		}

		$datetime = new DateTime($data, new DateTimeZone("America/Bahia"));
		$datetime->setTimezone(new DateTimeZone($fuso_horario));
        return $datetime->format($formatoSaida);
    }

    public function ListarTabelasConexao( $parametros = array() )
    {
        $pdo = new Conexao();

        $sql = "SHOW TABLES LIKE 'posicao_%'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($rs) > 0)
        {
            $retorno = array();
            foreach($rs as $row)
                array_push($retorno,array("nome_tabela" => $row['tables_in_webcop (posicao_%)']));
        }
        return $retorno;
    }

    //Data e hora padrao brasileiro dia/mes/ano
    public function DeterminarTabelasConexao($dataHoraInicio, $dataHoraFim, $idFusoHorario, $parametros = array(),$antigo = 1)
    {
        $pdo = new Conexao();
		if (strpos($dataHoraInicio, "/") !== false)
        	$dataHoraInicio = $pdo->PrepararDataBD($dataHoraInicio, $idFusoHorario );

		if (strpos($dataHoraFim, "/") !== false)
        	$dataHoraFim = $pdo->PrepararDataBD($dataHoraFim, $idFusoHorario );

        list($data, $hora) = explode(" ", $dataHoraInicio);
        list($ano, $mes, $dia) = explode("-", $data);
        list($hora, $minuto, $segundo) = explode(":", $hora);
        $timestampInicial = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);

        list($data, $hora) = explode(" ", $dataHoraFim);
        list($ano, $mes, $dia) = explode("-", $data);
        list($hora, $minuto, $segundo) = explode(":", $hora);
        $timestampFinal = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);


        $tabelasExistentes = $this->ListarTabelasConexao( $parametros );

        $qtdTabelasExistentes = count($tabelasExistentes);
        for( $i = 0; $i < $qtdTabelasExistentes; $i++ )
            $tabelasExistentes[$i] = $tabelasExistentes[$i]['nome_tabela'];

        $i = $timestampInicial;
        $datas = array();
        while($i <= $timestampFinal)
        {
            $tabelaPosicao = "posicao_".date("Ymd", $i);

            if( @array_search($tabelaPosicao, $tabelasExistentes) !== false )
                $datas[$tabelaPosicao] = $tabelaPosicao;

            if($i >= $timestampFinal)
                break;

            if(($timestampFinal - $i) > (60*60*24))
                $i+=(60*60*24);
            else
                $i+=($timestampFinal-$i);

        }
        return $datas;
    }

    public function ListarTabelasTemperatura()
    {
        $pdo = new Conexao();

        $sql = "SHOW TABLES LIKE 'telemetria_temperatura_%'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($rs)>0)
        {
            $retorno = array();
            foreach($rs as $row)
                array_push($retorno,array("nome_tabela" => $row['tables_in_webcop (telemetria_temperatura_%)']));
        }
        return $retorno;
    }

    public function DeterminarTabelasTemperatura($dataHoraInicio, $dataHoraFim, $idFusoHorario)
    {
        $pdo = new Conexao();
        if (strpos($dataHoraInicio, "/") !== false)
            $dataHoraInicio = $pdo->PrepararDataBD($dataHoraInicio, $idFusoHorario );

        if (strpos($dataHoraFim, "/") !== false)
            $dataHoraFim = $pdo->PrepararDataBD($dataHoraFim, $idFusoHorario );

        list($data, $hora) = explode(" ", $dataHoraInicio);
        list($ano, $mes, $dia) = explode("-", $data);
        list($hora, $minuto, $segundo) = explode(":", $hora);
        $timestampInicial = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);

        list($data, $hora) = explode(" ", $dataHoraFim);
        list($ano, $mes, $dia) = explode("-", $data);
        list($hora, $minuto, $segundo) = explode(":", $hora);
        $timestampFinal = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);

        $tabelasExistentes = $this->ListarTabelasTemperatura();
        $qtdTabelasExistentes = count($tabelasExistentes);
        for( $i = 0; $i < $qtdTabelasExistentes; $i++ )
            $tabelasExistentes[$i] = $tabelasExistentes[$i]['nome_tabela'];

        $i = $timestampInicial;
        $datas = array();
        while($i <= $timestampFinal)
        {
            $tabelaTemperatura = "telemetria_temperatura_".date("Ymd", $i);

            if( @array_search($tabelaTemperatura, $tabelasExistentes) !== false )
                $datas[$tabelaTemperatura] = $tabelaTemperatura;

            if($i >= $timestampFinal)
                break;

            if(($timestampFinal - $i) > (60*60*24))
                $i+=(60*60*24);
            else
                $i+=($timestampFinal-$i);

        }
        return $datas;
    }

    public function ListarTabelasTelemetria()
    {
        $pdo = new Conexao();

        $sql = "SHOW TABLES LIKE 'telemetria_2%'"; // para nao trazer as outras tabelas de telemetria_algumacoisa tem o 2 ali de 20XX
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($rs)>0)
        {
            $retorno = array();
            foreach($rs as $row)
                array_push($retorno,array("nome_tabela" => $row['tables_in_webcop (telemetria_2%)']));
        }
        return $retorno;
    }

    public function DeterminarTabelasTelemetria($dataHoraInicio, $dataHoraFim, $idFusoHorario)
    {
        $pdo = new Conexao();
        if (strpos($dataHoraInicio, "/") !== false)
            $dataHoraInicio = $pdo->PrepararDataBD($dataHoraInicio, $idFusoHorario );

        if (strpos($dataHoraFim, "/") !== false)
            $dataHoraFim = $pdo->PrepararDataBD($dataHoraFim, $idFusoHorario );

        list($data, $hora) = explode(" ", $dataHoraInicio);
        list($ano, $mes, $dia) = explode("-", $data);
        list($hora, $minuto, $segundo) = explode(":", $hora);
        $timestampInicial = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);

        list($data, $hora) = explode(" ", $dataHoraFim);
        list($ano, $mes, $dia) = explode("-", $data);
        list($hora, $minuto, $segundo) = explode(":", $hora);
        $timestampFinal = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);

        $tabelasExistentes = $this->ListarTabelasTelemetria();
        $qtdTabelasExistentes = count($tabelasExistentes);
        for( $i = 0; $i < $qtdTabelasExistentes; $i++ )
            $tabelasExistentes[$i] = $tabelasExistentes[$i]['nome_tabela'];

        $i = $timestampInicial;
        $datas = array();
        while($i <= $timestampFinal)
        {
            $tabelaTelemetria = "telemetria_".date("Ymd", $i);

            if( @array_search($tabelaTelemetria, $tabelasExistentes) !== false )
                $datas[$tabelaTelemetria] = $tabelaTelemetria;

            if($i >= $timestampFinal)
                break;

            if(($timestampFinal - $i) > (60*60*24))
                $i+=(60*60*24);
            else
                $i+=($timestampFinal-$i);

        }
        return $datas;
    }

    public static function pr($dados,$var = false)
    {
        echo $rs = "<pre>";
        $rs .= print_r($dados,$var);
        echo $rs .="</pre>";
        if($var) return $rs;
    }

    public static function FormatarTextosPregReplace($texto)
    {
        return preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $texto));
    }
    public function RetirarAcentos($texto)
    {
        $textoFinal = strtr($texto, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ", "aaaaeeiooouucAAAAEEIOOOUUCC");
        return $textoFinal;
    }


}