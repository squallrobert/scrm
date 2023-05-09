<?php
include_once(URL_FILE . "classes/Tabela.php");
include_once(URL_FILE . "classes/LogAtividade.php");
include_once(URL_FILE . "modulos/fuso_horario/classe.fuso_horario.php");
include_once(URL_FILE . "modulos/veiculo_rastreador/classe.veiculo_rastreador.php");
include_once(URL_FILE . "modulos/veiculo/classe.veiculo.php");
include_once(URL_FILE . "modulos/mapa/Geo.php");

class Logradouros extends Tabela2
{
	public function __construct($siglaIdioma = null)
	{
		parent::Tabela("logradouros", $siglaIdioma);
	}

	public function Gerar()
	{
	    $i = '2010-01-01 00:00:00';
        $f = '2011-01-01 23:59:59';
        $datas = $this->DeterminarTabelas($i, $f, 23);

       // print_r($datas);

        $fuso_horario = FusoHorario::Editar(23);

		$intervalo['inicial'] = Tabela::PrepararDataBD($i, $fuso_horario );
		$intervalo['final'  ] = Tabela::PrepararDataBD($f, $fuso_horario );

		$parametros_listar = array();
		$parametros_total_listar = array();
		$parametros_listar_pag = array();

        foreach($datas as $indice=>$tabela_posicao)
		{
			$campos = array( "{$tabela_posicao}.latitude","{$tabela_posicao}.longitude","{$tabela_posicao}.logradouro");
			$from = array("{$tabela_posicao}");
			$where = array(
				array("operador"=>"","condicao"=>"{$tabela_posicao}.data_hora >= '".$i."'"),
				array("operador"=>"AND","condicao"=>"{$tabela_posicao}.data_hora <= '".$f."'")
			);

			$novo = array(
				"campos" => $campos,
				"from" => $from,
				"where" => $where
			);
			array_push($parametros_listar, $novo);
		}

        print_r($parametros_listar);

		$parametros_listar['order'] = array("data_hora DESC");

		$retorno = $this->ListarUnion($parametros_listar);


        print_r($retorno);
		return $retorno;
	}

    public function DeterminarTabelas($dataHoraInicio, $dataHoraFim, $idFusoHorario)
    {
        $fuso_horario = FusoHorario::Editar($idFusoHorario);

        $dataHoraInicio = Tabela::PrepararDataBD($dataHoraInicio, $fuso_horario );
		$dataHoraFim = Tabela::PrepararDataBD($dataHoraFim, $fuso_horario );

        list($data, $hora) = explode(" ", $dataHoraInicio);
		list($ano, $mes, $dia) = explode("-", $data);
		list($hora, $minuto, $segundo) = explode(":", $hora);
		$timestampInicial = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);

        list($data, $hora) = explode(" ", $dataHoraFim);
		list($ano, $mes, $dia) = explode("-", $data);
		list($hora, $minuto, $segundo) = explode(":", $hora);
		$timestampFinal = @mktime($hora, $minuto, $segundo, $mes, $dia, $ano);

        $tabelasExistentes = $this->ListarTabelas( array("like" => "posicao_%") );
        //print_r($tabelasExistentes);
		$qtdTabelasExistentes = count($tabelasExistentes->resultado);
		for( $i = 0; $i < $qtdTabelasExistentes; $i++ )
		    $tabelasExistentes->resultado[$i] = $tabelasExistentes->resultado[$i]['nome_tabela'];

        $i = $timestampInicial;
        $datas = array();
		while($i <= $timestampFinal)
		{
            $tabelaPosicao = "posicao_".date("Ymd", $i);

            if( array_search($tabelaPosicao, $tabelasExistentes->resultado) !== false )
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




}

?>
