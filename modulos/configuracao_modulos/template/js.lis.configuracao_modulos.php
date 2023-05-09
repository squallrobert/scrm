<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:18
 */
?>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#AdicionarRegistro').click(function ()
        {
            var m = BootstrapModalWrapperFactory.createAjaxModal({
                message: `<p class="text-center"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando... </p>`,
                closable: true,
                title: "<div class='titulo_modal'>Modificar Campo</div>",
                closeByBackdrop: false,
                centered: true,
                updateSizeAfterDataFetchTo: "modal-xl",
                ajax: { // all jquery.ajax parameters are supported.
                    url: `ajax/configuracao_modulos/frm_adicionar_campo/`,
                    data: {}
                },
                ajaxContainerReadyEventName: "event-name-triggered-once-ajax-content-updated",
                buttons: [
                    {
                        label: "Fechar",
                        cssClass: "btn btn-danger text-white fechar",
                        action: function (button, buttonData, originalEvent) {
                            return this.hide();
                        }
                    },
                    {
                        label: "Salvar",
                        cssClass: "btn btn-success-500 bg-success-500 salvar text-white",
                        action:   function (dialogRef){
                            ExecutarAcao(dialogRef, "ajax/configuracao_modulos/adicionar_campo/");
                        }
                    },
                ]
            });
            m.originalModal.find(".modal-dialog").css({transition: 'all 1s'});
            $(".salvar").html("<i class=\"fa fa-check text-white\"></i> Salvar");
            $(".fechar").html("<i class=\"fa fa-times text-white\"></i> Fechar");
        });

        $('#ExcluirRegistro').click(function()
        {
            var checked = $("input[name='lista[]']:checked").length;
            if(checked > 0)
            {
                var values = new Array();
                $.each($("input[name='lista[]']:checked"), function() {
                    values.push($(this).val());
                });
                //ConfirmBootStrap('<?//= TXT_CONFIRME_DELETE_REGISTROS?>// <br>ID´s ('+values+')','<?//= TXT_ATENCAO?>//',values,ExcluirRegistros,'',4);
                //Parameter
                swal.fire({
                    title: "Confirme, por favor",
                    text: "Você realmente gostaria de remover estes registros?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, continue!",
                    cancelButtonText: "Não, cancelar!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then((isConfirm) =>{
                    if (isConfirm.value) {
                        ExcluirRegistros(values);
                        swal.fire("Removido!", "O(s) registro(s) foram removidos com sucesso.", "success");
                    } else {
                        swal.fire("Cancelado", "Remoção cancelada pelo usuário", "error");
                    }
                });

            }
            else
            {
                ToastMsg('warning','Selecione pelo menos 1 registro');
            }
        });

        $('[data-toggle="tooltip"]').tooltip();
    });

    function ModificarCampo(id)
    {
        var m = BootstrapModalWrapperFactory.createAjaxModal({
            message: `<p class="text-center"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando... </p>`,
            closable: true,
            title: "<div class='titulo_modal'>Modificar Campo</div>",
            closeByBackdrop: false,
            centered: true,
            updateSizeAfterDataFetchTo: "modal-xl",
            ajax: { // all jquery.ajax parameters are supported.
                url: `ajax/configuracao_modulos/frm_modificar_campo/${id}/`,
                data: {}
            },
            ajaxContainerReadyEventName: "event-name-triggered-once-ajax-content-updated",
            buttons: [
                {
                    label: "Fechar",
                    cssClass: "btn btn-danger text-white fechar",
                    action: function (button, buttonData, originalEvent) {
                        return this.hide();
                    }
                },
                {
                    label: "Salvar",
                    cssClass: "btn btn-success-500 bg-success-500 salvar text-white",
                    action:   function (dialogRef){
                        ExecutarAcao(dialogRef, "ajax/configuracao_modulos/modificar_campo/");
                    }
                },
            ]
        });
        m.originalModal.find(".modal-dialog").css({transition: 'all 1s'});
        $(".salvar").html("<i class=\"fa fa-check text-white\"></i> Salvar");
        $(".fechar").html("<i class=\"fa fa-times text-white\"></i> Fechar");
    }

    function AtribuirCampo(id)
    {
        window.location = `#ajax/configuracao_modulos/frm_atribuir_campo/${id}/`
    }

    function ExcluirRegistros(dados)
    {
        $.post('ajax/configuracao_modulos/deletar_campo/',
            {
                registros: dados
            },
            function (response)
            {
                if (response['codigo'] == 0) {
                    ToastMsg('success','Sucesso ao Remover registro(s)');
                }else{
                    ToastMsg('warning','Erro ao Remover registro(s)');
                }
            }
            , 'json'
        );
        AtualizarGridConfiguracaoCampos(0, "");
    }

    function ExecutarAcao(dialog, url)
    {
        if (ValidateForm($("#conteudo_config"))) {
            // ao clicar em salvar enviando dados por post via AJAX
            $.post(url,
                $('#conteudo_config').serialize(),
                // pegando resposta do retorno do post
                function (response)
                {
                    if (response["codigo"] == 0) {
                        $('.fechar').click();
                        ToastMsg('success',response["mensagem"]);
                    } else {
                        ToastMsg('warning',response["mensagem"]);
                    }
                }
                , "json" // definindo retorno para o formato json
            );
            AtualizarGridConfiguracaoCampos(0, "");
        }
    }
</script>
