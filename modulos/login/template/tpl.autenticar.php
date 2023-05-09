<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 06/12/2022
 * Time: 11:37
 */
session_name('WEBCOP-SESSION');
session_start();
$ultimosDoisDigitos = substr($_SESSION['usuario']['celular'], -2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        Autenticação em duas etapas
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
    <link rel="stylesheet" media="screen, print" href="/link_report/css/fa-regular.css">
    <link rel="stylesheet" media="screen, print" href="/link_report/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/link_report/css/fa-duotone.css">
    <link rel="stylesheet" media="screen, print" href="/link_report/css/fa-brands.css">
</head>
<style>
    .div-options div:hover{
        transform: scale(1);
        border-radius: 5px;
        background-color: #f8f8f9;
    }

    div.input-block {
        position: relative;
    }
    div.input-block input {
        display: block;
        width: 100%;
        height: calc(1.47em + 1rem + 2px);
        padding: 0.5rem 0.875rem;
        padding-top: 0.5rem;
        font-size: 1.40rem;
        font-weight: 400;
        line-height: 1.47;
        color: #121212;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #cbced7;
        border-radius: 4px;
        -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
    }
    div.input-block span.placeholder {
        position: absolute;
        margin: 14px 0;
        padding: 0 4px;
        font-family: Roboto, sans-serif;
        color:  #6c757d;
        display: flex;
        align-items: center;
        font-size: 16px;
        top: 0;
        left: 15px;
        transition: all 0.2s;
        transform-origin: 0% 0%;
        background: none;
        pointer-events: none;
    }
    div.input-block input:valid + span.placeholder,
    div.input-block input:focus + span.placeholder {
        transform: scale(0.8) translateY(-30px);
        background: #fff;
    }
    div.input-block input:focus{
        color: #284B63;
        border-color: #DD2C00;
    }
    div.input-block input:focus + span.placeholder {
        color: #DD2C00;
    }

    .loader {
        animation: rotate 1s infinite;
        height: 50px;
        width: 50px;
        display: inline-block;
    }

    .loader:before,
    .loader:after {
        border-radius: 50%;
        content: "";
        display: block;
        height: 20px;
        width: 20px;
    }
    .loader:before {
        animation: ball1 1s infinite;
        background-color: #000;
        box-shadow: 30px 0 0 #ff3d00;
        margin-bottom: 10px;
        opacity: 0.5;
    }
    .loader:after {
        animation: ball2 1s infinite;
        background-color: #ff3d00;
        box-shadow: 30px 0 0 #fff;
        opacity: 0.5;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg) scale(0.8) }
        50% { transform: rotate(360deg) scale(1.2) }
        100% { transform: rotate(720deg) scale(0.8) }
    }

    @keyframes ball1 {
        0% {
            box-shadow: 30px 0 0 #ff3d00;
        }
        50% {
            box-shadow: 0 0 0 #ff3d00;
            margin-bottom: 0;
            transform: translate(15px, 15px);
        }
        100% {
            box-shadow: 30px 0 0 #ff3d00;
            margin-bottom: 10px;
        }
    }

    @keyframes ball2 {
        0% {
            box-shadow: 30px 0 0 #000;
        }
        50% {
            box-shadow: 0 0 0 #000;
            margin-top: -20px;
            transform: translate(15px, 15px);
        }
        100% {
            box-shadow: 30px 0 0 #000;
            margin-top: 0;
        }
    }

    .spinftw {
       border-radius: 100%;
       display: inline-block;
       height: 30px;
       width: 30px;
       top: 45%;
       position: absolute;
       background-size: contain;
    }

    #CssLoader
    {
        display: none;
        padding-right: 12%;
        text-align: center;
        height: 100%;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(255,255,255 , 0.9);
        z-index: 9999;
        border-radius: 4px;
    }
</style>
<body>

