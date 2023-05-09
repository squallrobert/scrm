<?

switch($app_comando)
{
	case "frm_adicionar_cliente_tipo_pessoa":
		$template = "tpl.frm.cliente_tipo_pessoa.php";
		break;

	case "adicionar_cliente_tipo_pessoa":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objClienteTipoPessoa = new ClienteTipoPessoa($pdo);
			$objClienteTipoPessoa->setRotulo($_REQUEST['rotulo']);
			$novoId = $objClienteTipoPessoa->Adicionar();
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
		$template = "ajax.cliente_tipo_pessoa.php";
		break;

	case "frm_atualizar_cliente_tipo_pessoa" :
		$cliente_tipo_pessoa = new ClienteTipoPessoa();
		$cliente_tipo_pessoa->setId($_REQUEST["app_codigo"]);
		$linha = $cliente_tipo_pessoa->Editar();
		$template = "tpl.frm.cliente_tipo_pessoa.php";
		break;

	case "atualizar_cliente_tipo_pessoa":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objClienteTipoPessoa = new ClienteTipoPessoa($pdo);
			$objClienteTipoPessoa->setId($_REQUEST['id']);
			$objClienteTipoPessoa->setRotulo($_REQUEST['rotulo']);
			$objClienteTipoPessoa->Modificar();
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
		$template = "ajax.cliente_tipo_pessoa.php";
		break;

	case "listar_cliente_tipo_pessoa":
		$template = "tpl.geral.cliente_tipo_pessoa.php";
		break;

	case "deletar_cliente_tipo_pessoa":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objClienteTipoPessoa = new ClienteTipoPessoa($pdo);
			$objClienteTipoPessoa->Remover($_REQUEST['registros']);
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
		$template = "ajax.cliente_tipo_pessoa.php";
		break;

	case "ajax_listar_cliente_tipo_pessoa":
		$template = "tpl.lis.cliente_tipo_pessoa.php";
		break;

	case "cliente_tipo_pessoa_pdf":
		$template = "tpl.lis.cliente_tipo_pessoa.pdf.php";
		break;

	case "cliente_tipo_pessoa_xlsx":
		$template = "tpl.lis.cliente_tipo_pessoa.xlsx.php";
		break;

	case "cliente_tipo_pessoa_print":
		$template = "tpl.lis.cliente_tipo_pessoa.print.php";
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
			$_SESSION["configuracao_usuario"]["cliente_tipo_pessoa"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("cliente_tipo_pessoa");
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
		$template = "ajax.cliente_tipo_pessoa.php";
		break;
}
