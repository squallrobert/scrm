<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:24
 */
include_once("js.frm.configuracao_modulos.php");
?>
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-container show" >
                <div class="panel-content" id="sla">
                    <form action="#" name="conteudo_config" id="conteudo_config" method="post">
                        <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                        <div class="form-body">
                            <div class="row p-t-20">
                                <div class="col-md-6 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="id_rastreador_status_tipo">Nome:</label>
                                        <input type="text" id="nome" name="nome" placeholder="Nome" value="<?=$linha['nome']?>" class="form-control validar-obrigatorio"/>
                                        <small class="form-text text-muted"> Preencha o campo  nome </small>
                                    </div>
                                </div>
                                <div class="col-md-6 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="id_rastreador_status_tipo">Valor:</label>
                                        <input type="text" id="valor" placeholder="Valor" name="valor" value="<?=$linha['valor']?>" class="form-control"/>
                                        <small class="form-text text-muted"> Preencha o campo  valor </small>
                                    </div>
                                </div>
                                <div class="col-md-6 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="id_rastreador_status_tipo">Name/id (html):</label>
                                        <input type="text" id="name_id" name="name_id" placeholder="Name/id (html)" value="<?=$linha['name_id']?>" class="form-control validar-obrigatorio"/>
                                        <small class="form-text text-muted"> Preencha o campo  name/id </small>
                                    </div>
                                </div>
                                <div class="col-md-6 pt-2">
                                    <div class="form-group">
                                        <label class="form-label" for="id_rastreador_status_tipo">Classe (html):</label>
                                        <input type="text" id="classe" name="classe" placeholder="Classe" value="<?=$linha['classe']?>" class="form-control"/>
                                        <small class="form-text text-muted"> Preencha o campo  placa </small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pt-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="padrao" id="padrao" <? if ($linha['padrao'] == 1) echo "checked" ?>>
                                        <label class="custom-form-label" id="textoPadrao" for="padrao">Habilitado</label>
                                    </div>
                                </div>
                                <div class="col-md-6 pt-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" name="obrigatorio"  <? if ($linha['obrigatorio'] == 1) echo "checked" ?> id="obrigatorio">
                                        <label class="custom-form-label" id="textoObrigatorio" for="obrigatorio">Obrigatório</label>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>