<?php
$form = new GerarForm();
$form->breadcrumb =  [
    ["class" => "breadcrumb-item" , "text" => "Configurações"]
    ,["class" => "breadcrumb-item" , "text" => "Gerais"]
    ,["class" => "breadcrumb-item active" , "text" => "Unidade Federativa (UF)","href" => "javascript:void(0);"]
];
$form->titulo = "Unidade Federativa (UF)";
$form->icone_titulo = "fa fa-globe";
$form->descricao_titulo = "Cadastro de Unidade Federativa (UF)";

$form->id_div_form = "uf_formulario";
$form->form = ["name" => "frm_uf","id" => "frm_uf", "method" => "post"];

$regiao     = new RegiaoUf();
$registros = $regiao->ComboRegiao();
$campos[] = ["parametros"=> ["type" =>"hidden","name" => "id", "id" => "id","value" => $linha['id']]];
$campos[] = ["class_col_md" => "col-md-6 pt-2","nome" => "Select", "selecionados" =>[$linha['id_regiao_uf']], "descricao" => "Selecione uma região","parametros"=> ["type" =>"select","name" => "id_regiao_uf", "id" => "id_regiao_uf","class" => "form-control select2 m-b-20 m-r-10 validar-obrigatorio-select2"],"options" => $registros];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Nome", "descricao" => "Preencha o campo nome", "parametros"=> ["type" =>"text","name" => "nome", "id" => "nome","value" => $linha['nome'],"class" => "form-control validar-obrigatorio"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Sigla", "descricao" => "Preencha o campo Sigla", "parametros"=> ["type" =>"text","name" => "sigla","maxlength" => "2", "id" => "sigla","value" => $linha['sigla'],"class" => "form-control validar-obrigatorio"]];

$buttons[] = ["rotulo" => "Salvar", "icone" => "fa fa-check", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-success","type" => "button"]];
$buttons[] = ["rotulo" => "Voltar para Listagem", "icone" => "fa fa-arrow-left", "parametros"=> [ "id" => "bt_voltar","class" => "btn btn-default","type" => "button"]];

$form->campos = $campos;
$form->buttons = $buttons;
echo $form->GerarFormulario();

include_once("modulos/uf/template/js.frm.uf.php");

