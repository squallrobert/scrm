<?php
include("modulos/grupo/template/js.vinculo.grupo.php");
?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Segurança</a></li>
    <li class="breadcrumb-item">Listagem de Grupos</li>
    <li class="breadcrumb-item active">Vincular Veículos a Grupos</li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
</ol>
<div class="subheader">
    <h1 class="subheader-title">
        <i class="subheader-icon fas fa-map-marker"></i> Vincular Veículos ao Grupo (<?=$linha['nome_grupo'];?>)
        <small>
            Lista de Veículos vinculados ao Grupo.
        </small>
    </h1>
</div>
<div class="row">

    <div class="col-lg-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr  ">
                <h2>
                    Veículos Disponíveis
                </h2>
                <div class="panel-toolbar">
                    <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                    <button class="btn btn-panel bg-transparent fs-xl w-auto h-auto rounded-0 waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"><i class="fas fa-expand"></i></button>
                    <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="veiculo_vinculo">



                </div>
            </div>
        </div>
    </div>
    <!-- column -->

    <div class="col-lg-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr  ">
                <h2>
                    Veículos Selecionados
                </h2>
                <div class="panel-toolbar">
                    <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                    <button class="btn btn-panel bg-transparent fs-xl w-auto h-auto rounded-0 waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"><i class="fas fa-expand"></i></button>
                    <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="veiculos_vinculados">


                </div>
                <br><div  class=" panel-content border-faded border-left-0 border-right-0 border-bottom-0 ">
                    <button type="button" class="btn btn-success" id="bt_salvar_vinculos"> <i class="fas fa-check"></i> Salvar</button>
                    <a href="#index_xml.php?app_modulo=ponto_interesse&app_comando=listar_ponto_interesse" type="button" class="btn btn-default"> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
                </div>
            </div>

        </div>
    </div>

</div>
