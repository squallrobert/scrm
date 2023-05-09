<?php
/**
 * @author    Squall
 * @copyright 2015
 */
?>
<script type="text/javascript">
	$(document).ready(function ()
	{
		//vw e vh significam largura e altura do viewport
		$('.modal-dialog').css("width", "75vw");
		$('.modal-dialog').css("height", "90vh");

		//CarregarPadrao("#conteudo_central");
		$('#fechar-lista-grupo').click(function ()
		{
			$('#conteudo_dash').fadeOut();
		});

	});
	function AtualizarGridUsuarioConfiguracao(pagina, busca, filtro, ordem)
	{
		if (filtro == "" || filtro === undefined) {
			filtro = "";
		}
		if (ordem == "" || ordem === undefined) {
			ordem = "";
		}
		busca = encodeURI(busca);

		$("#conteudo_usuario_configuracao").load("index_xml.php?app_comando=lis_usuario_configuracao&app_modulo=usuario_configuracao&app_codigo&pagina=" + pagina + "&busca=" + $.trim(busca) + "&filtro=" + filtro + "&ordem=" + ordem);
	}


</script>
