<?php

/**
 * @author squall
 * @copyright 2009
 */

// inclusão de bibliotécas necessárias
include_once("includes/config.inc.php");
Utils::TratarRequest();
$grupo = new Grupo();

$app_modulo 	= addslashes(strip_tags($_REQUEST['app_modulo']));
$app_comando 	= addslashes(strip_tags($_REQUEST['app_comando']));
$app_codigo 	= addslashes(strip_tags($_REQUEST['app_codigo']));
$app_topo 	    = addslashes(strip_tags($_REQUEST['app_topo']));

if($_SESSION['usuario']['id'])
{
	if($app_topo == "") include_once("template/topo.sistema.print.php");
    $objUsuario = new Usuario();

    $logAcesso = new LogAcesso();
    $logAcesso->Gravar();

    if($_SESSION['usuario']['permissao_especifica'])
        $PERMISSAO = $objUsuario->ChecarPermissao($_SESSION['usuario']['id'], $app_comando);
    else
        $PERMISSAO = $grupo->ChecarPermissao($_SESSION['usuario']['id_grupo'], $app_comando);

	if($PERMISSAO || $app_comando == "")
	{
		if($app_modulo != "" && $app_comando != "")
		{
			$arquivo = "modulos/". $app_modulo ."/modulo." .$app_modulo . ".php";
			if(file_exists($arquivo) && !is_dir($arquivo))
			{
				if (file_exists(URL_FILE . "idioma/" . DIR_IDIOMA . "/modulos/$app_modulo.loc.php")) {
					include_once(URL_FILE . "idioma/" . DIR_IDIOMA . "/modulos/$app_modulo.loc.php");
				}
				include(URL_FILE . $arquivo);
			} else {
				$resposta =  "Arquivo de Modulo não encontrado";
				include_once("includes/mensagem.php");
				echo"<pre>";
				print_r($_REQUEST);
				echo "</pre>";
				echo "caminho do Template = " . $temp;
			}
			$temp = URL_FILE . "modulos/". $app_modulo ."/template/". $template;
			if(file_exists($temp) && ! is_dir($temp))
			{
				//include_once("includes/mensagem.php");
				include($temp);
			}
			else
			{
				$resposta =  "Arquivo de Template não encontrado";
				include_once("includes/mensagem.php");
				echo"<pre>";
				print_r($_REQUEST);
				echo "</pre>";
				echo "caminho do Template = " . $temp;
			}
		}
		else
		{
			$app_comando = "home";
			$app_modulo = "home";
			$arquivo = "modulos/home/modulo.home.php";
			include(URL_FILE . $arquivo);
			$temp = URL_FILE . "modulos/". $app_modulo ."/template/". $template;
			include($temp);
		}
	}
	else
	{
		include_once("template/permissao.sistema.php");
	}
	include_once("template/baixo.sistema.print.php");
}
else
{
	$arquivo = "modulos/login/modulo.login.php";
	include(URL_FILE . $arquivo);
}
