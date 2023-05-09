<?php
/**
* @author Squall Robert
* @copyright 2016
*/
?>
<script type="text/javascript">
	$(function()
	{
		//LoadDiv("#conteudo_uf");
		AtualizarGridUf('<?=$_REQUEST['pagina']?>','<?=$_REQUEST['busca']?>','<?=$_REQUEST['filtro']?>','<?=$_REQUEST['ordem']?>');
	});

	function AtualizarGridUf(pagina,busca,filtro,ordem)
	{
        var registros = $('#numero_registros').val();
		var load = '<div class="d-flex justify-content-center">' +
    '     <div class="spinner-grow" style="width: 3rem; height: 3rem;"  role="status">' +
    '         <span class="sr-only">Carregando...</span>' +
    '     </div>' +
    ' </div>';
		if(filtro == "" || filtro === undefined)  filtro = ""; 
		if(ordem == "" || ordem  === undefined)  ordem = "";

$('#conteudo_uf').html(load);
			var toPost = {
			pagina: pagina,
			busca: busca,
			filtro: filtro,
			ordem: ordem,
            numero_registros:registros
		};

		$("#conteudo_uf").load("index_xml.php?app_modulo=uf&app_comando=ajax_listar_uf", toPost);
	}

	function ImprimirRelatorio(form)
	{
		if (ValidarFormulario()) {
			form.action = "index_print.php?app_modulo=uf&app_comando=uf_print";
			form.target = "_blank";
			form.submit();
		}
	}

	function GerarPdf(form)
	{
		if (ValidarFormulario()) {
			form.action = "index_file.php?app_modulo=uf&app_comando=uf_pdf";
			form.target = "_blank";
			form.submit();
		}
	}

	function GerarXml(form)
	{
		if (ValidarFormulario()) {
			form.action = "index_file.php?app_modulo=uf&app_comando=uf_xlsx";
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
			message:   $("<div></div>").load("index_xml.php?app_modulo=uf&app_comando=frm_configurar_listagem"),
			draggable: true,
			buttons:   [{
				label:    "<?=ROTULO_SALVAR?>",
				cssClass: "btn-lg btn-confirm",
				action:   function (dialogRef)
						{
							SalvarConfiguracoes(dialogRef, "index_xml.php?app_modulo=uf&app_comando=configurar_listagem", AtualizarGridUf)
						}
			}]
		});
	}
</script>
