<?php 
include("modulos/usuario/template/js.usuario.php");
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Segurança";
$configTitulo['titulo_modulo'] = "Usuários";
echo $objApp->GerarBreadCrumb($configTitulo);

$configModulo['titulo_card'] = "Listagem Usuários";
$configModulo['id_card'] = "conteudo_usuario";
echo $objApp->GerarCardContainer($configModulo);


