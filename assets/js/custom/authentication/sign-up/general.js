"use strict";

// Class definition
var KTSignupGeneral = function() {
    // Elements
    var form;
    var submitButton;
    var validator;
    var passwordMeter;

    // Handle form
    var handleForm  = function(e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
			form,
			{
				fields: {
					'nome': {
						validators: {
							notEmpty: {
								message: 'Nome é necessário'
							}
						}
                    },
                    'cpf_cnpj': {
                        validators: {
                            notEmpty: {
                                message: 'CPF/CNPJ é necessário'
                            },
                            callback: {
                                message: 'Por favor entre com CPF/CNPJ válido',
                                callback: function(input) {
                                    if (input.value.length > 0) {
                                        return validaCpfCnpj(input.value);
                                    }
                                }
                            }
                        }
                    },
                    'endereco': {
                        validators: {
                            notEmpty: {
                                message: 'Endereço é necessário'
                            }
                        }
                    },
                    'telefone': {
                        validators: {
                            notEmpty: {
                                message: 'Telefone é necessário'
                            }
                        }
                    },
					'email': {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: 'Por favor insira um e-mail válido',
                            },
							notEmpty: {
								message: 'E-mail é necessário'
							}
						}
					},
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'Senha é necessário'
                            },
                            callback: {
                                message: 'Por favor entre com uma senha válida',
                                callback: function(input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm-password': {
                        validators: {
                            notEmpty: {
                                message: 'Confirmação de senha é necessário'
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'Senha e confirmação de senha não são iguais'
                            }
                        }
                    },
                    'toc': {
                        validators: {
                            notEmpty: {
                                message: 'É necessário aceitar os termos e condicões de nossa plataforma'
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }  
                    }),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',  // comment to enable invalid state icons
                        eleValidClass: '' // comment to enable valid state icons
                    })
				}
			}
		);

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            validator.revalidateField('password');

            validator.validate().then(function(status) {
		        if (status == 'Valid') {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click 
                    submitButton.disabled = true;

                    var dados ={
                        "senha" :form.querySelector('[name="password"]').value,
                        "repetir_senha" :form.querySelector('[name="confirm-password"]').value,
                        "nome" :form.querySelector('[name="nome"]').value,
                        "cpf_cnpj" :form.querySelector('[name="cpf_cnpj"]').value,
                        "endereco" :form.querySelector('[name="endereco"]').value,
                        "telefone" :form.querySelector('[name="telefone"]').value,
                        "email" :form.querySelector('[name="email"]').value,
                        "numero" :form.querySelector('[name="numero"]').value,
                        "latitude" :form.querySelector('[name="latitude"]').value,
                        "longitude" :form.querySelector('[name="longitude"]').value,
                        "bairro" :form.querySelector('[name="bairro"]').value,
                        "cidade" :form.querySelector('[name="cidade"]').value,
                        "estado" :form.querySelector('[name="estado"]').value,
                        "cep" :form.querySelector('[name="cep"]').value
                    }

                    var url   = 'index_xml.php?app_modulo=login&app_comando=confirmar_cadastro';
                    $.post(url, dados,
                        function (data)
                        {
                            // Hide loading indication
                            submitButton.removeAttribute('data-kt-indicator');
                            // Enable button
                            submitButton.disabled = false;
                            if (data['codigo'] > 0) {
                                Swal.fire({
                                    text: data.mensagem,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                            else {
                                // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                                Swal.fire({
                                    text: "Um e-mail de confirmação foi enviado para você com os proximos passos de acesso.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        form.querySelector('[name="nome"]').value= "";
                                        form.querySelector('[name="cpf_cnpj"]').value= "";
                                        form.querySelector('[name="endereco"]').value= "";
                                        form.querySelector('[name="numero"]').value= "";
                                        form.querySelector('[name="bairro"]').value= "";
                                        form.querySelector('[name="cidade"]').value= "";
                                        form.querySelector('[name="estado"]').value= "";
                                        form.querySelector('[name="cep"]').value= "";
                                        form.querySelector('[name="latitude"]').value= "";
                                        form.querySelector('[name="longitude"]').value= "";
                                        form.querySelector('[name="telefone"]').value= "";
                                        form.querySelector('[name="email"]').value= "";
                                        form.querySelector('[name="password"]').value= "";
                                        form.querySelector('[name="password-confirm"]').value= "";
                                        //form.submit(); // submit form
                                        var redirectUrl = form.getAttribute('data-kt-redirect-url');
                                        if (redirectUrl) {
                                            location.href = redirectUrl;
                                        }
                                    }
                                });
                            }
                        },
                        'json'
                    );

                } else {
                    // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Desculpe, parece que alguns erros foram detectados, tente novamente.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, Entendi!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
		    });
        });

        // Handle password input
        form.querySelector('input[name="password"]').addEventListener('input', function() {
            if (this.value.length > 0) {
                validator.updateFieldStatus('password', 'NotValidated');
            }
        });
    }

    // Password input validation
    var validatePassword = function() {
        return (passwordMeter.getScore() === 100);
    }

    // Public functions
    return {
        // Initialization
        init: function() {
            // Elements
            form = document.querySelector('#kt_sign_up_form');
            submitButton = document.querySelector('#kt_sign_up_submit');
            passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

            $(" #telefone").keydown(function() {
                //Recebe o elemento ativo
                var focus = $(document.activeElement);
                //Timeout para pegar o valor do campo depois do evento, sem ele, o valor é testado antes do evento ser finalizado
                setTimeout(function() {
                    //Se o campo focado é algum dos 3 campos de telefone, aplica a máscara de acordo
                    if (focus.attr('id') == "telefone") {

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
            var options = {
                onKeyPress: function (cpf, ev, el, op) {
                    var masks = ['000.000.000-000', '00.000.000/0000-00'];
                    $('#cpf_cnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
                }
            }

            $('#cpf_cnpj').length > 11 ? $('#cpf_cnpj').mask('00.000.000/0000-00', options) : $('#cpf_cnpj').mask('000.000.000-00#', options);

            handleForm ();
        }
    };
}();


function validaCpfCnpj(val) {
    if (val.length == 14) {
        var cpf = val.trim();

        cpf = cpf.replace(/\./g, '');
        cpf = cpf.replace('-', '');
        cpf = cpf.split('');

        var v1 = 0;
        var v2 = 0;
        var aux = false;

        for (var i = 1; cpf.length > i; i++) {
            if (cpf[i - 1] != cpf[i]) {
                aux = true;
            }
        }

        if (aux == false) {
            return false;
        }

        for (var i = 0, p = 10; (cpf.length - 2) > i; i++, p--) {
            v1 += cpf[i] * p;
        }

        v1 = ((v1 * 10) % 11);

        if (v1 == 10) {
            v1 = 0;
        }

        if (v1 != cpf[9]) {
            return false;
        }

        for (var i = 0, p = 11; (cpf.length - 1) > i; i++, p--) {
            v2 += cpf[i] * p;
        }

        v2 = ((v2 * 10) % 11);

        if (v2 == 10) {
            v2 = 0;
        }

        if (v2 != cpf[10]) {
            return false;
        } else {
            return true;
        }
    } else if (val.length == 18) {
        var cnpj = val.trim();

        cnpj = cnpj.replace(/\./g, '');
        cnpj = cnpj.replace('-', '');
        cnpj = cnpj.replace('/', '');
        cnpj = cnpj.split('');

        var v1 = 0;
        var v2 = 0;
        var aux = false;

        for (var i = 1; cnpj.length > i; i++) {
            if (cnpj[i - 1] != cnpj[i]) {
                aux = true;
            }
        }

        if (aux == false) {
            return false;
        }

        for (var i = 0, p1 = 5, p2 = 13; (cnpj.length - 2) > i; i++, p1--, p2--) {
            if (p1 >= 2) {
                v1 += cnpj[i] * p1;
            } else {
                v1 += cnpj[i] * p2;
            }
        }

        v1 = (v1 % 11);

        if (v1 < 2) {
            v1 = 0;
        } else {
            v1 = (11 - v1);
        }

        if (v1 != cnpj[12]) {
            return false;
        }

        for (var i = 0, p1 = 6, p2 = 14; (cnpj.length - 1) > i; i++, p1--, p2--) {
            if (p1 >= 2) {
                v2 += cnpj[i] * p1;
            } else {
                v2 += cnpj[i] * p2;
            }
        }

        v2 = (v2 % 11);

        if (v2 < 2) {
            v2 = 0;
        } else {
            v2 = (11 - v2);
        }

        if (v2 != cnpj[13]) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTSignupGeneral.init();
});
