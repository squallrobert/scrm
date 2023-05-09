<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:28
 */
?>
<script type="text/javascript">
    $(function ()
    {
        FuncoesFormulario();
    });

    function FuncoesFormulario()
    {
        var $padrao      = $("#padrao"),
            $obrigatorio = $("#obrigatorio"),
            $campos      = $("#campos");

        $obrigatorio.on("change", function () {
            if ($obrigatorio.is(":checked") == true){
                $("#textoObrigatorio").text("Obrigatório");
            }else{
                $("#textoObrigatorio").text("Opcional");
            }
        })
        $padrao.on("change", function () {
            if ($padrao.is(":checked") == true){
                $("#textoPadrao").text("Habilitado");
            }else{
                $("#textoPadrao").text("Desabilitado");
            }
        })
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
                        dialog.close();
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
