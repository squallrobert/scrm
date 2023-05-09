<?php
if ($linha['id_usuario'] != "") {$_SESSION['id_usuario_temp'] = $linha['id_usuario'];}


$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Cadastro";
$configTitulo['titulo_modulo'] = $titulo_retorno;
echo $objApp->GerarBreadCrumb($configTitulo);

?>
    <!--begin::Image input placeholder-->
    <style>
        .image-input-placeholder {
            background-image: url('assets/media/svg/avatars/blank.svg');
        }

        [data-bs-theme="dark"] .image-input-placeholder {
            background-image: url('assets/media/svg/avatars/blank-dark.svg');
        }
    </style>
    <!--end::Image input placeholder-->
    <div id="kt_app_content_container" class="app-container  p-0">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title"> <?=$titulo_retorno?></h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light ms-3" id="bt_voltar" onclick="history.back()"> <i class="fas fa-arrow-left"></i> Voltar para listagem</button>
                </div>
            </div>
            <div class="card-body" id="formulario_modulos">
                <form action="#" name="frm_usuario" id="frm_usuario" method="post">
                    <input type="password" style="display: none" name="numimporta" id="numimporta">
                    <input type="hidden" value="<?=$linha['id_usuario'];?>" name="id_usuario" id="id_usuario"/>
                    <input type="hidden" value="<?=$linha['id_endereco'];?>" name="id_endereco" id="id_endereco"/>
                    <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>

                    <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1"><i class="fa fa-user"></i>  Pessoal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2"><i class="fa fa-home"></i> Endereco</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                            <div class="row ">
                                <div class="col-md-2 pt-10  ">
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-empty float" data-kt-image-input="true" style="background-image: url(assets/media/svg/avatars/blank.svg)" >
                                        <!--begin::Image preview wrapper-->
                                        <div class="image-input-wrapper w-150px h-150px" style="background-image: url(<?=$linha['foto'];?>)"></div>
                                        <!--end::Image preview wrapper-->

                                        <!--begin::Edit button-->
                                        <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                               data-kt-image-input-action="change"
                                               data-bs-toggle="tooltip"
                                               data-bs-dismiss="click"

                                               title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input type="file" name="foto" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="avatar_remove" />
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Edit button-->

                                        <!--begin::Cancel button-->
                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                              data-kt-image-input-action="cancel"
                                              data-bs-toggle="tooltip"
                                              data-bs-dismiss="click"
                                              title="Cancel avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                        <!--end::Cancel button-->

                                        <!--begin::Remove button-->
                                        <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                              data-kt-image-input-action="remove"
                                              data-bs-toggle="tooltip"
                                              data-bs-dismiss="click"
                                              title="Remove avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <!--end::Remove button-->
                                    </div>
                                    <!--end::Image input-->
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <?php
                                        if($_SESSION['usuario']['tipo'] != 3)
                                        {
                                        ?>
                                        <div class="col-md-6 pb-3 pt-2">
                                            <div class="form-group">
                                                <label class="form-label" for="nome">* Grupo</label>
                                                <select name="id_grupo" id="id_grupo" data-placeholder="Selecione um Grupo" data-style="btn-default btn-outline-default" data-validar="select2"  class=" form-select " >
                                                    <?php
                                                    $objGrupo = New Grupo();
                                                    $preencher = $linha['id_grupo'] != "" ? $linha['id_grupo'] : $_SESSION['usuario']['id_grupo'] ;
                                                    $rs = $objGrupo->ListarCombo($preencher);
                                                    echo Componente::GerarCombo($rs,'id','nome',$preencher,'','Selecione um Grupo')
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 pb-3 pt-2">
                                            <div class="form-group">
                                                <label class="form-label" for="nome">* Tipo de usuário</label>
                                                <?php
                                                $tipo_usuario = new UsuarioTipo();
                                                $registros = $tipo_usuario->ComboTipoUsuario($_SESSION['usuario']['id_usuario_tipo']);
                                                echo Componente::GerarSelectPDO("id_usuario_tipo", "id_usuario_tipo", "", $registros, array($linha['id_usuario_tipo']), array('', ''), array("id", "nome"), false, 'form-select','data-placeholder="Selecione o Tipo de usuário" data-validar="select2"' );
                                                ?>

                                            </div>
                                        </div>
                                            <?php
                                        }
                                            ?>
                                        <div class="col-md-4 pb-3 pt-2">
                                            <div class="form-group">
                                                <label class="form-label" for="nome">* Nome</label>
                                                <input type="text" name="nome"  id="nome" maxlength="100" class="form-control validar-obrigatorio " value="<?=$linha['nome'];?>"/>

                                            </div>
                                        </div>
                                        <div class="col-md-5 pb-3 pt-2">
                                            <div class="form-group">
                                                <label class="form-label" for="email">E-mail (login de acesso)</label>
                                                <input type="text" name="email"  id="email" maxlength="50" class="form-control  validar-email-usuario" value="<?=$linha['email_usuario'];?>"/>
                                            </div>
                                        </div>
                                        <div class="col-md-3 pb-3 pt-2">
                                            <div class="form-group">
                                                <label class="form-label" for="nome">RG</label>
                                                <input type="text" name="rg"  id="rg" maxlength="100" class="form-control validar-obrigatorio " value="<?=$linha['rg'];?>"/>

                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-3 pb-3 ">
                                            <div class="form-group">
                                                <label class="form-label" for="senha">* Senha</label>
                                                <input type="password" name="senha" id="senha" class="form-control  <? if($linha['id_usuario'] == "") echo 'validar-senha'; else echo 'validar-senha2 ';?>"/>

                                            </div>
                                        </div>
                                        <div class="col-md-3 pb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="senha">* Confirmar Senha</label>
                                                <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control validar-confirma-senha"/>

                                            </div>
                                        </div>
                                        <div class="col-md-5 pb-3">
                                            <div class="form-group">
                                                <label class="form-label" for="timezone">Fuso Horário</label>
                                                <?
                                                $arrayFusoHorario = [
                                                    ["id" => "America/Sao_Paulo", "nome" => "ES / GO / MG / PR / RJ / RS / SP / DF / SC"],
                                                    ["id" => "America/Fortaleza", "nome" => "CE / MA / PB / PI / RN"],
                                                    ["id" => "America/Belem", "nome" => "Amapá / Pará"],
                                                    ["id" => "America/Maceio", "nome" => "Sergipe / Alagoas"],
                                                    ["id" => "America/Bahia", "nome" => "Bahia / Tocantins"],
                                                    ["id" => "America/Rio_branco", "nome" => "Acre"],
                                                    ["id" => "America/Manaus", "nome" => "Amazonas"],
                                                    ["id" => "America/Cuiaba", "nome" => "Mato Grosso"],
                                                    ["id" => "America/Campo_Grande", "nome" => "Mato Grosso do Sul"],
                                                    ["id" => "America/Recife", "nome" => "Pernambuco"],
                                                    ["id" => "America/Porto_Velho", "nome" => "Rondônia"],
                                                    ["id" => "America/Boa_Vista", "nome" => "Roraima"],
                                                ];

                                                echo Componente::GerarSelectPDO("usu_timezone", "usu_timezone", "", $arrayFusoHorario, [$linha->timezone], [], ["id", "nome"], false,'form-control select2')
                                                ?>
                                                <!--<input type="text" name="timezone"  id="timezone" maxlength="50" class="form-control  " value="--><?//=$linha['timezone'];?><!--"/>-->
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-2 pt-10" >
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input type="checkbox" <? if ($linha['ativo'] == 1) { echo "checked";} ?> name="ativo" id="ativo" value="1" class="form-check-input" />
                                                <label class="form-check-label" for="ativo">* Ativo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 pt-10" >
                                            <div class="form-check form-check-custom form-check-solid">
                                                <input type="checkbox" <? if ($linha['master'] == 1) { echo "checked";} ?> name="master" id="master" value="1" class="form-check-input" />
                                                <label class="form-check-label" for="master">* Master</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                            <div class="row p-t-20">
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="logradouro">Logradouro</label>
                                        <input type="text" name="logradouro"  id="logradouro" maxlength="255" class="form-control  " value="<?=$linha['logradouro'];?>"/>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-2 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="numero">Numero</label>
                                        <input type="text" name="numero"  id="numero" maxlength="10" class="form-control  " value="<?=$linha['numero'];?>"/>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="complemento">Complemento</label>
                                        <input type="text" name="complemento"  id="complemento" maxlength="50" class="form-control  " value="<?=$linha['complemento'];?>"/>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="bairro">Bairro</label>
                                        <input type="text" name="bairro"  id="bairro" maxlength="255" class="form-control  " value="<?=$linha['bairro'];?>"/>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="cidade">Cidade</label>
                                        <input type="text" name="cidade"  id="cidade" maxlength="150" class="form-control  " value="<?=$linha['cidade'];?>"/>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="estado">Estado</label>
                                        <input type="text" name="estado"  id="estado" maxlength="100" class="form-control  " value="<?=$linha['estado'];?>"/>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3 mb-4">
                                    <label class="form-label" for="cep">Cep</label>
                                    <div class="input-group"   >
                                        <input type="text" name="cep"  id="cep" onblur="BuscarCep(this.value)"  class="form-control  mask-cep" value="<?=$linha['cep'];?>"/>
                                        <span class="input-group-text" >
                                    <i class="fas fa-search"></i>
                                </span>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="referencia">Referencia</label>
                                        <input type="text" name="referencia"  id="referencia"  maxlength="255"  class="form-control  " value="<?=$linha['referencia'];?>"/>
                                    </div>
                                </div>
                                <!--/span-->

                                <div class="col-md-3 mb-4">
                                    <label class="form-label" for="telefone">Telefone</label>
                                    <div class="input-group"   >
                                        <input type="text" name="telefone"  id="telefone"  class="form-control  " value="<?=$linha['telefone'];?>"/>
                                        <span class="input-group-text" >
                                    <i class="fas fa-phone"></i>
                                </span>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3 mb-4">
                                    <label class="form-label" for="cep">Comercial</label>
                                    <div class="input-group"   >
                                        <input type="text" name="comercial"  id="comercial"  class="form-control  " value="<?=$linha['comercial'];?>"/>
                                        <span class="input-group-text" >
                                    <i class="fas fa-phone"></i>
                                </span>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3 mb-4">
                                    <label class="form-label" for="celular">Celular</label>
                                    <div class="input-group"   >
                                        <input type="text" name="celular"  id="celular"  class="form-control  validar-obrigatorio " value="<?=$linha['celular'];?>"/>
                                        <span class="input-group-text" >
                                    <i class="fas fa-mobile"></i>
                                </span>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="email_mkt">Email 2</label>
                                        <input type="text" name="email_mkt"  id="email_mkt" maxlength="150" class="form-control  " value="<?=$linha['email_mkt'];?>"/>

                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="email_mkt2">Email 3</label>
                                        <input type="text" name="email_mkt2"  id="email_mkt2" maxlength="150" class="form-control  " value="<?=$linha['email_mkt2'];?>"/>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div class="form-group">
                                        <label class="form-label" for="observacao">Observação de endereço</label>
                                        <textarea class="form-control  " name="observacao" rows="7"  id="observacao" placeholder="Insira o texto" ><?=$linha['observacao'];?></textarea>

                                    </div>
                                </div>
                                <!--/span-->
                                <!--/span-->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer d-flex flex-row-reverse">
                <button type="button" class="btn btn-success ms-3" id="bt_salvar"> <i class="fas fa-check"></i> Salvar</button>
                <button type="button" class="btn btn-light ms-3" id="bt_voltar" onclick="history.back()"> <i class="fas fa-arrow-left"></i> Voltar para listagem</button>
            </div>
        </div>

    </div>
<?php
include_once("modulos/usuario/template/js.frm.usuario.php");
