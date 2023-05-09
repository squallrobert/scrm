<?php

/**
 * @author Squall Robert
 * @copyright 2011
 */

class ConexaoLog extends PDO
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

    public static function PrepararDataBD( $data, $fuso_horario = "America/Sao_Paulo", $formatoSaida = "Y-m-d H:i:s" )
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
		$datetime->setTimezone(new DateTimeZone("UTC"));
		return $datetime->format($formatoSaida);
    }

    public static function PrepararDataPHP( $data, $fuso_horario = "America/Sao_Paulo", $formatoSaida = "d/m/Y H:i:s" )
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

		if ($fuso_horario == NULL || $fuso_horario == "") {
			$fuso_horario = "America/Sao_Paulo";
		}

		$datetime = new DateTime($data, new DateTimeZone("UTC"));
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

        //print_r($rs);

        if(count($rs) > 0)
        {

            $retorno = array();
            foreach($rs as $row)
                array_push($retorno,array("nome_tabela" => $row['tables_in_monitoramento (posicao_%)']));
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


        //print_r($tabelasExistentes);
        $i = $timestampInicial;
        //echo $i;
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

    public static function pr($dados)
    {
        echo "<pre>";
        print_r($dados);
        echo "</pre>";
    }

}