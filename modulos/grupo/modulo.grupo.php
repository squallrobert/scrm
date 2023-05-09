<?
ignore_user_abort(true);
include_once(URL_FILE . "idioma/" . DIR_IDIOMA . "/modulos/grupo.loc.php");

$acoes       = array();
$prioridades = array();

if (count($_POST))
{
	foreach ($_POST as $campo => $valor)
	{
		if (strpos($campo, "id_acao_") !== false)
		{
			array_push($acoes, array("id" => str_replace("id_acao_", "", $campo)));
		}

		if (strpos($campo, "id_evento_tipo_") !== false)
		{
			array_push($prioridades, array("id_evento_tipo" => str_replace("id_evento_tipo_", "", $campo), "id_prioridade" => $valor));
		}
	}
}


switch ($app_comando) {
	case "frm_adicionar_grupo" :
		$tituloPagina = "Adicionar Grupo";
		$bt_acao      = "bt_salvar_grupo";
		$template     = "tpl.frm.grupo.php";
		break;

    case "adicionar_grupo" :
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try{
            //INICIO ADICIONAR GRUPO
            $grupo = new Grupo($pdo);
            $grupoAux = new Grupo(); //grupo_pai
            $grupoAux->setId($_POST['id_grupo_pai']);
            $grupoAux = $grupoAux->Editar();


            $grupo->setId_grupo_pai($_POST['id_grupo_pai']);
            $grupo->setNome($_POST['nome']);
            $grupo->setIdCategoria($grupoAux['id_categoria']);


            //VERIFICA SE O NOME DO GRUPO ESTA CADASTRADO
            if (!$grupo->VerificarCadastrado()) {
                $idGrupo = $grupo->Adicionar();
                if ($idGrupo > 0) {
                    foreach ($_POST['acoes'] as $permissao) {
                        if ($permissao[0] == 'j') {
                            continue;
                        }
                        $grupo->AdicionarGrupoAcao($idGrupo, $permissao);
                    }
                }
            } else {
                $nome_erro = TXT_GRUPO_JA_CADASTRADO;
                throw new Exception('Group Exception');
            }


            $msg["codigo"]   = 0;
            $msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
            $pdo->commit();
        }catch (Exception $e){
            $msg["codigo"]   = 1;
            $msg["mensagem"] = $nome_erro ?: TXT_ALERT_ERRO_OPERACAO;
            $msg["debug"]['error'] = $e->getMessage();
            $msg["debug"]['file'] = $e->getFile();
            $msg["debug"]['line'] = $e->getLine();
            $pdo->rollBack();
        }

        echo json_encode($msg);
        $template = "ajax.grupo.php";
        break;

	case "frm_atualizar_grupo" :
		$tituloPagina = "Atualizar Grupo";
		$bt_acao      = "bt_atualizar_grupo";
		$grupo        = new Grupo();
		$grupo->setId($_REQUEST['app_codigo']);
		$linha    = $grupo->Editar();
		$template = "tpl.frm.grupo.php";

		break;

	case "atualizar_grupo" :

		foreach ($_REQUEST['acoes'] as $acao) {
			if (substr($acao, 0, 1) !== "j") {
				$acoes[] = $acao;
			}
		}
//        Conexao::pr($acoes);
		$grupo = new Grupo();
		$grupo->setId($_POST['id']);
		$grupo->setId_grupo_pai($_POST['id_grupo_pai']);

		if ($_POST['id_grupo'] == $_POST['id_grupo_pai']) {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = "...";
			echo json_encode($msg);
			die();
		}

		$grupo->setNome($_POST['nome']);
		if (!$grupo->VerificarCadastrado()) {
			if ($acoes[0] != "") {
				if ($grupo->Modificar()) {
					$grupo->LimparAcoes();
					$grupo->LimparAcoesArvore($acoes);
					foreach ($acoes as $permissao) {
						$resultado = $grupo->AdicionarGrupoAcao($_POST['id'], $permissao, 1);
					}
				}
			}

			if ($resultado == 1) {
				$msg["codigo"]   = 0;
				$msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
			} else {
				$msg["codigo"]   = 1;
				$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
			}
		} else {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_NOME_CADASTRADO;
		}
		echo json_encode($msg);
		$template = "ajax.grupo.php";
		break;

	case "listar_grupo" :

		$template = "tpl.geral.grupo.php";

		break;

	case "deletar_grupo" :

		$grupo = new Grupo();
		if (count($_POST['registros']) > 0) {
			$retorno = $grupo->Remover($_POST['registros']);
			if ($retorno == 1) {
				$msg["codigo"]   = 0;
				$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
			}else if($retorno == false) {
                $msg["codigo"]   = 1;
                $msg["mensagem"] = TXT_ERRO_CONTRATOS_ATIVOS;
            }
            else{
                $msg["codigo"]   = 1;
                $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            }
			echo json_encode($msg);
		}
		$template = "ajax.grupo.php";

		break;
	case "ajax_listar_grupo" :

		$template = "tpl.lis.grupo.php";

		break;
	case "popup_localizar_grupo":
	case "popup_localizar_grupo_franqueado_franqueadora":
	case "popup_localizar_grupo_franqueado":
	case "popup_localizar_grupo_franqueado_todos":
	case "popup_localizar_grupo_permissoes":

		$template = "ajax.grupo.php";
		break;
		
	

	case "atualizar_funcionalidade_grupo":
		$grupo = new Grupo();
		echo json_encode($grupo->ListarFuncionalidades($_REQUEST['id_grupo_copiar'], $_REQUEST['id_grupo_copiar']));
		$template = "ajax.grupo.php";
		break;


	case "frm_atribuir_veiculos_rastreador":

		$grupo = new Grupo();
		$grupo->setId($app_codigo);
		$dados_grupo  = $grupo->Editar();
		$selecionados = $grupo->ListaRastreadoresVeiculosVinculados($app_codigo);
		$template     = "tpl.veiculos_rastreador.grupo.php";
		break;

    case "checar_veiculos_cliente":
        $grupo = new Grupo();
        $grupo->setId($_POST['id_grupo']);
        if($_POST['veiculos']){
            $veiculos = $grupo->ChecarVeiculosGrupo($_POST['veiculos']);
        }
        if(count($veiculos)>0){
            $msg['codigo']   = 0; //Encontrou veiculos que são de outro grupo
            $msg['veiculos'] = $veiculos;
        }else{
            $msg['codigo']   = 1; //Todos os veículos são permitidos
        }
        echo json_encode($msg);
        $template = "ajax.grupo.php";
        break;

	case "atribuir_veiculos_rastreador":
		$pdo = new Conexao();
		$pdo->beginTransaction();

		try {
			$grupo = new Grupo($pdo);
			$grupo->setId($_POST['id_grupo']);

			$grupo->AtualizarVinculoVeiculos($_POST['veiculos']);

			$msg['codigo']   = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
			$pdo->commit();
		} catch (Exception $e) {

			$msg['codigo']   = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
			$msg["debug"]    = $e->getMessage() . "<br/>Favor, abra um chamado anexando uma print desse erro.";
			$pdo->rollBack();
		}

		echo json_encode($msg);
		$template = "ajax.grupo.php";
		break;

	case "veiculos_ratreadores_grupo":
		$template = "ajax.grupo.php";
		break;


	case "popup_chage_prioridade_eventos":
		//$template = "tpl.grupo_eventos.grupo.php";
		$template = "prioridade.grupo.php";
		break;
	case "atualizar_veiculo_grupo":
		$grupo->setId($app_codigo);
		$linha    = $grupo->Editar();
		$template = "veiculo.grupo.php";
		break;
	case "atualizar_filho_grupo":
		$grupo->setId($app_codigo);
		$linha    = $grupo->Editar();
		$template = "filho.grupo.php";
		break;

	case "atualizar_modelo_grupo":
		//$grupo->setId($app_codigo);
		//$linha = $grupo->Editar();
		$template = "modelo.grupo.php";
		break;
	case "atualizar_rastreador_grupo":
		$grupo->setId($app_codigo);
		$linha    = $grupo->Editar();
		$template = "rastreador.grupo.php";
		break;
	case "atualizar_ponto_interesse_grupo":
		$grupo->setId($app_codigo);
		$linha    = $grupo->Editar();
		$template = "ponto_interesse.grupo.php";
		break;
	case "listar_grupos_ponto_interesse":
		$template = "ajax.grupo.php";
		break;
	case "popup_localizar_ponto_interesse_categoria":
		$template = "ajax.grupo.php";
		break;

	case "atualizar_popup_chage_prioridade_eventos":

		$grupo = new Grupo();
		$grupo->setId($_SESSION['usuario']['id_grupo']);
		$resultado = $grupo->AtribuirPrioridadeEventoTipo($prioridades);
		$resposta  = $resultado->mensagem;
		$template = "prioridade.grupo.php";
		break;
	case "arvore_grupos":
		$template = "tpl.arvore.grupo.php";
		break;
	case "frm_tratativas_grupo_eventos":

		$grupoNovo = new GrupoNovo();
		$grupoNovo->setId($app_codigo);
		$dados_grupo = $grupoNovo->Editar();
		$cont        = count($_SESSION['eventos']);
		if ($_GET['voltar'] != 1) {
			$_SESSION['eventos']['evento']['id']   = array();
			$_SESSION['eventos']['evento']['nome'] = array();


			$grupo_evento = new GrupoEvento();
			$grupo_evento->setIdGrupo($app_codigo);
			$listar = $grupo_evento->ListarEventoGrupoEvento();

			if (count($listar) > 0) {
				foreach ($listar as $indice) {
					array_push($_SESSION['eventos']['evento']['id'], $indice->id);
					array_push($_SESSION['eventos']['evento']['nome'], $indice->rotulo);
				}
			}
		}
		$template = "tpl.frm.tratativas_grupo_evento.php";
		break;
	case "listar_evento_tipo":
		$evento_tipo = new Evento_tipo();
		$evento_tipo->setIdGrupo($_GET['id_grupo']);
		$buscar = $_POST['buscar'];
		if (count($_SESSION['eventos']) > 0) {
			foreach ($_SESSION['eventos']['evento']['id'] as $indice => $valor) {
				$lista_ids .= $valor . ',';
			}
		}
		$listar = $evento_tipo->ListarEventoTipo(substr($lista_ids, 0, -1), $buscar);
		echo json_encode($listar);
		$template = "ajax.grupo.php";
		break;
	case "gerar_sessao_tratativas":

		$x = 0;
		if (count($_POST['lista']) > 0) {
			foreach ($_POST['lista'] as $indice) {
				$vetor['evento']['id'][$x]   = $indice;
				$vetor['evento']['nome'][$x] = $_POST['lista_nome'][$x];

				$x++;
			}
			unset($_SESSION['eventos']);
			$_SESSION['eventos'] = $vetor;
		}

		$template = "ajax.grupo.php";
		break;
	case "atribuir_veiculos_tratativas_grupo":
		$grupoNovo = new GrupoNovo();
		$grupoNovo->setId($app_codigo);
		$dados_grupo = $grupoNovo->Editar();

		$grupo_evento = new GrupoEvento();
		$grupo_evento->setIdGrupo($app_codigo);
		$listar   = $grupo_evento->ListarVeiculoGrupoEvento();
		$template = "tpl.frm.veiculo_grupo_evento.php";
		break;
	case "adicionar_tratativas_grupo":
		$grupo_evento = new GrupoEvento();
		$grupo_evento->setIdGrupo($_POST['id_grupo']);

		$retorno = $grupo_evento->Remover();
		/* echo "<pre>";
			print_r($remover);
		echo "</pre>";*/
		if ($retorno == 1) {
			if (count($_POST['lista']) > 0) {
				foreach ($_SESSION['eventos']['evento']['id'] as $indice => $valor) {
					$grupo_evento->setIdEventoTipo($valor);

					foreach ($_POST['lista'] as $indice) {
						$grupo_evento->setIdVeiculo($indice);
						$retorno = $grupo_evento->AdicionarGrupoEvento();
					}
				}
			}
		}
		if ($retorno == 1) {
			$msg['codigo']   = 0;
			$msg['mensagem'] = OPERACAO_SUCESSO;
		} else {
			$msg['codigo']   = 1;
			$msg['mensagem'] = OPERACAO_ERRO;
		}
		echo json_encode($msg);

		$template = "ajax.grupo.php";
		break;

	case "frm_configurar_listagem":

		$template = "configuracao_listagem.php";
		break;

	case "configurar_listagem":
		$usuarioConfiguracao = new UsuarioConfiguracao();
		foreach ($_POST as $checkbox => $idCampo) {
			if ($checkbox == 'limite_colunas') {
				continue;
			}

			if (is_array($idCampo)) {

				if ($idCampo['valor'] == "") {
					continue;
				} else {
					$colunasSelecionadas[$checkbox] = Array("id_campo" => $idCampo['id'], "valor_campo" => $idCampo['valor']);
				}
			} else {
				$colunasSelecionadas[$checkbox] = $idCampo;
			}
		}

		$countColunasSelecionadas = count($colunasSelecionadas);
		if ($countColunasSelecionadas > 0) {
			if (($countColunasSelecionadas > $_REQUEST['limite_colunas']) && $_REQUEST['limite_colunas'] != "0" && $_REQUEST['limite_colunas'] != "") {
				$msg["codigo"]   = 1;
				$msg["mensagem"] = TXT_LIMITE_COLUNAS_EXCEDIDO_RESOLUCAO;
				echo json_encode($msg);
				die();
			}

			$_SESSION['configuracao_usuario']['grupo'] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION['usuario']['id']);
			$usuarioConfiguracao->setDirModulo("grupo");
			$usuarioConfiguracao->LimparConfiguracoes();

			foreach ($colunasSelecionadas AS $nomeCampo => $idCampo) {
				if (is_array($idCampo)) {
					$usuarioConfiguracao->setIdCampoModulo($idCampo['id_campo']);
					$usuarioConfiguracao->setValorCampo($idCampo['valor_campo']);
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

		$template = "ajax.grupo.php";
		break;

	case "grupo_pdf":
		$template = "tpl.lis.grupo.pdf.php";
        break;

    case "grupo_xlsx":
		$template = "tpl.lis.grupo.xlsx.php";
        break;

	case "grupo_print":
		$template = "tpl.lis.grupo.print.php";
		break;

	case "gerar_relatorio_grupo":
		$template = "tpl.lis.grupo.veiculos.php";
		break;

    case "relatorio_grupo_xlsx":
        $template = "tpl.lis.grupo.veiculos.xlsx.php";
        break;

    case "relatorio_grupo_print":
        $template = "tpl.lis.grupo.veiculos.print.php";
        break;

    case "relatorio_grupo_pdf":
        $template = "tpl.lis.grupo.veiculos.pdf.php";
        break;

    case "frm_config_carga":
        $template = "tpl.frm.grupo.config_carga_horaria.php";
        break;

    case "config_carga":
        $objVeiculo = new Veiculo();
        $listaVeiculos = $objVeiculo->ListarVeiculosGrupoeVeiculosVinculados($_REQUEST['id_grupo']);

        if(count($listaVeiculos)>0){

            foreach ($listaVeiculos as $veiculo){
                $objVeiculo->setId($veiculo);
                $retorno = $objVeiculo->RemoverJornadas();

                if($retorno)
                {
                    for($x = 0; $x < count($_REQUEST['dia']); $x++)
                    {
                        $vetorJornada = Array("dia" => $_REQUEST['dia'][$x],
                            "ini_jornada"   => $_REQUEST['ini_jornada'][$x],
                            "ini_intervalo" => $_REQUEST['ini_intervalo'][$x],
                            "fim_intervalo" => $_REQUEST['fim_intervalo'][$x],
                            "fim_jornada"   => $_REQUEST['fim_jornada'][$x]);

                      $retorno = $objVeiculo->AdicionarJornada($vetorJornada);
                    }
                }
            }
            if($retorno == 1){
                $msg["codigo"]   = 0;
                $msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
            }else{
                $msg['codigo']   = 1;
                $msg['mensagem'] = ROTULO_OPERACAO_EXECUTADA_ERRO;
            }
        }else{
            $msg["codigo"]   = 2;
            $msg["mensagem"] = "Não há nenhum veículo cadastrado nem vinculado neste grupo.";
        }
        echo json_encode($msg);
        $template = "ajax.grupo.php";
        break;
    case "vincular_veiculos_grupo":
        $objGrupo = new Grupo();
        $objGrupo->setId($app_codigo);
        $linha = $objGrupo->Editar();
        $template = "tpl.vincular_veiculos.grupo.php";
        break;
    case "listar_veiculos_vinculos_grupo":
        $template = "tpl.veiculos.grupo.php";
        break;
    case "listar_veiculos_vinculados_grupos":

        $template = "tpl.veiculos_vinculados.grupo.php";
        break;
    case "atualizar_lista_vinculos_grupos":

        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objGrupoVeiculo = new GrupoVeiculo($pdo);
            $objGrupoVeiculo->setId_grupo($_REQUEST['id_grupo']);
            $objGrupoVeiculo->DesvincularTodos();
            $x = 0;
            if (count($_POST['id_veiculo']) > 0) {
                foreach ($_POST['id_veiculo'] as $row) {

                    $objGrupoVeiculo = new GrupoVeiculo($pdo);
                    $objGrupoVeiculo->setId_grupo($_REQUEST['id_grupo']);
                    $objGrupoVeiculo->setId_veiculo($row);
                    $novoId = $objGrupoVeiculo->Adicionar();
                }
            }
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
        $template = "ajax.grupo.php";
        break;

}

