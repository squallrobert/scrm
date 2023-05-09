<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Endereco";
echo $objApp->GerarBreadCrumb($configTitulo);
?>
<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Formulário Endereco</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=endereco&app_comando=listar_endereco'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="formulario_endereco">
            <form action="#" name="frm_endereco" id="frm_endereco" method="post">
                <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="logradouro">Logradouro</label>
                                <input type="text" name="logradouro"  id="logradouro" maxlength="255" class="form-control  " value="<?=$linha['logradouro'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Logradouro </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="numero">Numero</label>
                                <input type="text" name="numero"  id="numero" maxlength="10" class="form-control  " value="<?=$linha['numero'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Numero </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="complemento">Complemento</label>
                                <input type="text" name="complemento"  id="complemento" maxlength="50" class="form-control  " value="<?=$linha['complemento'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Complemento </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="bairro">Bairro</label>
                                <input type="text" name="bairro"  id="bairro" maxlength="255" class="form-control  " value="<?=$linha['bairro'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Bairro </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="cidade">Cidade</label>
                                <input type="text" name="cidade"  id="cidade" maxlength="150" class="form-control  " value="<?=$linha['cidade'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Cidade </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="estado">Estado</label>
                                <input type="text" name="estado"  id="estado" maxlength="100" class="form-control  " value="<?=$linha['estado'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Estado </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="cep">Cep</label>
                                <input type="text" name="cep"  id="cep"  class="form-control  " value="<?=$linha['cep'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Cep </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="referencia">Referencia</label>
                                <input type="text" name="referencia"  id="referencia" maxlength="255" class="form-control  " value="<?=$linha['referencia'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Referencia </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="observacao">Observacao</label>
                                <textarea class="form-control  " name="observacao"  id="observacao" placeholder="Insira o texto" ><?=$linha['observacao'];?></textarea>
                                <small class="form-text text-muted"> Preencha o campo  Observacao </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="telefone">Telefone</label>
                                <input type="text" name="telefone"  id="telefone" maxlength="15" class="form-control  " value="<?=$linha['telefone'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Telefone </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="comercial">Comercial</label>
                                <input type="text" name="comercial"  id="comercial" maxlength="20" class="form-control  " value="<?=$linha['comercial'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Comercial </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="celular">Celular</label>
                                <input type="text" name="celular"  id="celular" maxlength="20" class="form-control  " value="<?=$linha['celular'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Celular </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <input type="text" name="email"  id="email" maxlength="150" class="form-control  " value="<?=$linha['email'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Email </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="email_mkt">Email Mkt</label>
                                <input type="text" name="email_mkt"  id="email_mkt" maxlength="150" class="form-control  " value="<?=$linha['email_mkt'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Email Mkt </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="email_mkt2">Email Mkt2</label>
                                <input type="text" name="email_mkt2"  id="email_mkt2" maxlength="150" class="form-control  " value="<?=$linha['email_mkt2'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Email Mkt2 </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="latitude">Latitude</label>
                                <input type="text" name="latitude"  id="latitude"  class="form-control  " value="<?=$linha['latitude'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Latitude </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label" for="longitude">Longitude</label>
                                <input type="text" name="longitude"  id="longitude"  class="form-control  " value="<?=$linha['longitude'];?>"/>
                                <small class="form-text text-muted"> Preencha o campo  Longitude </small> </div>
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
include_once("modulos/endereco/template/js.frm.endereco.php");
?>
