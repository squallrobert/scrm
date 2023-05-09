    var MenusListagem = function (){
        return{
            init : function (){
                $('#AdicionarRegistro').click(function()
                {
                    window.location ='#index_xml.php?app_comando=frm_adicionar_menu&app_modulo=menu';
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
                                MenusListagem.ExcluirRegistros(values);
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
                        Menus.AtualizarGridMenu("",$("#busca").val());
                        return false;
                    } else {
                        return true;
                    }
                });
            },
            ModificarMenu : function (id){
                window.location ='#index_xml.php?app_comando=frm_atualizar_menu&app_modulo=menu&app_codigo='+id;
            },
            ExcluirRegistros : function (dados){
                $.post("index_xml.php?app_modulo=menu&app_comando=deletar_menu",
                    {
                        registros:dados
                    },
                    function(response)
                    {
                        if(response['codigo'] == 0)
                        {
                            Squall.ToastMsg('success','Sucesso ao Remover registro(s)');
                            Menus.AtualizarGridMenu(0,"");
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
        MenusListagem.init();
    });
