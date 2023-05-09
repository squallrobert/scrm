<?php
//include configurações do sistema
include_once("includes/config.inc.php");
Utils::TratarRequest();

$app_modulo  = $_REQUEST['app_modulo'];
$app_comando = $_REQUEST['app_comando'];
$app_codigo  = $_REQUEST['app_codigo'];

if ($_SESSION['usuario']['id']) {
    include_once("template/topo_full.php");
    $objUsuario = new Usuario();
    $grupo = new Grupo();
    // SE O USUÁRIO FOI CONFIGURADO COM PERMISSÕES ESPECIFICAS PARA USER
    if($_SESSION['usuario']['permissao_especifica'])
        $PERMISSAO = $objUsuario->ChecarPermissao($_SESSION['usuario']['id'], $app_comando);
    else
        $PERMISSAO = $grupo->ChecarPermissao($_SESSION['usuario']['id_grupo'], $app_comando);

    $logAcesso = new LogAcesso();
    $logAcesso->Gravar();

    if (!$PERMISSAO && $app_comando != "") {
        include "template/401_unauthorized_request.php";
    } else if ($PERMISSAO && $app_modulo != "" && $app_comando != "") {
        $arquivo = "modulos/" . $app_modulo . "/modulo." . $app_modulo . ".php";
        if (file_exists($arquivo) && !is_dir($arquivo)) {
            $arquivoIdioma = URL_FILE . "idioma/" . DIR_IDIOMA . "/modulos/$app_modulo.loc.php";

            if (file_exists($arquivoIdioma)) {
                include_once $arquivoIdioma;
            }

            include(URL_FILE . $arquivo);
        } else {
            include "template/404_not_found_request.php";
        }

        $temp = URL_FILE . "modulos/" . $app_modulo . "/template/" . $template;
        if (file_exists($temp) && !is_dir($temp)) {
            include($temp);
        } else {
            include "template/404_not_found_request.php";
        }
    } else {
       // $arquivo = "modulos/home/modulo.home.php";
       // include(URL_FILE . $arquivo);
    }
    include_once("template/foot_full.php");
} else {
    include(URL_FILE . "modulos/login/modulo.login.php");
    include(URL_FILE . "modulos/login/template/tpl.login.php");
}
