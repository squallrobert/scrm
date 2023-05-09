<script type="text/javascript">
$(document).ready(function () {
    $('.dropify').dropify({
        messages: {
            'default': 'Arraste e solte ou clique para fazer o upload',
            'replace': 'Arraste e solte ou clique para substituir',
            'remove':  'Remover',
            'error':   'Ooops, erro!'
        }
    });

    <?php
        if(!empty($_SESSION['usuario']['provisoria']) && $_SESSION['usuario']['provisoria'] == '1'){

            echo 'swal.fire({
                        title: "Usuário com senha Provisória!",
                        text: "Mude a senha para sua segurança",
                        type: "warning",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, continue!",
                        })';
        }                
    ?>

    $('.select2').select2({language: "pt-BR"});
    $("#bt_salvar").click(function () {

        url = "index_xml.php?app_modulo=usuario&app_comando=atualizar_usuario_meus_dados&app_codigo";

        if($("#nova_senha").val() != $("#repetir_nova_senha").val())
            ToastMsg('warning',"Senhas não são iguais");

        else
            ExecutarAcaoa(url);
         
    });
});

function ExecutarAcaoa(url)
{
	if (ValidateForm($("#frm_meus_dados"))) {
		// ao clicar em salvar enviando dados por post via AJAX
        $('#frm_meus_dados').ajaxSubmit
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
                        ToastMsg('success',msg["mensagem"],'#index_xml.php?app_modulo=home&app_comando=home','1000');
                        // window.location ='#index_xml.php?app_modulo=usuario&app_comando=listar_usuario';

                    } else {
                        ToastMsg('warning',msg["mensagem"]+ "<BR>" +msg["debug"]+"");
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
function BuscarCep(cep){
    var cep_final = cep.replace("-","");
    var url = 'https://viacep.com.br/ws/'+ cep_final +'/json/';

    $.getJSON(url, function(result) {
        console.log(result);
        if(result.uf != ""){

            var id_estado = $(`#id_estado option[data-value='${result.uf}']`).val();
            $("#id_estado").val(id_estado).trigger("change");
            
            setTimeout(function(){
                var id_cidade = $(`#id_cidade option:contains('${result.localidade}')`).val();
                $('#id_cidade').val(id_cidade).trigger('change');
            }, 1000);

            $('#logradouro').val(result.logradouro);
            $('#bairro').val(result.bairro);
            $('#numero').val(result.numero);
            //$('#complemento').val(result.complemento);
            $('#cidade').val(result.localidade);
            $('#uf').val(result.uf);
        }
        if (("erro" in result)) {
            $.toast({
                heading: "Mensagem!!",
                text:'CEP não encontrado!',
                position: "top-right",
                icon: "warning",
                hideAfter: 3500,
                stack: 6
            });
        }
    });
}    
