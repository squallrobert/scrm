<?

switch($app_comando)
{
	case "frm_adicionar_grupo_acao":
		$template = "tpl.frm.grupo_acao.php";
		break;

	case "adicionar_grupo_acao":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
            $objGrupoAcao = new Grupo($pdo);
            if(count($_POST['grupos']))
            {
                foreach($_POST['grupos'] as $grupo)
                {
                    foreach ($_POST['acoes'] as $permissao) {
                        if ($permissao[0] == 'j') {
                            continue;
                        }
                        $objGrupoAcao->AdicionarGrupoAcaoDuplicate($grupo, $permissao);
                    }
                }

            }
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
		$template = "ajax.grupo_acao.php";
		break;

	case "frm_atualizar_grupo_acao" :
		$grupo_acao = new GrupoAcao();
		$grupo_acao->setId($_REQUEST["app_codigo"]);
		$linha = $grupo_acao->Editar();
		$template = "tpl.frm.grupo_acao.php";
		break;

	case "atualizar_grupo_acao":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objGrupoAcao = new GrupoAcao($pdo);
			$objGrupoAcao->setIdAcao($_REQUEST['id_acao']);
			$objGrupoAcao->setCustomizada($_REQUEST['customizada']);
			$objGrupoAcao->Modificar();
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
		$template = "ajax.grupo_acao.php";
		break;

	case "listar_grupo_acao":
		$template = "tpl.geral.grupo_acao.php";
		break;

	case "deletar_grupo_acao":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objGrupoAcao = new GrupoAcao($pdo);
			$objGrupoAcao->Remover($_REQUEST['registros']);
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
		$template = "ajax.grupo_acao.php";
		break;

	case "ajax_listar_grupo_acao":
		$template = "tpl.lis.grupo_acao.php";
		break;

	case "grupo_acao_pdf":
		$template = "tpl.lis.grupo_acao.pdf.php";
		break;

	case "grupo_acao_xlsx":
		$template = "tpl.lis.grupo_acao.xlsx.php";
		break;

	case "grupo_acao_print":
		$template = "tpl.lis.grupo_acao.print.php";
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
			$_SESSION["configuracao_usuario"]["grupo_acao"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("grupo_acao");
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
		$template = "ajax.grupo_acao.php";
		break;
}
