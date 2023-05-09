<?

switch($app_comando)
{
	case "frm_adicionar_uf":
		$template = "tpl.frm.uf.php";
		break;

	case "adicionar_uf":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objUf = new Uf($pdo);
			$objUf->setIdRegiaoUf($_REQUEST['id_regiao_uf']);
			$objUf->setNome($_REQUEST['nome']);
			$objUf->setSigla($_REQUEST['sigla']);
			$novoId = $objUf->Adicionar();
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
		$template = "ajax.uf.php";
		break;

	case "frm_atualizar_uf" :
		$uf = new Uf();
		$uf->setId($_REQUEST["app_codigo"]);
		$linha = $uf->Editar();
		$template = "tpl.frm.uf.php";
		break;

	case "atualizar_uf":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objUf = new Uf($pdo);
			$objUf->setId($_REQUEST['id']);
			$objUf->setIdRegiaoUf($_REQUEST['id_regiao_uf']);
			$objUf->setNome($_REQUEST['nome']);
			$objUf->setSigla($_REQUEST['sigla']);
			$objUf->Modificar();
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
		$template = "ajax.uf.php";
		break;

	case "listar_uf":
		$template = "tpl.geral.uf.php";
		break;

	case "deletar_uf":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objUf = new Uf($pdo);
			$objUf->Remover($_REQUEST['registros']);
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
		$template = "ajax.uf.php";
		break;

	case "ajax_listar_uf":
		$template = "tpl.lis.uf.php";
		break;

	case "uf_pdf":
		$template = "tpl.lis.uf.pdf.php";
		break;

	case "uf_xlsx":
		$template = "tpl.lis.uf.xlsx.php";
		break;

	case "uf_print":
		$template = "tpl.lis.uf.print.php";
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
			$_SESSION["configuracao_usuario"]["uf"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("uf");
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
		$template = "ajax.uf.php";
		break;

    case "filtrar_uf":
        $template = "ajax.uf.php";
        break;
}