<div class="blankpage-form-field" style="width: 500px">
        <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4" style="background-color: #DD2C00">
            <a href="javascript:void(0)" class="page-logo-link">
                <img src="/link_report/img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
            </a>
        </div>
        <div id="CssLoader">
            <div class='spinftw'>
                <span class="loader"></span>
            </div>
        </div>
        <div style="background-color: #fff; border-radius: 4px">
            <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">
                <form id="autenticacaoFrm" >

                    <center>
                        <h3><strong>Verificação em duas etapas</strong><h4>
                        <span style="color: #202124;font-size: 16px;font-weight: 400;letter-spacing: 0.1px;line-height: 1.5;">
                            Para ajudar a protejer a sua conta, nós precisamos confirmar se é realmente você que está tentando fazer login
                        </span>
                    </center>
                    <div id="div-metodo">
                        <a href="javascript:void(0);" class="div-options" data-option="1" disabled="" style="color: inherit; <?if(empty($ultimosDoisDigitos))  echo 'opacity:0.3; pointer-events: none;  cursor: default;'?>">
                            <div class="border-faded border-left-0 border-right-0 ">
                                <div class="row pt-2 pb-2" style="margin-right: 2px; margin-left: 2px">
                                    <div class="col-md-1 pt-3">
                                        <span> <i class="fas fa-mobile fa-lg"></i></span>
                                    </div>
                                    <div class="col-md-10 pt-1">
                                        <span>Autenticar via SMS</span><br>
                                        <?if (!empty($ultimosDoisDigitos)){
                                            echo "<small>Um SMS será enviado em: (**) ****-**{$ultimosDoisDigitos}</small>";
                                        }else{
                                            echo "<small>Você não possuí um celular cadastrado</small>";
                                        }?>
                                    </div>
                                    <div class="col-md-1 pt-1">
                                        <span> <i class="fas fa-chevron-double-right"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="javascript:void(0);" class="div-options" data-option="2" style="color: inherit;">
                            <div class="border-faded border-top-0 border-left-0 border-right-0">
                                <div class="row pt-2 pb-2" style="margin-right: 2px; margin-left: 2px">
                                    <div class="col-md-1 pt-1">
                                        <span> <i class="fas fa-envelope fa-lg"></i></span>
                                    </div>
                                    <div class="col-md-10 pt-1">
                                        Autenticar via e-mail
                                    </div>
                                    <div class="col-md-1 pt-1">
                                        <span> <i class="fas fa-chevron-double-right"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="row pt-3">
                            <div class="col-md-12 text-right">
                                <a href="/link_report/modulos/login/template/tpl.sair.php" class="btn btn-danger"> <i class="fa fa-chevron-double-left"></i> Voltar</a>
                            </div>
                        </div>
                    </div>
                    <div id="via-sms" style="display: none">
                        <h5><strong> Verificação em duas etapas</strong></h5>
                        <span style="font-size: 14px"> Um SMS foi enviado com um código de verificação de seis dígitos para: (**) *****-**<?=$ultimosDoisDigitos?></span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-block" style="margin-top: 10px">
                                    <input type="text" maxlength="6" class="codigo_validador" required autocomplete="off" >
                                    <span class="placeholder">Digite o código</span>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <a href="javascript:void(0);" id="mostrar-opcoes"> Tentar de outro jeito</a>
                            </div>
                            <div class="col-md-6 pt-3 text-right">
                                <a href="/link_report/modulos/login/template/tpl.sair.php" class="btn btn-danger"> <i class="fa fa-chevron-double-left"></i> Voltar</a>
                                <button class="btn btn-danger btn-confirmar" type="button"> Próximo <i class="fa fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div id="via-email" style="display: none">
                        <h5><strong> Verificação em duas etapas</strong></h5>
                        <span style="font-size: 14px"> Um e-mail foi enviado com um código de verificação de seis dígitos para: <?=substr_replace(substr_replace($_SESSION['usuario']['email'], '*****', 1, strpos($_SESSION['usuario']['email'], '@') - 2), '**********', strpos($_SESSION['usuario']['email'], '@') + 3, strpos($_SESSION['usuario']['email'], '.') - 8); ?></span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-block" style="margin-top: 10px">
                                    <input type="text" maxlength="6" class="codigo_validador" required autocomplete="off">
                                    <span class="placeholder">Digite o código</span>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <a href="javascript:void(0);" id="mostrar-opcoes"> Tentar de outro jeito</a>
                            </div>
                            <div class="col-md-6 pt-3 text-right">
                                <a href="/link_report/modulos/login/template/tpl.sair.php" class="btn btn-danger"> <i class="fa fa-chevron-double-left"></i> Voltar</a>
                                <button class="btn btn-danger btn-confirmar" type="button"> Próximo <i class="fa fa-chevron-right"></i></button>
                            </div>
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
<script>
    $('.div-options').on('click', function () {
        var option = $(this).attr('data-option');
        if (option == 1){
            $("#CssLoader").show();
            var url   = '/link_report/includes/validacao.php?act=validar_via_sms';
            $.post(url, $('#loginform').serialize(),
                function (data)
                {
                    if (data['codigo'] == 0) {
                        $('#div-metodo').hide('slow');
                        $('#via-sms').show('slow');
                    }else{
                        ToastMsgLogin("error", data['msg']);
                    }
                    $("#CssLoader").hide();
                },
                'json'
            );
        }else if(option == 2){
            $("#CssLoader").show();
            var url   = '/link_report/includes/validacao.php?act=validar_via_email';
            $.post(url, $('#loginform').serialize(),
                function (data)
                {
                    if (data['codigo'] == 0) {
                        $('#div-metodo').hide('slow');
                        $('#via-email').show('slow');
                    }else{
                        ToastMsgLogin("error", data['msg']);
                    }
                    $("#CssLoader").hide();
                },
                'json'
            );
        }
    });

    $('.btn-confirmar').on('click', function () {
        console.log()
        var codigoValidador = $(this).parent().parent().find('.codigo_validador'),
            lenCod = codigoValidador[0].textLength;
        if (codigoValidador.val() == ''){
            ToastMsgLogin("error", "Insira um código!");
            return;
        }else if(lenCod < 6){
            var num = lenCod == 1 ? "número" : "números";
            ToastMsgLogin("error", `Você inseriu apenas ${lenCod} ${num}. Verifique seu código e tente novamente.`);
            return;
        }
        $("#CssLoader").show();
        var url   = '/link_report/includes/validacao.php?act=verificar_codigo_duas_etapas';
        $.post(url, {codigo_validador: codigoValidador.val()},
            function (data){
                if (data['codigo'] == 1) {
                    window.location = `/link_report`;
                }else{
                    ToastMsgLogin("error", data['msg']);
                }
                $("#CssLoader").hide();
            },
            'json'
        );
    });

    $('#mostrar-opcoes').on('click', function () {
        $('#div-metodo').show('slow');
        $('#via-sms').hide('slow');
        $('#via-email').hide('slow');
    })

    function ToastMsgLogin(tipo,mensagem) {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "10000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        toastr[tipo](mensagem);
    }
</script>
