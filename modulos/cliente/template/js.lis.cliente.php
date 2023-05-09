<script type="text/javascript">
$(document).ready(function(){
	$("#AdicionarRegistro").click(function()
	{
        window.location = "#index_xml.php?app_comando=frm_adicionar_cliente&app_modulo=cliente&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>";
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
            Squall.ToastMsg('warning','Selecione pelo menos 1 registro');
		}
	});
	$('[data-toggle="tooltip"]').tooltip();
	$("#busca").keypress(function (e) {
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            AtualizarGridCliente(0,$("#busca").val());
			return false;
		} else {
			return true;
		}
	});
});
function AtualizarSaldo(id)
{
    $('#modal_saldo_clientes').modal('show');
    $('#div_frm_saldo').load('index_xml.php?app_comando=frm_saldo_cliente&app_modulo=cliente&app_codigo='+id);
}


function ModificarCliente(id){
    window.location = "#index_xml.php?app_comando=frm_atualizar_cliente&app_modulo=cliente&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>&app_codigo="+id;
}

function ExcluirRegistros(dados)
{
	$.post("index_xml.php?app_modulo=cliente&app_comando=deletar_cliente",
		{
			registros:dados
		},
		function(response)
		{
			if(response["codigo"] == 0)
			{
                Squall.ToastMsg('success','Sucesso ao Remover registro(s)');
                AtualizarGridCliente('<?=$_REQUEST['pagina']?>','<?=$_REQUEST['busca']?>','<?=$_REQUEST['filtro']?>','<?=$_REQUEST['ordem']?>');			}
			else
			{
                Squall.ToastMsg('warning','Erro ao Remover registro(s)');
			}
		}, "json"
	);
}
function AtualizarIdAsaas(id)
{
	$.post("index_xml.php?app_modulo=cliente&app_comando=atualizar_id_asaas",
		{
			id:id
		},
		function(response)
		{
			if(response["codigo"] == 0)
			{
                Squall.ToastMsg('success','Sucesso ao atualizar id ASAAS ');
                AtualizarGridCliente('<?=$_REQUEST['pagina']?>','<?=$_REQUEST['busca']?>','<?=$_REQUEST['filtro']?>','<?=$_REQUEST['ordem']?>');			}
			else
			{
                Squall.ToastMsg('warning','Sucesso ao atualizar id ASAAS');
			}
		}, "json"
	);
}
</script>
