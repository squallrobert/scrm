<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Cadastro</a></li>
    <li class="breadcrumb-item">Itens</li>
    <li class="breadcrumb-item active">Grupo Acao</li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
</ol>
<div class="subheader">
    <h1 class="subheader-title">
        <i class="subheader-icon fas fa-times-circle"></i> Grupo Acao
        <small>
            Cadastro de  Grupo Acao
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr bg-primary-500 bg-info-gradient">
                <h2>
                    Grupo Acao
                </h2>
                <div class="panel-toolbar">
                    <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                    <button class="btn btn-panel bg-transparent fs-xl w-auto h-auto rounded-0 waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"><i class="fas fa-expand"></i></button>
                    <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="conteudo_chip">
                    <form action="#" name="frm_grupo_acao" id="frm_grupo_acao" method="post">
                        <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-12 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="id_grupo">*Id Grupo</label>
                                        <input type="text" name="id_grupo"  id="id_grupo" maxlength="10" class="form-control validar-obrigatorio mask-numero" value="<?=$linha['id_grupo'];?>"/>
                                        <small class="form-text text-muted"> Preencha o campo  Id Grupo </small> </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="id_acao">*Id Acao</label>
                                        <input type="text" name="id_acao"  id="id_acao" maxlength="10" class="form-control validar-obrigatorio mask-numero" value="<?=$linha['id_acao'];?>"/>
                                        <small class="form-text text-muted"> Preencha o campo  Id Acao </small> </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-12 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="customizada">*Customizada</label>
                                        <input type="text" name="customizada"  id="customizada" maxlength="1" class="form-control validar-obrigatorio mask-numero" value="<?=$linha['customizada'];?>"/>
                                        <small class="form-text text-muted"> Preencha o campo  Customizada </small> </div>
                                </div>
                                <!--/span-->

                            </div>
                            <br><div  class=" panel-content border-faded border-left-0 border-right-0 border-bottom-0 ">
                                <button type="button" class="btn btn-success" id="bt_salvar"> <i class="fas fa-check"></i> Salvar</button>
                                <a href="#index_xml.php?app_modulo=grupo_acao&app_comando=listar_grupo_acao" type="button" class="btn btn-default"> <i class="fas fa-arrow-circle-left"></i> Voltar para Listagem</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row -->

<?
include_once("modulos/grupo_acao/template/js.frm.grupo_acao.php");
?>
