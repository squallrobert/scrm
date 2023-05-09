var ordem = $('#ordem').val() || '';
var pagina =  $('#pagina').val()|| '';
var busca =  $('#busca').val()|| '';
var filtro =  $('#filtro').val()|| '';

var Modulos = function (){
    return{
        init:function (){
            Modulos.AtualizarGridModulo(pagina,busca,filtro,ordem);
        },
        AtualizarGridModulo : function (pagina,busca,filtro,ordem){
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

            $("#conteudo_modulo").load("index_xml.php?app_modulo=modulo&app_comando=ajax_listar_modulo", toPost);
        },
        ImprimirRelatorio : function (form){
            if (ValidarFormulario()) {
                form.action = "index_print.php?app_modulo=modulo&app_comando=modulo_print";
                form.target = "_blank";
                form.submit();
            }
        },
        GerarPdf : function (form){
            if (ValidarFormulario()) {
                form.action = "index_file.php?app_modulo=modulo&app_comando=modulo_pdf";
                form.target = "_blank";
                form.submit();
            }
        },
        GerarXml : function (form){
            if (ValidarFormulario()) {
                form.action = "index_file.php?app_modulo=modulo&app_comando=modulo_xlsx";
                form.target = "_blank";
                form.submit();
            }
        }
    }
}();
Squall.onDOMContentLoaded(function (){
    Modulos.init();
});




