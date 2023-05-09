<?php
/**
 * Created by PhpStorm.
 * User: squal
 * Date: 06/04/2016
 * Time: 11:57
 */
session_start();
session_destroy();
session_name('WEBCOP-SESSION');
session_start();
set_time_limit(0);

define('URL_FILE', "../");
define('URL_ERP', "../erp/");

date_default_timezone_set('America/Sao_Paulo');
ini_set("display_errors", true);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

include_once("../classes/Email.php");
include_once("../classes/Sms.php");

//include_once("../modulos/grupo/classe.grupo.php");
include_once("../modulos/usuario/classe.usuario.php");
//include_once("../classes/Utils.php");
include_once("../classes/Conexao.php");

//Utils::TratarRequest();
//$usuario = new Usuario();

switch($_REQUEST['act'])
{
    case "checar_email":
        $parametros['nome']             = $_SESSION['usuario']['nome'];
        $parametros['id']               = $_SESSION['SESSION_PAINEL_ID'];
        $parametros['usuario']          = $_SESSION['usuario']['nome_usuario'];
        $parametros['novo_email']       = $_REQUEST['email'];
        $parametros['celular']          = $_REQUEST['celular'];
        $parametros['email']            = $_SESSION['SESSION_PAINEL_LOGIN'];
        $parametros['codigo_validador'] = rand(10000,99999);

        $resultado = $usuario->ChecarEmail($parametros);

        if($resultado->email == '')
        {
            $msg['mensagem'] = "Sucesso ao Alterar E-mail";
            $msg['tipo'] = 1;
            $_SESSION['SESSION_PAINEL_LOGIN']= $_REQUEST['email'];
        }
        else
        {
            $msg['mensagem'] = "E-mail já esta sendo usado por outro usuário!";
            $msg['tipo'] = 2;
        }
        echo json_encode($msg);
        break;

    case "reenviar_codigo":
    	$usuario->setId($_SESSION['SESSION_PAINEL_ID']);
    	$auxUsuario = $usuario->Editar();

        $parametros['nome']             = $_SESSION['usuario']['nome'];
        $parametros['id']               = $_SESSION['SESSION_PAINEL_ID'];
        $parametros['usuario']          = $_SESSION['usuario']['nome_usuario'];
        $parametros['novo_email']       = $_SESSION['SESSION_PAINEL_LOGIN'];
        $parametros['codigo_validador'] = $auxUsuario->codigo_validador;

        $resultado = $usuario->NovoValidador($parametros);

        if($resultado)
        {
            $msg['mensagem'] = "O código validador foi reenviado para seu e-mail";
            $msg['tipo'] = 1;
        }
        else
        {
            $msg['mensagem'] = "Erro ao reenviar código validador!";
            $msg['tipo'] = 2;
        }
        echo json_encode($msg);
        break;

    case "validar_codigo":
        $parametros['nome']             = $_SESSION['usuario']['nome'];
        $parametros['id']               = $_SESSION['SESSION_PAINEL_ID'];
        $parametros['usuario']          = $_SESSION['usuario']['nome_usuario'];
        $parametros['novo_email']       = $_SESSION['SESSION_PAINEL_LOGIN'];
        $parametros['codigo_validador'] = $_REQUEST['codigo_validador'];
        
        $resultado = $usuario->ChecarValidor($parametros);

        if ($resultado->codigo_validador != '')
        {
            $param['usuario'] = $_SESSION['usuario']['nome'];
            $param['sistema'] =1;
            $param['id_usuario'] =$_SESSION['SESSION_PAINEL_ID'];
            $param['id_grupo'] =$_SESSION['SESSION_PAINEL_GRUPO'];
            $usuario->GerarBiSistema($param);

            $_SESSION['email_valido'] = 1;
            $msg['mensagem'] = "Sucesso ao comparar validador";
            $msg['tipo'] = 1;
        }
        else
        {
            $msg['mensagem'] = "Este número não corresponde ao código validador enviado.";
            $msg['tipo'] = 2;
        }
        echo json_encode($msg);
        break;

    case "sistema_antigo":
        $param['usuario'] = $_SESSION['usuario']['nome'];
        $param['sistema'] =2;
        $param['id_usuario'] =$_SESSION['SESSION_PAINEL_ID'];
        $param['id_grupo'] =$_SESSION['SESSION_PAINEL_GRUPO'];
        $usuario->GerarBiSistema($param);
        $senha = $_SESSION['usuario']['senha'];
        $usuario = $_SESSION['SESSION_PAINEL_LOGIN'];
        header("location:http://app.sheephouse.com.br/link/includes/confirm.php?email=$usuario&cript=$senha&emular=sim");
        break;

    case "comparar_reposta_secreta":
        $usuario = new Usuario();
        $usuario->setId($_REQUEST['id_usuario']);
        $dados = $usuario->BuscarPeguntaSecreta(null,$_REQUEST['resposta_senha']);

        if($dados['id'] != "")
        {
            $senha = $usuario->randPass(8);
            $usuario->setId($dados['id']);
            $usuario->setSenha(md5($senha));
            $update = $usuario->MudarSenha();
            if($update == 1)
            {
                $vetor['nome'] = $dados['nome'];
                $vetor['email'] = $dados['email'];
                $vetor['senha'] = $senha;
                $usuario->EnviarEmail($vetor);
            }
            $dados['msg'] = "Um e-mail foi enviado para você com a nova senha";
            $dados['codigo'] = 1;
        }
        else
        {
            $dados['msg'] = "Resposta secreta incorreta.";
            $dados['codigo'] = 2;
        }
        echo json_encode($dados);
        break;

    case "localizar_usuario_login":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
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
                $parametros['url'] = "http://linkmonitoramento.com.br/link_report/modulos/login/template/tpl.frm.mudar_senha.php?codigo={$parametros['codigo_validador']}&hash={$parametros['hash']}&id={$dados['id']}";
                $parametros['nome'] = $dados['nome'];
                $conteudo = $usuario->FormatarEmailCodigoValidador($parametros);
                $destinatarios[] = $dados['email'];
                $titulo = "Seu código de recuperação do ERP é: {$parametros['codigo_validador']}";
                $mail = $enviar->enviarEmail($titulo, $conteudo, $destinatarios);
                if(!$mail)
                    throw new Exception('Falha ao enviar e-mail!', 2);
            }
            $pdo->commit();
        } catch (Exception $e) {
            $dados["codigo"] = ($e->getCode() == 2) ? 2 : 1;
            $dados["msg"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $dados["debug"]["error"] = $e->getMessage();
            $dados["debug"]["file"] = $e->getFile();
            $dados["debug"]["linha"] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($dados);
        break;

    case "sair_validacao":
        unset($_SESSION);
        session_destroy();
        $vetor['msg'] = "Sucesso";
        $vetor['codigo'] =1;
        echo json_encode($vetor);
        break;

    case "verificar_codigo_validador":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $usuario = new Usuario($pdo);
            $params['id'] = $_REQUEST['id_usuario'];
            $codigo = $usuario->VerificarCodigoValidadorHash($params);
            if (!empty($_REQUEST['hash'])){
                if ($_REQUEST['hash'] != $codigo['hash_validador'])
                    throw new Exception("Hash inválida.", 2);
            }
            if ($_REQUEST['codigo_validador'] != $codigo['codigo_validador'])
                throw new Exception("O número inserido não corresponde ao seu código. Tente novamente.", 2);

            $dados["codigo"] = 1;
            $pdo->commit();
        } catch (Exception $e) {
            $dados["codigo"] = ($e->getCode() == 2) ? 2 : 1;
            $dados["msg"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $dados["debug"]["error"] = $e->getMessage();
            $dados["debug"]["file"] = $e->getFile();
            $dados["debug"]["linha"] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($dados);
        break;

    case "salvar_nova_senha":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $usuario = new Usuario($pdo);
            $params['senha'] = md5($_REQUEST['mudar_senha']);
            $params['id'] = $_REQUEST['id_usuario'];
            $codigo = $usuario->VerificarCodigoValidadorHash($params);
            if ($_REQUEST['codigo_validador'] != $codigo['codigo_validador'])
                throw new Exception("Código de segurança inválido.", 2);
            $retorno = $usuario->UpdateSenha($params);
            $msg["codigo"] = 0;
            $msg["mensagem"] = 'Sucesso ao mudar senha!';
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
        break;

    case "validar_via_sms":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $usuario = new Usuario($pdo);
            $enviar = new Email($pdo);
            $parametros['id'] = $_SESSION['usuario']['id'];
            $parametros['codigo_validador'] = rand(100000,999999);
            $parametros['hash_duas_etapas'] = md5($parametros['codigo_validador']);
            $usuario->UpdateParamsCodigoValidador($parametros);
            $sms = new Sms(1, 1);
            $mensagem = "O seu codigo de verificacao do Link report e {$parametros['codigo_validador']}. Nao compartilhe com ninguem.";
            $sms->setConteudo($mensagem);
            $sms->setDestinatario($_SESSION['usuario']['celular']);
            $retorno = $sms->EnviarMensagens();
            $retorno = 'sucesso';
            if (strpos($retorno, 'sucesso') !== false){
                $dados['msg'] = 'Sucesso ao enviar sms';
                $dados['codigo'] = 0;
            }else{
                throw new Exception('Erro ao enviar SMS', 2);
            }
            $pdo->commit();
        } catch (Exception $e) {
            $dados["codigo"] = ($e->getCode() == 2) ? 2 : 1;
            $dados["msg"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $dados["debug"]["error"] = $e->getMessage();
            $dados["debug"]["file"] = $e->getFile();
            $dados["debug"]["linha"] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($dados);
        break;

    case "validar_via_email":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $usuario = new Usuario($pdo);
            $enviar = new Email($pdo);

            $parametros['id'] = $_SESSION['usuario']['id'];
            $parametros['codigo_validador'] = rand(100000,999999);
            $parametros['hash_duas_etapas'] = md5($parametros['codigo_validador']);
            $usuario->UpdateParamsCodigoValidador($parametros);

            $parametros['nome'] = $_SESSION['usuario']['nome'];
            $conteudo = $usuario->FormatarEmailDuasEtapas($parametros);
            $destinatarios[] = $_SESSION['usuario']['email'];
            $titulo = "Código de verificação LINK REPORT";
            $mail = $enviar->enviarEmail($titulo, $conteudo, $destinatarios);
            if(!$mail)
                throw new Exception('Falha ao enviar e-mail!', 2);

            $dados['msg'] = 'Sucesso ao enviar e-mail';
            $dados['codigo'] = 0;
            $pdo->commit();
        } catch (Exception $e) {
            $dados["codigo"] = ($e->getCode() == 2) ? 2 : 1;
            $dados["msg"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $dados["debug"]["error"] = $e->getMessage();
            $dados["debug"]["file"] = $e->getFile();
            $dados["debug"]["linha"] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($dados);
        break;

    case "verificar_codigo_duas_etapas":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $usuario = new Usuario($pdo);
            $params['id'] = $_SESSION['usuario']['id'];
            $params['hash_duas_etapas'] = md5($_REQUEST['codigo_validador']);
            $codigo = $usuario->VerificarHashDuasEtapas($params);
            if (empty($codigo))
                throw new Exception("O número inserido não corresponde ao seu código. Tente novamente.", 2);

            $cookieAutenticado = 'autenticado_' . $_SESSION['usuario']['id'];
            setcookie($cookieAutenticado, $cookieAutenticado, time() + (3600 * 24 * 365), '/', '', false, false);

            $dados["codigo"] = 1;
            $pdo->commit();
        } catch (Exception $e) {
            $dados["codigo"] = ($e->getCode() == 2) ? 2 : 1;
            $dados["msg"] = ($e->getCode() == 2) ? $e->getMessage() : "Erro ao Executar Operação";
            $dados["debug"]["error"] = $e->getMessage();
            $dados["debug"]["file"] = $e->getFile();
            $dados["debug"]["linha"] = $e->getLine();
            $pdo->rollBack();
        }
        echo json_encode($dados);
        break;

}

