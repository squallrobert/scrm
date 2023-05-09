var config_squall = {
    id: '#squall',
    conteudo:'#conteudo',
    class: 'squallClass',
    isMobile: ((/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) || window.innerWidth < 767),
    ajax: {
        attr: 'data-toggle="ajax"',
        clearOption: '',
        error: {
            html: '<div class="px-3 text-center fs-20px"><i class="fa fa-warning fa-lg text-muted me-1"></i> <span class="fw-600 text-inverse">Error 404! Page not found.</span></div>'
        }
    }
};
const loadingEl = document.createElement("div");
var Squall = function (){
    var setting;
    return {
        emptyHtml: '',
        init: function(option) {
            if (option) {
                setting = option;
            }
            this.initHashChange();
            this.initToggler();
            this.initDefaultUrl();
            this.LoadingAjax();
        },
        initDefaultUrl: function() {
            this.emptyHtml = (setting && setting.emptyHtml) ?  setting.emptyHtml : config_squall.ajax.error.html;
            this.defaultUrl = (setting && setting.ajaxDefaultUrl) ? setting.ajaxDefaultUrl : 'index_xml.php?app_modulo=home&app_comando=home';
            this.defaultUrl = (window.location.hash) ? window.location.hash : this.defaultUrl;

            if (this.defaultUrl === '') {
                var elm = document.querySelector(config_squall.conteudo);
                if (elm) {
                    elm.innerHTML = this.emptyHtml;
                }
            } else {
                this.renderAjax(this.defaultUrl, '', true);
            }
        },
        LoadingAjax : function (){
            $(document).ajaxStart(function () {
                KTApp.showPageLoading();
            });
            $(document).ajaxStop(function () {
                KTApp.hidePageLoading();
            });
        },
        Descadastro : function (){
            swal.fire({
                title: "Confirme Por favor",
                text: "Você realmente gostaria de Remover sua conta definitivamente da Sheephouse ?",
                type: "danger",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, continue!",
                cancelButtonText: "Não, cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }).then((isConfirm) =>{
                if (isConfirm.value) {
                    window.location.href = '#index_xml.php?app_modulo=home&app_comando=excluir_conta';
                } else {
                    swal.fire("Cancelado", "Remoção cancelada pelo usuário", "error");
                }
            });
        },
        AtualizarSaldo : function (){
            $.ajax({
                url: 'index_xml.php?app_modulo=cliente&app_comando=verificar_saldo',  beforeSend: function( xhr ) {
                    KTApp.hidePageLoading();
                },
                dataType:'json'
            }).done(function(response) {
                console.log(response);
                KTApp.hidePageLoading();
                $('#saldo_topo').html(response.saldo);
            });
        },
        /**
         *    Parâmetros:
         *        campoOrigem = id do campo html que deve ser usada para o autocomplete
         *        campoDestino = campo html que armazenará o id do item selecionado no resultado do autocomplete
         *        url = url chamada para obter os resultados
         *        filtro = valor do parametro (a url receberá como $_REQUEST['param'])
         *        callback = função disparada ao se selecionar uma opção
         *
         *    Exemplo de uso:
         *        AutoSelect('nome_grupo', 'id_grupo', 'index_xml.php?app_modulo=erp_grupo&app_comando=popup_localizar_erp_grupo', 1, 'nomeFuncaoTeste');
         */
        autoComplete : function (campoOrigem, url,filtro,callback){
            if(filtro == undefined || filtro == '') filtro = '';
            $(campoOrigem).select2(
                {
                    "language": "pt-br",
                    allowClear: true,
                    ajax:
                        {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            beforeSend: function( xhr ) {
                                KTApp.hidePageLoading();
                            },
                            data: function(params)
                            {
                                return {
                                    term: params.term, // search term
                                    page: params.page,
                                    filtro: filtro
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        },
                    placeholder: 'Digite algo para iniciar a busca'
                });
            // após selecionar pega o tronco do vetor e manda para afunção callback
            if (callback !== "" && callback !== undefined) {
                $(campoOrigem).on('`[data-validar="select2"]`:select', function (e) {
                    var data = e.params.data;
                    eval(callback)(data);
                });
            }
        },
        getParams : function ()
        {
            var urlParams;
            (window.onpopstate = function () {
                var match,
                    pl     = /\+/g,  // Regex for replacing addition symbol with a space
                    search = /([^&=]+)=?([^&]*)/g,
                    decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
                    query  = window.location.search.substring(1);

                urlParams = {};
                while (match = search.exec(query))
                    urlParams[decode(match[1])] = decode(match[2]);
            })();
            return urlParams;
        },
        initToggler: function () {
            var elms = [].slice.call(document.querySelectorAll('['+config_squall.ajax.attr+']'));
            //console.log(elms);
            if (elms) {
                elms.map(function (elm) {
                    elm.onclick = function (e) {
                        e.preventDefault();
                        Squall.renderAjax(this.getAttribute('href'), this);
                    };
                });
            }
        },
        onDOMContentLoaded: function(callback) {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', callback);
            } else {
                callback();
            }
        },
        GridListaCheckbox : function (master, todos)
        {
            for (cont = 0; cont < todos.length; cont++) {
                if (!todos[cont].disabled && $(todos[cont]).is(":visible")) {
                    todos[cont].checked = master.checked
                }
            }
        },
        loadPage : function (){
            // Populate the page loading element dynamically.
            // Optionally you can skipt this part and place the HTML
            // code in the body element by refer to the above HTML code tab.
            document.body.prepend(loadingEl);
            loadingEl.classList.add("page-loader");
            loadingEl.classList.add("flex-column");
            loadingEl.classList.add("bg-dark");
            loadingEl.classList.add("bg-opacity-25");
            loadingEl.innerHTML = `
        <span class="spinner-border text-primary" role="status"></span>
        <span class="text-gray-800 fs-6 fw-semibold mt-5">Carregando...</span>
        `;

            // Show page loading
            KTApp.showPageLoading();
        },
        removeLoad : function ()
        {
            KTApp.hidePageLoading();
            loadingEl.remove();
        },
        initHashChange: function() {
            window.addEventListener('hashchange', function() {
                if (window.location.hash) {
                    Squall.loadPage();
                    Squall.renderAjax(window.location.hash, '', true);
                } else {
                    Squall.renderAjax(defaultUrl, '', true);
                }
            });
        },
        checkPushState: function(url) {
            var targetUrl = url.replace('#','');
            var targetUserAgent = window.navigator.userAgent;
            var isIE = targetUserAgent.indexOf('MSIE ');

            if (isIE && (isIE > 0 && isIE < 9)) {
                window.location.href = targetUrl;
            } else {
                history.pushState('', '', '#' + targetUrl);
            }
        },
        ToastMsg: function(tipo,mensagem,url,timeout) {

            if(timeout === '' && timeout === undefined) timeout = '5000';
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toastr-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": timeout,
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            if(url !== '' && url !== undefined){
                toastr.options.onHidden = function() {  window.location = url; };
            }
            toastr[tipo](mensagem);
        },
        ValidateForm : function ($form)
        {

            var result         = true,
                currentDiv,
                $this,
                contadorSelect = 0,
                classes        = [],
                idTab          = '',
                nodeName = '',
                validados      = [];

            $(".invalid-feedback").remove();

            //Validação de campos obrigatórios com a classe .validar-obrigatorio
            $form.find(".validar-obrigatorio").each(function ()
            {
                $this      = $(this);
                //console.log($this[0].id);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    if ($this.val() == "" ) {

                        $this.addClass('is-invalid');
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        $this.focus();
                    } else {
                        $this.removeClass("is-invalid");
                        // $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    // $this.addClass('is-valid');
                }
            });
            //Validação de campos obrigatórios com a classe .validar-obrigatorio
            $form.find(`[data-validar="tagit"]`).each(function ()
            {
                $this      = $(this);
                // console.log($this.val()); console.log($this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    if ($this.val() == "" ) {

                        $this.addClass('is-invalid');
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Pelo menos um item deve ser adicionado.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        $this.focus();
                    } else {
                        $this.removeClass("is-invalid");
                        // $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    // $this.addClass('is-valid');
                }
            });

            $form.find(".validar-obrigatorio-0").each(function ()
            {
                $this      = $(this);
                // console.log($this.val()); console.log($this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    if ($this.val() == "" || $this.val() == "0") {

                        if ($this.val() == ""){
                            $this.addClass('is-invalid');
                            currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório.</div>");
                            result = false;
                        }

                        if ($this.val() == "0"){
                            $this.addClass('is-invalid');
                            currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Não pode ser igual a 0.</div>");
                            result = false;
                        }

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        $this.focus();
                    } else {
                        $this.removeClass("is-invalid");
                        // $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    // $this.addClass('is-valid');
                }
            });

            $form.find(".validar-obrigatorio-nove-digitos").each(function ()
            {
                $this      = $(this);
                // console.log($this.val()); console.log($this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    var onlyNumber = $this.val().replace(/\D+/g, '');
                    if (($this.val().length < 15) && (onlyNumber.length < 11)) {

                        $this.addClass('is-invalid');
                        currentDiv.append("<div class='invalid-feedback nove_digitos' style=\"display:initial !important; \">Celular precisa de 9 Digitos.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        $this.focus();
                    } else {
                        $this.removeClass("is-invalid");
                        // $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    // $this.addClass('is-valid');
                }

            });
            $form.find(".validar-obrigatorio-group").each(function ()
            {
                $this      = $(this);
                // console.log($this.val()); console.log($this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    if ($this.val() == "" ) {

                        $this.addClass('is-invalid');
                        $this.parent().append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        $this.focus();
                    } else {
                        $this.removeClass("is-invalid");
                        // $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    // $this.addClass('is-valid');
                }
            });

            //Validação de campos obrigatórios com a classe .validar-obrigatorio
            $form.find(".validar-obrigatorio-m").each(function ()
            {
                $this      = $(this);
                // console.log($this.val()); console.log($this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    if ($this.val() == "" ) {
                        $this.addClass('is-invalid');
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        $this.focus();
                    } else {
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    $this.addClass('is-valid');
                }
            });
            $form.find(".validar-cpf").each(function ()
            {
                $this = $(this);
                // console.log($this.val()); console.log($this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true) {
                    if ($this.val() == "" || !validarCPF($this.val())) {
                        result = false;
                        $this.addClass('is-invalid');
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Formato inválido</div>");
                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        $this.focus();
                    } else {
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    $this.addClass('is-valid');
                }
            });
            $form.find(".validar-obrigatorio-select-picker").each(function ()
            {
                $this      = $(this);
                // console.log($this.val()); console.log($this);
                nodeName = $this[0].nodeName;
                currentDiv = $this.parent().parent();
                // console.log(currentDiv);
                if (currentDiv.hasClass("form-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                // console.log(currentDiv);
                if(nodeName != 'DIV') {
                    if ($this.prop("disabled") !== true) {
                        if ($this.val() == "") {
                            // currentDiv.addClass('has-error');
                            currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório selecionar uma opção.</div>");
                            currentDiv.addClass('is-invalid');
                            $this.selectpicker('setStyle', 'btn-danger', 'add');
                            result = false;

                            if (idTab == "") {
                                idTab = currentDiv.closest(".tab-pane").attr("id");
                            }
                            $this.focus();
                        } else {
                            $this.selectpicker('setStyle', 'btn-danger', 'remove');
                            $this.removeClass("is-invalid");
                            $this.addClass('is-valid');
                            $this.selectpicker('refresh');
                        }
                    } else {
                        $this.selectpicker('setStyle', 'btn-danger', 'remove');
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                        $this.selectpicker('refresh');
                    }
                }
            });
            $form.find(`[data-validar="select2-input-group"]`).each(function ()
            {
                $this      = $(this);
                //console.log($this[0].id);
                nodeName = $this[0].nodeName;
                currentDiv = $this.parent().parent().parent();
                if (currentDiv.hasClass("form-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if(nodeName != 'DIV') {
                    if ($this.prop("disabled") !== true) {

                        if ($this.val() == "" || $this.val() == null) {
                            $this.addClass('is-invalid');
                            currentDiv.children().append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório selecionar uma opção.</div>");
                            result = false;

                            if (idTab == "") {
                                idTab = currentDiv.closest(".tab-pane").attr("id");
                            }
                            $this.focus();

                        } else {
                            $this.removeClass("is-invalid");
                            // $this.addClass('is-valid');   Removido a pedido da Kaline

                        }
                    } else {
                        $this.removeClass("is-invalid");
                        // $this.addClass('is-valid');   Removido a pedido da Kaline
                    }
                }

            });
            $form.find(`[data-validar="select2"]`).each(function ()
            {
                $this      = $(this);
                //console.log($this[0].id);
                nodeName = $this[0].nodeName;
                currentDiv = $this.parent();
                if (currentDiv.hasClass("form-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if(nodeName != 'DIV') {
                    if ($this.prop("disabled") !== true) {

                        if ($this.val() == "" || $this.val() == null) {
                            $this.addClass('is-invalid');
                            currentDiv.children().append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório selecionar uma opção.</div>");
                            result = false;

                            if (idTab == "") {
                                idTab = currentDiv.closest(".tab-pane").attr("id");
                            }
                            $this.focus();
                        } else {
                            $this.removeClass("is-invalid");
                            // $this.addClass('is-valid');   Removido a pedido da Kaline

                        }
                    } else {
                        $this.removeClass("is-invalid");
                        // $this.addClass('is-valid');   Removido a pedido da Kaline
                    }
                }

            });
            /*
             ESSA FUNÇÃO VALIDA OS "AUTOCOMPLETES" OBRIGATÓRIOS,
             ELA IRA PROCURAR O INPUT HIDDEN DO CAMPO TXT.
             O CAMPO HIDDEN DEVE TER NO MESMO ID DO CAMPO TXT PRECEDIDO PELO PREFIXO "id_"
             EX:
             CAMPO TXT    = franquia
             CAMPO HIDDEN = id_franquia
             */
            $form.find(".validar-text-hidden-obrigatorio").each(function(){

                $idHidden  = $("#id_" + $(this).attr("name"));
                $this      = $(this);
                // console.log($this.val()); console.log($this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true) {
                    if ( $idHidden.val() == "") {
                        $this.addClass('is-invalid');

                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }

                    } else {
                        currentDiv.removeClass("has-danger");
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                } else {
                    currentDiv.removeClass("has-danger");
                    $this.removeClass("is-invalid");
                }
            });

            $form.find(".validar-select-plugin").each(function ()
            {
                contadorSelect++;
                if (contadorSelect % 2 != 0) {
                    $this      = $(this);
                    currentDiv = $this.parent();

                    if (currentDiv.hasClass("input-group"))
                        currentDiv = currentDiv.parent().first();

                    if ($this.val() == null || typeof $this.val() === "undefined" || $this.val() == " ") {
                        $this.addClass('is-invalid');
                        currentDiv.append('<div class="invalid-feedback" style=\"display:initial !important; \">Obrigatório.</div>');
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                    } else {
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                }
            });

            $form.find(".validar-duallist").each(function ()
            {
                $this      = $("#ms-" + $(this).attr("id"));
                currentDiv = $this.parent();

                if ($this.find(".ms-selection").first().find(".ms-selected").length <= 0) {

                    currentDiv.append('<div class="invalid-feedback" style=\"display:initial !important; \">Selecione algum registro</div>');
                    result = false;
                    if (idTab == "") {
                        idTab = currentDiv.closest(".tab-pane").attr("id");
                    }
                } else {
                    currentDiv.removeClass("has-danger");
                }
            });

            /*Validação de mínimo e máximo, usar as classes: valididar-min-max, min_VALORMINIMO, max_VALORMAXIMO*/
            var valMin = null,
                valMax = null;

            $form.find(".validar-min-max").each(function ()
            {
                $this      = $(this);
                classes    = $this.attr("class").split(" ");
                currentDiv = $this.parent();
                valMin     = null;
                valMax     = null;

                for (var x = 0; x < classes.length; x++) {
                    if (classes[x].indexOf("min_") >= 0) {
                        valMin = classes[x].substring(classes[x].indexOf("min_") + 4);
                    } else if (classes[x].indexOf("max_") >= 0) {
                        valMax = classes[x].substring(classes[x].indexOf("max_") + 4);
                    }
                }

                if ($this.val() != "") {
                    if (currentDiv.hasClass("input-group")) {
                        currentDiv = currentDiv.parent();
                    }
                }
                if ((!isNaN(valMin) && Number($this.val()) < Number(valMin)) || (!isNaN(valMax) && Number($this.val()) > Number(valMax))) {

                    currentDiv.append('<div class="invalid-feedback" style=\"display:initial !important; \">Valor maior ou menor do que o permitido</div>');
                    result = false;

                    if (idTab == "") {
                        idTab = currentDiv.closest(".tab-pane").attr("id");
                    }
                } else {
                    currentDiv.removeClass("has-danger");
                }
            });

            $form.find(".validar-tamanho").each(function ()
            {
                $this      = $(this);
                classes    = $this.attr("class").split(" ");
                currentDiv = $this.parent();
                valMin     = null;
                valMax     = null;

                for (var x = 0; x < classes.length; x++) {
                    if (classes[x].indexOf("min_") >= 0) {
                        valMin = classes[x].substring(classes[x].indexOf("min_") + 4);
                    } else if (classes[x].indexOf("max_") >= 0) {
                        valMax = classes[x].substring(classes[x].indexOf("max_") + 4);
                    }
                }

                if ($this.val() != "") {
                    if (currentDiv.is(":visible") || $this.attr("type") == "hidden") {
                        if (currentDiv.hasClass("input-group")) {
                            currentDiv = currentDiv.parent();
                        }
                        if ((!isNaN(valMin) && $this.val().length < Number(valMin)) || (!isNaN(valMax) && $this.val().length > Number(valMax))) {
                            $this.addClass('is-invalid');
                            currentDiv.append('<div class="invalid-feedback" style=\"display:initial !important; \">Texto muito longo ou muito curto</div>');
                            result = false;

                            if (idTab == "") {
                                idTab = currentDiv.closest(".tab-pane").attr("id");
                            }
                        } else {
                            $this.removeClass("is-invalid");
                            $this.addClass('is-valid');
                        }
                    }
                }
            });

            var namesValidados = [],
                currentName    = '',
                qtdSelecionado = 0;

            $form.find(".validar-radio").each(function ()
            {
                $this       = $(this);
                currentDiv  = $this.parent().parent().parent();
                currentName = $this.attr("name");
                if (namesValidados.indexOf(currentName) < 0) {
                    $form.find("[name='" + currentName + "']").each(function ()
                    {
                        if ($(this).is(":checked")) {
                            qtdSelecionado++;
                        }
                    });

                    namesValidados.push(currentName);

                    //if (currentDiv.is(":visible") || $this.attr("type") == "hidden") {
                    if (currentDiv.hasClass("input-group")) {
                        currentDiv = currentDiv.parent();
                    }
                    if (qtdSelecionado == 0) {
                        $this.addClass('is-invalid');
                        currentDiv.append('<div class="invalid-feedback" style=\"display:initial !important; \">Selecione uma opção</div>');
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                    } else {
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                    //}

                    qtdSelecionado = 0;
                }
            });
            $form.find(".validar-email").each(function ()
            {
                $this      = $(this);
                currentDiv = $this.parent();

                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true) {
                    if (!/^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}/.exec($this.val()) || $this.val() == "") {

                        $this.addClass('is-invalid');
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Obrigatório.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                    }
                    else {
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }

                }

            });
            $form.find(".validar-email-cliente").each(function ()
            {
                $this      = $(this);
                currentDiv = $this.parent();

                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true) {
                    if ($this.val() == "") {

                        currentDiv.append('<div class="invalid-feedback" style=\"display:initial !important; \">Obrigatório</div>');
                        result = false;
                        //
                    } else if (!/^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}/.exec($this.val())) {

                        currentDiv.append('<div class="invalid-feedback">Email em formato inválido</div>');
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                    }
                    else {
                        currentDiv.removeClass("has-danger");
                    }
                }

            });

            $form.find(".validar-cnpj").each(function ()
            {
                $this = $(this);

                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true) {
                    if ($this.val() == "" || !validaCnpj($this.val())) {
                        $this.addClass('is-invalid');
                        result = false;
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Formato inválido</div>");
                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                    } else {
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                } else {
                    $this.removeClass("is-invalid");
                    $this.addClass('is-valid');
                }
            });

            $form.find('.valida-ip').each(function ()
            {
                var a = (/^((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])$/);

                $this = $(this);

                $this.focusout(function ()
                {
                    result = (!a.test($this.val()) || $this.val() == '0.0.0.0' || $this.val() == '255.255.255.255');

                    var currentDiv = $this.parent();
                    if (currentDiv.hasClass("input-group")) {
                        currentDiv = currentDiv.parent().first();
                    }
                    if ($this.prop("disabled") !== true) {
                        if (!result) {

                            currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \">Ip inválido</div>");
                            if (idTab == "") {
                                idTab = currentDiv.closest(".tab-pane").attr("id");
                            }
                        } else {
                            currentDiv.removeClass("has-danger");
                        }
                    } else {
                        currentDiv.removeClass("has-danger");
                    }
                });
            });
            $form.find(".validar-email-usuario").each(function ()
            {
                $this      = $(this);
                currentDiv = $this.parent();

                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true) {
                    if ($this.val() == ""){
                        $this.addClass('is-invalid');
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \"><i class=\"fa fa-times text-danger\"></i> Obrigatório.</div>");
                        result = false;
                    }else if (!/^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}/.exec($this.val())) {
                        $this.addClass('is-invalid');
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \"><i class=\"fa fa-times text-danger\"></i> E-mail no formato inválido.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                    }
                    else {
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }

                }

            });
            $form.find(".validar-confirma-senha").each(function ()
            {
                $this      = $(this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    if ($this.val() == ""  && $('#senha').val() != '') {

                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \"><i class=\"fa fa-times text-danger\"></i> Obrigatório.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }

                    } else {
                        if ($this.val() != $('#senha').val())
                        {
                            $this.addClass('is-invalid');
                            $this.focus();
                            currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \"><i class=\"fa fa-times text-danger\"></i> Confirmação de senha não é igual a senha.</div>");
                            result = false;
                        }
                        else
                        {
                            $this.addClass('is-valid');
                            $this.removeClass("is-invalid");
                        }
                    }
                } else {
                    $this.removeClass("is-invalid");
                    $this.addClass('is-valid');
                }
            });
            $form.find(".validar-confirma-senha-mudar").each(function ()
            {
                $this      = $(this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {
                    if ($this.val() == "" ) {

                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \"><i class=\"fa fa-times text-danger\"></i> Obrigatório.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }

                    } else {
                        if ($this.val() != $('#mudar_senha').val())
                        {
                            $this.addClass('is-invalid');
                            $this.focus();
                            currentDiv.append("<div class='invalid-feedback' style=\"display:initial !important; \"><i class=\"fa fa-times text-danger\"></i> Confirmação de senha não é igual a senha.</div>");
                            result = false;
                        }
                        else
                        {
                            $this.addClass('is-valid');
                            $this.removeClass("is-invalid");
                        }
                    }
                } else {
                    $this.removeClass("is-invalid");
                    $this.addClass('is-valid');
                }
            });
            $form.find(".validar-senha").each(function ()
            {
                var ucase = new RegExp("[A-Z]+");
                var lcase = new RegExp("[a-z]+");
                var num = new RegExp("[0-9]+");
                var senha_sucesso = true;

                $this      = $(this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {

                    if ($this.val().length < 8 ) {

                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Senha tem que ser maior ou igual 8 caracteres.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }

                    if (! ucase.test($this.val()) ) {
                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Deve-se conter pelo menos 1 maiúscula.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }
                    if (!lcase.test($this.val()) ) {
                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Deve-se conter pelo menos 1 minúscula.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }
                    if (!num.test($this.val()) ) {
                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Deve-se conter pelo menos 1 número.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }
                    if(senha_sucesso){
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                } else {

                    $this.removeClass("is-invalid");
                    $this.addClass('is-valid');
                }
            });
            $form.find(".validar-senha2").each(function ()
            {
                var ucase = new RegExp("[A-Z]+");
                var lcase = new RegExp("[a-z]+");
                var num = new RegExp("[0-9]+");
                var senha_sucesso = true;

                $this      = $(this);
                currentDiv = $this.parent();
                if (currentDiv.hasClass("input-group")) {
                    currentDiv = currentDiv.parent().first();
                }
                if ($this.prop("disabled") !== true ) {

                    if ($this.val().length < 8  && $this.val().length > 0) {

                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Senha tem que ser maior ou igual 8 Caracteres.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }

                    if (! ucase.test($this.val()) && $this.val().length > 0) {
                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Deve-se conter pelo menos 1 Maiscula.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }
                    if (!lcase.test($this.val()) && $this.val().length > 0) {
                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Deve-se conter pelo menos 1 Minuscula.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }
                    if (!num.test($this.val()) && $this.val().length > 0) {
                        $this.addClass('is-invalid');
                        $this.focus();
                        currentDiv.append("<div class='invalid-feedback' style=\"display:block !important; \"><i class=\"fa fa-times text-danger\"></i> Deve-se conter pelo menos 1 Número.</div>");
                        result = false;

                        if (idTab == "") {
                            idTab = currentDiv.closest(".tab-pane").attr("id");
                        }
                        senha_sucesso = false;
                    }
                    if(senha_sucesso){
                        $this.removeClass("is-invalid");
                        $this.addClass('is-valid');
                    }
                } else {

                    $this.removeClass("is-invalid");
                    $this.addClass('is-valid');
                }
            });
            // console.log('este e id da tab = ' + idTab);
            if(idTab != '')
                $form.find("a[href='#" + idTab + "']").tab('show');
            // $('#'+idTab).get(0).click();
            // $('.nav-tabs a[href="'+ idTab +'"]').tab('show');
            return result;
        },
        renderAjax: function(url, elm, disablePushState) {
            // if (window.location.search) {
            //     window.location.href =
            //         window.location.href.replace(window.location.search, '')
            //             .replace(window.location.hash, '') + '#' + url;
            // } else {
            //     window.location.hash = url;
            // }
            if (!disablePushState) {
                Squall.checkPushState(url);
            }
            var targetContainer= document.querySelector(config_squall.conteudo);
            if (!targetContainer) {
                return;
            }
            var targetUrl 	   = url.replace('#','');
            var targetType 	   = (setting && setting.ajaxType) ? setting.ajaxType : 'GET';
            var targetDataType = (setting && setting.ajaxDataType) ? setting.ajaxDataType : 'html';
            if (elm) {
                targetDataType = (elm.getAttribute('data-type')) ? elm.getAttribute('data-type') : targetDataType;
                targetDataDataType = (elm.getAttribute('data-data-type')) ? elm.getAttribute('data-data-type') : targetDataType;
            }
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == XMLHttpRequest.DONE) {
                    if (xmlhttp.status == 200) {
                        setInnerHTML(targetContainer, xmlhttp.responseText);

                    } else if (xmlhttp.status == 400) {
                        console.log('There was an error 400');
                        setInnerHTML(targetContainer, emptyHtml);
                    } else {
                        console.log('something else other than 200 was returned');
                    }
                    // Squall.checkLoading(true);
                    document.body.scrollTop = 0;
                    //App.initComponent();
                }
            };

            xmlhttp.open(targetType, targetUrl, true);
            xmlhttp.send();

        }
    }
}();
var setInnerHTML = function(elm, html) {
    elm.innerHTML = html;
    Array.from(elm.querySelectorAll('script')).forEach( oldScript => {
        const newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
    Squall.removeLoad();
};
window.addEventListener('DOMContentLoaded', (event) => {
    Squall.init();
});


function validarCPF(cpf)
{
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf == '') {
        return false;
    }
    // Elimina CPFs invalidos conhecidos
    if (cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999") {
        return false;
    }
    // Valida 1o digito
    add = 0;
    for (i = 0; i < 9; i++)
        add += parseInt(cpf.charAt(i)) * (10 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11) {
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(9))) {
        return false;
    }
    // Valida 2o digito
    add = 0;
    for (i = 0; i < 10; i++)
        add += parseInt(cpf.charAt(i)) * (11 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11) {
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(10))) {
        return false;
    }
    return true;
}

function validaCnpj(str)
{
    str            = str.replace('.', '');
    str            = str.replace('.', '');
    str            = str.replace('.', '');
    str            = str.replace('-', '');
    str            = str.replace('/', '');
    cnpj           = str;
    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    digitos_iguais = 1;
    if (cnpj.length < 14 && cnpj.length < 15) {
        return false;
    }
    for (i = 0; i < cnpj.length - 1; i++)
        if (cnpj.charAt(i) != cnpj.charAt(i + 1)) {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais) {
        tamanho = cnpj.length - 2;
        numeros = cnpj.substring(0, tamanho);
        digitos = cnpj.substring(tamanho);
        soma    = 0;
        pos     = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0)) {
            return false;
        }
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma    = 0;
        pos     = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) {
                pos = 9;
            }
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1)) {
            return false;
        }
        return true;
    }
    else {
        return false;
    }
}