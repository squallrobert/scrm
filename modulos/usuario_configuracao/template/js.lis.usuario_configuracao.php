<script type="text/javascript">
$(document).ready(function ()
{
	$('[data-toggle="tooltip"]').tooltip();
});

function ModificarUsuarioConfiguracao(id)
{
	BootstrapDialog.show({
		size:      BootstrapDialog.SIZE_WIDE,
		type:      BootstrapDialog.TYPE_DEFAULT,
		title:     "<div class='titulo_modal'><?=RTL_MODIFICAR_CONFIGURACOES_USUARIO?></div>",
		message:   $('<div></div>').load('index_xml.php?app_modulo=usuario_configuracao&app_comando=frm_alterar_configuracoes&app_codigo=' + id),
		draggable: true,
		buttons:   []
	});
}
</script>