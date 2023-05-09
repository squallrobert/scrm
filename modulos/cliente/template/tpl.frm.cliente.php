<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Cliente";
echo $objApp->GerarBreadCrumb($configTitulo);
?>
<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Formulário Cliente</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=cliente&app_comando=listar_cliente'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="formulario_cliente">
            <form action="#" name="frm_cliente" id="frm_cliente" method="post">
                <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                <input type="hidden" name="id_endereco"  id="id_endereco"   value="<?=$linha['id_endereco'];?>"/>
                <input type="hidden" name="id_endereco_cobranca"  id="id_endereco_cobranca"   value="<?=$linha['id_endereco_cobranca'];?>"/>


                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_7"><i class="fas fa-user"></i> Dados do Cliente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_8"><i class="fas fa-address-card"></i> Endereço</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_9" ><i class="fas fa-address-card"></i> Endereço de cobrança</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_tab_pane_7" role="tabpanel">
                        <div class="row p-t-20">
                            <div class="col-md-2 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="nome">* Tipo Pessoa</label>
                                    <?php
                                    $tipo_pessoa      = new ClienteTipoPessoa();
                                    $registros = $tipo_pessoa->ComboTipoPessoa();
                                    $outros = 'data-placeholder="Selecione o Tipo Pessoa" data-validar="select2" onchange = "MudarForm(this.value)"';
                                    echo Componente::GerarSelectPDO("id_cliente_tipo_pessoa", "id_cliente_tipo_pessoa", "", $registros, array($linha['id_cliente_tipo_pessoa']), null, array("id", "rotulo"), false, 'form-select',$outros);
                                    ?>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-5 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="nome">* Nome/Razão Social</label>
                                    <input type="text" name="nome"  id="nome" maxlength="100" class="form-control validar-obrigatorio " value="<?=$linha['nome'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-5 mb-4 pj">
                                <div class="form-group">
                                    <label class="form-label" for="nome_fantasia">Nome Fantasia</label>
                                    <input type="text" name="nome_fantasia"  id="nome_fantasia" maxlength="200" class="form-control  " value="<?=$linha['nome_fantasia'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-5 pb-2 pf" id="div_cpf">
                                <div class="form-group">
                                    <label class="form-label" for="cpf">* CPF </label>
                                    <input type="text" name="cpf" id="cpf" maxlength="20" class="form-control mask-cpf validar-cpf pf-input" value="<?php echo $linha['cpf_cnpj']; ?>" />
                                </div>
                            </div>
                            <div class="col-md-3 pb-2 pj" id="div_cnpj">
                                <div class="form-group">
                                    <label class="form-label" for="cnpj">* CNPJ </label>
                                    <input type="text" name="cnpj" id="cnpj" maxlength="20" class="form-control validar-cnpj mask-cnpj pj-input" value="<?php echo $linha['cpf_cnpj']; ?>" />
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3 pj mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="inscricao_estadual">Inscricao Estadual</label>
                                    <input type="text" name="inscricao_estadual"  id="inscricao_estadual" maxlength="20" class="form-control  " value="<?=$linha['inscricao_estadual'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 pf mb-4">
                                <label class="form-label" for="data_nascimento">* Data de Nascimento</label>
                                <div class="input-group" id="kt_td_picker_simple" data-td-target-input="nearest" data-td-target-toggle="nearest" >
                                    <input name="data_nascimento" id="data_nascimento" value="<?php echo Conexao::PrepararDataPHP($linha['data_nascimento']); ?>"  type="text" class="form-control singledate mask-data  pf-input" data-td-target="#kt_td_picker_basic"/>
                                    <span class="input-group-text" data-td-target="#kt_td_picker_basic" data-td-toggle="datetimepicker">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 pf mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="id_cliente_estado_civil">* Estado Civil</label>
                                    <?php
                                    $estado_civil      = new ClienteEstadoCivil();
                                    $registros = $estado_civil->ComboEstadoCivil();
                                    echo Componente::GerarSelectPDO("id_cliente_estado_civil", "id_cliente_estado_civil", "", $registros, array($linha['id_cliente_estado_civil']), null, array("id", "rotulo"), false, 'form-select pf-select','data-placeholder="Selecione o estado civil" data-validar="select2"');
                                    ?>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 pf mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="sexo">* Sexo</label>
                                    <select name="sexo" id="sexo" data-placeholder="Selecione o sexo" data-validar="select2"   class="form-select  pf-select" style="width: 100%">
                                        <option value="" disabled selected>Selecione</option>
                                        <option value="M" <?php echo ($linha['sexo'] == "M") ? "selected" : ""; ?>>Masculino </option>
                                        <option value="F" <?php echo ($linha['sexo'] == "F") ? "selected" : "" ?>>Feminino </option>
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-2 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="dia_vencimento">Dia Vencimento</label>
                                    <input type="text" name="dia_vencimento"  id="dia_vencimento" maxlength="" class="form-control  mask-numero validar-obrigatorio" value="<?=$linha['dia_vencimento'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="nome">* Forma Pagamento</label>
                                    <?php
                                    $forma_pagamento = new FormaPagamento();
                                    $registros = $forma_pagamento->ComboFormaPagamento();
                                    echo Componente::GerarSelectPDO("id_forma_pagamento", "id_forma_pagamento", "", $registros, array($linha['id_forma_pagamento']), array('', ''), array("id", "rotulo"), false, 'form-select','data-placeholder="Selecione a forma de pagamento" data-validar="select2"' );
                                    ?>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 pf mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="rg">Rg</label>
                                    <input type="text" name="rg"  id="rg" maxlength="20" class="form-control  " value="<?=$linha['rg'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-2  pt-10">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox" <? if ($linha['status'] == 1) { echo "checked";} ?> name="ativo" id="ativo" value="1" class="form-check-input " />
                                    <label class="form-check-label" for="ativo"> Ativo</label>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-6">
                            <div class="col-md-2 mb-4 pt-10">
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
                            <div class="col-md-10 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="observacao_dados">Observacao Dados</label>
                                    <textarea class="form-control  " name="observacao_dados" rows="7"  id="observacao_dados" placeholder="Insira o texto" ><?=$linha['observacao_dados'];?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_8" role="tabpanel">
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
                                    <input type="text" name="cidade"  id="cidade" maxlength="150" class="form-control validar-obrigatorio " value="<?=$linha['cidade'];?>"/>
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
                                    <input type="text" name="cep"  id="cep" onblur="BuscarCep(this.value)"  class="form-control  mask-cep validar-obrigatorio" value="<?=$linha['cep'];?>"/>
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
                                    <input type="text" name="celular"  id="celular"  class="form-control  " value="<?=$linha['celular'];?>"/>
                                    <span class="input-group-text" >
                                    <i class="fas fa-mobile"></i>
                                </span>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="text" name="email"  id="email" maxlength="150" class="form-control validar-email " value="<?=$linha['email'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="email_mkt">Email Marketing</label>
                                    <input type="text" name="email_mkt"  id="email_mkt" maxlength="150" class="form-control  " value="<?=$linha['email_mkt'];?>"/>

                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="email_mkt2">Email Marketing 2</label>
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
                    <div class="tab-pane fade" id="kt_tab_pane_9" role="tabpanel">
                        <div class="row p-t-20">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="logradouro">Logradouro</label>
                                <div class="input-group">
                                    <input type="text" name="c_logradouro"  id="c_logradouro" maxlength="255" class="form-control  " value="<?=$linha['c_logradouro'];?>"/>
                                    <span class="input-group-text cursor-pointer" onclick="CopiarEndereco()"  >
                                    <i class="fas fa-copy " ></i> &nbsp; Copiar de Endereço
                                </span>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-2 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="numero">Numero</label>
                                    <input type="text" name="c_numero"  id="c_numero" maxlength="10" class="form-control  " value="<?=$linha['c_numero'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="complemento">Complemento</label>
                                    <input type="text" name="c_complemento"  id="c_complemento" maxlength="50" class="form-control  " value="<?=$linha['c_complemento'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="bairro">Bairro</label>
                                    <input type="text" name="c_bairro"  id="c_bairro" maxlength="255" class="form-control  " value="<?=$linha['c_bairro'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="cidade">Cidade</label>
                                    <input type="text" name="c_cidade"  id="c_cidade" maxlength="150" class="form-control  " value="<?=$linha['c_cidade'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="estado">Estado</label>
                                    <input type="text" name="c_estado"  id="c_estado" maxlength="100" class="form-control  " value="<?=$linha['c_estado'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3 mb-4">
                                <label class="form-label" for="cep">Cep</label>
                                <div class="input-group"   >
                                    <input type="text" name="c_cep"  id="c_cep" onblur="BuscarCep(this.value,2)"  class="form-control  mask-cep" value="<?=$linha['c_cep'];?>"/>
                                    <span class="input-group-text" >
                                    <i class="fas fa-search"></i>
                                </span>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="referencia">Referencia</label>
                                    <input type="text" name="c_referencia"  id="c_referencia"  maxlength="255"  class="form-control  " value="<?=$linha['c_referencia'];?>"/>
                                </div>
                            </div>
                            <!--/span-->

                            <div class="col-md-3 mb-4">
                                <label class="form-label" for="telefone">Telefone</label>
                                <div class="input-group"   >
                                    <input type="text" name="c_telefone"  id="c_telefone"  class="form-control  " value="<?=$linha['c_telefone'];?>"/>
                                    <span class="input-group-text" >
                                    <i class="fas fa-phone"></i>
                                </span>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3 mb-4">
                                <label class="form-label" for="cep">Comercial</label>
                                <div class="input-group"   >
                                    <input type="text" name="c_comercial"  id="c_comercial"  class="form-control  " value="<?=$linha['c_comercial'];?>"/>
                                    <span class="input-group-text" >
                                    <i class="fas fa-phone"></i>
                                </span>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3 mb-4">
                                <label class="form-label" for="celular">Celular</label>
                                <div class="input-group"   >
                                    <input type="text" name="c_celular"  id="c_celular"  class="form-control  " value="<?=$linha['c_celular'];?>"/>
                                    <span class="input-group-text" >
                                    <i class="fas fa-mobile"></i>
                                </span>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="text" name="c_email"  id="c_email" maxlength="150" class="form-control  " value="<?=$linha['c_email'];?>"/>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="email_mkt">Email Marketing</label>
                                    <input type="text" name="c_email_mkt"  id="c_email_mkt" maxlength="150" class="form-control  " value="<?=$linha['c_email_mkt'];?>"/>

                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="email_mkt2">Email Marketing 2</label>
                                    <input type="text" name="c_email_mkt2"  id="c_email_mkt2" maxlength="150" class="form-control  " value="<?=$linha['c_email_mkt2'];?>"/>
                                </div>
                            </div>
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="observacao">Observação de endereço</label>
                                    <textarea class="form-control  " name="c_observacao" rows="7"  id="c_observacao" placeholder="Insira o texto" ><?=$linha['c_observacao'];?></textarea>

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
            <a href='#index_xml.php?app_modulo=cliente&app_comando=listar_cliente'  class="btn  btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
        </div>
    </div>
</div>

<?
include_once("modulos/cliente/template/js.frm.cliente.php");
?>
