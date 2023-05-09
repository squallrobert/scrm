<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 30/09/2021
 * Time: 07:37
 */
?>
<script>
    $(function() {
        <? if(empty($_REQUEST['codigo']) || empty($_REQUEST['id'])){
            header('Location: /link_report');
        }?>
        ValidarCodigoHash(<?=$_REQUEST['codigo']?>, '<?=$_REQUEST['hash']?>', <?=$_REQUEST['id']?>);
        var popoverSenha = 0; //flag do popover do campo senha
        var popoverConfirmar = 0;
        $("#mudar_senha, #confirmar_mudar_senha").keydown(function () {
            //Pega o elemento focado, espera 50ms e executa a validação ao campo senha
            //Timeout para pegar o valor do campo após o evento do teclado
            var focus = $(document.activeElement);
            setTimeout(function () {
                if (focus.attr('id') == "mudar_senha") {
                    if (focus.val() == "") {
                        focus.removeClass('is-invalid');
                        focus.removeClass('is-valid');
                        return false;
                    }
                    //Lógica para senha com menos de 8 ou mais de 32 caracteres
                    if (focus.val().length < 8 || focus.val().length > 32) {
                        //Aplica as classes e o popover apenas a primeira vez para essa validacao
                        //Isso evita que o popover fique abrindo e fechando a cada evento e melhora a performance
                        if (popoverSenha == 0 || popoverSenha == 2) {
                            focus.addClass('is-invalid');
                            focus.attr("data-toggle", "popover");
                            focus.attr("data-template","<div class='popover bg-danger-500 border-danger' role='tooltip'><div class='arrow'></div><h3 class='popover-header bg-transparent'></h3><div class='popover-body text-white'></div></div>");
                            focus.attr("data-placement", "top");
                            focus.attr("data-content", "Tamanho da senha inválido");
                            focus.popover("show");
                            popoverSenha = 1;
                        }
                    }
                    //Validação de pelo menos 1 letra e 1 número
                    else if (focus.val().length >= 8 && (!focus.val().match(/[a-z]/i)) || (!focus.val().match(/[0-9]/i))) {
                        if (popoverSenha == 1 || popoverSenha == 2) {
                            focus.addClass('is-invalid');
                            focus.attr("data-toggle", "popover");
                            focus.attr("data-template","<div class='popover bg-danger-500 border-danger' role='tooltip'><div class='arrow'></div><h3 class='popover-header bg-transparent'></h3><div class='popover-body text-white'></div></div>");
                            focus.attr("data-placement", "top");
                            focus.attr("data-content", "A senha deve conter 1 letra e 1 número");
                            focus.popover("show");
                            popoverSenha = 0;
                        }
                    }
                    //Senha com padrão permitido
                    else {
                        popoverSenha = 2;
                        focus.removeClass('is-invalid');
                        focus.addClass('is-valid');
                        focus.popover("hide");
                        $("#confirmar_senha").removeAttr("disabled");
                    }
                }
                if (focus.attr('id') == "confirmar_mudar_senha") {
                    //Não permite que o campo confirmar_senha seja utilizado antes da senha ser válida
                    if (popoverSenha != 2 && focus.attr('id') == "confirmar_senha") {
                        focus.addClass('is-invalid');
                        focus.attr("data-toggle", "popover");
                        focus.attr("data-template","<div class='popover bg-danger-500 border-danger' role='tooltip'><div class='arrow'></div><h3 class='popover-header bg-transparent'></h3><div class='popover-body text-white'></div></div>");
                        focus.attr("data-placement", "top");
                        focus.attr("data-content", "Senha inválida");
                        focus.popover("show");
                        popoverConfirmar = 0;
                    }
                    //Aponta erro caso a senha seja diferente da confirmação
                    else if ($("#mudar_senha").val() != $("#confirmar_mudar_senha").val()) {
                        setTimeout(function () {
                            if (popoverConfirmar == 0 && popoverSenha == 2) {
                                focus.addClass('is-invalid');
                                focus.attr("data-toggle", "popover");
                                focus.attr("data-template","<div class='popover bg-danger-500 border-danger' role='tooltip'><div class='arrow'></div><h3 class='popover-header bg-transparent'></h3><div class='popover-body text-white'></div></div>");
                                focus.attr("data-placement", "top");
                                focus.attr("data-content", "Confirmação de senha é diferente de senha");
                                focus.popover("show");
                                popoverConfirmar = 1;
                            }
                        }, 50);
                    }
                    //Senha e confirmação iguais
                    else if ($("#mudar_senha").val().length > 0) {
                        focus.removeClass('is-invalid');
                        focus.addClass('is-valid');
                        focus.popover("hide");
                        popoverConfirmar = 0;
                    }
                }
                //Ativa todos os popovers
                $('[data-toggle="popover"]').popover();
            }, 10);
        });
    });
    function ValidarCodigoHash(codigo, hash, id) {
        var url   = '/link_report/includes/validacao.php?act=verificar_codigo_validador';
        $.post(url,  {codigo_validador: codigo, hash:hash, id_usuario:id},
            function (data)
            {
                if (data['codigo'] == 2) {
                    window.location = "/link_report";
                }
            },
            'json'
        );
    }
    $('#bt_salvar_mudar_senha').click(function () {
        if ($('#mudar_senha').val() == ''){
            ToastMsgLogin("error", "Insira uma nova senha!");
            return;
        }else if($('#confirmar_mudar_senha').val() == ""){
            ToastMsgLogin("error", "Confirme sua senha!");
            return;
        }
        var url   = '../../../link_report/includes/validacao.php?act=salvar_nova_senha';
        $.post(url, $('#mudar_senha_form').serialize(),
            function (data)
            {
                if (data['codigo'] == 0) {
                    ToastMsgLogin("success", data['mensagem']);
                    setTimeout(function () {
                        window.location = "/link_report";
                    }, 1000)
                }else{
                    ToastMsgLogin("error", data['mensagem']);
                }
            },
            'json'
        );
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
