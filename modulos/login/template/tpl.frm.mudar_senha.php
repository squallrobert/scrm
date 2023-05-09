<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 29/09/2021
 * Time: 15:42
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        Login - www.linkmonitoramento.com.br
    </title>
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" media="screen, print" href="/link_report/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/link_report/css/app.bundle.css">
    <link id="myskins" rel="stylesheet" media="screen, print" href="/link_report/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="/link_report/css/notifications/toastr/toastr.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/link_report/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/link_report/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="/link_report/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="/link_report/css/page-login.css">
</head>
<body>
<div class="blankpage-form-field" style="width: 500px">
    <div id="div_recuperar_senha">
        <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4" style="background-color: #DD2C00">
            <a href="javascript:void(0)" class="page-logo-link">
                <img src="/link_report/img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
            </a>
        </div>
        <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">
            <form  id="mudar_senha_form">
                <input type="hidden" id="id_usuario" name="id_usuario" value="<?=$_REQUEST['id']?>">
                <input type="hidden" id="codigo_validador" name="codigo_validador" value="<?=$_REQUEST['codigo']?>">
                <div class="form-group">
                    <input type="password" id="mudar_senha" name="mudar_senha"  class="form-control" placeholder="Nova senha" value="">
                </div>
                <div class="form-group">
                    <input type="password" id="confirmar_mudar_senha" name="confirmar_mudar_senha"  class="form-control" placeholder="Confirme sua nova senha" value="">
                </div>
                <div class="row mt-3 mt-3">
                    <div class="col-md-12 text-right">
                        <a type="button" id="bt_cancelar" href="/link_report" class="btn btn-default">Cancelar</a>
                        <button type="button" id="bt_salvar_mudar_senha" class="btn btn-danger">Continuar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<video poster="/link_report/img/backgrounds/link.png" id="bgvid" playsinline autoplay muted loop>
    <source src="/link_report/media/video/link.webm" type="video/webm">
    <source src="/link_report/media/video/link.mp4" type="video/mp4">
</video>

<script src="/link_report/js/vendors.bundle.js"></script>
<script src="/link_report/js/app.bundle.js"></script>
<script src="/link_report/js/notifications/toastr/toastr.js"></script>
</body>
</html>
<?include_once ('js.mudar_senha.php');?>