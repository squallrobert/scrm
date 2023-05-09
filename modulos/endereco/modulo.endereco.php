<?

switch($app_comando)
{
	case "frm_adicionar_endereco":
		$template = "tpl.frm.endereco.php";
		break;

	case "adicionar_endereco":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objEndereco = new Endereco($pdo);
			$objEndereco->setLogradouro($_REQUEST['logradouro']);
			$objEndereco->setNumero($_REQUEST['numero']);
			$objEndereco->setComplemento($_REQUEST['complemento']);
			$objEndereco->setBairro($_REQUEST['bairro']);
			$objEndereco->setCidade($_REQUEST['cidade']);
			$objEndereco->setEstado($_REQUEST['estado']);
			$objEndereco->setCep($_REQUEST['cep']);
			$objEndereco->setReferencia($_REQUEST['referencia']);
			$objEndereco->setObservacao($_REQUEST['observacao']);
			$objEndereco->setTelefone($_REQUEST['telefone']);
			$objEndereco->setComercial($_REQUEST['comercial']);
			$objEndereco->setCelular($_REQUEST['celular']);
			$objEndereco->setEmail($_REQUEST['email']);
			$objEndereco->setEmailMkt($_REQUEST['email_mkt']);
			$objEndereco->setEmailMkt2($_REQUEST['email_mkt2']);
			$objEndereco->setLatitude($_REQUEST['latitude']);
			$objEndereco->setLongitude($_REQUEST['longitude']);
			$novoId = $objEndereco->Adicionar();
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
		$template = "ajax.endereco.php";
		break;

	case "frm_atualizar_endereco" :
		$endereco = new Endereco();
		$endereco->setId($_REQUEST["app_codigo"]);
		$linha = $endereco->Editar();
		$template = "tpl.frm.endereco.php";
		break;

	case "atualizar_endereco":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objEndereco = new Endereco($pdo);
			$objEndereco->setId($_REQUEST['id']);
			$objEndereco->setLogradouro($_REQUEST['logradouro']);
			$objEndereco->setNumero($_REQUEST['numero']);
			$objEndereco->setComplemento($_REQUEST['complemento']);
			$objEndereco->setBairro($_REQUEST['bairro']);
			$objEndereco->setCidade($_REQUEST['cidade']);
			$objEndereco->setEstado($_REQUEST['estado']);
			$objEndereco->setCep($_REQUEST['cep']);
			$objEndereco->setReferencia($_REQUEST['referencia']);
			$objEndereco->setObservacao($_REQUEST['observacao']);
			$objEndereco->setTelefone($_REQUEST['telefone']);
			$objEndereco->setComercial($_REQUEST['comercial']);
			$objEndereco->setCelular($_REQUEST['celular']);
			$objEndereco->setEmail($_REQUEST['email']);
			$objEndereco->setEmailMkt($_REQUEST['email_mkt']);
			$objEndereco->setEmailMkt2($_REQUEST['email_mkt2']);
			$objEndereco->setLatitude($_REQUEST['latitude']);
			$objEndereco->setLongitude($_REQUEST['longitude']);
			$objEndereco->Modificar();
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
		$template = "ajax.endereco.php";
		break;

	case "listar_endereco":
		$template = "tpl.geral.endereco.php";
		break;

	case "deletar_endereco":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objEndereco = new Endereco($pdo);
			$objEndereco->Remover($_REQUEST['registros']);
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
		$template = "ajax.endereco.php";
		break;

	case "ajax_listar_endereco":
		$template = "tpl.lis.endereco.php";
		break;

	case "endereco_pdf":
		$template = "tpl.lis.endereco.pdf.php";
		break;

	case "endereco_xlsx":
		$template = "tpl.lis.endereco.xlsx.php";
		break;

	case "endereco_print":
		$template = "tpl.lis.endereco.print.php";
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
			$_SESSION["configuracao_usuario"]["endereco"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("endereco");
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
		$template = "ajax.endereco.php";
		break;
}
