<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 29/09/2021
 * Time: 07:47
 */
?>
<script type="text/javascript">
$(function() {

});
$('input[type=text]').on('keydown', function(e) {
    var campoAtual = e.target.attributes.id.value;
    if (e.which == 13) {
        e.preventDefault();
        if (campoAtual == "email")
            VerificarLogin();
    }
});
$('#bt_enviar').click(function () {
    VerificarLogin();
});

function VerificarLogin() {
    if ($('#email').val() == ''){
        ToastMsgLogin("warning", "Por favor, informe o e-mail!");
        return;
    }
    var url   = '/link_report/includes/validacao.php?act=localizar_usuario_login';
    $.post(url, $('#loginform').serialize(),
        function (data)
        {
            if (data['codigo'] == 2) {
                ToastMsgLogin("info", data['msg']);
            }else if(data['codigo'] == 1){
                ToastMsgLogin("error", data['msg']);
            }
            else if (data['nome'] != "" && data['nome'] != undefined)
            {
                $('#div_email_enviado').fadeIn();
                $('#div_recuperar_senha').fadeOut();
                $('#nome_usuario').html(data.nome);
                $('#id_usuario').val(data.id);
                $('#email-txt').html(data.email);
            }else{
                ToastMsgLogin("error", data['msg']);
            }
        },
        'json'
    );
}
$('#bt_continuar').click(function () {
    var lenCod = $('#codigo_validador')[0].textLength;
    if ($('#codigo_validador').val() == ''){
        ToastMsgLogin("error", "Insira um código!");
        return;
    }else if(lenCod < 6){
        var num = lenCod == 1 ? "número" : "números";
        ToastMsgLogin("error", `Você inseriu apenas ${lenCod} ${num}. Verifique seu código e tente novamente.`);
        return;
    }
    var url   = '/link_report/includes/validacao.php?act=verificar_codigo_validador';
    $.post(url, $('#codigoValidador').serialize(),
        function (data)
        {
            if (data['codigo'] == 1) {
                window.location = `/link_report/modulos/login/template/tpl.frm.mudar_senha.php?codigo=${$('#codigo_validador').val()}&id=${$('#id_usuario').val()}`;
            }else{
                ToastMsgLogin("error", data['msg']);
            }
        },
        'json'
    );
});

$('#reenviar_codigo').click(function () {
    $('#div_recuperar_senha').fadeIn();
    $('#div_email_enviado').fadeOut();
});
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