<?php
/**
* @author Squall Robert
* @copyright 2016
*/
?>
<script type="text/javascript">
	$(function()
	{
        FuncoesFormulario();
        $('#bt_salvar').click(function () {
            ExecutarAcao();
        });
	});

    function FuncoesFormulario()
    {
        Squall.autoComplete('#id_grupo_pai', 'index_xml.php?app_modulo=grupo&app_comando=popup_localizar_grupo');

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
        //Inicializa o plugin de árvore, tirando o style padrão e usando checkboxes
        $('#treeAcoes2').jstree(
            {
                "core": {
                    "themes": {
                        "responsive": false
                    }
                },
                'checkbox': {
                    three_state: false,
                    cascade: 'up'
                },
                "types": {
                    "default": {
                        "icon": "fas fa-user text-success fa-lg"
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
            $("#treeAcoes,#treeAcoes2").jstree("check_node", $(this));
        });

        //Fecha todos os nós
        setTimeout(function()
        {
            $("#treeAcoes,#treeAcoes2").jstree('close_all');
        }, 1000)
    }


    function ExecutarAcao(url)
    {

       // console.log($("#treeAcoes2").jstree("get_selected"));
       //  console.log($("#treeAcoes").jstree(true).get_selected('full',true));
       //  if (ValidateForm($("#frm_grupo_acao"))) {

            // ao clicar em salvar enviando dados por post via AJAX
            $.post('index_xml.php?app_modulo=grupo_acao&app_comando=adicionar_grupo_acao',
                {
                    acoes:        $("#treeAcoes").jstree("get_selected"),
                    grupos:        $("#treeAcoes2").jstree("get_selected")
                },
                // pegando resposta do retorno do post
                function (response)
                {
                    if (response["codigo"] == 0) {
                        ToastMsg('success',response["mensagem"]);
                    } else {
                        ToastMsg('warning',response["mensagem"]+ "<BR>" +response["debug"]+"");
                    }
                }
                , "json" // definindo retorno para o formato json
            );
        // }

    }

</script>
