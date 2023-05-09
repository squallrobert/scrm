<?php
/**
 * @author squall
 * @copyright 2009
 */
ini_set( 'memory_limit','2048M');

include_once("includes/config.inc.php");
Utils::TratarRequest();

$grupo = new Grupo($_SESSION['usuario']['configuracoes']['sigla_idioma']);

$app_modulo 	= addslashes(strip_tags($_REQUEST['app_modulo']));
$app_comando 	= addslashes(strip_tags($_REQUEST['app_comando']));
$app_codigo 	= addslashes(strip_tags($_REQUEST['app_codigo']));

if($_SESSION['usuario']['id'])
{
    $objUsuario = new Usuario($_SESSION['usuario']['configuracoes']['sigla_idioma']);
    // SE O USUÁRIO FOI CONFIGURADO COM PERMISSÕES ESPECIFICAS PARA USER
    if($_SESSION['usuario']['permissao_especifica'])
        $PERMISSAO = $objUsuario->ChecarPermissao($_SESSION['usuario']['id'], $app_comando);
    else
        $PERMISSAO = $grupo->ChecarPermissao($_SESSION['usuario']['id_grupo'], $app_comando);

    $logAcesso = new LogAcesso();
    $logAcesso->Gravar();

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
            }
            else
            {
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
                include_once("includes/mensagem.php");
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
            $arquivo = "modulos/home/modulo.home.php";
            include(URL_FILE . $arquivo);
        }
    }
    //include_once("template/baixo.sistema.php");
}
else
{
    echo "<script>location.href = 'index.php'</script>";
}
