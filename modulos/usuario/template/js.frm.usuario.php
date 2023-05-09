<script type="text/javascript">

/*
 * Executa o post do formulário
 * */

var app_modulo = "<?=$modulo_retorno?>";
var app_comando = "<?=$comando_retorno?>";

$(document).ready(function () {
    //
    $("#bt_salvar").click(function () {
        var id = "<?=$app_codigo?>";
        var comando = "<?=$app_comando?>";
        var tipo = 1;
        validar_senha = false;

        if(id != "")
        {
            url = "index_xml.php?app_modulo=usuario&app_comando=atualizar_usuario&app_codigo";
            tipo = 2;
        }
        else
        {
            url = "index_xml.php?app_modulo=usuario&app_comando=adicionar_usuario&app_codigo";
        }
        if(comando == "frm_atualizar_meus_dados")
        {
            url = "index_xml.php?app_modulo=usuario&app_comando=atualizar_usuario_meus_dados&app_codigo";
        }


        ExecutarAcao(url,validar_senha);
    });
    $('.select2').select2({language: "pt-BR"});
     FuncoesFormulario();
     Mascaras();
});
function BuscarCep(cep){
    var cep_final = cep.replace("-","");
    var url = 'https://viacep.com.br/ws/'+ cep_final +'/json/';

    $.getJSON(url, function(result) {
        if(result.uf != ""){

            $('#logradouro').val(result.logradouro);
            $('#bairro').val(result.bairro);
            $('#numero').val(result.numero);
            $('#cidade').val(result.localidade);
            $('#estado').val(result.uf);
        }
        if (("erro" in result)) {
            Squall.ToastMsg('warning','CEP não encontrado!');
        }
    });
}

function FuncoesFormulario()
{
    KTImageInput.createInstances();
    Squall.autoComplete('#id_grupo', 'index_xml.php?app_modulo=grupo&app_comando=popup_localizar_grupo');

    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });

    //Adaptação para telefones com 9 dígitos
    //Binda qualquer evento de teclado aos campos de telefone
    $("#telefone, #celular, #comercial").keydown(function ()
    {
        //Recebe o elemento ativo
        var focus = $(document.activeElement);

        //Timeout para pegar o valor do campo depois do evento, sem ele, o valor é testado antes do evento ser finalizado
        setTimeout(function ()
        {
            //Se o campo focado é algum dos 3 campos de telefone, aplica a máscara de acordo
            if (focus.attr('id') == "telefone" || focus.attr('id') == "celular" || focus.attr('id') == "comercial") {
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

    //Mesmo campo pode ter cpf e cnpj
    var maskBehavior = function (val)
        {
            return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
        },
        options      = {
            onKeyPress: function (val, e, field, options)
            {
                field.mask(maskBehavior.apply({}, arguments), options);
            }
        };
    $('#cpf_cnpj').mask(maskBehavior, options);

    //Na máscara, 9 representa número opcional, e 0 número obrigatório
    $('#data_hora_expirado').mask("00/00/2000 00:00:00");
    $("#cep").mask("00000-000");
    $('#id_nextel').mask("00*0999999*00999");

    $('#calendario_expirado').click(function(event){
        event.preventDefault();
        $('#data_hora_expirado').focus();
    });
    $('#id_usuario_tipo').select2({language: "pt-BR"});
}

/**
 * @return {boolean}
 */

function ExecutarAcao(url,validar_senha)
{
    var imageInputElement = document.querySelector("#kt_image_input_control");
    var imageInput = KTImageInput.getInstance(imageInputElement);


    if (Squall.ValidateForm($("#frm_usuario"))) {
            $('#foto').val(imageInput);
            $('#frm_usuario').ajaxSubmit
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
                            Squall.ToastMsg('success',msg["mensagem"],"#index_xml.php?app_modulo="+app_modulo+"&app_comando="+app_comando,'1500');
                            // window.location ='#index_xml.php?app_modulo=usuario&app_comando=listar_usuario';

                        } else {
                            Squall.ToastMsg('warning',msg["mensagem"]);
                        }
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
