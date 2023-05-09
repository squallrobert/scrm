<?php
 include_once(URL_FILE . "modulos/fuso_horario/classe.fuso_horario.php");
 include_once(URL_FILE . "modulos/sistema/classe.sistema.php");

define("LOG_LIS", 1);
define("LOG_ADI", 2);
define("LOG_MOD", 4);
define("LOG_REM", 8);


class LogAtividade extends Tabela
{
	var $_idFuncionalidade;
	var $_idUsuario;
	var $_dataHora;
    var $_tipo;

	public function LogAtividade( $idFuncionalidade, $idUsuario,$tipo = "0" )
	{
		// Pega o fuso horario do sistema
		$this->_dataHora = FusoHorario::ObterHoraAtualServidor();

		list($data, $hora) = explode(" ", $this->_dataHora);
		$data = str_replace("-", "", $data);

		$this->_idUsuario = $idUsuario;
		$this->_idFuncionalidade = $idFuncionalidade;
        $this->_tipo = $tipo;
		parent::Tabela("log_atividade_$data");
	}

	public function Adicionar( $parametros = array() )
	{
		$parametros['data_hora'] = $this->_dataHora;
		$parametros['ip'       ] = $_SERVER['REMOTE_ADDR'];

		$resultado = parent::Adicionar( $parametros );

		// Se a tabela de log ainda nao existe
		if( $resultado->codigo == Erro::ERRO_TABELA_NAO_EXISTE )
		{
			list($data, $hora) = explode(" ", $this->_dataHora);
			list($ano, $mes, $dia) = explode("-", $data);
			$data = str_replace("-", "", $data);

			// Executa as rotinas diarias para criacao das tabelas de posicao e log
			//include_once( "HTTP/Client.php" );
			//$client = new HTTP_Client();
			//$client->get( "index.php?app_modulo=rotina_diaria&app_comando=gerar_tabelas" );
			$objSistema = new Sistema();
			$objSistema->CriarTabelasDiarias($dia, $mes, $ano);

			// Tenta adicionar o registro de posicao novamente
			$resultado = parent::Adicionar( $parametros );
		}
	}

	public function Modificar( $parametros = array() )
	{
		return;
	}

	public function Remover( $parametros = array() )
	{
		return;
	}

	public function ListarPermitidos($id_franqueado, $parametros = array())
	{
		if(trim($id_franqueado) == "" || !is_numeric($id_franqueado) || $id_franqueado <= 0 ){
			return array("codigo" => "1", "mensagem" => "Grupo Invalido");
		}

		$objGrupo = new Grupo();

		$resultado = $objGrupo->BuscaFilhos($id_franqueado);
		$operador = "";

		if(!isset($parametros['where']) || count($parametros['where']) == 0 ){
			$parametros['where'] = array();
		}

		if( count($parametros['where']) > 0){
			$operador = "AND";
		}

		array_push($parametros['where'], array("operador" => "$operador", "condicao" => "categoria.id_franqueado IN ({$resultado})"));
		return parent::Listar($parametros);
	}

	public function GravarLog( $tipoAtividade, $consultaSql, $observacao = null )
	{
		$acessar	= 0;
		$adicionar	= 0;
		$modificar	= 0;
		$remover	= 0;

		switch($tipoAtividade)
		{
			case LOG_LIS:	$acessar   = 1;		break;
			case LOG_ADI:	$adicionar = 1;		break;
			case LOG_MOD:	$modificar = 1;		break;
			case LOG_REM:	$remover   = 1;		break;
		}

		$log = array(

			"id_usuario" => $this->_idUsuario,
            "id_funcionalidade" => $this->_idFuncionalidade,
            "acessar" => $acessar,
			"adicionar" => $adicionar,
            "modificar" => $modificar,
            "remover" => $remover,
            "tipo" => $this->_tipo,
        	"consulta_sql" => str_replace("'", "\'", $consultaSql)

		);

		if(isset($observacao) AND $observacao != "")
			$log['observacao'] = $observacao;

		return $this->Adicionar($log);
	}
}
?>