/*
 * Author: Péricles
 * Copyright 2014
 */
function Mascaras()
{
    //Máscara para codigo franquia
    $(".mask-codigo").mask("SS-999");

    // Máscara para 4 dígitos - Ano; Pin
    $(".mask-ano").mask("9999");

    // Máscara para CEP
    $('.mask-cep').mask('99999-999');

    // Máscara para número de contrato
    $('.mask-contrato').mask('SS-999-9999');

    // Máscara para CNPJ
    $('.mask-cnpj').mask('99.999.999/9999-99');

    // Máscara para CPF
    $('.mask-cpf').mask('000.000.000-00');

    // Máscara de controle interno
    $('.mask-controle-interno').mask('SS-000-9999');

    // Máscara para o datepicker
    $('.mask-data').mask("99/99/9999");
    $('.mask-data-time').mask("99/99/9999 99:99:99");
    $('.mask-periodo').mask("99/99/9999 - 99/99/9999");

    // Máscara para dia
    $(".mask-dia").mask("?99");

    // Máscara para DDD
    $(".mask-ddd").mask("99");

    // Máscara Telefone sem ddd
    $('.mask-tel').mask("0000-0000");

    // Máscara para moeda, não mostrando R$
    $(".mask-dinheiro").maskMoney({showSymbol:false, decimal:",", thousands:"."});
    //
    $(".mask-number-float").maskMoney({showSymbol:false, decimal:".", thousands:""});

    $(".mask-number-float-negative").mask("Z0999999.00", {

        translation: {
            '0': {pattern: /\d/},
            '9': {pattern: /\d/, optional: true},
            'Z': {pattern: /[\-\+]/, optional: true}
        }

    });
    $(".mask-teperatura").mask("Z09.000", {

        translation: {
            '0': {pattern: /\d/},
            '9': {pattern: /\d/, optional: true},
            'Z': {pattern: /[\-\+]/, optional: true}
        }

    });

    // Máscara para horário
    $('.mask-horario').mask("99:99");

    // Máscara para ID Nextel
    $('.mask-id_nextel').mask("00*0999999*00999");

    //Máscara ip
    $('.ip_address').mask("099.099.099.099");
    //numeros negativos
    $(".negative").mask("S#############", {
        translation: {
            'S': {
                pattern: /-/,
                optional: true
            }
        }
    });
    // Máscara para letras sem limite de tamanho
    $(".mask-letras").bind("input", function (event) {
        var out = "";
        var str = this.value;
        for (var i = 0; i < str.length; i++) {
            if (/[A-Za-z]/.test(str.charAt(i))) {
                out = out.concat(str.charAt(i));
            }
        }
        this.value = out;
    });

    // Máscara para números sem limite de tamanho
    $(".mask-numeros").bind("input", function (event) {
        var out = "";
        var str = this.value;
        for (var i = 0; i < str.length; i++) {
            if (/[0-9]/.test(str.charAt(i))) {
                out = out.concat(str.charAt(i));
            }
        }
        this.value = out;
    });

    // Máscara para números de até 11 casas
    $(".mask-numeros-11").mask("99999999999");

    // Máscara para parcelas
    $('.mask-parcelas').mask('9?9');

    // Máscara para Placas
    maskMercosul('.mask-placa');

    // Máscara de celular para troca do hífen em caso de 9 dígitos
    $('.mask-celular').focus(function()
    {
        var phone, element;
        element = $(this);
        phone = element.val();

        if(phone.length <= 9)
        {
            element.unmask();
            element.mask("(00) 0000-00009");
        }
        else if(phone.length >= 10)
        {
            element.unmask();
            element.mask("(00) 00000-0000");
        }

    }).trigger('focus');

    $('.mask-telefone').mask("(00) 0000-0000");

    // Máscara para UF
    $(".mask-uf").mask("SS");
    $(".mask-uf").mask("xx", {
        translation: {
            'x': {
                pattern:  /[A-Za-z]/
            }
        }
    });

    // Máscara para 0800
    $(".mask-zero800").mask("0800-9999999");

    //Máscara apenas números
    $(".mask-numero").mask("0#");

    (function ($) {
        $.fn.cpfcnpj = function (options) {
            // Default settings
            var settings = $.extend({
                mask: false,
                validate: 'cpfcnpj',
                event: 'focusout',
                handler: $(this),
                validateOnlyFocus: false,
                ifValid: null,
                ifInvalid: null,
                returnType: null
            }, options);

            if (settings.mask) {
                if (jQuery().mask == null) {
                    settings.mask = false;
                    console.log("jQuery mask not found.");
                }
                else {
                    var masks = ['000.000.000-009', '00.000.000/0000-00'];
                    var ctrl = $(this);
                    if (settings.validate == 'cpf') {
                        ctrl.mask(masks[0]);
                    }
                    else if (settings.validate == 'cnpj') {
                        ctrl.mask(masks[1]);
                    }
                    else {
                        var cpfCnpjMsk = function (val) {
                            return val.length === 0 || val.length >= 12 ? masks[1] : masks[0];
                        }

                        var opt = {
                            onChange: function (val, e, currentField) {
                                var field = $(currentField);
                                var value = field.cleanVal();
                                field.mask(cpfCnpjMsk(value), opt);
                            }
                        };
                        ctrl.mask(cpfCnpjMsk, opt);
                    }
                }
            }

            return this.each(function () {
                var valid = null;
                var control = $(this);

                $(document).unbind(settings.event).on(settings.event, settings.handler,
                    function () {
                        if (!settings.validateOnlyFocus || settings.validateOnlyFocus && control.is(':focus')) {
                            var value = control.val();
                            var lgt = value.length;
                            returnType = null;

                            valid = false;

                            if (lgt == 11 || lgt == 14 || lgt == 18) {
                                if (settings.validate == 'cpf') {
                                    valid = validate_cpf(value, settings.mask);
                                }
                                else if (settings.validate == 'cnpj') {
                                    valid = validate_cnpj(value, settings.mask)
                                }
                                else if (settings.validate == 'cpfcnpj') {
                                    if (validate_cpf(value, settings.mask)) {
                                        valid = true;
                                        returnType = 'cpf';
                                    }
                                    else if (validate_cnpj(value, settings.mask)) {
                                        valid = true;
                                        returnType = 'cnpj';
                                    }
                                }
                            }

                            if ($.isFunction(settings.ifValid)) {
                                if (valid != null && valid) {
                                    if ($.isFunction(settings.ifValid)) {
                                        var callbacks = $.Callbacks();
                                        callbacks.add(settings.ifValid);
                                        callbacks.fire(control);
                                    }
                                }
                                else if ($.isFunction(settings.ifInvalid)) {
                                    settings.ifInvalid(control);
                                }
                            }
                        }
                    });
            });
        }

        function validate_cnpj(val, msk) {
            val = val.replace(/[^\d]+/g, '');

            // Elimina CNPJs inválidos conhecidos
            if (val == '' || val.length != 14 || /^(.)\1+$/.test(val))
                return false;

            // Valida DVs
            tamanho = val.length - 2
            numeros = val.substring(0, tamanho);
            digitos = val.substring(tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                return false;

            tamanho = tamanho + 1;
            numeros = val.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                return false;

            return true;
        }

        function validate_cpf(val, msk) {
            var regex = msk != undefined && msk ? /^\d{3}\.\d{3}\.\d{3}\-\d{2}$/ : /^[0-9]{11}$/;

            if (val.match(regex) != null) {
                //check all same numbers
                if (val.match(/\b(.+).*(\1.*){10,}\b/g) != null)
                    return false;

                var strCPF = val.replace(/\D/g, '');
                var sum;
                var rest;
                sum = 0;

                for (i = 1; i <= 9; i++)
                    sum = sum + parseInt(strCPF.substring(i - 1, i)) * (11 - i);

                rest = (sum * 10) % 11;

                if ((rest == 10) || (rest == 11))
                    rest = 0;

                if (rest != parseInt(strCPF.substring(9, 10)))
                    return false;

                sum = 0;
                for (i = 1; i <= 10; i++)
                    sum = sum + parseInt(strCPF.substring(i - 1, i)) * (12 - i);

                rest = (sum * 10) % 11;

                if ((rest == 10) || (rest == 11))
                    rest = 0;
                if (rest != parseInt(strCPF.substring(10, 11)))
                    return false;

                return true;
            }

            return false;
        }
    }(jQuery));
}

function maskMercosul(selector) {
    var MercoSulMaskBehavior = function (val) {
        var myMask = 'AAA0A00';
        var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
        var normal = /([A-Za-z]{3}[0-9]{2})/;
        var replaced = val.replace(/[^\w]/g, '');
        if (normal.exec(replaced)) {
            myMask = 'AAA-0000';
        } else if (mercosul.exec(replaced)) {
            myMask = 'AAA-0A00';
        }
        return myMask;
    },
        mercoSulOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
            }
        };
    $(function () {
        $(selector).bind('paste', function (e) {
            $(this).unmask();
        });
        $(selector).bind('input', function (e) {
            $(selector).mask(MercoSulMaskBehavior, mercoSulOptions);
        });
    });
}
