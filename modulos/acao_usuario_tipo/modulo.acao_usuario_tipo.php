<?

switch($app_comando)
{
	case "frm_adicionar_acao_usuario_tipo":
		$template = "tpl.frm.acao_usuario_tipo.php";
		break;

	case "adicionar_acao_usuario_tipo":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
		    if(is_array($_POST['acoes']))
            {
                $objAcaoUsuarioTipo = new AcaoUsuarioTipo($pdo);
                $objAcaoUsuarioTipo->RemoverAll($_REQUEST['id_usuario_tipo']);
                foreach ($_POST['acoes'] as $permissao) {
                    if ($permissao[0] == 'j') {
                        continue;
                    }
                    $objAcaoUsuarioTipo = new AcaoUsuarioTipo($pdo);
                    $objAcaoUsuarioTipo->setIdUsuarioTipo($_REQUEST['id_usuario_tipo']);
                    $objAcaoUsuarioTipo->setIdAcao($permissao);
                    $novoId = $objAcaoUsuarioTipo->Adicionar();
                }
            }

		    $objmapusuario = new MapUsuarioAcao($pdo);
            $objmapusuario->RemoverALLUsers($_REQUEST['id_usuario_tipo']);

            $objAcaoUsuarioTipo = new AcaoUsuarioTipo($pdo);
            $objAcaoUsuarioTipo->AdicionarTodosUsers($_REQUEST['id_usuario_tipo']);


			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO . " ". $e->getMessage();
			$msg["debug"]['msg'] = $e->getMessage();
			$msg["debug"]['file'] = $e->getFile();
			$msg["debug"]['line'] = $e->getLine();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.acao_usuario_tipo.php";
		break;

	case "frm_atualizar_acao_usuario_tipo" :
		$acao_usuario_tipo = new AcaoUsuarioTipo();
		$acao_usuario_tipo->setId($_REQUEST["app_codigo"]);
		$linha = $acao_usuario_tipo->Editar();
		$template = "tpl.frm.acao_usuario_tipo.php";
		break;

	case "atualizar_acao_usuario_tipo":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
            if(is_array($_POST['acoes']))
            {
                $objAcaoUsuarioTipo = new AcaoUsuarioTipo($pdo);
                $objAcaoUsuarioTipo->RemoverAll($_REQUEST['id_usuario_tipo']);
                foreach ($_POST['acoes'] as $permissao) {
                    if ($permissao[0] == 'j') {
                        continue;
                    }
                    $objAcaoUsuarioTipo = new AcaoUsuarioTipo($pdo);
                    $objAcaoUsuarioTipo->setIdUsuarioTipo($_REQUEST['id_usuario_tipo']);
                    $objAcaoUsuarioTipo->setIdAcao($permissao);
                    $novoId = $objAcaoUsuarioTipo->Adicionar();
                }
            }
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
		$template = "ajax.acao_usuario_tipo.php";
		break;

	case "listar_acao_usuario_tipo":
		$template = "tpl.geral.acao_usuario_tipo.php";
		break;

	case "deletar_acao_usuario_tipo":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objAcaoUsuarioTipo = new AcaoUsuarioTipo($pdo);
			$objAcaoUsuarioTipo->Remover($_REQUEST['registros']);
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
		$template = "ajax.acao_usuario_tipo.php";
		break;

	case "ajax_listar_acao_usuario_tipo":
		$template = "tpl.lis.acao_usuario_tipo.php";
		break;

	case "acao_usuario_tipo_pdf":
		$template = "tpl.lis.acao_usuario_tipo.pdf.php";
		break;

	case "acao_usuario_tipo_xlsx":
		$template = "tpl.lis.acao_usuario_tipo.xlsx.php";
		break;

	case "acao_usuario_tipo_print":
		$template = "tpl.lis.acao_usuario_tipo.print.php";
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
			$_SESSION["configuracao_usuario"]["acao_usuario_tipo"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("acao_usuario_tipo");
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
		$template = "ajax.acao_usuario_tipo.php";
		break;
}
