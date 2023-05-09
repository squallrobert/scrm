<script type="text/javascript">
$(document).ready(function(){
	$("#AdicionarRegistro").click(function()
	{
        window.location = "#index_xml.php?app_comando=frm_adicionar_usuario&app_modulo=usuario";
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
	$('[data-toggle="tooltip"]').tooltip();	$("#busca").keypress(function (e) {
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
			AtualizarGridUsuario("",$("#busca").val());
			return false;
		} else {
			return true;
		}
	});

});


function ModificarUsuario(id){
    window.location ="#index_xml.php?app_comando=frm_atualizar_usuario&app_modulo=usuario&app_codigo="+id;
}
function ResetarPermissao(id)
{
    $.post("index_xml.php?app_modulo=usuario&app_comando=resetar_permissao",
        {
            id:id
        },
        function(response)
        {
            if(response["codigo"] == 0)
            {
                Squall.ToastMsg('success','Sucesso ao Resetar Permissões');
                AtualizarGridUsuario(0,"");
            }
            else
            {
                Squall.ToastMsg('warning','Erro ao Resetar Permissões');
            }
        }, "json"
    );
}

function ExcluirRegistros(dados)
{
	$.post("index_xml.php?app_modulo=usuario&app_comando=deletar_usuario",
		{
			registros:dados
		},
		function(response)
		{
			if(response["codigo"] == 0)
			{
               Squall.ToastMsg('success','Sucesso ao Remover registro(s)');
                AtualizarGridUsuario(0,"");
			}
			else
			{
                Squall.ToastMsg('warning','Erro ao Remover registro(s)');
			}
		}, "json"
	);
}

function Emular(email, senha){
    var dados = {'email': email, 'senha': senha};
    var url   = 'includes/checar.login.php?md5=1&emular=sim';
    $.post(url, dados,
        function (data) {
            if (data['tipo'] == 1){
                Squall.ToastMsg( 'warning',data.mensagem,);
            }else{
                Squall.ToastMsg('success',data.mensagem);
                window.location.reload();
            }
        },
        'json'
    );
}
</script>
