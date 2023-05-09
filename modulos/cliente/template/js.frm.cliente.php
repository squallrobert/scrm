<script type="text/javascript">

    /*
     * Executa o post do formulário
     * */
    $(document).ready(function () {
        MudarForm(<?=$linha['id_cliente_tipo_pessoa'];?>);
        $("#bt_salvar").click(function () {
            var id = "<?=$app_codigo?>";
            var tipo = 1;

            if(id != "")
            {
                url = "index_xml.php?app_modulo=cliente&app_comando=atualizar_cliente&app_codigo";
                tipo = 2;
            }
            else
            {
                url = "index_xml.php?app_modulo=cliente&app_comando=adicionar_cliente&app_codigo";
            }

            ExecutarAcao(url);
        });
        $("#data_nascimento").flatpickr({
            onReady: function () {
                this.jumpToDate("<?=$data_prevista?>")
            },
            dateFormat: "d/m/Y",
            locale:'pt_br'
        });

        // new tempusDominus.TempusDominus(document.getElementById("data_nascimento"), {
        //     display: {
        //         viewMode: "calendar",
        //         components: {
        //             decades: true,
        //             year: true,
        //             month: true,
        //             date: true,
        //             hours: false,
        //             minutes: false,
        //             seconds: false
        //         }
        //     }
        // });
        $('#id_cliente_tipo_pessoa,#id_cliente_estado_civil,#id_forma_pagamento,#sexo').select2({language: "pt-BR"});
        KTImageInput.createInstances();
        Mascaras();

        $("#celular, #c_celular, #telefone, #c_telefone, #comercial, #c_comercial").keydown(function() {
            //Recebe o elemento ativo
            var focus = $(document.activeElement);
            //Timeout para pegar o valor do campo depois do evento, sem ele, o valor é testado antes do evento ser finalizado
            setTimeout(function() {
                //Se o campo focado é algum dos 3 campos de telefone, aplica a máscara de acordo
                if (focus.attr('id') == "telefone"
                    || focus.attr('id') == "celular"
                    || focus.attr('id') == "comercial"
                    || focus.attr('id') == "c_telefone"
                    || focus.attr('id') == "c_celular"
                    || focus.attr('id') == "c_comercial" ) {

                    if (focus.val().length <= 14) {
                        focus.unmask();
                        focus.mask("(00) 0000-00009");
                    }
                    else {
                        focus.unmask();
                        focus.mask("(00) 00000-0000");
                    }
                }
            }, 10);
        });

    });
    function CopiarEndereco() {
        $('#c_cep').val($('#cep').val());
        $('#c_logradouro').val($('#logradouro').val());
        $('#c_numero').val($('#numero').val());
        $('#c_complemento').val($('#complemento').val());
        $('#c_bairro').val($('#bairro').val());
        $('#c_cidade').val($('#cidade').val());
        $('#c_estado').val($('#estado').val());
        $('#c_uf').val($('#uf').val());
        $('#c_referencia').val($('#referencia').val());
        $('#c_observacao').val($('#observacao').val());
        $('#c_telefone').val($('#telefone').val());
        $('#c_comercial').val($('#comercial').val());
        $('#c_celular').val($('#celular').val());
        $('#c_email').val($('#email').val());
        $('#c_email_mkt').val($('#email_mkt').val());
        $('#c_email_mkt2').val($('#email_mkt2').val());
        Squall.ToastMsg('success',"Endereço copiado com Sucesso!!");
    }

    function BuscarCep(cep,tipo) {
        var cep_final = cep.replace("-", "");
        var url = 'https://viacep.com.br/ws/' + cep_final + '/json/';

        $.getJSON(url, function (result) {
            if (result.uf != "") {
                if(tipo == 2)
                {
                    $('#c_logradouro').val(result.logradouro);
                    $('#c_bairro').val(result.bairro);
                    $('#c_numero').val(result.numero);
                    $('#c_cidade').val(result.localidade);
                    $('#c_estado').val(result.uf);
                }
                else
                {
                    $('#logradouro').val(result.logradouro);
                    $('#bairro').val(result.bairro);
                    $('#numero').val(result.numero);
                    $('#cidade').val(result.localidade);
                    $('#estado').val(result.uf);
                }


            }
            if (("erro" in result)) {
                $.toast({
                    heading: "Mensagem!!",
                    text: 'CEP não encontrado!',
                    position: "top-right",
                    icon: "warning",
                    hideAfter: 3500,
                    stack: 6
                });
            }
        });
    }

    MudarForm = function (id)
    {
        var pessoa_fisica_campos = $('.pf-input');
        var pessoa_fisica_select = $('.pf-select');
        var pessoa_fisica = $('.pf');
        var pessoa_juridica = $('.pj');
        var pessoa_juridica_campos = $('.pj-input');
        var pessoa_juridica_select = $('.pj-select');
        var cpf = $('#cpf');
        var cnpj = $('#cnpj');
        var estado_civil = $('#cnpj');
        var cnpj = $('#cnpj');

        if(id == 1)
        {
            pessoa_juridica.hide();
            pessoa_juridica_select.removeAttr('data-validar')
            pessoa_juridica_campos.removeClass('validar-obrigatorio');
            cnpj.removeClass('validar-cnpj');

            pessoa_fisica.show();
            pessoa_fisica_campos.addClass('validar-obrigatorio');
            cpf.addClass('validar-cpf');
            pessoa_fisica_select.attr('data-validar="select2"');


        }
        else
        {
            pessoa_juridica.show();
            pessoa_juridica_select.attr('data-validar="select2"')
            pessoa_juridica_campos.addClass('validar-obrigatorio');
            cnpj.addClass('validar-cnpj');

            pessoa_fisica.hide();
            pessoa_fisica_campos.removeClass('validar-obrigatorio');
            cpf.removeClass('validar-cpf');
            pessoa_fisica_select.removeAttr('data-validar');
        }

    }

    function ExecutarAcao(url)
    {
        var imageInputElement = document.querySelector("#kt_image_input_control");
        var imageInput = KTImageInput.getInstance(imageInputElement);

        if (Squall.ValidateForm($("#frm_cliente"))) {
            $('#foto').val(imageInput);
            $("#bt_salvar").prop("disabled",true).html("<i class=\"fa fa-spin fa-spinner\"></i> AGUARDE...").addClass("btn-warning");
            // ao clicar em salvar enviando dados por post via AJAX

            $('#frm_cliente').ajaxSubmit
            (
                {
                    target:       '_blank',
                    beforeSubmit: function (formData)
                    {
                        var queryString = $.param(formData);
                        //$('.preloader').show();
                        return true;
                    },
                    success:   function (msg)
                    {
                        if (msg["codigo"] == 0) {
                            Squall.ToastMsg('success',msg["mensagem"],"#index_xml.php?app_modulo=cliente&app_comando=listar_cliente&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>",'600');

                        } else {
                            Squall.ToastMsg('warning',msg["mensagem"]);
                        }
                        $("#bt_salvar").prop("disabled",false).html("<i class=\"fa fa-check\"></i> SALVAR").removeClass("btn-warning");
                    },
                    url:          url,
                    resetForm:    false,
                    type:         'post',
                    dataType:     'json'
                }
            );

        }
    }
</script>
