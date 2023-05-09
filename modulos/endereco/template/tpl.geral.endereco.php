<?php 
include("modulos/endereco/template/js.endereco.php");
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Endereco";
echo $objApp->GerarBreadCrumb($configTitulo);
$configModulo['titulo_card'] = "Listagem Endereco";
$configModulo['id_card'] = "conteudo_endereco";
echo $objApp->GerarCardContainer($configModulo);
