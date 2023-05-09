<?php 
include("modulos/usuario_tipo/template/js.usuario_tipo.php");
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Usuario Tipo";
echo $objApp->GerarBreadCrumb($configTitulo);
$configModulo['titulo_card'] = "Listagem Tipos de usuários";
$configModulo['id_card'] = "conteudo_usuario_tipo";
echo $objApp->GerarCardContainer($configModulo);
