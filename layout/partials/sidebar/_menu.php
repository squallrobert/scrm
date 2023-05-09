<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div
            id="kt_app_sidebar_menu_wrapper"
            class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true"
            data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu"
            data-kt-scroll-offset="5px"
            data-kt-scroll-save-state="true"
    >

        <?php
        $menu = new Menu();
        if($_SESSION['usuario']['permissao_especifica'])
            echo $menu->GerarMenuUsuario($_SESSION['usuario']['id']);
        else
            echo $menu->GerarMenu($_SESSION['usuario']['id_grupo']);
        ?>

    </div>
    <!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->