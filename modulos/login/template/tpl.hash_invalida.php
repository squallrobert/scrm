<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <title><?=TITULO_GERAL;?></title>
    <meta charset="utf-8"/>
    <meta name="description" content="sistema de controle de mídias para imobiliarias"/>
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
<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<!--end::Theme mode setup on page load-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Page bg image-->
    <style>body { background-image: url('assets/media/auth/bg5.jpg'); } [data-bs-theme="dark"] body { background-image: url('assets/media/auth/bg5-dark.jpg'); }</style>
    <!--end::Page bg image-->
    <!--begin::Authentication - Signup Welcome Message -->
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <!--begin::Content-->
        <div class="d-flex flex-column flex-center text-center p-10">
            <!--begin::Wrapper-->
            <div class="card card-flush w-lg-650px py-5">
                <div class="card-body py-15 py-lg-20">
                    <!--begin::Logo-->
                    <div class="mb-14">
                        <a href="index.php" class="">
                            <img alt="Logo" src="assets/logo_full.png" class="h-40px" />
                        </a>
                    </div>
                    <!--end::Logo-->
                    <!--begin::Title-->
                    <h1 class="fw-bolder text-gray-900 mb-5">Solicitação expirada ou inválida!</h1>
                    <!--end::Title-->
                    <!--begin::Action-->
                    <div class="fs-6 mb-8">
                        <span class="fw-semibold text-gray-500">entre com uma nova requisição</span>
                        <a href="index.php?app_comando=esqueceu_senha" class="link-primary fw-bold">Tentar Novamente</a>
                    </div>
                    <!--end::Action-->
                    <!--begin::Link-->
                    <div class="mb-11">
                        <a href="index.php" class="btn btn-sm btn-primary">Ignorar por enquanto</a>
                    </div>
                    <!--end::Link-->
                    <!--begin::Illustration-->
                    <div class="mb-0">
                        <img src="assets/media/auth/please-verify-your-email.png" class="mw-100 mh-300px theme-light-show" alt="" />
                        <img src="assets/media/auth/please-verify-your-email-dark.png" class="mw-100 mh-300px theme-dark-show" alt="" />
                    </div>
                    <!--end::Illustration-->
                </div>
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Authentication - Signup Welcome Message-->
</div>
<!--end::Root-->
<!--begin::Javascript-->
<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>