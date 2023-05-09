<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Cadastro</a></li>
    <li class="breadcrumb-item">Itens</li>
    <li class="breadcrumb-item active">Cidades</li>
    <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
</ol>
<div class="subheader">
    <h1 class="subheader-title">
        <i class="subheader-icon fas fa-times-circle"></i> Cidades
        <small>
            Cadastro de  Cidades
        </small>
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr bg-primary-500 bg-info-gradient">
                <h2>
                    Cidades
                </h2>
                <div class="panel-toolbar">
                    <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                    <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                    <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                </div>
            </div>
            <div class="panel-container show" >
                <div class="panel-content" id="conteudo_chip">
                    <form action="#" name="frm_cidades" id="frm_cidades" method="post">
                        <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-4 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="id_estado">* Unidade Federativa</label>
                                        <?
                                        $estado    = new Uf();
                                        $registros = $estado->ComboUf();
                                        echo Componente::GerarSelectPDO("id_estado", "id_estado", "", $registros, array($linha['id_estado']), array('','Selecione um Unidade Federativa'), array("id", "nome"), false,'form-control select2 m-b-20 m-r-10 validar-obrigatorio-select2');
                                        ?>
                                        <small class="form-text text-muted"> Selecione uma Unidade Federativa </small>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-8 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="nome">*Nome</label>
                                        <input type="text" name="nome"  id="nome" maxlength="80" class="form-control validar-obrigatorio " value="<?=$linha['nome'];?>"/>
                                        <small class="form-text text-muted"> Preencha o campo  Nome </small> </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-8 pt-4">
                                    <button type="button" class="btn btn-success" id="bt_salvar"> <i class="fas fa-check"></i> Salvar</button>
                                    <a href="#index_xml.php?app_modulo=cidades&app_comando=listar_cidades" type="button" class="btn btn-default"> <i class="fas fa-arrow-circle-left"></i> Voltar para Listagem</a>
                                </div>

                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Row -->

<?
include_once("modulos/cidades/template/js.frm.cidades.php");
?>
