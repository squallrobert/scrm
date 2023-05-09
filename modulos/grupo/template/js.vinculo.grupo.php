<script>
    var veiculosSelecionados = [];
    $(document).ready(function () {
        AtualizarGridVeiculos();
        AtualizarGridViculos();
        $("#bt_salvar_vinculos").click(function () {
            ExecutarAcaoAtualizarVinculos();
        });


    });
    function SelecionarTodosItens(master, todos)
    {

        if ($(master).is(':checked')) {
            $(todos).prop('checked', true);
        } else {
            $(todos).prop('checked', false);
        }
    }
    function ExecutarAcaoAtualizarVinculos()
    {
        // console.log( $("#frm_veiculo_vinculados"));

        $('#frm_veiculo_vinculados').ajaxSubmit
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
                        ToastMsg('success',msg["mensagem"],"#index_xml.php?app_modulo=grupo&app_comando=listar_grupo",'600');
                    } else {
                        ToastMsg('warning',msg["mensagem"]);
                    }
                },
                url:          'index_xml.php?app_modulo=grupo&app_comando=atualizar_lista_vinculos_grupos',
                resetForm:    false,
                type:         'post',
                dataType:     'json'
            }
        );
    }
    function AtualizarGridVeiculos(pagina, busca, filtro, ordem)
    {
        //alert(filtro);
        if (filtro == "" || filtro === undefined) {
            filtro = "";
        }
        if (ordem == "" || ordem === undefined) {
            ordem = "";
        }
        if (busca == "" || busca === undefined) {
            busca = "";
        }
        busca = encodeURI(busca);
        $("#veiculo_vinculo").load("index_xml.php?app_comando=listar_veiculos_vinculos_grupo&app_modulo=grupo&app_codigo&pagina=" + pagina + "&busca=" + $.trim(busca) + "&filtro=" + filtro + "&ordem=" + ordem + "&veiculos_selecionados=" + veiculosSelecionados.toString());
        // $("#envio_comando").empty();
    }
    function AtualizarGridViculos(pagina, busca, filtro, ordem)
    {
        //alert(filtro);
        if (filtro == "" || filtro === undefined) {
            filtro = "";
        }
        if (ordem == "" || ordem === undefined) {
            ordem = "";
        }
        if (busca == "" || busca === undefined) {
            busca = "";
        }
        if (pagina == "" || pagina === undefined) {
            pagina = 0;
        }
        busca = encodeURI(busca);
        $("#veiculos_vinculados").load("index_xml.php?app_comando=listar_veiculos_vinculados_grupos&app_modulo=grupo&app_codigo=<?=$_REQUEST['app_codigo']?>&pagina=" + pagina + "&busca=" + $.trim(busca) + "&filtro=" + filtro + "&ordem=" + ordem + "&veiculos_selecionados=" + veiculosSelecionados.toString());
        // $("#envio_comando").empty();
    }
    function selecionarTodosVeiculos(){
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
    function SelecionarVeiculo(id)
    {
        // console.log(id);
        //Faz uma cópia do veículo selecionado e o remove da lista
        var $selecionado = $('#veiculo_' + id).closest('tr').remove().clone();
        // console.log($selecionado)
        var $children    = $selecionado.children();
        // console.log($children);
        //Monta o html para ser colocado na lista de selecionados
        var html = "" +
            "<tr id='linha_" + id + "'>" +
            "   <td> <input type=\"hidden\" name=\"vinculado[" + id + "]\" id=\"vinculado" + id + "\" value=\"\"><input type=\"hidden\" name=\"id_veiculo[]\" id=\"id_veiculo_" + id + "\" value=\"" + id + "\">" + $children[1].innerHTML + "</td>" +
            "   <td>" + $children[2].innerHTML + "</td>" +
            "   <td>" + $children[3].innerHTML + "</td>" +
            "   <td>" +
            "       <a class='fas fa-trash text-danger m-l-10' href='javascript:RemoverSelecionado(" + id + ")'    title='Remover'></a>" +
            "   </td>" +
            "</tr>";
        // console.log(html);
        //Adiciona o veículo à lista de selecionados e ativa o tooltip
        // $("#listagem_veiculos_selecionados").find("tbody").append(html);
        $("#listagem_veiculos_selecionados").find("tbody").append(html);
        $('[data-toggle="tooltip"]').tooltip();

        //Guarda o ID do veículo selecionado para posts
        veiculosSelecionados.push(id);

    }

    /**
     * Remove o campo da lista de selecionados
     * */
    function RemoverSelecionado(id)
    {
        $("#linha_" + id).remove();
        veiculosSelecionados.splice(veiculosSelecionados.indexOf(id), 1);
        AtualizarGridVeiculos();
    }


</script>