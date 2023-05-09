<?php 
include("modulos/cliente_estado_civil/template/js.cliente_estado_civil.php");
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Cliente Estado Civil";
echo $objApp->GerarBreadCrumb($configTitulo);
$configModulo['titulo_card'] = "Listagem Cliente Estado Civil";
$configModulo['id_card'] = "conteudo_cliente_estado_civil";
echo $objApp->GerarCardContainer($configModulo);
