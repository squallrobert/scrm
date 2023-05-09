<?php
/**
* @author Squall Robert
* @copyright 2016
*/
?>
<script type="text/javascript">
	$(function()
	{
		//LoadDiv("#conteudo_usuario");
		AtualizarGridUsuario(0,"");
	});

	function AtualizarGridUsuario(pagina,busca,filtro,ordem)
	{
        var registros = $('#numero_registros').val();
		if(filtro == "" || filtro === undefined)  filtro = ""; 
		if(ordem == "" || ordem  === undefined)  ordem = "";

			var toPost = {
			pagina: pagina,
			busca: busca,
			filtro: filtro,
			ordem: ordem,
            numero_registros:registros
		};

		$("#conteudo_usuario").load("index_xml.php?app_modulo=usuario&app_comando=ajax_listar_usuario", toPost);
	}

	function ImprimirRelatorio(form)
	{
		if (ValidarFormulario()) {
			form.action = "index_print.php?app_modulo=usuario&app_comando=usuario_print";
			form.target = "_blank";
			form.submit();
		}
	}

	function GerarPdf(form)
	{
		if (ValidarFormulario()) {
			form.action = "index_file.php?app_modulo=usuario&app_comando=usuario_pdf";
			form.target = "_blank";
			form.submit();
		}
	}

	function GerarXml(form)
	{
		if (ValidarFormulario()) {
			form.action = "index_file.php?app_modulo=usuario&app_comando=usuario_xlsx";
			form.target = "_blank";
			form.submit();
		}
	}

	function AbrirConfig()
	{
		BootstrapDialog.show({
			size:      BootstrapDialog.SIZE_SMALL,
			type:      BootstrapDialog.TYPE_DEFAULT,
			title:     "<div class='titulo_modal'><?=ROTULO_CONFIGURACOES?></div>",
			message:   $("<div></div>").load("index_xml.php?app_modulo=usuario&app_comando=frm_configurar_listagem"),
			draggable: true,
			buttons:   [{
				label:    "<?=ROTULO_SALVAR?>",
				cssClass: "btn-lg btn-confirm",
				action:   function (dialogRef)
						{
							SalvarConfiguracoes(dialogRef, "index_xml.php?app_modulo=usuario&app_comando=configurar_listagem", AtualizarGridUsuario)
						}
			}]
		});
	}
</script>
