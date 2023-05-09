var AcoesListagem = function (){
    return{
        init : function (){
            $('#AdicionarRegistro').click(function()
            {
                window.location ='#index_xml.php?app_comando=frm_adicionar_acao&app_modulo=acao';
            });
            $('#ExcluirRegistro').click(function()
            {
                var checked = $("input[name='lista[]']:checked").length;
                if(checked > 0)
                {
                    var values = new Array();
                    $.each($("input[name='lista[]']:checked"), function() {
                        values.push($(this).val());
                    });
                    swal.fire({
                        title: "Confirme Por favor",
                        text: "Você realmente gostaria de Remover estes registros ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, continue!",
                        cancelButtonText: "Não, cancelar!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }).then((isConfirm) =>{
                        if (isConfirm.value) {
                            AcoesListagem.ExcluirRegistros(values);
                            swal.fire("Removido!", "O(s) Registro(s) Foram removidos com sucesso.", "success");
                        } else {
                            swal.fire("Cancelado", "Remoção cancelada pelo usuário", "error");
                        }
                    });

                }
                else
                {
                    Squall.ToastMsg('warning','Selecione pelo menos 1 registro');
                }
            });
            $('[data-toggle="tooltip"]').tooltip();
            $("#busca").keypress(function (e) {
                if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                    Acoes.AtualizarGridAcao("",$("#busca").val());
                    return false;
                } else {
                    return true;
                }
            });
        },
        ModificarAcao : function (id){
            busca = $('#busca').val();
            window.location ='#index_xml.php?app_comando=frm_atualizar_acao&app_modulo=acao&app_codigo='+id+"&pagina="+pagina+"&filtro="+filtro+"&busca="+busca+"&ordem="+ordem;
        },
        ExcluirRegistros : function (dados){
            $.post("index_xml.php?app_modulo=acao&app_comando=deletar_acao",
                {
                    registros:dados
                },
                function(response)
                {
                    if(response['codigo'] == 0)
                    {
                        Squall.ToastMsg('success','Sucesso ao Remover registro(s)');
                        Acoes.AtualizarGridAcao(0,"");
                    }
                    else
                    {
                        Squall.ToastMsg('warning','Erro ao Remover registro(s)');
                    }
                }, 'json'
            );
        }
    }
}();
Squall.onDOMContentLoaded(function (){
    AcoesListagem.init();
});
