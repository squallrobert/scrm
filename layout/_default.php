<!--begin::App-->
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
        <?php include_once('layout/partials/_header.php'); ?>
        <!--layout-partial:layout/partials/_header.html-->
        <!--begin::Wrapper-->
        <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
            <?php include_once('layout/partials/_sidebar.php'); ?>
            <!--layout-partial:layout/partials/_sidebar.html-->
            <!--begin::Main-->
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid ">
                    <?php
//                    include_once('layout/partials/_toolbar.php');
                    include_once('layout/partials/_content.php');
                    ?>
                    <!--layout-partial:layout/partials/_toolbar.html-->
                    <!--layout-partial:layout/partials/_content.html-->
                </div>
                <!--end::Content wrapper-->
                <?php include_once('layout/partials/_footer.php'); ?>
                <!--layout-partial:layout/partials/_footer.html-->
                            </div>
            <!--end:::Main-->
                    </div>
        <!--end::Wrapper-->
            </div>
    <!--end::Page-->
</div>
<!--end::App-->
<!--layout-partial:partials/_drawers.html-->
<?php include_once('partials/_drawers.html');?>