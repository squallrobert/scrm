<?php
//Conexao::pr($_REQUEST);
switch ($app_comando) {
    case "login":
        $template = "tpl.login.php";
        break;
    case "cadastro":
        $template = "tpl.cadastro.login.php";
        break;
    case "esqueceu_senha":
        $template = "tpl.recuperar.php";
        break;
    case "enviar_email_recuperar_senha":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            //$_REQUEST = json_decode(file_get_contents("php://input"),true);
            //$_POST = json_decode(file_get_contents("php://input"),true);
            $usuario = new Usuario($pdo);
            $enviar = new Email($pdo);
            $dados = $usuario->LocalizarDadosUsuario($_REQUEST['email']);

            if (empty($dados['id']))
                throw new Exception("Email não encontrado!", 2);

            $parametros['id'] = $dados['id'];
            $parametros['codigo_validador'] = rand(100000,999999);
            $parametros['hash'] = md5(rand(100000,999999) . mktime());
            $usuario->UpdateParamsSenha($parametros);

            if(!empty($dados['id'])){
                $parametros['url'] = BASE_URL."/index.php?app_modulo=login&app_comando=frm_alterar_senha&codigo={$parametros['codigo_validador']}&hash={$parametros['hash']}&id={$dados['id']}";
                $parametros['nome'] = $dados['nome'];
                $conteudo = $usuario->FormatarEmailCodigoValidador($parametros);
                $destinatarios[] = $dados['email'];
                $titulo = "Seu código de recuperação do Sheephouse é: {$parametros['codigo_validador']}";
                $mail = $enviar->enviarEmail($titulo, $conteudo, $destinatarios);
                if(!$mail)
                    throw new Exception('Falha ao enviar e-mail!', 2);
            }
            $pdo->commit();
            $dados["codigo"] = 0;
        } catch (Exception $e) {
            $dados["codigo"] = ($e->getCode() == 2) ? 2 : 1;
            $dados["msg"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $dados["debug"]["error"] = $e->getMessage();
            $dados["debug"]["file"] = $e->getFile();
            $dados["debug"]["linha"] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($dados);
        $template = "ajax.login.php";
        break;
    case "frm_alterar_senha":
        $objUser = new Usuario();
        $rs = $objUser->VerificarHash($_REQUEST['codigo'],$_REQUEST['hash']);
        if($rs['codigo_validador'] == "")
            $template = "tpl.hash_invalida.php";
        else
            $template = "tpl.nova_senha.php";
        break;
    case "salvar_nova_senha":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $usuario = new Usuario($pdo);
            $params['senha'] = md5($_REQUEST['senha']);
            $params['hash'] = $_REQUEST['hash'];
            $params['codigo'] = $_REQUEST['codigo'];
            $rs = $usuario->VerificarHash($_REQUEST['codigo'],$_REQUEST['hash']);
            //$codigo = $usuario->VerificarCodigoValidadorHash($params);
            if($rs['codigo_validador'] == "")
                throw new Exception("Código de segurança inválido.", 2);
            $retorno = $usuario->UpdateSenha($params);
            $msg["codigo"] = 0;
            $msg["mensagem"] = 'Sucesso ao criar nova senha!';
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = ($e->getCode() == 2) ? 2 : 1;
            $msg["mensagem"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $msg["debug"]["error"] = $e->getMessage();
            $msg["debug"]["file"] = $e->getFile();
            $msg["debug"]["linha"] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.login.php";
        break;
    case "confirmar_cadastro":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            //SET DOS PARAMETROS DO ENDEREÇO
            $objEndereco = new Endereco($pdo);
            $objEndereco->setLogradouro($_REQUEST['endereco']);
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
            $objEndereco->setCelular($_REQUEST['telefone']);
            $objEndereco->setEmail($_REQUEST['email']);
            $objEndereco->setEmailMkt($_REQUEST['email_mkt']);
            $objEndereco->setEmailMkt2($_REQUEST['email_mkt2']);
            $objEndereco->setLatitude($_REQUEST['latitude']);
            $objEndereco->setLongitude($_REQUEST['longitude']);

            $objEndereco2 = new Endereco($pdo);
            $objEndereco2->setLogradouro($_REQUEST['endereco']);
            $objEndereco2->setNumero($_REQUEST['numero']);
            $objEndereco2->setComplemento($_REQUEST['complemento']);
            $objEndereco2->setBairro($_REQUEST['bairro']);
            $objEndereco2->setCidade($_REQUEST['cidade']);
            $objEndereco2->setEstado($_REQUEST['estado']);
            $objEndereco2->setCep($_REQUEST['cep']);
            $objEndereco2->setReferencia($_REQUEST['referencia']);
            $objEndereco2->setObservacao($_REQUEST['observacao']);
            $objEndereco2->setTelefone($_REQUEST['telefone']);
            $objEndereco2->setComercial($_REQUEST['comercial']);
            $objEndereco2->setCelular($_REQUEST['telefone']);
            $objEndereco2->setEmail($_REQUEST['email']);
            $objEndereco2->setEmailMkt($_REQUEST['email_mkt']);
            $objEndereco2->setEmailMkt2($_REQUEST['email_mkt2']);
            $objEndereco2->setLatitude($_REQUEST['latitude']);
            $objEndereco2->setLongitude($_REQUEST['longitude']);
            $idEndereco2 = $objEndereco2->Adicionar();

            $objEndereco3 = new Endereco($pdo);
            $objEndereco3->setLogradouro($_REQUEST['endereco']);
            $objEndereco3->setNumero($_REQUEST['numero']);
            $objEndereco3->setComplemento($_REQUEST['complemento']);
            $objEndereco3->setBairro($_REQUEST['bairro']);
            $objEndereco3->setCidade($_REQUEST['cidade']);
            $objEndereco3->setEstado($_REQUEST['estado']);
            $objEndereco3->setCep($_REQUEST['cep']);
            $objEndereco3->setReferencia($_REQUEST['referencia']);
            $objEndereco3->setObservacao($_REQUEST['observacao']);
            $objEndereco3->setTelefone($_REQUEST['telefone']);
            $objEndereco3->setComercial($_REQUEST['comercial']);
            $objEndereco3->setCelular($_REQUEST['telefone']);
            $objEndereco3->setEmail($_REQUEST['email']);
            $objEndereco3->setEmailMkt($_REQUEST['email_mkt']);
            $objEndereco3->setEmailMkt2($_REQUEST['email_mkt2']);
            $objEndereco3->setLatitude($_REQUEST['latitude']);
            $objEndereco3->setLongitude($_REQUEST['longitude']);
            $idEndereco3 = $objEndereco3->Adicionar();


            //INICIO ADICIONAR GRUPO
            $grupo = new Grupo($pdo);
            $grupoAux = new Grupo(); //grupo_pai
            $grupoAux->setId(4);
            $grupoAux = $grupoAux->Editar();


            $grupo->setId_grupo_pai(4);
            $grupo->setNome($_REQUEST['nome']);

            //VERIFICA SE O NOME DO GRUPO ESTA CADASTRADO
            if (!$grupo->VerificarCadastrado()) {
                $idGrupo = $grupo->Adicionar();
                $grupo->AdicionarPermissaoCliente($idGrupo);
            } else {
                $msg['debug']['msg'] = 'Já existe um grupo com este nome';
                throw new Exception('Group Exception');
            }


            $objCliente = new Cliente($pdo);
            $objCliente->setIdGrupo($idGrupo);
            $objCliente->setIdClienteTipoPessoa(2);
            $objCliente->setNome($_REQUEST['nome']);
            $objCliente->setNomeFantasia($_REQUEST['nome']);
            $objCliente->setCpfCnpj($_REQUEST['cpf_cnpj']);
            $objCliente->setInscricaoEstadual($_REQUEST['inscricao_estadual']);
            $objCliente->setIdEndereco($idEndereco2);
            $objCliente->setIdEnderecoCobranca($idEndereco3);
            $objCliente->setDataNascimento(Conexao::PrepararDataBD($_REQUEST['data_nascimento']));
            $objCliente->setIdClienteEstadoCivil($_REQUEST['id_cliente_estado_civil']);
            $objCliente->setSexo($_REQUEST['sexo']);
            $objCliente->setDiaVencimento($_REQUEST['dia_vencimento']);
            $objCliente->setIdFormaPagamento(16);
            $objCliente->setRg($_REQUEST['rg']);
            $objCliente->setStatus(1);
            $objCliente->setObservacaoDados($_REQUEST['observacao_dados']);
            $novoIdCliente = $objCliente->Adicionar();

            //SET DOS PARAMETROS DO USUARIO
            $usuario  = new Usuario($pdo);


            $usuario->setId_grupo($idGrupo);
            $usuario->setIdUsuarioTipo(4);
            $usuario->setNome($_POST['nome']);
            $usuario->setUsuario($_REQUEST['email']);
            $usuario->setTimezone('America/Sao_Paulo');
//            $senha = $usuario->gerarSenha();
            $usuario->setSenha($_POST['senha']);
            $usuario->setSenha_confirmacao($_POST['senha']);
            $usuario->setEmail($_REQUEST['email']);
            $usuario->setId_fuso_horario(23);
            $usuario->setAtivo(1);
            $usuario->setMaster(1);
            $usuario->setRg('00000');

            //CHECA SE JÁ EXISTE USUARIO CADASTRADO COM O EMAIL DIGITADO E RETORNA TRUE CASO NÃO EXISTA
            $validarUsuario = $usuario->VerificarCadastrado(true);
            if( $validarUsuario['codigo'] === 0){
                //ADICIONA O ENDERECO
                $idEndereco = $objEndereco->Adicionar();

                $usuario->setId_endereco($idEndereco);

                //ADICIONA O USUARIO
                $idUsuario = $usuario->Adicionar();

                $enviar = new Email($pdo);
                $params['email'] = $_POST['email'];
                $params['nome'] = $_POST['nome'];
                $params['senha'] = $_POST['senha'];
                $corpoEmail = GerarEmails::EmailWelcome($params);
                $destinatarios[] = $_REQUEST['email'];
                $destinatarios[] = 'contato@squall.com.br';
                $destinatarios[] = 'fotografia@sheephouse.com.br';
                $enviar->EnviarEmail("Bem vindo a SheepHouse", $corpoEmail, $destinatarios);

            }else{
                $msg['debug']['msg'] = $validarUsuario;
                throw new Exception('Group Exception');
            }
            $msg["codigo"] = 0;
            $msg["mensagem"] = 'Sucesso ao adicionar usuário';
            $pdo->commit();

        }catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $msg["debug"]['error'] = $e->getMessage();
            $msg["debug"]['file'] = $e->getFile();
            $msg["debug"]['line'] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($msg);

        $template = "ajax.login.php";
        break;
    case "fatura_pdf":
        include_once('MPDF6/mpdf.php');

        $objContasReceber = new ContasReceber();
        $objContasReceber->setId($_REQUEST['hash']);
        $linha = $objContasReceber->FaturaHash();

        $objitens = new ContasReceberItens();
        $objitens->setIdContaReceber($linha['id']);
        $itens = $objitens->ListarServicosFatura();
        $_REQUEST['tipo'] = 'download';
        include_once ("modulos/contas_receber/template/tpl.fatura.pdf.php");

        $template = "ajax.login.php";
        break;
}