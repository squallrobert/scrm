<?php
//include configurações do sistema
include_once("includes/config.inc.php");
Utils::TratarRequest();
$grupo = new Grupo();
// verificando id do grupo do usuário logado
if ($_SESSION['usuario']['id_grupo']) {
	$_SESSION['usuario']['configuracoes'] = $grupo->ListarConfiguracoes($_SESSION['usuario']['id_grupo']);
}
// verificando se senha e provisória obrigando o usuário a mudar a senha

$app_modulo  = addslashes(strip_tags($_REQUEST['app_modulo']));
$app_comando = addslashes(strip_tags($_REQUEST['app_comando']));
$app_codigo  = addslashes(strip_tags($_REQUEST['app_codigo']));
if ($_SESSION['usuario']['id']) {
	// Atualiza o status de todos os usuarios expirados para INATIVO
	// Se o usuario logado esta inativo redireciona para a tela de login
	$objUsuario = new Usuario($_SESSION['usuario']['configuracao']['id_idioma']);
	if (!$objUsuario->AtualizarUsuarioExpirado($_SESSION['usuario']['id_fuso_horario'], $_SESSION['usuario']['id'])) {
		session_destroy();
		echo "<script>location.reload()</script>";
		die();
	}

    $logAcesso = new LogAcesso();
    $logAcesso->Gravar();

	//$objIdioma = new Idioma($_SESSION['usuario']['configuracao']['id_idioma']);
	//	$objIdioma->CarregarIdioma(  );
	include_once("template/body_app.php");
    // SE O USUÁRIO FOI CONFIGURADO COM PERMISSÕES ESPECIFICAS PARA USER
    if($_SESSION['usuario']['permissao_especifica'])
        $PERMISSAO = $objUsuario->ChecarPermissao($_SESSION['usuario']['id'], $app_comando);
    else
        $PERMISSAO = $grupo->ChecarPermissao($_SESSION['usuario']['id_grupo'], $app_comando);

	if ($_SESSION['emular'] == "sim") {
		if ($PERMISSAO || $app_comando == "") {

			//print_r($_SESSION);

			if ($app_modulo != "" && $app_comando != "") {
				$appFuncionalidade = new AppFuncionalidade();
				$appFuncionalidade->AdicionarFuncionalidadeAcessada($_SESSION['usuario']['id'], $app_modulo);

				$arquivo = "modulos/" . $app_modulo . "/modulo." . $app_modulo . ".php";
				if (file_exists($arquivo) && !is_dir($arquivo)) {
					include(URL_FILE . $arquivo);
				} else {
					$resposta = "Arquivo de Modulo não encontrado";
					include_once("includes/mensagem.php");
					echo "<pre>";
					print_r($_REQUEST);
					echo "</pre>";
					echo "caminho do Template = " . $temp;
				}

				$temp = URL_FILE . "modulos/" . $app_modulo . "/template/" . $template;
				if (file_exists($temp) && !is_dir($temp)) {
					include_once("includes/mensagem.php");
					include($temp);
				} else {
					$resposta = "Arquivo de Template não encontrado";
					include_once("includes/mensagem.php");
					echo "<pre>";
					print_r($_REQUEST);
					echo "</pre>";
					echo "caminho do Template = " . $temp;
				}
			} else {
				$app_comando = "home";
				$app_modulo  = "home";
				$arquivo     = "modulos/home/modulo.home.php";
				include(URL_FILE . $arquivo);
				$temp = URL_FILE . "modulos/" . $app_modulo . "/template/" . $template;
				include($temp);
			}
		} else {
			include_once("template/permissao.sistema.php");
		}

		//include_once("template/baixo.sistema.php");
	} else {

		if ($_SESSION['usuario']['franquia'] == "sim") {

			if ($_SESSION['usuario']['ip'] == $_SERVER['REMOTE_ADDR'] || $_SESSION['usuario']['ip_secundario'] == $_SERVER['REMOTE_ADDR']) {
				if ($PERMISSAO || $app_comando == "") {
					//print_r($_SESSION);
					if ($app_modulo != "" && $app_comando != "") {
						$arquivo = "modulos/" . $app_modulo . "/modulo." . $app_modulo . ".php";
						if (file_exists($arquivo) && !is_dir($arquivo)) {
							include(URL_FILE . $arquivo);
						} else {
							$resposta = "Arquivo de Modulo não encontrado";
							include_once("includes/mensagem.php");
							echo "<pre>";
							print_r($_REQUEST);
							echo "</pre>";
							echo "caminho do Template = " . $temp;
						}

						$temp = URL_FILE . "modulos/" . $app_modulo . "/template/" . $template;
						if (file_exists($temp) && !is_dir($temp)) {
							include_once("includes/mensagem.php");
							include($temp);
						} else {
							$resposta = "Arquivo de Template não encontrado";
							include_once("includes/mensagem.php");
							echo "<pre>";
							print_r($_REQUEST);
							echo "</pre>";
							echo "caminho do Template = " . $temp;
						}
					} else {
						$app_comando = "home";
						$app_modulo  = "home";
						$arquivo     = "modulos/home/modulo.home.php";
						include(URL_FILE . $arquivo);
						$temp = URL_FILE . "modulos/" . $app_modulo . "/template/" . $template;
						include($temp);
					}
				} else {
					include_once("template/permissao.sistema.php");
				}
				//include_once ( "template/baixo.sistema.php" );

			} else {
				session_destroy();
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=./index.php?msg=O SISTEMA DE MAPAS SO PODE SER ABERTO NA SEDE  DA FRANQUIA">';
			}
		} else {

			if ($PERMISSAO || $app_comando == "") {

				//print_r($_SESSION);

				if ($app_modulo != "" && $app_comando != "") {
					$appFuncionalidade = new AppFuncionalidade();
					$appFuncionalidade->AdicionarFuncionalidadeAcessada($_SESSION['usuario']['id'], $app_modulo);

					$arquivo = "modulos/" . $app_modulo . "/modulo." . $app_modulo . ".php";
					if (file_exists($arquivo) && !is_dir($arquivo)) {
						include(URL_FILE . $arquivo);
					} else {
						$resposta = "Arquivo de Modulo não encontrado";
						include_once("includes/mensagem.php");
						echo "<pre>";
						print_r($_REQUEST);
						echo "</pre>";
						echo "caminho do Template = " . $temp;
					}

					$temp = URL_FILE . "modulos/" . $app_modulo . "/template/" . $template;
					if (file_exists($temp) && !is_dir($temp)) {
						include_once("includes/mensagem.php");
						include($temp);
					} else {
						$resposta = "Arquivo de Template não encontrado";
						include_once("includes/mensagem.php");
						echo "<pre>";
						print_r($_REQUEST);
						echo "</pre>";
						echo "caminho do Template = " . $temp;
					}
				} else {
					$app_comando = "home";
					$app_modulo  = "home";
					$arquivo     = "modulos/home/modulo.home.php";
					include(URL_FILE . $arquivo);
					$temp = URL_FILE . "modulos/" . $app_modulo . "/template/" . $template;
					include($temp);
				}
			} else {
				include_once ( "template/permissao.sistema.php" );
			}
			//include_once("includes/js_foot.includes.php");
		}
	}
} else {
	if ($app_modulo == "rotina_diaria") {
		include(URL_FILE . "modulos/" . $app_modulo . "/modulo." . $app_modulo . ".php");
		include(URL_FILE . "modulos/" . $app_modulo . "/template/" . $template);
	} else {
		echo "<script>location.href = 'index.php'</script>";
	}
}
