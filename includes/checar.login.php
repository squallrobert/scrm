<?php

define('URL_FILE', "../");
define('URL_ERP', "../erp/");

include_once("config.inc.php");
session_start();
session_destroy();
session_start();
set_time_limit(0);


ini_set("display_errors", true);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
if ($_REQUEST['emular'] == "sim") {
    $emular = $_REQUEST['emular'];
}else
{
    $emular = 'nao';
    $_REQUEST = json_decode(file_get_contents("php://input"),true);
    $_POST = json_decode(file_get_contents("php://input"),true);
}

if ($_REQUEST['manter'])
{
    setcookie('manter_logado_usuario', $_REQUEST['email'], time() + (3600 * 24 * 365),'/','',false,false);
    setcookie('manter_logado_senha', $_REQUEST['senha'], time() + (3600 * 24 * 365),'/','',false,false);
}
else
{
    setcookie('manter_logado_usuario', "", time() + (3600 * 24 * 365),'/','',false,false);
    setcookie('manter_logado_senha', "", time() + (3600 * 24 * 365),'/','',false,false);
}

include_once("../classes/Conexao.php");
include_once("../modulos/grupo/classe.grupo.php");
include_once("../modulos/usuario/classe.usuario.php");
include_once("../classes/Utils.php");
include_once("../classes/CalculosListagem.php");

Utils::TratarRequest();

$grupo = new Grupo();
$erp = $_POST['erp'];
$login = strip_tags($_REQUEST['email']);


$senha_cookie = $_POST['senha'];
if ($_REQUEST['cript'] != "") {
	$senha = $_REQUEST['cript'];
} else {
	$senha = $_POST['senha'];
}


