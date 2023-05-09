<head>
    <title><?=TITULO_GERAL;?></title>
    <meta charset="utf-8"/>
    <meta name="description" content="Maximize sua performance comercial com a nossa solução de agendamentos.Solicitar uma captação de Imagens profissionais nunca foi tão fácil."/>
    <meta name="keywords" content="Sheephouse, gestão de midia,fotógrafos"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?=TITULO_GERAL;?>" />
    <meta property="og:url" content="https://<?=URL_SITE;?>/Sheephouse"/>
    <meta property="og:site_name" content="Squall Sollutions | Sheephouse" />
    <link rel="canonical" href="https://preview.<?=URL_SITE;?>/Sheephouse8"/>
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico"/>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="app-blank">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<!--end::Theme mode setup on page load-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
            <!--begin::Form-->
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-500px p-10">
                    <!--begin::Form-->
                    <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" id="kt_sign_up_form" data-kt-redirect-url="index.php" action="#">

                        <input type="hidden" name="numero" id="numero" value="">
                        <input type="hidden" name="bairro" id="bairro" value="">
                        <input type="hidden" name="cidade" id="cidade" value="">
                        <input type="hidden" name="estado" id="estado" value="">
                        <input type="hidden" name="cep" id="cep" value="">
                        <input type="hidden" name="latitude" id="latitude" value="">
                        <input type="hidden" name="longitude" id="longitude" value="">
                        <!--begin::Heading-->
                        <div class="text-center mb-11">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bolder mb-3">Cadastre-se</h1>
                            <!--end::Title-->
                        </div>
                        <!--begin::Heading-->
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8 fv-plugins-icon-container">
                            <!--begin::Email-->
                            <input type="text" placeholder="Nome" name="nome" autocomplete="off" class="form-control bg-transparent">
                            <!--end::Email-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8 fv-plugins-icon-container">
                            <!--begin::Email-->
                            <input type="text" placeholder="CPF/CNPJ" name="cpf_cnpj" id="cpf_cnpj" autocomplete="off" class="form-control bg-transparent">
                            <!--end::Email-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8 fv-plugins-icon-container">
                            <!--begin::Email-->
                            <input type="text" placeholder="Endereço" name="busca_endereco" id="busca_endereco" autocomplete="off" class="form-control bg-transparent">
                            <input type="hidden" name="endereco" id="endereco" value="">
                            <!--end::Email-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8 fv-plugins-icon-container">
                            <!--begin::Email-->
                            <input type="text" placeholder="Telefone" name="telefone" id="telefone" autocomplete="off" class="form-control bg-transparent">
                            <!--end::Email-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8 fv-plugins-icon-container">
                            <!--begin::Email-->
                            <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent">
                            <!--end::Email-->
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--begin::Input group-->
                        <div class="fv-row mb-8 fv-plugins-icon-container" data-kt-password-meter="true">
                            <!--begin::Wrapper-->
                            <div class="mb-1">
                                <!--begin::Input wrapper-->
                                <div class="position-relative mb-3">
                                    <input class="form-control bg-transparent" type="password" placeholder="Senha" name="password" autocomplete="off">
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
												<i class="bi bi-eye-slash fs-2"></i>
												<i class="bi bi-eye fs-2 d-none"></i>
											</span>
                                </div>
                                <!--end::Input wrapper-->
                                <!--begin::Meter-->
                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>
                                <!--end::Meter-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Hint-->
                            <div class="text-muted">No mínimo 8 caracteres com Maiusculas e Minusculas, simblos e números não necessários</div>
                            <!--end::Hint-->
                            <div class="fv-plugins-message-container invalid-feedback"></div></div>
                        <!--end::Input group=-->
                        <!--end::Input group=-->
                        <div class="fv-row mb-8 fv-plugins-icon-container">
                            <!--begin::Repeat Password-->
                            <input placeholder="Repita a senha" name="confirm-password" type="password" autocomplete="off" class="form-control bg-transparent">
                            <!--end::Repeat Password-->
                            <div class="fv-plugins-message-container invalid-feedback"></div></div>
                        <!--end::Input group=-->
                        <!--begin::Accept-->
                        <div class="fv-row mb-8 fv-plugins-icon-container">
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="toc" value="1">
                                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">Eu aceito os
										<a href="#" class="ms-1 link-primary">Termos</a></span>
                            </label>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <!--end::Accept-->
                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Cadastrar</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Aguarde...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                <!--end::Indicator progress-->
                            </button>
                        </div>
                        <!--end::Submit button-->
                        <!--begin::Sign up-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">Já tem conta?
                            <a href="index.php" class="link-primary fw-semibold">Entre</a></div>
                        <!--end::Sign up-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Form-->
        </div>
        <!--end::Body-->
        <!--begin::Aside-->
        <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url(assets/media/misc/auth-bg.png)">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                <!--begin::Logo-->
                <a href="../../demo1/dist/index.html" class="mb-0 mb-lg-12">
                    <img alt="Logo" src="assets/media/logos/custom-1.png" class="h-60px h-lg-75px" />
                </a>
                <!--end::Logo-->
                <!--begin::Image-->
                <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="assets/media/misc/auth-screens.png" alt="" />
                <!--end::Image-->
                <!--begin::Title-->
                <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Rápido, eficiente e produditvo</h1>
                <!--end::Title-->
                <!--begin::Text-->
                <div class="d-none d-lg-block text-white fs-base text-center">Controle eficiente de medias para imobiliárias e corretores,controlando medias e proficionais
                    <br />de fotografias, fimagens e edição.
                </div>
                <!--end::Text-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Aside-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
