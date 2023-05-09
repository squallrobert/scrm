<script type="text/javascript">

    /*
     * Executa o post do formulário
     * */
    $(document).ready(function () {
        $("#bt_salvar").click(function () {
            var id = "<?=$app_codigo?>";
            var tipo = 1;

            if(id != "")
            {
                url = "index_xml.php?app_modulo=acao_usuario_tipo&app_comando=atualizar_acao_usuario_tipo&app_codigo";
                tipo = 2;
            }
            else
            {
                url = "index_xml.php?app_modulo=acao_usuario_tipo&app_comando=adicionar_acao_usuario_tipo&app_codigo";
            }

            ExecutarAcao(url);
        });

        FuncoesFormulario();
    });
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
            $("#frm_grupo").serialize()
            ,
            // pegando resposta do post
            function (response)
            {
                if (response == null) {
                    ToastMsg('warning','Grupo sem permissão nenhuma!');
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
    function FuncoesFormulario()
    {

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
    function ExecutarAcao(url)
    {
        var acoes =  $("#treeAcoes").jstree("get_selected");
        var verify = $("#treeAcoes").jstree("get_checked");
        console.log($("#treeAcoes").jstree("get_checked"));
        if (verify.length > 0) {
            $("#bt_salvar").prop("disabled",true).html("<i class=\"fa fa-spin fa-spinner\"></i> AGUARDE...").addClass("btn-warning");
            // ao clicar em salvar enviando dados por post via AJAX
            $.post(url,
                {
                    id_usuario_tipo: $("#id_usuario_tipo").val(),
                    acoes: acoes
                },
                // pegando resposta do retorno do post
                function (response)
                {
                    if (response["codigo"] == 0) {
                        Squall.ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=usuario_tipo&app_comando=listar_usuario_tipo&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>",'600');

                        // $("#frm_acao_usuario_tipo").each (function(){
                        //     this.reset();
                        // });


                    } else {
                        Squall.ToastMsg('warning',response["mensagem"]);
                    }
                    $("#bt_salvar").prop("disabled",false).html("<i class=\"fa fa-check\"></i> SALVAR").removeClass("btn-warning");
                }
                , "json" // definindo retorno para o formato json
            );
        }
    }
</script>
