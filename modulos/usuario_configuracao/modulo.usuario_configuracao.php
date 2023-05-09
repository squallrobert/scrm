<?
include_once(URL_FILE . "idioma/" . DIR_IDIOMA . "/modulos/usuario_configuracao.loc.php");
include_once(URL_FILE . "modulos/mapa/GoogleMapsGeoCode.php");
$usuarioConfiguracao = new UsuarioConfiguracao();

switch ($app_comando) {
	case "configuracoes_dialog" :
		$template = "tpl.configuracoes_dialog.php";
		break;
	case "listar_usuario_configuracao" :
		$template = "tpl.geral.usuario_configuracao.php";
		break;

	case "lis_usuario_configuracao":
		$template = 'tpl.lis.usuario_configuracao.php';
		break;

	case "frm_alterar_configuracoes":
		($_REQUEST['app_codigo'] != "") ? $usuario = $_REQUEST['app_codigo'] : $usuario = $_SESSION['usuario']['id'];
		$usuarioConfiguracao->setIdUsuario($usuario);
		$usuarioConfiguracao->setIdSessao(5);
		$centroid  = $usuarioConfiguracao->ListaUsuarioConfiguracao();
		$centroid  = unserialize($centroid->configuracao);
		$latitude  = $centroid['latitude'];
		$longitude = $centroid['longitude'];

		$template = "tpl.frm.usuario_configuracao.php";
		break;

	case "alterar_configuracoes":
		$prioridades = $_POST;
		$usuario     = new Usuario();
		$usuario->setId($app_codigo);
		$usuarioConf = new UsuarioConfiguracao();

		$coordenadas    = array('latitude' => $_POST['latitude'], 'longitude' => $_POST['longitude']);
		$coordenadas    = serialize($coordenadas);
		$conf_posicao   = serialize($_POST['seleciona_posicao']);
		$conf_monitorar = serialize($_POST['seleciona_monitorar']);
		$conf_rel_por_email = serialize($_POST['seleciona_rel_por_email']);
        $conf_relatorios = serialize($_POST['seleciona_placa_sem_hifen']);
		$conf_comandos = $_POST['seleciona_comandos'];
		$sms           = $_POST['seleciona_sms'];

		$retorno[] = $usuario->AtribuirPrioridadeEventoTipo($prioridades);

		if ($app_codigo == $_SESSION['usuario']['id']) {
			$_SESSION['configura_relatorio'] = unserialize($conf_posicao);
			$_SESSION['configura_tabela']    = unserialize($conf_monitorar);
		}


		if (count($conf_posicao) != 0) {
			$usuarioConfiguracao->setIdUsuario($app_codigo);
			$usuarioConfiguracao->setIdSessao(1);
			$usuarioConfiguracao->setConfiguracao($conf_posicao);
			$retorno[] = $usuarioConfiguracao->AdicionarUsuarioConfiguracaoAntigo();
		}
		if (count($conf_monitorar) != 0) {
			$usuarioConfiguracao->setIdSessao(2);
			$usuarioConfiguracao->setConfiguracao($conf_monitorar);
			$retorno[] = $usuarioConfiguracao->AdicionarUsuarioConfiguracaoAntigo();
		}
		if (count($coordenadas) != 0) {
			$_SESSION['usuario']['centroid']['latitude'] = $_POST['latitude'];
			$_SESSION['usuario']['centroid']['longitude'] = $_POST['longitude'];
			$usuarioConfiguracao->setIdSessao(5);
			$usuarioConfiguracao->setConfiguracao($coordenadas);
			$retorno[] = $usuarioConfiguracao->AdicionarUsuarioConfiguracaoAntigo();
		}
		if(count($conf_rel_por_email) != 0)
		{
			$usuarioConfiguracao->setIdSessao(10);
			$usuarioConfiguracao->setConfiguracao($conf_rel_por_email);
			$retorno[] = $usuarioConfiguracao->AdicionarUsuarioConfiguracaoAntigo();
		}
		if (count($conf_relatorios) > 0) {
            $usuarioConfiguracao->setIdSessao(16);
            $usuarioConfiguracao->setConfiguracao($conf_relatorios);
            $retorno[] = $usuarioConfiguracao->AdicionarUsuarioConfiguracaoAntigo();
        }
        if (count($sms) > 0) {
            $usuarioConfiguracao->setIdUsuario($app_codigo);
            $retorno[] = $usuarioConfiguracao->LiberarSms($sms);
        }
        if ($_SESSION['id_franquia'] != "") {
            $usuarioConf->RemoverComandosUsuario($app_codigo);
        }
		if(count($conf_comandos) != 0)
		{
			$retorno[] = $usuarioConf->AdicionarUsuarioComandos($conf_comandos,$app_codigo);
		}

		$resultadoFinal = 1;
		foreach ($retorno AS $resultado) {
			if (!$resultado) {
				$resultadoFinal = 0;
				break;
			}
		}

		if ($resultadoFinal == 1) {
			$msg["codigo"]   = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
		} else {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
		}

		echo json_encode($msg);
		$template = "ajax.usuario_configuracao.php";
		break;
}