if ($erp == 2) {
	$caminho = "../erp/index.php";
} else {
	$caminho = "../index.php";
}
if ($login == "") {
	$msg['tipo'] = 1;
	$msg['mensagem'] = "Favor informar o login";
} elseif ($senha == "") {
	$msg['tipo'] = 1;
	$msg['mensagem'] = "Favor informar a senha";
} else {
	if ($_REQUEST['md5'] == "") {
		$senha = md5($senha);
	}
	$banco = new Usuario();
	//$user = new Usuario();
	$banco->setEmail($login);
	$banco->setSenha($senha);
	$banco->setId_grupo($_REQUEST['id_grupo']);
	$lista = $banco->Logar();
//	Conexao::pr($lista);
	if (count($lista) == 1)
	{
		if ($senha == $lista[0]['senha'])
		{                          		  
            if($lista[0]['ativo'] != 1 || ( $lista[0]['acesso_bloqueado'] != 1 && $lista[0]['id_cliente'] != ''))
			{
				$msg['tipo'] = 1;
				$msg['mensagem'] = htmlentities("Usuário Bloqueado! Favor entrar em contato com a " .TITULO_GERAL);
			}
			else
			{
				$msg['mensagem'] = htmlentities("Sucesso!! Aguarde...");
				$msg['tipo'] = 2;

				if($lista[0]['id_franqueado'] != "")
				    $tipo = 2;
				elseif($lista[0]['id_grupo_cliente'] != "")
                    $tipo = 3;
				else
				    $tipo = 1;

				$_SESSION['pagina_login']                     = $_SERVER['HTTP_REFERER'];
				$_SESSION['usuario']['id']                    = $lista[0]['id'];
				$_SESSION['usuario']['nome']                  = $lista[0]['nome'];
				$_SESSION['usuario']['login']                 = $lista[0]['usuario'];
				$_SESSION['usuario']['email']                 = $lista[0]['email'];
				$_SESSION['usuario']['senha']                 = $lista[0]['senha'];
				$_SESSION['usuario']['id_grupo']              = $lista[0]['id_grupo'];
				$_SESSION['usuario']['nome_grupo']            = $lista[0]['nome_grupo'];
				$_SESSION['usuario']['nome_fuso_horario']     = $lista[0]['nome_fuso_horario'];
				$_SESSION['usuario']['franqueado']            = $lista[0]['nome_franqueado'];
				$_SESSION['usuario']['foto']                  = $lista[0]['foto'];
				$_SESSION['usuario']['arvore']                = $lista[0]['arvore'];
				$_SESSION['usuario']['id_fuso_horario']       = $lista[0]['id_fuso_horario'];
				$_SESSION['usuario']['timezone']              = $lista[0]['timezone'];
				$_SESSION['usuario']['celular']               = $lista[0]['celular'];
				$_SESSION['usuario']['id_grupo_cliente']      = $lista[0]['id_grupo_cliente'];
				$_SESSION['usuario']['id_cliente']            = $lista[0]['id_cliente'];
				$_SESSION['usuario']['id_forma_pagamento']    = $lista[0]['id_forma_pagamento'];
				$_SESSION['usuario']['master']                = $lista[0]['master'];
				$_SESSION['usuario']['saldo']                 = "R$ " . number_format($lista[0]['saldo'],'2',',','.');
				$_SESSION['usuario']['id_usuario_tipo']       = $lista[0]['id_usuario_tipo'];
				$_SESSION['usuario']['tipo']                  = $tipo;
				$_SESSION['usuario']['nome_tipo']             = $lista[0]['nome_tipo'];
				$_SESSION['id_franqueado']                    = $lista[0]['id_franqueado'];
				$_SESSION['franqueado']['codigo']             = $lista[0]['codigo'];
                $_SESSION['sessao_pendente']                  = 0;
				$_SESSION['senha_expirada']                   = false;

				if ($_SESSION['usuario']['foto'] == ""){
					$_SESSION['usuario']['foto'] = "assets/media/avatars/blank.png";
				}

				//BUSCANDO PERMISSÕES DE USUÁRIO
				$banco->setId($lista[0]['id']);
                $rows = $banco->VetorPermissaoUsuario();
				if(count($rows) > 0)
                {
                    foreach ($rows as $row) {
                        $acoes = explode("|", $row['acao']);
                        foreach ($acoes as $acao) {
                            $_SESSION['PERMISSAO'][$acao] = $row['id_acao'];
                        }
                    }
                    $_SESSION['usuario']['permissao_especifica'] = true;
                }
				else
                {
                    $_SESSION['usuario']['permissao_especifica'] = false;
                    $grupo->setId($lista[0]['id_grupo']);
                    $listar = $grupo->VetorPermissao();
                    if (count($listar) > 0)
                    {
                        foreach ($listar as $linha) {
                            $acoes = explode("|", $linha['acao']);
                            foreach ($acoes as $acao) {
                                $_SESSION['PERMISSAO'][$acao] = $linha['id_acao'];
                            }
                        }
                    }
                    else
                    {
                        // gerando permissões padrão para usuário sem permissão.
                        $param['id_grupo'] = $lista[0]['id_grupo'];
                        $param['categoria'] = 1;
                        $listar = $grupo->GerarPermissao($param);

                        $listar = $grupo->VetorPermissao();
                        foreach ($listar as $linha) {
                            $acoes = explode("|", $linha['acao']);
                            foreach ($acoes as $acao) {
                                $_SESSION['PERMISSAO'][$acao] = $linha['id_acao'];
                            }
                        }
                    }
                }

				$caminho_sistema = 'index.php';

				$dataAtual = gmdate("Y-m-d H:i:s");

				if ($_SESSION['id_franqueado'] != "" && CalculosListagem::IntervaloDataHora($dataAtual, $lista[0]['data_hora_atualizacao_senha']) > (60 * 24 * 60 * 60)) {
					$_SESSION['senha_expirada'] = true;
				}

                $_SESSION['emular'] = $emular;

                if ($lista[0]['senha_provisoria'] == 1) {
						$_SESSION['usuario']['provisoria'] = "1";
						$msg['url'] = "$caminho_sistema=&app_modulo=home&app_comando=home&app_codigo&provisoria=1";

				} else {
						$msg['url'] = $caminho_sistema;
				}

				$_SESSION['usuario']['qtd_filhos'] = count($banco->BuscarGruposVinculados($_SESSION['usuario']['id'], $_SESSION['usuario']['id_grupo']));

				// Armazena o ultimo login
				$banco->setId($lista[0]['id']);
				$banco->AtualizarUltimoLogin();
			}
		} else {
			$msg['tipo'] = 1;
			$msg['mensagem'] = "Dados incorretos";
		}
	} else {
		$msg['tipo'] = 1;
		$msg['mensagem'] = "Usuário ou senha incorretos ou inativo !";
	}
	unset($_POST);
}
echo json_encode($msg);
