<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:05
 */
include("js.configuracao_modulos.php");
?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Configurações</a></li>
    <li class="breadcrumb-item">Configurações de Módulos</li>
    <li class="breadcrumb-item active">Listagem</li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
</ol>

<div class="subheader">
    <h1 class="subheader-title">
        <i class="subheader-icon fal fa-cogs"></i> Configurações de Módulos
<small>
            Listagem de campos e módulos configurados
</small>
    </h1>
</div>
<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr  ">
                <h2>Listagem de Campos</h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="conteudo_configuracao_campos">

                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr  ">
                <h2>Listagem de Módulos</h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="conteudo_configuracao_relatorios_campos">
                </div>
            </div>
        </div>
    </div>
</div>
