var Menus = function (){
	return{
		init:function (){
			Menus.AtualizarGridMenu(0,"");
		},
		AtualizarGridMenu : function (pagina,busca,filtro,ordem){
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

			$("#conteudo_menu").load("index_xml.php?app_modulo=menu&app_comando=ajax_listar_menu", toPost);
		},
		ImprimirRelatorio : function (form){
			if (ValidarFormulario()) {
				form.action = "index_print.php?app_modulo=menu&app_comando=menu_print";
				form.target = "_blank";
				form.submit();
			}
		},
		GerarPdf : function (form){
			if (ValidarFormulario()) {
				form.action = "index_file.php?app_modulo=menu&app_comando=menu_pdf";
				form.target = "_blank";
				form.submit();
			}
		},
		GerarXml : function (form){
			if (ValidarFormulario()) {
				form.action = "index_file.php?app_modulo=menu&app_comando=menu_xlsx";
				form.target = "_blank";
				form.submit();
			}
		}
	}
}();
Squall.onDOMContentLoaded(function (){
	Menus.init();
});