<!--begin::Javascript-->
<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="assets/js/custom/authentication/sign-up/general.js?v=3"></script>
<!--end::Custom Javascript-->

<script src="assets/js/makedMoney.js"></script>
<script src="assets/js/MascarasGeral.js"></script>
<script src="assets/js/mask.js"></script>
<script src="assets/js/jquery.mask.min.js"></script>
<script src="assets/js/jquery.form.js"></script>

<script src="assets/js/bloodhound.js"></script>
<script src="assets/js/addresspicker.js"></script>
<script src="assets/js/addresspicker-typeahead.jquery.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLEKEYMAP?>&language=pt-BR&channel=1&libraries=geometry,drawing,places" type="text/javascript"></script>
<script>
    var autocomplete;
    $(document).ready(function () {
        const input = document.getElementById("busca_endereco");
        const options = {
            fields: ["address_components", "geometry"],
            types: ["address"]
        };
        autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.addListener("place_changed", ManipularResultado);
    });

    function ManipularResultado()
    {
        //Limpando as variáveis, por segurança
        $("#bairro").val("");
        $("#cidade").val("");
        $("#estado").val("");
        $("#numero").val("");
        $("#cep").val("");
        $('#endereco').val("");
        $("#latitude").val("");
        $("#longitude").val("");



        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";
        let latitude = place.geometry.location.lat();
        let longitude = place.geometry.location.lng();
        console.log(place.geometry.location.lat());

        $("#latitude").val(latitude);
        $("#longitude").val(longitude);


        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
            // @ts-ignore remove once typings fixed
            const componentType = component.types[0];

            switch (componentType) {
                case "street_number": {
                    address1 = `${component.long_name} ${address1}`;
                    $("#numero").val(component.long_name);
                    break;
                }

                case "route": {
                    $('#endereco').val(component.short_name);
                    address1 += component.short_name;
                    break;
                }

                case "postal_code": {
                    $('#cep').val(`${component.long_name}${postcode}`);
                    break;
                }

                case "postal_code_suffix": {
                    postcode = `${postcode}-${component.long_name}`;
                    break;
                }
                case "locality":
                case "sublocality_level_1":
                case "sublocality":
                    $("#bairro").val(component.long_name);
                    // document.querySelector("#locality").value = component.long_name;
                    break;
                case "administrative_area_level_1": {
                    $("#estado").val(component.short_name);
                    // document.querySelector("#state").value = component.short_name;
                    break;
                }
                case "administrative_area_level_2": {
                    $("#cidade").val(component.short_name);
                    // document.querySelector("#state").value = component.short_name;
                    break;
                }
            }
        }
    }
</script>

<!--end::Javascript-->
</body>
<!--end::Body-->
</html>