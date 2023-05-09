<?
include_once(URL_FILE . "idioma/" . DIR_IDIOMA . "/modulos/acao.loc.php");
switch($_REQUEST['app_comando'])
{
	case "frm_adicionar_acao":
        echo '
            <script>
                    var ordem = "'.$_REQUEST['ordem'].'";
                    var pagina = "'.$_REQUEST['pagina'].'";
                    var busca = "'.$_REQUEST['busca'].'";
                    var filtro = "'.$_REQUEST['filtro'].'";
                    var id = "'.$_REQUEST['app_codigo'].'";
            </script>
        ';
		$template = "tpl.frm.acao.php";
		break;
	case "adicionar_acao":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
            $acao = Utils::PegarVetorTagIt($_REQUEST['acao']);
			$objAcao = new Acao($pdo);
			$objAcao->setNome($_REQUEST['nome']);
            $acoes = implode('|',$acao);
			$objAcao->setAcao($acoes);
			$objAcao->setModulo($_REQUEST['modulo']);
			$novoId = $objAcao->Adicionar();
			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
            $msg["mensagem"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $msg["debug"]["error"] = $e->getMessage();
            $msg["debug"]["file"] = $e->getFile();
            $msg["debug"]["linha"] = $e->getLine();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.acao.php";
		break;

	case "frm_atualizar_acao" :
        echo '
            <script>
                    var ordem = "'.$_REQUEST['ordem'].'";
                    var pagina = "'.$_REQUEST['pagina'].'";
                    var busca = "'.$_REQUEST['busca'].'";
                    var filtro = "'.$_REQUEST['filtro'].'";
                    var id = "'.$_REQUEST['app_codigo'].'";
            </script>
        ';
		$acao = new Acao();
		$acao->setId($_REQUEST["app_codigo"]);
		$linha = $acao->Editar();
		$template = "tpl.frm.acao.php";
		break;

	case "atualizar_acao":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
            $acao = Utils::PegarVetorTagIt($_REQUEST['acao']);
			$objAcao = new Acao($pdo);
			$objAcao->setId($_REQUEST['id']);
			$objAcao->setNome($_REQUEST['nome']);
            $acoes = implode('|',$acao);
			$objAcao->setAcao($acoes);
			$objAcao->setModulo($_REQUEST['modulo']);
			$objAcao->Modificar();
			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_MODIFICAR;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            $msg["debug"] = $e->getMessage();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.acao.php";
		break;

	case "listar_acao":
        echo '
            <script>
                    var ordem = "'.$_REQUEST['ordem'].'";
                    var pagina = "'.$_REQUEST['pagina'].'";
                    var busca = "'.$_REQUEST['busca'].'";
                    var filtro = "'.$_REQUEST['filtro'].'";
                    var id = "'.$_REQUEST['app_codigo'].'";
            </script>
        ';
		$template = "tpl.geral.acao.php";
		break;

	case "deletar_acao":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objAcao = new Acao($pdo);
			$objAcao->Remover($_REQUEST['registros']);
			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            $msg["debug"] = $e->getMessage();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.acao.php";
		break;

	case "ajax_listar_acao":
		$template = "tpl.lis.acao.php";
		break;

	case "acao_pdf":
		$template = "tpl.lis.acao.pdf.php";
		break;

	case "acao_xlsx":
		$template = "tpl.lis.acao.xlsx.php";
		break;

	case "acao_print":
		$template = "tpl.lis.acao.print.php";
		break;

	case "frm_configurar_listagem":
		$template = "configuracao_listagem.php";
		break;

	case "configurar_listagem":
		$usuarioConfiguracao = new UsuarioConfiguracao();
		foreach ($_POST as $checkbox => $idCampo) {
			if ($checkbox == "limite_colunas") {
				continue;
			}
			if (is_array($idCampo)) {
				if ($idCampo["valor"] == "") {
					continue;
				} else {
					$colunasSelecionadas[$checkbox] = Array("id_campo" => $idCampo["id"], "valor_campo" => $idCampo["valor"]);
				}
			} else {
				$colunasSelecionadas[$checkbox] = $idCampo;
			}
		}
		$countColunasSelecionadas = count($colunasSelecionadas);
		if ($countColunasSelecionadas > 0) {
			if (($countColunasSelecionadas > $_REQUEST["limite_colunas"]) && $_REQUEST["limite_colunas"] != "0" && $_REQUEST["limite_colunas"] != "") {
				$msg["codigo"]   = 1;
				$msg["mensagem"] = TXT_LIMITE_COLUNAS_EXCEDIDO_RESOLUCAO;
				echo json_encode($msg);
				die();
			}
			$_SESSION["configuracao_usuario"]["acao"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("acao");
			$usuarioConfiguracao->LimparConfiguracoes();
			foreach ($colunasSelecionadas AS $nomeCampo => $idCampo) {
				if (is_array($idCampo)) {
					$usuarioConfiguracao->setIdCampoModulo($idCampo["id_campo"]);
					$usuarioConfiguracao->setValorCampo($idCampo["valor_campo"]);
				} else {
					$usuarioConfiguracao->setIdCampoModulo($idCampo);
				}
				$resultado = $usuarioConfiguracao->AdicionarUsuarioConfiguracao();
				if (!$resultado) {
					break;
				}
			}
			if ($resultado) {
				$msg["codigo"]   = 0;
				$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
			} else {
				$msg["codigo"]   = 1;
				$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
			}
		} else {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_ALERT_SELECIONAR_COLUNAS;
		}
		echo json_encode($msg);
		$template = "ajax.acao.php";
		break;
}
