<script>
    $(document).ready(function (){
        FuncoesFormulario();
        $("#bt_salvar").click(function () {
            url = "index_xml.php?app_modulo=usuario&app_comando=permissoes_usuario&app_codigo";
            ExecutarAcao(url);
        });
    });

    function ExecutarAcao(url)
    {
        //console.log($("#treeAcoes").jstree("get_selected"));
        $("#bt_salvar").prop("disabled",true).html("<i class=\"fa fa-spin fa-spinner\"></i> AGUARDE...").addClass("btn-warning");
        // ao clicar em salvar enviando dados por post via AJAX
        $.post(url,
            {
                id_usuario:     $("#id_usuario").val(),
                acoes:        $("#treeAcoes").jstree("get_selected")
            },
            // pegando resposta do retorno do post
            function (response)
            {
                if (response["codigo"] == 0) {
                    Squall.ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=usuario&app_comando=listar_usuario&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>",'600');
                } else {
                    Squall.ToastMsg('warning',response["mensagem"]);
                }
                $("#bt_salvar").prop("disabled",false).html("<i class=\"fa fa-check\"></i> SALVAR").removeClass("btn-warning");
            }
            , "json" // definindo retorno para o formato json
        );
    }

    function FuncoesFormulario()
    {
        Squall.autoComplete('#id_grupo_copiar', 'index_xml.php?app_modulo=grupo&app_comando=popup_localizar_grupo_permissoes&id_usuario=<?=$app_codigo?>');

        //

        //Inicializa o plugin de árvore, tirando o style padrão e usando checkboxes
        $('#treeAcoes').jstree(
            {
                "core": {
                    "themes": {
                        "responsive": false
                    }
                },
                "checkbox": {
                    "keep_selected_style": false
                },

                "types": {
                    "default": {
                        "icon": "fas fa-folder text-warning fa-lg"
                    },
                    "file": {
                        "icon": "fas fa-file text-inverse fa-lg"
                    }
                },
                "plugins": ["wholerow", "checkbox", "types"]
            });

        //Dá check em todos já selecionados
        $("li[data-checkstate='checked']").each(function ()
        {
            $("#treeAcoes").jstree("check_node", $(this));
        });

        //Fecha todos os nós
        setTimeout(function()
        {
            $("#treeAcoes").jstree('close_all');
        }, 1000)
    }

    /*
 * Seleciona as funções do grupo escolhido para gravar as ações ao novo grupo
 * */
    function BuscarFuncionalidades()
    {
        //Funcionalidades do grupo selecionado
        var selecionados = [];

        //abre todos os nós
        $("#treeAcoes").jstree('open_all');

        //Busca as funcionalidades
        $.post("index_xml.php?app_modulo=grupo&app_comando=atualizar_funcionalidade_grupo",
            $("#frm_usuario_permissao").serialize()
            ,
            // pegando resposta do post
            function (response)
            {
                if (response == null) {
                    Squall.ToastMsg('warning','Grupo sem permissão nenhuma!');
                    return false;
                }

                //Salva o id das ações do retorno
                $.each(response, function (modulo, acoes)
                {
                    $.each(acoes, function (acao, valores)
                    {
                        if (parseInt(valores.selecionado) > 0) {
                            selecionados.push(valores.id_acao.toString());
                        }
                    })
                });

                //Deseleciona todos os checkboxes por segurança
                $("#treeAcoes").jstree("deselect_all");

                //Marca todos os checkboxes do retorno
                $("li[class*='tree']").each(function ()
                {
                    if ($.inArray($(this).attr('id'), selecionados) >= 0) {
                        $("#treeAcoes").jstree("check_node", $(this));
                    }
                });
            }
            , "json" // definindo retorno para o formato json
        );
    }

</script>