<?php
switch($app_comando)
{
    case "frm_adicionar_cliente":
        $linha['id_cliente_tipo_pessoa'] = 1;
        $linha['status'] = 1;
        $template = "tpl.frm.cliente.php";
        break;
    case "frm_saldo_cliente":
        $cliente = new Cliente();
        $cliente->setId($_REQUEST["app_codigo"]);
        $linha = $cliente->Editar();
//        Conexao::pr($linha);
        $template = "tpl.frm.saldo.cliente.php";
        break;
    case "saldo_cliente":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objHistoricoSaldo = new HistoricoSaldo($pdo);
            $objHistoricoSaldo->setIdCliente($_REQUEST['id_cliente']);
            $objHistoricoSaldo->setSaldoAtual($_REQUEST['saldo_atual']);
            $objHistoricoSaldo->setOperacao($_REQUEST['operacao']);
            $objHistoricoSaldo->setValor($_REQUEST['valor']);
            $objHistoricoSaldo->setIdUsuario($_SESSION['usuario']['id']);
            $novoId = $objHistoricoSaldo->Adicionar();

            $msg["codigo"] = 0;
            $msg["mensagem"] = "Sucesso ao Atualizar Saldo";
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = " Erro ao Atualizar Saldo". $e->getMessage();
            $msg["debug"] = $e->getMessage();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.cliente.php";
        break;
    case "atualizar_id_asaas":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objCliente = new Cliente($pdo);
            $objCliente->setId($_REQUEST['id']);
            $asaas = new Asaas();
            $linha = $objCliente->Editar();
           // Conexao::pr($linha);
            $rs = $asaas->BuscarPorCNPJCPF(str_replace("/","",$asaas->LimparTelefone($linha['cpf_cnpj'])));
            if($rs['status'] == 200)
            {
                if($rs['totalCount'] == 1)
                {
                    $objCliente->setId($linha['id']);
                    $objCliente->ModificarAsaas($rs['data'][0]['id']);
                }
                else if($rs['totalCount'] === 0)
                {
                    $asaas = new Asaas();
                    $emails_adicionais = $linha['email_mkt']. "," .$linha['email_mkt2'];
                    $content = array(
                        "name"=> $linha['nome'],
                        "email"=> $linha['email'],
                        "phone"=> $asaas->LimparTelefone($linha['telefone']),
                        "mobilePhone"=> $asaas->LimparTelefone($linha['celular']),
                        "cpfCnpj"=> str_replace("/","",$asaas->LimparTelefone($linha['cpf_cnpj'])),
                        "postalCode"=> $linha['cep'],
                        "address"=> $linha['logradouro'],
                        "addressNumber"=> $linha['numero'],
                        "complement"=> $linha['complemento'],
                        "province"=> $linha['bairro'],
                        "externalReference"=> "",
                        "notificationDisabled"=> false,
                        "additionalEmails"=> $emails_adicionais,
                        "observations"=> $linha['observacao_dados']);
                    $rs_asass = $asaas->AdicionarCliente($content);
                    $objCliente->setId($linha['id']);
                    $objCliente->setIdAsaas($rs_asass['id']);
                    $objCliente->ModificarAsaas($rs_asass['id']);
                    if($rs_asass['status'] != 200) throw new Exception("Não foi possível criar cliente na plataforma Asaas!");
                }
            }
            else
            {
                throw new Exception("Cliente não encontrado na plataforma ASAAS");
            }
            $msg["codigo"] = 0;
            $msg["objasaas"] = $rs;
            $msg["mensagem"] = "Sucesso ao Atualizar ID ASAAS";
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = 'Erro ao Atualizar ID ASAAS' . " ". $e->getMessage();
            $msg["debug"]['erro'] = $e->getMessage();
            $msg["debug"]['file'] = $e->getFile();
            $msg["debug"]['line'] = $e->getLine();
            $msg["debug"]['asaas'] = $rs;
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.cliente.php";
        break;
    case "verificar_saldo":
        $objCliente = new Cliente();
        $objCliente->setId($_SESSION['usuario']['id_cliente']);
        $dados = $objCliente->VerificarSaldo();

        $saldo = ($dados['saldo']!="") ? $dados['saldo'] : 0.00;
        $dados['saldo'] = "R$ " . number_format($saldo,'2',',','.');
        $_SESSION['usuario']['saldo'] = "R$ " . number_format($saldo,'2',',','.');
        echo json_encode($dados);

        $template = "ajax.cliente.php";
        break;

    case "adicionar_cliente":
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

            $objEnderecoCobranca = new Endereco($pdo);
            $objEnderecoCobranca->setLogradouro($_REQUEST['c_logradouro']);
            $objEnderecoCobranca->setNumero($_REQUEST['c_numero']);
            $objEnderecoCobranca->setComplemento($_REQUEST['c_complemento']);
            $objEnderecoCobranca->setBairro($_REQUEST['c_bairro']);
            $objEnderecoCobranca->setCidade($_REQUEST['c_cidade']);
            $objEnderecoCobranca->setEstado($_REQUEST['c_estado']);
            $objEnderecoCobranca->setCep($_REQUEST['c_cep']);
            $objEnderecoCobranca->setReferencia($_REQUEST['c_referencia']);
            $objEnderecoCobranca->setObservacao($_REQUEST['c_observacao']);
            $objEnderecoCobranca->setTelefone($_REQUEST['c_telefone']);
            $objEnderecoCobranca->setComercial($_REQUEST['c_comercial']);
            $objEnderecoCobranca->setCelular($_REQUEST['c_celular']);
            $objEnderecoCobranca->setEmail($_REQUEST['c_email']);
            $objEnderecoCobranca->setEmailMkt($_REQUEST['c_email_mkt']);
            $objEnderecoCobranca->setEmailMkt2($_REQUEST['c_email_mkt2']);
            $objEnderecoCobranca->setLatitude($_REQUEST['c_latitude']);
            $objEnderecoCobranca->setLongitude($_REQUEST['c_longitude']);
            $novoIdCobranca = $objEnderecoCobranca->Adicionar();

            //INICIO ADICIONAR GRUPO
            $grupo = new Grupo($pdo);
            $grupoAux = new Grupo(); //grupo_pai
            $grupoAux->setId($_SESSION['usuario']['id_grupo']);
            $grupoAux = $grupoAux->Editar();


            $grupo->setId_grupo_pai($_SESSION['usuario']['id_grupo']);
            $grupo->setNome($_REQUEST['nome']);

            //VERIFICA SE O NOME DO GRUPO ESTA CADASTRADO
            if (!$grupo->VerificarCadastrado()) {
                $idGrupo = $grupo->Adicionar();
                $grupo->AdicionarPermissaoCliente($idGrupo);
            } else {
                $msg['debug']['msg'] = 'Já existe um grupo com este nome';
                throw new Exception('Já existe um grupo com este nome');
            }


            $objCliente = new Cliente($pdo);
            $objCliente->setIdGrupo($idGrupo);
            $objCliente->setIdClienteTipoPessoa($_REQUEST['id_cliente_tipo_pessoa']);
            $objCliente->setNome($_REQUEST['nome']);
            $objCliente->setNomeFantasia($_REQUEST['nome_fantasia']);
            $objCliente->setCpfCnpj(($_REQUEST['id_cliente_tipo_pessoa'] == 1)? $_REQUEST['cpf']: $_REQUEST['cnpj']);
            $objCliente->setInscricaoEstadual($_REQUEST['inscricao_estadual']);
            $objCliente->setIdEndereco($novoId);
            $objCliente->setIdEnderecoCobranca($novoIdCobranca);
            $objCliente->setDataNascimento(Conexao::PrepararDataBD($_REQUEST['data_nascimento']));
            $objCliente->setIdClienteEstadoCivil($_REQUEST['id_cliente_estado_civil']);
            $objCliente->setSexo($_REQUEST['sexo']);
            $objCliente->setDiaVencimento($_REQUEST['dia_vencimento']);
            $objCliente->setIdFormaPagamento($_REQUEST['id_forma_pagamento']);
            $objCliente->setRg($_REQUEST['rg']);
            $objCliente->setStatus($_REQUEST['ativo']);
            $objCliente->setObservacaoDados($_REQUEST['observacao_dados']);
            $objCliente->setIdUsuarioAtualizacao($_SESSION['usuario']['id']);
            //ADICIONANDO FOTO DO USUARIO
            if (count($_FILES) > 0) {
                $upload = new Upload("upload/fotos_clientes/");
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
                $objCliente->setFoto($imagem);
                $_SESSION['usuario']['foto'] = $imagem;

                $thumbs = new Thumbs();
                $thumbs->caminho     = "upload/fotos_clientes/";
                $thumbs->arquivo     = $imagem;
                $thumbs->largura_max = 600;
                $thumbs->altura_max  = 600;
                $thumbs->Prepare();
            }

            //SET DOS PARAMETROS DO USUARIO
            $usuario  = new Usuario($pdo);

            $usuario->setId_grupo($idGrupo);
            $usuario->setIdUsuarioTipo(4);
            $usuario->setNome($_POST['nome']);
            $usuario->setUsuario($_REQUEST['email']);
            $usuario->setTimezone('America/Sao_Paulo');
            $senha = $usuario->gerarSenha();
            $usuario->setSenha($senha);
            $usuario->setSenha_confirmacao($senha);
            $usuario->setEmail($_REQUEST['email']);
            $usuario->setId_fuso_horario(23);
            $usuario->setAtivo(1);
            $usuario->setMaster(1);
            $usuario->setRg(($_POST['rg'] != "") ? $_POST['rg']: '0000');

            //CHECA SE JÁ EXISTE USUARIO CADASTRADO COM O EMAIL DIGITADO E RETORNA TRUE CASO NÃO EXISTA
            $validarUsuario = $usuario->VerificarCadastrado(true);
            if( $validarUsuario['codigo'] === 0){

                $asaas = new Asaas();
                $emails_adicionais = $_REQUEST['email_mkt']. "," .$_REQUEST['email_mkt2'];
                $content = array(
                    "name"=> $_POST['nome'],
                    "email"=> $_REQUEST['email'],
                    "phone"=> $asaas->LimparTelefone($_REQUEST['telefone']),
                    "mobilePhone"=> $asaas->LimparTelefone($_REQUEST['celular']),
                    "cpfCnpj"=> str_replace("/","",$asaas->LimparTelefone(($_REQUEST['id_cliente_tipo_pessoa'] == 1)? $_REQUEST['cpf']: $_REQUEST['cnpj'])),
                    "postalCode"=> $_REQUEST['cep'],
                    "address"=> $_REQUEST['logradouro'],
                    "addressNumber"=> $_REQUEST['numero'],
                    "complement"=> $_REQUEST['complemento'],
                    "province"=> $_REQUEST['bairro'],
                    "externalReference"=> "",
                    "notificationDisabled"=> false,
                    "additionalEmails"=> $emails_adicionais,
                    "observations"=> $_REQUEST['observacao_dados']);
                $rs_asass = $asaas->AdicionarCliente($content);
                $objCliente->setIdAsaas($rs_asass['id']);
                if($rs_asass['status'] != 200) throw new Exception("Não foi possível criar cliente na plataforma Asaas!");

                //ADICIONA O ENDERECO
                $idEnderecousuario = $objEndereco->Adicionar();
                $usuario->setId_endereco($idEnderecousuario);
                //ADICIONA O USUARIO
                $idUsuario = $usuario->Adicionar();

                //ADICIONANDO CLIENTE
                $novoId = $objCliente->Adicionar();



                $enviar = new Email($pdo);
                $params['email'] = $_REQUEST['email'];
                $params['nome'] = $_REQUEST['nome'];
                $params['senha'] = $senha;
                $corpoEmail = GerarEmails::EmailWelcome($params);
                $destinatarios[] = $_REQUEST['email'];
                $destinatarios[] = 'fotografia@sheephouse.com.br';
                $enviar->EnviarEmail("Bem vindo a SheepHouse", $corpoEmail, $destinatarios);
            }else{
                $msg['debug']['msg'] = $validarUsuario;
                throw new Exception($validarUsuario['mensagem']);
            }
            $msg["codigo"] = 0;
            $msg["data"] = $rs_asass;
            $msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO . " ". $e->getMessage();
            $msg["debug"]['erro'] = $e->getMessage();
            $msg["debug"]['file'] = $e->getFile();
            $msg["debug"]['line'] = $e->getLine();
            $msg["debug"]['asaas'] = $rs_asass;
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.cliente.php";
        break;
    case "popup_localizar_clientes":
        $template = "ajax.cliente.php";
        break;

    case "frm_atualizar_cliente" :
        $cliente = new Cliente();
        $cliente->setId($_REQUEST["app_codigo"]);
        $linha = $cliente->Editar();
        $template = "tpl.frm.cliente.php";
        break;

    case "atualizar_cliente":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objEndereco = new Endereco($pdo);
            $objEndereco->setId($_REQUEST['id_endereco']);
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

            $objEnderecoCobranca = new Endereco($pdo);
            $objEnderecoCobranca->setId($_REQUEST['id_endereco_cobranca']);
            $objEnderecoCobranca->setLogradouro($_REQUEST['c_logradouro']);
            $objEnderecoCobranca->setNumero($_REQUEST['c_numero']);
            $objEnderecoCobranca->setComplemento($_REQUEST['c_complemento']);
            $objEnderecoCobranca->setBairro($_REQUEST['c_bairro']);
            $objEnderecoCobranca->setCidade($_REQUEST['c_cidade']);
            $objEnderecoCobranca->setEstado($_REQUEST['c_estado']);
            $objEnderecoCobranca->setCep($_REQUEST['c_cep']);
            $objEnderecoCobranca->setReferencia($_REQUEST['c_referencia']);
            $objEnderecoCobranca->setObservacao($_REQUEST['c_observacao']);
            $objEnderecoCobranca->setTelefone($_REQUEST['c_telefone']);
            $objEnderecoCobranca->setComercial($_REQUEST['c_comercial']);
            $objEnderecoCobranca->setCelular($_REQUEST['c_celular']);
            $objEnderecoCobranca->setEmail($_REQUEST['c_email']);
            $objEnderecoCobranca->setEmailMkt($_REQUEST['c_email_mkt']);
            $objEnderecoCobranca->setEmailMkt2($_REQUEST['c_email_mkt2']);
            $objEnderecoCobranca->setLatitude($_REQUEST['c_latitude']);
            $objEnderecoCobranca->setLongitude($_REQUEST['c_longitude']);
            $objEnderecoCobranca->Modificar();

            $objCliente = new Cliente($pdo);
            $objCliente->setId($_REQUEST['id']);
            $objCliente->setIdClienteTipoPessoa($_REQUEST['id_cliente_tipo_pessoa']);
            $objCliente->setNome($_REQUEST['nome']);
            $objCliente->setNomeFantasia($_REQUEST['nome_fantasia']);
            $objCliente->setCpfCnpj(($_REQUEST['id_cliente_tipo_pessoa'] == 1)? $_REQUEST['cpf']: $_REQUEST['cnpj']);
            $objCliente->setInscricaoEstadual($_REQUEST['inscricao_estadual']);
            $objCliente->setDataNascimento(Conexao::PrepararDataBD($_REQUEST['data_nascimento']));
            $objCliente->setIdClienteEstadoCivil($_REQUEST['id_cliente_estado_civil']);
            $objCliente->setSexo($_REQUEST['sexo']);
            $objCliente->setDiaVencimento($_REQUEST['dia_vencimento']);
            $objCliente->setIdFormaPagamento($_REQUEST['id_forma_pagamento']);
            $objCliente->setRg($_REQUEST['rg']);
            $objCliente->setStatus($_REQUEST['ativo']);
            $objCliente->setObservacaoDados($_REQUEST['observacao_dados']);
            $objCliente->setIdUsuarioAtualizacao($_SESSION['usuario']['id']);
            $objCliente->setDataHoraAtualizacao(date('YmdHis'));
            if (count($_FILES) > 0) {
                $upload = new Upload("upload/fotos_clientes/");
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
                $objCliente->setFoto($imagem);
                $_SESSION['usuario']['foto'] = $imagem;

                $thumbs = new Thumbs();
                $thumbs->caminho     = "upload/fotos_clientes/";
                $thumbs->arquivo     = $imagem;
                $thumbs->largura_max = 600;
                $thumbs->altura_max  = 600;
                $thumbs->Prepare();
            }
            $objCliente->Modificar();
            $msg["codigo"] = 0;
            $msg["mensagem"] = TXT_ALERT_SUCESSO_MODIFICAR;
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO. " ". $e->getMessage();
            $msg["debug"]['erro'] = $e->getMessage();
            $msg["debug"]['file'] = $e->getFile();
            $msg["debug"]['line'] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.cliente.php";
        break;

    case "listar_cliente":
        $template = "tpl.geral.cliente.php";
        break;

    case "deletar_cliente":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objCliente = new Cliente($pdo);
            $objCliente->Remover($_REQUEST['registros']);
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
        $template = "ajax.cliente.php";
        break;

    case "ajax_listar_cliente":
        $template = "tpl.lis.cliente.php";
        break;

    case "cliente_pdf":
        $template = "tpl.lis.cliente.pdf.php";
        break;

    case "cliente_xlsx":
        $template = "tpl.lis.cliente.xlsx.php";
        break;

    case "cliente_print":
        $template = "tpl.lis.cliente.print.php";
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
            $_SESSION["configuracao_usuario"]["cliente"] = $colunasSelecionadas;
            $usuarioConfiguracao->setIdUsuario($_SESSION["usuario"]["id"]);
            $usuarioConfiguracao->setDirModulo("cliente");
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
        $template = "ajax.cliente.php";
        break;
}
