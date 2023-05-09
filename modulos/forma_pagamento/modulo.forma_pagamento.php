<?

switch($app_comando)
{
	case "frm_adicionar_forma_pagamento":
		$template = "tpl.frm.forma_pagamento.php";
		break;

	case "adicionar_forma_pagamento":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objFormaPagamento = new FormaPagamento($pdo);
			$objFormaPagamento->setRotulo($_REQUEST['rotulo']);
			$novoId = $objFormaPagamento->Adicionar();
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
		$template = "ajax.forma_pagamento.php";
		break;

	case "frm_atualizar_forma_pagamento" :
		$forma_pagamento = new FormaPagamento();
		$forma_pagamento->setId($_REQUEST["app_codigo"]);
		$linha = $forma_pagamento->Editar();
		$template = "tpl.frm.forma_pagamento.php";
		break;

	case "atualizar_forma_pagamento":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objFormaPagamento = new FormaPagamento($pdo);
			$objFormaPagamento->setId($_REQUEST['id']);
			$objFormaPagamento->setRotulo($_REQUEST['rotulo']);
			$objFormaPagamento->Modificar();
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
		$template = "ajax.forma_pagamento.php";
		break;

	case "listar_forma_pagamento":
		$template = "tpl.geral.forma_pagamento.php";
		break;

	case "deletar_forma_pagamento":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objFormaPagamento = new FormaPagamento($pdo);
			$objFormaPagamento->Remover($_REQUEST['registros']);
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
		$template = "ajax.forma_pagamento.php";
		break;

	case "ajax_listar_forma_pagamento":
		$template = "tpl.lis.forma_pagamento.php";
		break;

	case "forma_pagamento_pdf":
		$template = "tpl.lis.forma_pagamento.pdf.php";
		break;

	case "forma_pagamento_xlsx":
		$template = "tpl.lis.forma_pagamento.xlsx.php";
		break;

	case "forma_pagamento_print":
		$template = "tpl.lis.forma_pagamento.print.php";
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
			$_SESSION["configuracao_usuario"]["forma_pagamento"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("forma_pagamento");
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
		$template = "ajax.forma_pagamento.php";
		break;
}
