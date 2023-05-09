var ordem = $('#ordem').val() || '';
var pagina =  $('#pagina').val()|| '';
var busca =  $('#busca').val()|| '';
var filtro =  $('#filtro').val()|| '';

var Acoes = function ()
{
 return{
  init:function (){
   $("#bt_salvar").click(function ()
   {
    if ($('#id').val() > 0 )
     url = "index_xml.php?app_modulo=acao&app_comando=atualizar_acao&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem;
    else
     url = "index_xml.php?app_modulo=acao&app_comando=adicionar_acao&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem;

    Acoes.ExecutarAcao(url);
   });
   $("#bt_voltar").click(function () {
    window.location = "#index_xml.php?app_modulo=acao&app_comando=listar_acao&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem;
   });

// The DOM elements you wish to replace with Tagify
   var input1 = document.querySelector("#acao");
// Initialize Tagify components on the above inputs
   var itens = new Tagify(input1);

   $('#modulo').select2();
  },
  ExecutarAcao : function (url) {
   if (Squall.ValidateForm($("#frm_acao"))) {
    $('#acao').val();
// ao clicar em salvar enviando dados por post via AJAX
    $.post(url,
        $("#frm_acao").serialize(),
// pegando resposta do retorno do post
        function (response)
        {
         if (response["codigo"] == 0) {
          Squall.ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=acao&app_comando=listar_acao&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem+"&app_codigo=",'600');
          // Squall.ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=menu&app_comando=listar_menu",'600');
         } else {
          Squall.ToastMsg('warning',response["mensagem"]+ "<BR>" +response["debug"]+"");
         }
        }
        , "json" // definindo retorno para o formato json
    );
   }
  }
 }
}();
Squall.onDOMContentLoaded(function (){
 Acoes.init();
});