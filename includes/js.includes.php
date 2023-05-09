<!--begin::Javascript-->
<script>
    var hostUrl = "assets/";        </script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<script src="assets/js/custom/squall.bundle.js?v=2"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
<!--<script src="https://cdn.amcharts.com/lib/5/index.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/map.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>-->
<!--<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>-->
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="assets/js/widgets.bundle.js"></script>
<script src="assets/js/custom/widgets.js"></script>
<!--<script src="assets/js/custom/apps/chat/chat.js"></script>-->
<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="assets/js/custom/utilities/modals/create-app.js"></script>
<script src="assets/js/custom/utilities/modals/new-target.js"></script>
<script src="assets/js/custom/utilities/modals/users-search.js"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->

<script src="assets/plugins/custom/jstree/jstree.bundle.js"></script>
<script src="assets/js/makedMoney.js"></script>
<script src="assets/js/MascarasGeral.js"></script>
<script src="assets/js/mask.js"></script>
<script src="assets/js/jquery.mask.min.js"></script>
<script src="assets/js/jquery.form.js"></script>

<script src="assets/js/bootstrap-show-modal.js"></script>

<script src="assets/js/bloodhound.js"></script>
<script src="assets/js/addresspicker.js"></script>
<script src="assets/js/addresspicker-typeahead.jquery.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLEKEYMAP?>&language=pt-BR&channel=1&libraries=geometry,drawing,places" type="text/javascript"></script>

<script src="assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
<script src="assets/js/flappicker-pt-br.js"></script>


<!--<script src="assets/plugins/custom/dual-listbox-master/dist/dual-listbox.js"></script>-->




<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->

<script>
    $(function (){
        Inputmask("decimal", {
            numericInput: true,
            radixPoint: ',',
            digitsOptional: true
        }).mask("#pacote_custom");
        var iframe = document.getElementById("iframe_pagamento");
        $('#bt_comprar_credito').click(function (){
            $.post("index_xml.php?app_modulo=asaas&app_comando=comprar_credito",
                $('#fr-planos-credito').serialize(),
                function(response)
                {
                    if(response["codigo"] == 0) {
                        Squall.ToastMsg('success', 'Sucesso , você esta sendo encaminhado para o link de pagamento');
                        $('#modal_planos').modal('hide');
                        $('#modal_pagamento').modal('show');
                        // iframe.src = response.data.url;
                        $('#div_pgto').html('<a href="'+response.data.url+'" target="_blank" class="btn btn-primary" >Ir para pagamento... </a>');
                        // var anchor = document.createElement('a');
                        // anchor.href = response.data.url;
                        // anchor.target="_blank";
                        // anchor.click();

                    }
                    else
                    {
                        Squall.ToastMsg('warning','Erro ao direcionar para compra de cr[editos');
                    }
                }, "json"
            );

        });
        <?php

        if($_SESSION['usuario']['id_forma_pagamento'] == 16)
        {
            echo "
                    setInterval(function()
                    {
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
                        }, 10000
                    );";
        }
        ?>

    });
</script>
<!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=G-G7H13660FE"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-G7H13660FE'); </script>
