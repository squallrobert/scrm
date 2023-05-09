<script type="text/javascript">
$(document).ready(function(){
	$("#AdicionarRegistro").click(function()
	{
        window.location = "#index_xml.php?app_comando=frm_adicionar_cidades&app_modulo=cidades&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>";
	});
	$("#ExcluirRegistro").click(function()
	{
		var checked = $("input[name='lista[]']:checked").length;
		if(checked > 0)
		{
			var values = new Array();
			$.each($("input[name='lista[]']:checked"), function() {
				values.push($(this).val());
			});
            //Parameter
                swal.fire({
                    title: "Confirme Por favor",
                    text: "Você realmente gostaria de Remover estes registros ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, continue!",
                    cancelButtonText: "Não, cancelar!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }).then((isConfirm) =>{
                    if (isConfirm.value) {
                        ExcluirRegistros(values);
                         swal.fire("Removido!", "O(s) Registro(s) Foram removidos com sucesso.", "success");
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
	$("#busca").keypress(function (e) {
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            AtualizarGridCidades(0,$("#busca").val());
			return false;
		} else {
			return true;
		}
	});
});


function ModificarCidades(id){
    window.location = "#index_xml.php?app_comando=frm_atualizar_cidades&app_modulo=cidades&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>&app_codigo="+id;
}

function ExcluirRegistros(dados)
{
	$.post("index_xml.php?app_modulo=cidades&app_comando=deletar_cidades",
		{
			registros:dados
		},
		function(response)
		{
			if(response["codigo"] == 0)
			{
                ToastMsg('success','Sucesso ao Remover registro(s)');
                AtualizarGridCidades('<?=$_REQUEST['pagina']?>','<?=$_REQUEST['busca']?>','<?=$_REQUEST['filtro']?>','<?=$_REQUEST['ordem']?>');			}
			else
			{
                ToastMsg('warning','Erro ao Remover registro(s)');
			}
		}, "json"
	);
}
</script>
