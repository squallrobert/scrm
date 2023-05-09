<script type="text/javascript">

/*
 * Executa o post do formulário
 * */
$(document).ready(function () {

    $("#bt_salvar").click(function () {
        var id_grupo = "<?=$_REQUEST['app_codigo']?>";
        var tipo = 1;

        if(id_grupo != "")
        {
            url = "index_xml.php?app_modulo=grupo&app_comando=atualizar_grupo&app_codigo";
            tipo = 2;
        }
        else
        {
            url = "index_xml.php?app_modulo=grupo&app_comando=adicionar_grupo&app_codigo";
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
    Squall.autoComplete('#id_grupo_pai', 'index_xml.php?app_modulo=grupo&app_comando=popup_localizar_grupo');
    Squall.autoComplete('#id_grupo_copiar', 'index_xml.php?app_modulo=grupo&app_comando=popup_localizar_grupo');

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


function ExecutarAcao(url)
{
    // console.log($("#treeAcoes").jstree("get_selected"));
    var acoes =  $("#treeAcoes").jstree("get_selected");
    var verify = $("#treeAcoes").jstree("get_checked");
    console.log($("#treeAcoes").jstree("get_checked"));
	if (Squall.ValidateForm($("#frm_grupo")) && verify.length > 0 ) {
		// ao clicar em salvar enviando dados por post via AJAX
		$.post(url,
            {
                id_grupo_pai: $("#id_grupo_pai").val(),
                id:     $("#id").val(),
                nome:   $("#nome").val(),
                acoes:acoes
            },
			// pegando resposta do retorno do post
			function (response)
			{
				if (response["codigo"] == 0) {
                 // ToastMsg('success',response["mensagem"],"",'600');
                    Squall.ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=grupo&app_comando=listar_grupo&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>",'600');
                    // window.location ='#index_xml.php?app_modulo=grupo&app_comando=listar_grupo';

                        // $("#frm_grupo").each (function(){
                        //     this.reset();
                        // });


				} else {
                    Squall.ToastMsg('warning',response["mensagem"]+ "<BR>" +response["debug"]+"");
				}
			}
			, "json" // definindo retorno para o formato json
		);
	}

}


function ValidateForm($form)
{
    var result = true,
        currentDiv,
        $this,
        contadorSelect = 0,
        classes = [],
        validados = [],
        tabHref = "";

    $(".help-block").remove();

    //Validação de campos obrigatórios com a classe .validar-obrigatorio
    $form.find(".validar-obrigatorio").each(function(){
        $this = $(this);
        currentDiv = $this.parent();

        if (currentDiv.is(":visible") || $this.attr("type") == "hidden") {
            if (currentDiv.hasClass("input-group")) currentDiv = currentDiv.parent().first();
            if ($this.prop("disabled") !== true) {
                if ($this.val() == "") {
                    currentDiv.addClass('has-error');
                    currentDiv.append('<p class="help-block has-error"><?=TXT_CAMPO_OBRIGATORIO?><p>');
                    tabHref = $this.closest(".tab-pane").first().attr("id");
                    result = false;
                } else {
                    currentDiv.removeClass("has-error");
                }
            } else {
                currentDiv.removeClass("has-error");
            }
        }
    });

    $form.find(".validar-select-plugin").each(function(){
        contadorSelect++;
        if (contadorSelect % 2 != 0) {
            $this = $(this);
            currentDiv = $this.parent();

            if (currentDiv.is(":visible")) {
                if (currentDiv.hasClass("input-group")) currentDiv = currentDiv.parent().first();
                if ($this.val() == null || typeof $this.val() === "undefined" || $this.val() == " ") {
                    currentDiv.addClass('has-error');
                    currentDiv.append('<p class="help-block has-error"><?=TXT_CAMPO_OBRIGATORIO?><p>');
                    tabHref = $this.closest(".tab-pane").first().attr("id");
                    result = false;
                } else {
                    currentDiv.removeClass("has-error");
                }
            }
        }
    });


    if (!result) {
        $("a[href='#"+tabHref+"']").click();
    }

    return result;
}

</script>
