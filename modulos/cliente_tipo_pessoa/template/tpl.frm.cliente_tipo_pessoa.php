<?php 
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Cliente Tipo Pessoa";
echo $objApp->GerarBreadCrumb($configTitulo);
?>
<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Formulário Cliente Tipo Pessoa</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=cliente_tipo_pessoa&app_comando=listar_cliente_tipo_pessoa'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="formulario_cliente_tipo_pessoa">
                <form action="#" name="frm_cliente_tipo_pessoa" id="frm_cliente_tipo_pessoa" method="post">
                <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                      <label class="form-label" for="rotulo">*Rotulo</label>
			<input type="text" name="rotulo"  id="rotulo" maxlength="50" class="form-control validar-obrigatorio " value="<?=$linha['rotulo'];?>"/>
                                    <small class="form-text text-muted"> Preencha o campo  Rotulo </small> </div>
                            </div>
                            <!--/span-->
                            </div>
                    </form>
        
         </div>
        <div class="card-footer d-flex flex-row-reverse">
            <button type="button" class="btn btn-success ms-3" id="bt_salvar"> <i class="fas fa-check"></i> Salvar</button>
            <button type="button" class="btn btn-light " id="bt_voltar"> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</button>
        </div>
    </div>
</div>

<?
include_once("modulos/cliente_tipo_pessoa/template/js.frm.cliente_tipo_pessoa.php");
?>
