<?

switch($app_comando)
{
	case "frm_adicionar_menu":
		$template = "tpl.frm.menu.php";
		break;
    case "icons_menu_list":
        $template = "tpl.frmclass.php";
        break;
    case "icones_fontawesome":
        $template = "tpl.awesome.php";
        break;
	case "adicionar_menu":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objMenuNovo = new Menu($pdo);
			$objMenuNovo->setNome($_REQUEST['nome']);
			$objMenuNovo->setDescricao($_REQUEST['descricao']);
			$objMenuNovo->setIdAcao($_REQUEST['id_acao']);
			$objMenuNovo->setIdPai($_REQUEST['id_pai']);
			$objMenuNovo->setOrdem($_REQUEST['ordem']);
			$objMenuNovo->setAcao($_REQUEST['acao']);
			$objMenuNovo->setIndex($_REQUEST['index']);
			$objMenuNovo->setTarget($_REQUEST['target']);
			$objMenuNovo->setIcone($_REQUEST['icone']);
			$novoId = $objMenuNovo->Adicionar();
			$msg["codigo"] = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
			$pdo->commit();
		} catch (Exception $e) {
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
			$msg["debug"] = $e->getMessage();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.menu.php";
		break;
    case "frm_adicionar_menu_completo":
        $template = "tpl.frm.menu_completo.php";
        break;

    case "adicionar_menu_completo":
//        Conexao::pr($_REQUEST);
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {

            $acao = Utils::PegarVetorTagIt($_REQUEST['acao']);

            $objModulo = new Modulo($pdo);
            $objModulo->setNome($_REQUEST['nome']);
            $objModulo->setDir($_REQUEST['dir']);
            $novoIdModulo = $objModulo->Adicionar();

            $objAcao = new Acao($pdo);
            $objAcao->setNome($_REQUEST['nome']);
            $acoes = implode('|',$acao);
            $objAcao->setAcao($acoes);
            $objAcao->setModulo($novoIdModulo);
            $novoIdAcao = $objAcao->Adicionar();

            $objMenuNovo = new Menu($pdo);
            $objMenuNovo->setNome($_REQUEST['nome']);
            $objMenuNovo->setDescricao($_REQUEST['descricao']);
            $objMenuNovo->setIdAcao($novoIdAcao);
            $objMenuNovo->setIdPai($_REQUEST['id_pai']);
            $objMenuNovo->setOrdem($_REQUEST['ordem']);
            $objMenuNovo->setAcao($_REQUEST['acao_primaria']);
            $objMenuNovo->setIndex($_REQUEST['index']);
            $objMenuNovo->setTarget($_REQUEST['target']);
            $objMenuNovo->setIcone($_REQUEST['icone']);
            $novoId = $objMenuNovo->Adicionar();

            $msg["codigo"] = 0;
            $msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            $msg["debug"] = $e->getMessage() . " - " . $e->getLine() . " - " . $e->getFile();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.menu.php";
        break;
	case "frm_atualizar_menu" :
		$menu = new Menu();
		$menu->setId($_REQUEST["app_codigo"]);
		$linha = $menu->Editar();
		$template = "tpl.frm.menu.php";
		break;

	case "atualizar_menu":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objMenuNovo = new Menu($pdo);
			$objMenuNovo->setId($_REQUEST['id']);
			$objMenuNovo->setNome($_REQUEST['nome']);
			$objMenuNovo->setDescricao($_REQUEST['descricao']);
			$objMenuNovo->setIdAcao($_REQUEST['id_acao']);
			$objMenuNovo->setIdPai($_REQUEST['id_pai']);
			$objMenuNovo->setOrdem($_REQUEST['ordem']);
			$objMenuNovo->setAcao($_REQUEST['acao']);
			$objMenuNovo->setIndex($_REQUEST['index']);
			$objMenuNovo->setTarget($_REQUEST['target']);
			$objMenuNovo->setIcone($_REQUEST['icone']);
			$objMenuNovo->Modificar();
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
		$template = "ajax.menu.php";
		break;

	case "listar_menu":
		$template = "tpl.geral.menu.php";
		break;

	case "deletar_menu":
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {
			$objMenuNovo = new Menu($pdo);
			$objMenuNovo->Remover($_REQUEST['registros']);
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
		$template = "ajax.menu.php";
		break;

	case "ajax_listar_menu":
		$template = "tpl.lis.menu.php";
		break;

	case "menu_pdf":
		$template = "tpl.lis.menu.pdf.php";
		break;

	case "menu_xlsx":
		$template = "tpl.lis.menu.xlsx.php";
		break;

	case "menu_print":
		$template = "tpl.lis.menu.print.php";
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
			$_SESSION["configuracao_usuario"]["menu"] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
			$usuarioConfiguracao->setDirModulo("menu");
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
		$template = "ajax.menu.php";
		break;
}
