var ordem = $('#ordem').val() || '';
var pagina =  $('#pagina').val()|| '';
var busca =  $('#busca').val()|| '';
var filtro =  $('#filtro').val()|| '';

var Acoes = function (){
	return{
		init:function (){
			Acoes.AtualizarGridAcao(pagina,busca,filtro,ordem);
		},
		AtualizarGridAcao : function (pagina,busca,filtro,ordem){
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
			$("#conteudo_acao").load("index_xml.php?app_modulo=acao&app_comando=ajax_listar_acao", toPost);
		}
	}
}();
Squall.onDOMContentLoaded(function (){
	Acoes.init();
});