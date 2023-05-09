var ordem = $('#ordem').val() || '';
var pagina =  $('#pagina').val()|| '';
var busca =  $('#busca').val()|| '';
var filtro =  $('#filtro').val()|| '';

var Grupos = function (){
	return{
		init:function (){
			Grupos.AtualizarGridGrupo(pagina,busca,filtro,ordem);
		},
		AtualizarGridGrupo : function (pagina,busca,filtro,ordem){
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
			$("#conteudo_grupo").load("index_xml.php?app_modulo=grupo&app_comando=ajax_listar_grupo", toPost);
		}
	}
}();
Squall.onDOMContentLoaded(function (){
	Grupos.init();
});
