<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 09:09
 */
?>
<script type="text/javascript">
    var ColunasSelecionados  = [];
    var id_modulo = '<?=$app_codigo?>';
    $(document).ready(function () {
        AtualizarGridColunas(0, '');
        AtualizarGridVinculos(0, '');
        $("#bt_salvar_vinculos").click(function () {
            var id = "<?=$app_codigo?>";
            var tipo = 1;
            var url = "ajax/configuracao_modulos/atribuir_campo/";
            ExecutarAcaoAtualizarVinculos(url);
        });

        $('#modulo').select2({language: "pt-BR"});
    });
    
    function AtualizarGrids(id) {
        id_modulo = id;
        AtualizarGridColunas(0, "");
        AtualizarGridVinculos(0, "");
    }
    function SelecionarTodosItens(master, todos)
    {
        if ($(master).is(':checked')) {
            $(todos).prop('checked', true);
        } else {
            $(todos).prop('checked', false);
        }
    }
    function ExecutarAcaoAtualizarVinculos(url)
    {
        $("#bt_salvar_vinculos").prop("disabled",true).html("<i class=\"fa fa-spin fa-spinner\"></i> Aguarde...").addClass("btn-warning");
        $('#frm_configuracao_relatorios_campos').ajaxSubmit
        (
             {
                target:       '_blank',
                beforeSubmit: function (formData)
                {
                    var queryString = $.param(formData);
                    //$('.preloader').show();
                    return true;
                },
                success:      function (msg)
                {
                    if (msg["codigo"] == 0) {
                        ToastMsg('success',msg["mensagem"],"#ajax/configuracao_modulos/listar_configuracao_modulos/",'1000');
                    } else {
                        ToastMsg('warning',msg["mensagem"]);
                    }
                    $("#bt_salvar_vinculos").prop("disabled",false).html("<i class=\"fa fa-check\"></i> Salvar").removeClass("btn-warning");
                },
                url:          url,
                resetForm:    false,
                type:         'post',
                dataType:     'json'
            }

        );
    }
    function AtualizarGridColunas(pagina, busca, filtro, ordem)
    {
        if(filtro == "" || filtro === undefined)  filtro = "";
        if(ordem == "" || ordem  === undefined)  ordem = "";

        var toPost = {
            pagina: pagina,
            busca: busca,
            filtro: filtro,
            ordem: ordem,
            veiculos_selecionados:ColunasSelecionados.toString()
        };
        $("#colunas_vinculo").load("ajax/configuracao_modulos/listar_colunas_disponiveis/", toPost);
    }
    function AtualizarGridVinculos(pagina, busca, filtro, ordem)
    {
        if(filtro == "" || filtro === undefined)  filtro = "";
        if(ordem == "" || ordem  === undefined)  ordem = "";

        var toPost = {
            pagina: pagina,
            busca: busca,
            filtro: filtro,
            ordem: ordem,
            veiculos_selecionados:ColunasSelecionados.toString(),
            id_modulo: id_modulo
        };
        $("#colunas_vinculados").load("ajax/configuracao_modulos/listar_colunas_viculadas/", toPost);
    }
    function selecionarTodasColunas(){
        $("[name='lista[]']").each(function(index,element){
            var ids = $(this).val();
            var vetorIds  = ids.split(",");
            //console.log(vetorIds);
            SelecionarVeiculo(vetorIds[0],vetorIds[1]);
        });
    }
    /**
     * Retira o veículo da lista de disponíveis e o coloca na lista de selecionados
     *
     * @param id                id do veiculo
     * @param id_rastreador     id do rastreador
     * @param externo           booleano sinalizando chamada de fora do módulo
     * */
    function SelecionarColuna(id)
    {
        //Faz uma cópia do veículo selecionado e o remove da lista
        var $selecionado = $('#veiculo_' + id).closest('tr').remove().clone();
        var $children    = $selecionado.children();
        //Monta o html para ser colocado na lista de selecionados
        var html = "" +
            "<tr id='linha_" + id + "'>" +
            "   <td> <input type=\"hidden\" name=\"vinculado[" + id + "]\" id=\"vinculado" + id + "\" value=\"\"><input type=\"hidden\" name=\"id_coluna[]\" id=\"id_coluna_" + id + "\" value=\"" + id + "\">" + $children[0].innerHTML + "</td>" +
            "   <td>" + $children[1].innerHTML + "</td>" +
            "   <td>" + $children[2].innerHTML + "</td>" +
            "   <td>" +
            "       <a class='fal fa-trash text-danger m-l-10' href='javascript:RemoverSelecionado(" + id + ")'    title='Remover'></a>" +
            "   </td>" +
            "</tr>";
        if ($("#listagem_colunas_selecionados").find('td').text() == "Nenhum Registro Encontrado."){
            $("#listagem_colunas_selecionados").find('td').remove();
        }

        $("#listagem_colunas_selecionados").find("tbody").append(html);
        $('[data-toggle="tooltip"]').tooltip();
        ColunasSelecionados.push(id);
    }

    /**
     * Remove o campo da lista de selecionados
     * */
    function RemoverSelecionado(id)
    {
        $("#linha_" + id).remove();
        ColunasSelecionados.splice(ColunasSelecionados.indexOf(id), 1);
        AtualizarGridColunas();
    }

    function ExecutarAcao(url)
    {
        if (ValidateForm($("#frm_configuracao_relatorios_campos"))) {
            // ao clicar em salvar enviando dados por post via AJAX
            $.post(url,
                $("#frm_configuracao_relatorios_campos").serialize(),
                // pegando resposta do retorno do post
                function (response)
                {
                    if (response["codigo"] == 0) {
                        ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=configuracao_relatorios_campos&app_comando=listar_configuracao_modulo",'600');
                        // window.location="#index_xml.php?app_modulo=configuracao_relatorios_campos&app_comando=listar_configuracao_relatorios_campos";

                        // $("#frm_configuracao_relatorios_campos").each (function(){
                        //     this.reset();
                        // });
                    } else {
                        ToastMsg('warning',response["mensagem"]+ "<BR>" +response["debug"]+"");
                    }
                }
                , "json" // definindo retorno para o formato json
            );
        }
    }
</script>

