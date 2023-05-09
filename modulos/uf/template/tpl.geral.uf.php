<?php 
include("modulos/uf/template/js.uf.php");
$form = new GerarForm();
$form->breadcrumb =  [["class" => "breadcrumb-item" , "text" => "Configurações"],["class" => "breadcrumb-item" , "text" => "Gerais"],["class" => "breadcrumb-item active" , "text" => "Unidade Federativa (UF)","href" => "javascript:void(0);"]];
$form->titulo = "Estados (UF)";
$form->icone_titulo = "fa fa-globe";
$form->descricao_titulo = "Cadastro de Unidade Federativa (UF)";
$form->div_resultado = ["class" => "col-xl-12","id" => "conteudo_uf","titulo" => "Listagem de Unidade Federativa (UF)"];
echo $form->GerarBreadCrumb();
echo $form->GerarHeader();
echo $form->GerarDivResultado();

