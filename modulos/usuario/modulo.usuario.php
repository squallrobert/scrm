<?
//Conexao::pr($_SESSION);
include_once(URL_FILE . "idioma/" . DIR_IDIOMA . "/modulos/usuario.loc.php");
$_POST['id_usuario_tipo'] = ($_SESSION['usuario']['tipo'] == 3) ? 4 : $_POST['id_usuario_tipo'];
$_POST['id_grupo'] = ($_SESSION['usuario']['tipo'] == 3) ? $_SESSION['usuario']['id_grupo'] : $_POST['id_grupo'];

if ($_REQUEST['tipo_listagem'] == 1) {
    $titulo_retorno = "Cadastro de Fornecedores";
    $modulo_retorno = "fonecedor";
    $comando_retorno = "listar_fonecedor";
} elseif ($_REQUEST['tipo_listagem'] == 2) {
    $titulo_retorno = "Cadastro de Corretores";
    $modulo_retorno = "cliente_corretores";
    $comando_retorno = "listar_cliente_corretores";
} elseif ($_REQUEST['tipo_listagem'] == 3) {
    $titulo_retorno = "Meu Dados";
    $modulo_retorno = "usuario";
    $comando_retorno = "frm_atualizar_meus_dados";
} else {
    $titulo_retorno = "Cadastro de Usuários";
    $modulo_retorno = "usuario";
    $comando_retorno = "listar_usuario";
}


