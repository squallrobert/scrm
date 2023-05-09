<?

switch($app_comando)
{
	case "frm_adicionar_cidades":
		$template = "tpl.frm.cidades.php";
		break;

	case "adicionar_cidades":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objCidades = new Cidades($pdo);
			$objCidades->setIdEstado($_REQUEST['id_estado']);
			$objCidades->setNome($_REQUEST['nome']);
			$novoId = $objCidades->Adicionar();
			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO . " ". $e->getMessage();
			$msg["debug"] = $e->getMessage();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.cidades.php";
		break;

	case "frm_atualizar_cidades" :
		$cidades = new Cidades();
		$cidades->setId($_REQUEST["app_codigo"]);
		$linha = $cidades->Editar();
		$template = "tpl.frm.cidades.php";
		break;

	case "atualizar_cidades":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objCidades = new Cidades($pdo);
			$objCidades->setId($_REQUEST['id']);
			$objCidades->setIdEstado($_REQUEST['id_estado']);
			$objCidades->setNome($_REQUEST['nome']);
			$objCidades->Modificar();
			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_MODIFICAR;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO. " ". $e->getMessage();
			$msg["debug"] = $e->getMessage();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.cidades.php";
		break;

	case "listar_cidades":
		$template = "tpl.geral.cidades.php";
		break;

	case "deletar_cidades":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objCidades = new Cidades($pdo);
			$objCidades->Remover($_REQUEST['registros']);
			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO. " ". $e->getMessage();
			$msg["debug"] = $e->getMessage();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.cidades.php";
		break;

	case "ajax_listar_cidades":
		$template = "tpl.lis.cidades.php";
		break;

	case "cidades_pdf":
		$template = "tpl.lis.cidades.pdf.php";
		break;

	case "cidades_xlsx":
		$template = "tpl.lis.cidades.xlsx.php";
		break;

	case "cidades_print":
		$template = "tpl.lis.cidades.print.php";
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
					$colunasSelecionadas[$checkbox] = ["id_campo" => $idCampo["id"], "valor_campo" => $idCampo["valor"]];
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
			$_SESSION["configuracao_usuario"]["cidades"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("cidades");
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
		$template = "ajax.cidades.php";
		break;
    case "filtrar_cidade":
    case "filtrar_cidade_id":
		$template = "ajax.cidades.php";
		break;
}
