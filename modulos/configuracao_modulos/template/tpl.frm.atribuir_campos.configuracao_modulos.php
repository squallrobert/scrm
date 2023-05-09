<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 09:07
 */
include_once("js.frm.atribuir_campos.php");
?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Configurações</a></li>
    <li class="breadcrumb-item">Configurações de Campos</li>
    <li class="breadcrumb-item active">Vinculo de colunas à Módulos</li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
</ol>

<div class="subheader">
    <h1 class="subheader-title">
        <i class="subheader-icon fal fa-cog"></i> Vínculo de colunas à Módulos
        <small>
            Formulários de Vínculo de colunas à Módulos
        </small>
    </h1>
</div>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr bg-primary-500 bg-info-gradient ">
                <h2>
                    Formulário Vínculo de colunas à Módulos
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="conteudo_configuracao">
                    <form action="#" name="frm_configuracao_relatorios_campos" id="frm_configuracao_relatorios_campos" method="post">
                        <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="modulo">Modulo</label>
                                        <select name="modulo" id="modulo" class="form-control validar-obrigatorio-select-picker select2  "onchange="AtualizarGrids(this.value)" data-live-search="true" >
                                            <?php
                                            $modulos = New Modulo();
                                            $rs = $modulos->ListarCombo();
                                            echo Componente::GerarCombo($rs,'id','nome',$_REQUEST["app_codigo"],'','--Selecione um Módulo--')
                                            ?>
                                        </select>
                                        <small class="form-text text-muted"> Selecione uma módulo </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div id="panel-1" class="panel">
                                        <div class="panel-hdr  ">
                                            <h2>
                                                Colunas Disponíveis
                                            </h2>
                                            <div class="panel-toolbar">
                                                <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                                                <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                                                <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                                            </div>
                                        </div>
                                        <div class="panel-container show" >
                                            <div class="panel-content" id="colunas_vinculo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- column -->

                                <div class="col-lg-6">
                                    <div id="panel-1" class="panel">
                                        <div class="panel-hdr  ">
                                            <h2>
                                                Colunas Selecionados
                                            </h2>
                                            <div class="panel-toolbar">
                                                <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                                                <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                                                <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                                            </div>
                                        </div>
                                        <div class="panel-container show" >
                                            <div class="panel-content" id="colunas_vinculados">


                                            </div>
                                            <br><div  class=" panel-content border-faded border-left-0 border-right-0 border-bottom-0 ">
                                                <button type="button" class="btn btn-success" id="bt_salvar_vinculos"> <i class="fal fa-check"></i> Salvar</button>
                                                <a href="#ajax/configuracao_modulos/listar_configuracao_modulos/" type="button" class="btn btn-default"> <i class="fal fa-arrow-circle-left"></i> Voltar para listagem</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>