switch ($app_comando) {
	case "frm_adicionar_usuario" :
        $tituloPagina = RTL_ADICIONAR_USUARIO;
		$bt_acao      = "bt_salvar_usuario";
		//$linha        = new stdClass();
		$linha['ativo'] = 1;
		$template     = "tpl.frm.usuario.php";
		break;

	case "adicionar_usuario" :

        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            //ADAPTANDO CAMPO COM MASCARA PARA SEPARAR DDD DO TELEFONE

            //SET DOS PARAMETROS DO ENDEREÇO
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

            //SET DOS PARAMETROS DO USUARIO
            $usuario  = new Usuario($pdo);
            $grupo = $usuario->VerificarGrupo($_SESSION['usuario']['id_grupo'], $_POST['id_grupo']);
            if (empty($grupo))
                throw new Exception("Você não tem permissão para selecionar esse grupo. Selecione o grupo via select e tente novamente.", 2);

            $usuario->setId_grupo($_POST['id_grupo']);
            $usuario->setIdUsuarioTipo($_POST['id_usuario_tipo']);
            $usuario->setNome($_POST['nome']);
            $usuario->setUsuario($_REQUEST['email']);
            $usuario->setTimezone($_REQUEST['usu_timezone']);
            $usuario->setSenha($_POST['senha']);
            $usuario->setSenha_confirmacao($_POST['confirmar_senha']);
            $usuario->setEmail($_REQUEST['email']);
            $usuario->setSenha_provisoria($_POST['senha_provisoria']);
            $usuario->setId_fuso_horario(23);
            $usuario->setAtivo($_POST['ativo']);
                $usuario->setRg($_POST['rg']);
            $usuario->setMaster($_POST['master']);
            if ($_POST['data_hora_expirado']) $usuario->setData_hora_expirado(Conexao::PrepararDataBD($_POST['data_hora_expirado'], $_SESSION['usuario']['fuso_horario']));

            //CHECA SE JÁ EXISTE USUARIO CADASTRADO COM O EMAIL DIGITADO E RETORNA TRUE CASO NÃO EXISTA
            $validarUsuario = $usuario->VerificarCadastrado(true);
            if( $validarUsuario['codigo'] === 0){
                //ADICIONA O ENDERECO
                $idEndereco = $objEndereco->Adicionar();
                $usuario->setId_endereco($idEndereco);

                //ADICIONANDO FOTO DO USUARIO
                if (count($_FILES) > 0) {
                    $upload = new Upload("upload/fotos_usuario/");
                    list($nome, $extensao) = explode(".", $_FILES['foto']['name']);
                    switch ($extensao) {
                        case 'jpg':
                        case 'JPG':
                        case 'jpeg':
                        case 'JPEG':
                        case 'png':
                        case 'PNG':
                        case 'bmp':
                        case 'BMP':
                            break;
                        default:
                            $msg["codigo"]   = 1;
                            $msg["mensagem"] = 'Formato da imagem inválido';
                    }
                    $temp   = $_FILES['foto']['tmp_name'];
                    $novo   = $_SESSION['usuario']['id'] . (mktime()) . ".$extensao";
                    $imagem = $upload->Preparar($temp, $novo);
                    $usuario->setFoto($imagem);
//                    $_SESSION['usuario']['foto'] = $imagem;

                    $thumbs = new Thumbs();
                    $thumbs->caminho     = "upload/fotos_usuario/";
                    $thumbs->arquivo     = $imagem;
                    $thumbs->largura_max = 600;
                    $thumbs->altura_max  = 600;
                    $thumbs->Prepare();
                }
                //ADICIONA O USUARIO
                $idUsuario = $usuario->Adicionar();

                $acao_usuario_tipo = new AcaoUsuarioTipo();
                $acao_usuario_tipo->AdicionarFuncionalidadesTipo($idUsuario,$_POST['id_usuario_tipo']);

                $enviar = new Email($pdo);
                $params['email'] = $_REQUEST['email'];
                $params['nome'] = $_REQUEST['nome'];
                $params['senha'] = $_POST['senha'];
                $corpoEmail = GerarEmails::EmailWelcome($params);
                $destinatarios[] = $_REQUEST['email'];
                $destinatarios[] = 'contato@squall.com.br';
                $destinatarios[] = 'fotografia@sheephouse.com.br';
                $enviar->EnviarEmail("Bem vindo a SheepHouse", $corpoEmail, $destinatarios);

                $msg["codigo"] = 0;
                $msg["mensagem"] = 'Sucesso ao adicionar usuário';
                $pdo->commit();
            }else{
                $msg = $validarUsuario;
            }
        }catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $msg["debug"]['erro'] = $e->getMessage();
            $msg["debug"]['file'] = $e->getFile();
            $msg["debug"]['line'] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($msg);
		$template = "ajax.usuario.php";
		break;

	case "frm_atualizar_usuario" :
		$tituloPagina = 'Modificar Usuário';
		$bt_acao      = "bt_atualizar_usuario";
        $usuario = new Usuario();
		$usuario->setId($app_codigo);
		$linha    = $usuario->Editar();
		$template = "tpl.frm.usuario.php";
		break;

	case "frm_atualizar_meus_dados":
        $usuario = new Usuario();
		$tituloPagina = 'Modificar Meus Dados';
		$bt_acao      = "bt_atualizar_usuario";
		$usuario->setId($_SESSION['usuario']['id']);
		$linha    = $usuario->Editar();
        $template = "tpl.frm.usuario.php";
		break;

	case "atualizar_usuario_meus_dados" :
		$pdo = new Conexao();
		$pdo->beginTransaction();
		try {

			//SET DOS PARAMETROS DO ENDEREÇO

            $objEndereco = new Endereco($pdo);
            $objEndereco->setId($_POST['id_endereco']);
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

			//SET DOS PARAMETROS DO USUARIO
			$usuario  = new Usuario($pdo);
			$usuario->setId($_SESSION['usuario']['id']);
			$usuario->setNome($_POST['nome']);
			$usuario->setUsuario($_POST['usuario']);
			$usuario->setTimezone($_REQUEST['usu_timezone']);
			$usuario->setSenha($_POST['nova_senha']);
			$usuario->setSenha_confirmacao($_POST['repetir_nova_senha']);
			$usuario->setEmail($_POST['email_user']);
            $usuario->setRg($_POST['rg']);

			//VERIFICA SE HOUVE ALTERACAO DE EMAIL
			$linha = $usuario->Editar();
			if ($linha->email_usuario != $_POST['email']) $usuario->setValidacao('houve alteração de email');

			//CHECA SE JÁ EXISTE USUARIO CADASTRADO COM O EMAIL DIGITADO E RETORNA TRUE CASO NÃO EXISTA
            $confirmaUsuario = $usuario->VerificarCadastrado(true);

			if ($confirmaUsuario['codigo'] === 0 ) {
                if (count($_FILES) > 0) {
                    $usuarioAux = $usuario->Editar();
                    @unlink($usuarioAux->foto);
                    $upload = new Upload("upload/fotos_usuario/");
                    list($nome, $extensao) = explode(".", $_FILES['foto']['name']);

                    switch ($extensao) {
                        case 'jpg':
                        case 'JPG':
                        case 'jpeg':
                        case 'JPEG':
                        case 'png':
                        case 'PNG':
                        case 'bmp':
                        case 'BMP':
                            break;
                        default:
                            $msg["codigo"]   = 1;
                            $msg["mensagem"] = TXT_FORMATO_FOTO_INVALIDO;
                    }
                    $temp   = $_FILES['foto']['tmp_name'];
                    $novo   = $_SESSION['usuario']['id'] . (mktime()) . ".$extensao";
                    $imagem = $upload->Preparar($temp, $novo);
                    $usuario->setFoto($imagem);
//                    $_SESSION['usuario']['foto'] = $imagem;

                    $thumbs = new Thumbs();
                    $thumbs->caminho     = "upload/fotos_usuario/";
                    $thumbs->arquivo     = $imagem;
                    $thumbs->largura_max = 600;
                    $thumbs->altura_max  = 600;
                    $thumbs->Prepare();

                    $usuario->ModificarFoto();
                }

				$idEndereco = $objEndereco->Modificar();
				$usuario->ModificarSimples();

				//print_r($_REQUEST);
				unset($_SESSION['usuario']['provisoria']);
				$msg["codigo"]   = 0;
				$msg["mensagem"] = TXT_ALERT_SUCESSO_MODIFICAR;
				$pdo->commit();
			}else{
				$msg = $confirmaUsuario;
			}
		}catch (Exception $e){
			$msg["codigo"] = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO . $e->getMessage();
            $msg["debug"]['erro'] = $e->getMessage();
            $msg["debug"]['file'] = $e->getFile();
            $msg["debug"]['line'] = $e->getLine();
			$pdo->rollBack();
		}
		echo json_encode($msg);
		$template = "ajax.usuario.php";
		break;
	

	case "atualizar_usuario" :
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {


            //SET DOS PARAMETROS DO ENDEREÇO
            $objEndereco = new Endereco($pdo);
            $objEndereco->setId($_POST['id_endereco']);
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

            //SET DOS PARAMETROS DO USUARIO
            $usuario  = new Usuario($pdo);
            $grupo = $usuario->VerificarGrupo($_SESSION['usuario']['id_grupo'], $_POST['id_grupo']);
            if (empty($grupo))
                throw new Exception("Você não tem permissão para selecionar esse grupo. Selecione o grupo via select e tente novamente.", 2);

            $usuario->setId($_SESSION['id_usuario_temp']);
            $usuario->setId_grupo($_POST['id_grupo']);
            $usuario->setIdUsuarioTipo($_POST['id_usuario_tipo']);
            $usuario->setNome($_POST['nome']);
            $usuario->setUsuario($_POST['email']);
            $usuario->setTimezone($_REQUEST['usu_timezone']);
            $usuario->setSenha($_POST['senha']);
            $usuario->setSenha_confirmacao($_POST['confirmar_senha']);
            $usuario->setEmail($_POST['email']);
            $usuario->setId_fuso_horario(23);
            $usuario->setAtivo($_POST['ativo']);
            $usuario->setMaster($_POST['master']);
            $usuario->setRg($_POST['rg']);
            if ($_POST['data_hora_expirado']) $usuario->setData_hora_expirado(Conexao::PrepararDataBD($_POST['data_hora_expirado'], $_SESSION['usuario']['timezone']));
            //VERIFICA SE HOUVE ALTERACAO DE EMAIL
            $linha = $usuario->Editar();
            if ($linha->email_usuario != $_POST['email']) $usuario->setValidacao('houve alteração de email');

            //CHECA SE JÁ EXISTE USUARIO CADASTRADO COM O EMAIL DIGITADO E RETORNA TRUE CASO NÃO EXISTA
             $confirmaUsuario = $usuario->VerificarCadastrado(true);

            if ($confirmaUsuario['codigo'] === 0 ) {
                if (count($_FILES) > 0) {
                    $usuarioAux = $usuario->Editar();
                    @unlink($usuarioAux->foto);
                    $upload = new Upload("upload/fotos_usuario/");
                    list($nome, $extensao) = explode(".", $_FILES['foto']['name']);

                    switch ($extensao) {
                        case 'jpg':
                        case 'JPG':
                        case 'jpeg':
                        case 'JPEG':
                        case 'png':
                        case 'PNG':
                        case 'bmp':
                        case 'BMP':
                            break;
                        default:
                            $msg["codigo"]   = 1;
                            $msg["mensagem"] = TXT_FORMATO_FOTO_INVALIDO;
                    }
                    $temp   = $_FILES['foto']['tmp_name'];
                    $novo   = $_SESSION['usuario']['id'] . (mktime()) . ".$extensao";
                    $imagem = $upload->Preparar($temp, $novo);
                    $usuario->setFoto($imagem);
//                    $_SESSION['usuario']['foto'] = $imagem;

                    $thumbs = new Thumbs();
                    $thumbs->caminho     = "upload/fotos_usuario/";
                    $thumbs->arquivo     = $imagem;
                    $thumbs->largura_max = 600;
                    $thumbs->altura_max  = 600;
                    $thumbs->Prepare();

                    $usuario->ModificarFoto();
                }

                if (empty($_REQUEST['id_endereco'])){
                    $idEndereco = $objEndereco->Adicionar();
                    $usuario->setId_endereco($idEndereco);
                }else
                    $idEndereco = $objEndereco->Modificar();

                $usuario->Modificar();

                $acao_usuario_tipo = new AcaoUsuarioTipo($pdo);
                $acao_usuario_tipo->RemoverAllUsuario($_SESSION['id_usuario_temp']);
                $acao_usuario_tipo->AdicionarFuncionalidadesTipo($_SESSION['id_usuario_temp'],$_POST['id_usuario_tipo']);



                $msg["codigo"]   = 0;
                $msg["mensagem"] = TXT_ALERT_SUCESSO_MODIFICAR;
                $pdo->commit();
            }else{
                $msg = $confirmaUsuario;
            }
        }catch (Exception $e){
            $msg["codigo"] = 1;
            $msg["mensagem"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $msg["debug"] = $e->getMessage();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.usuario.php";
        break;

	case "listar_usuario" :
		$template = "tpl.geral.usuario.php";
		break;

	case "deletar_usuario" :
        $usuario = new Usuario();
		if (count($_POST['registros']) > 0) {
			$retorno = $usuario->Remover($_POST['registros']);
			if ($retorno == 1) {
				$msg["codigo"]   = 0;
				$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
			} else {
				$msg["codigo"]   = 1;
				$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
			}
			echo json_encode($msg);
		}
		$template = "ajax.usuario.php";

		break;
		case "resetar_permissao" :
            $pdo = new Conexao();
            $pdo->beginTransaction();
            try {
                $objUsuario = new MapUsuarioAcao($pdo);
                $objUsuario->setIdUsuario($_REQUEST['id']);
                $objUsuario->RemoveraAll();
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
		$template = "ajax.usuario.php";
		break;
	case "ajax_listar_usuario" :
		$template = "tpl.lis.usuario.php";
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

			$_SESSION['configuracao_usuario']['usuario'] = $colunasSelecionadas;
			$usuarioConfiguracao->setIdUsuario($_SESSION['usuario']['id']);
			$usuarioConfiguracao->setDirModulo("usuario");
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

		$template = "ajax.usuario.php";
		break;

	case "usuario_pdf":
		$template = "tpl.lis.usuario.pdf.php";
		break;

	case "usuario_print":
		$template = "tpl.lis.usuario.print.php";
		break;

    case "usuario_xlsx":
		$template = "tpl.lis.usuario.xlsx.php";
		break;

	case "remover_foto":
		$usuario = new Usuario();
		$usuario->setId($_REQUEST['id_usuario']);

		if ($usuario->RemoverFoto()) {
			$_SESSION['usuario']['foto'] = "";
			$msg["codigo"]               = 0;
			$msg["mensagem"]             = TXT_ALERT_SUCESSO_OPERACAO;
		} else {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
		}

		echo json_encode($msg);

		$template = "ajax.usuario.php";
		break;

	case "frm_vincular_grupos":
		$template = "tpl.frm.vincular_grupos.usuario.php";
		break;

	case "vincular_grupos":
		$usuario = new Usuario();
		$usuario->setId($_REQUEST['id_usuario']);

		//Remove todos os registros que não estão mais na lista postada
		$usuario->RemoverClones($_REQUEST['id_usuario'], @implode(",", $_REQUEST['grupos']));

		//Adiciona os registros postados que não estão no banco
		$usuarioBase = $usuario->Editar();

		try {
			if (count($_REQUEST['grupos']) > 0) {
				foreach ($_REQUEST['grupos'] AS $idGrupo) {
					$usuarioAux = new Usuario();
					$usuarioAux->setIdUsuarioPai($_REQUEST['id_usuario']);
					$usuarioAux->setId_grupo($idGrupo);
					$usuarioAux->setNome($usuarioBase->nome);
					$usuarioAux->setUsuario($usuarioBase->usuario);
					$usuarioAux->setSenha($usuarioBase->senha);
					$usuarioAux->setPergunta_senha($usuarioBase->pergunta_senha);
					$usuarioAux->setResposta_senha($usuarioBase->resposta_senha);
					$usuarioAux->setSenha_provisoria($usuarioBase->senha_provisoria);
					$usuarioAux->setId_fuso_horario($usuarioBase->id_fuso_horario);
					$usuarioAux->setAtivo($usuarioBase->ativo);
					$usuarioAux->setMaster($usuarioBase->master);
					$usuarioAux->setData_hora_expirado($usuarioBase->data_hora_expirado);
					$usuarioAux->setEmail($usuarioBase->usuario);
					$usuarioAux->setValidacao("1");
					$usuarioAux->setTutorial("1");
					$usuarioAux->Adicionar();
				}
			}

			$msg["codigo"]   = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
		} catch (Exception $e) {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            $msg["debug"] = $e->getMessage();
		}

		$_SESSION['usuario']['qtd_filhos'] = count($usuario->BuscarGruposVinculados($_SESSION['usuario']['id'], $_SESSION['usuario']['id_grupo']));
		$msg["qtd_filhos"]                 = $_SESSION['usuario']['qtd_filhos'];

		echo json_encode($msg);
		break;

	case "frm_acessar_grupos":
		$template = "tpl.frm.emular.usuario.php";
		break;

	case "frm_modificar_foto":
		$template = "tpl.frm.mudar_foto.usuario.php";
		break;
	case "modificar_foto":
		$usuario    = new Usuario();
		$usuarioAux = new Usuario();

		$usuario->setId($_REQUEST['id']);
		$usuarioAux->setId($_REQUEST['id']);

		if (count($_FILES) > 0) {
			$usuarioAux = $usuario->Editar();
			@unlink($usuarioAux->foto);
			$upload = new Upload("../assets/fotos_usuario/");
			list($nome, $extensao) = explode(".", $_FILES['foto']['name']);

			switch ($extensao) {
				case 'jpg':
				case 'JPG':
				case 'jpeg':
				case 'JPEG':
				case 'png':
				case 'PNG':
				case 'bmp':
				case 'BMP':
					break;

				default:
					$msg["codigo"]   = 1;
					$msg["mensagem"] = TXT_FORMATO_FOTO_INVALIDO;
					echo json_encode($msg);
					die();
			}
			$temp   = $_FILES['foto']['tmp_name'];
			$novo   = $_SESSION['usuario']['id'] . (mktime()) . ".$extensao";
			$imagem = $upload->Preparar($temp, $novo);
			$usuario->setFoto($imagem);

			$thumbs = new Thumbs();
			$thumbs->caminho     = "../assets/fotos_usuario/";
			$thumbs->arquivo     = $imagem;
			$thumbs->largura_max = 600;
			$thumbs->altura_max  = 600;
			$thumbs->Prepare();
		}

		if ($usuario->ModificarFoto()) {
			$msg["codigo"]   = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
			$msg["imagem"] = $imagem;
		} else {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
		}

		echo json_encode($msg);
		break;

	case "frm_fechar_sessao":
		$template = "tpl.frm.fechar_sessao.usuario.php";
		break;

	case "fechar_sessao":
		$usuario = new Usuario();
		$usuario->setId($_SESSION['usuario']['id']);
		$retorno = $usuario->finalizarSessaoPendente($_SESSION['usuario']['timezone'], $_REQUEST['data_hora'], $_REQUEST['observacao']);

		if ($retorno == 1) {
			$_SESSION['sessao_pendente'] = 0;
			$msg["codigo"]               = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
		} else {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
		}
		echo json_encode($msg);

		$template = 'ajax.usuario.php';
		break;

	case "frm_atualizar_fuso_horario":
		$template = "tpl.frm.fuso_horario.php";
		break;

	case "atualizar_fuso_horario":
		$usuario = new Usuario();
		$usuario->setId($_SESSION['usuario']['id']);
		$usuario->setTimezone($_REQUEST['timezone']);
		if ($usuario->ModificarTimezone() == 1) {
			$_SESSION['usuario']['timezone'] = $_REQUEST['timezone'];
			$msg["codigo"]   = 0;
			$msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
		} else {
			$msg["codigo"]   = 1;
			$msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
		}
		echo json_encode($msg);
		$template = "ajax.usuario.php";
		break;

	case "buscar_cep":
	case "json_listar_usuarios":
	case "buscar_quantidade_filhos":
	case "busca_usuario_multiselect":
	case "busca_usuario_auto_complete":
	case "popup_localizar_usuarios_corretores":
	case "checar_tutorial":
	case "verificar_timezone":
	case "verificar_senha_expirada":
	case "verificar_dica_diaria":
		$template = "ajax.usuario.php";
		break;
    case "frm_permissoes_usuario":
        $usuario= new Usuario();
        $usuario->setId($_REQUEST['app_codigo']);
        $linha = $usuario->Editar();
        $template = 'tpl.frm.permissoes.php';
        break;
    case "permissoes_usuario":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $usuarioAcao= new MapUsuarioAcao($pdo);
            $usuarioAcao->setIdUsuario($_REQUEST['id_usuario']);
            $usuarioAcao->RemoveraAll();
            $usuario= new Usuario();
            $usuario->setId($_REQUEST['id_usuario']);
            $linha = $usuario->Editar();
            if(count($_REQUEST['acoes']) > 0)
            {
                foreach ($_REQUEST['acoes'] as $permissao) {
                    if ($permissao[0] == 'j') {
                        continue;
                    }
                    $usuarioAcao->setCustomnizada(($usuarioAcao->ChecarCustomizada($linha['id_usuario_tipo'],$permissao) > 0) ? 0 : 1);
                    $usuarioAcao->setIdAcao($permissao);
                    $usuarioAcao->Adicionar();
                }
            }

            $msg["codigo"] = 0;
            $msg["mensagem"] = 'Sucesso ao executar Operação';
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO . " ". $e->getMessage();
            $msg["debug"] = $e->getMessage();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.usuario.php";
        break;
        
    case "popup_localizar_usuarios":
    case "popup_localizar_usuarios_mapas":
        $template = "ajax.usuario.php";
        break;

    case "listar_usuarios_launch":
        $template = "tpl.lis.usuarios_launch.php";
        break;

}



