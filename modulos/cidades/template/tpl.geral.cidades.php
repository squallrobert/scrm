<?php 
include("modulos/cidades/template/js.cidades.php");
?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Cadastro</a></li>
    <li class="breadcrumb-item">Estoque</li>
    <li class="breadcrumb-item active">Cidades</li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
</ol>
<div class="subheader">
    <h1 class="subheader-title">
        <i class="subheader-icon fas fa-mobile-android-alt"></i> Cidades
        <small>
            Listagem de todos os Cidades .
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr  ">
                <h2>
                    Listagem de Cidades 
                </h2>
                <div class="panel-toolbar">
                    <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                    <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="conteudo_cidades">
                </div>
            </div>
        </div>
    </div>
</div>
