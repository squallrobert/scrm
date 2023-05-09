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
            url = "index_xml.php?app_modulo=uf&app_comando=atualizar_uf&app_codigo";
            tipo = 2;
        }
        else
        {
            url = "index_xml.php?app_modulo=uf&app_comando=adicionar_uf&app_codigo";
        }

        ExecutarAcao(url);
    });
    $("#bt_voltar").click(function () {
        window.location = "#index_xml.php?app_modulo=uf&app_comando=listar_uf&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>&app_codigo=";
    });
    $('.select2').select2({language: "pt-BR"});
});
function ExecutarAcao(url)
{
	    if (ValidateForm($("#frm_uf"))) {
    $("#bt_salvar").prop("disabled",true).html("<i class=\"fa fa-spin fa-spinner\"></i> AGUARDE...").addClass("btn-warning");
		// ao clicar em salvar enviando dados por post via AJAX
		$.post(url,
			$("#frm_uf").serialize(),
			// pegando resposta do retorno do post
			function (response)
			{
				if (response["codigo"] == 0) {
                    ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=uf&app_comando=listar_uf&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>",'600');

                        // $("#frm_uf").each (function(){
                        //     this.reset();
                        // });


				} else {
                    ToastMsg('warning',response["mensagem"]);
				}
            $("#bt_salvar").prop("disabled",false).html("<i class=\"fa fa-check\"></i> SALVAR").removeClass("btn-warning");
			}
			, "json" // definindo retorno para o formato json
		);
	}
}
</script>
