var ordem = $('#ordem').val() || '';
var pagina =  $('#pagina').val()|| '';
var busca =  $('#busca').val()|| '';
var filtro =  $('#filtro').val()|| '';


var Modulos = function (){
        return {
            init: function ()
            {
                $('#bt_salvar').click(function ()
                {
                    if ($('#id').val() > 0 )
                        Modulos.ExecutarAcao("index_xml.php?app_modulo=modulo&app_comando=atualizar_modulo&app_codigo&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem);
                    else
                        Modulos.ExecutarAcao("index_xml.php?app_modulo=modulo&app_comando=adicionar_modulo&app_codigo&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem);
                });
                $('#dir').select2();
                $("#bt_voltar").click(function () {
                    window.location = "#index_xml.php?app_modulo=modulo&app_comando=listar_modulo&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem;
                });

            },
            ExecutarAcao : function (url)
            {
                if (Squall.ValidateForm($("#frm_modulo"))) {
                    // ao clicar em salvar enviando dados por post via AJAX
                    $.post(url,
                        $("#frm_modulo").serialize(),
                        // pegando resposta do retorno do post
                        function (response)
                        {
                            if (response["codigo"] == 0) {
                                Squall.ToastMsg('success',response["mensagem"],"#index_xml.php?app_modulo=modulo&app_comando=listar_modulo&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem,'600');
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
        Modulos.init();
    });


