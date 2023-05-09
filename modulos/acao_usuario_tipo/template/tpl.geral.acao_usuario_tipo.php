<?php 
include("modulos/acao_usuario_tipo/template/js.acao_usuario_tipo.php");
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Permissões por tipo de usuário";
echo $objApp->GerarBreadCrumb($configTitulo);
$configModulo['titulo_card'] = "Listagem Permissões por tipo de usuário";
$configModulo['id_card'] = "conteudo_acao_usuario_tipo";
echo $objApp->GerarCardContainer($configModulo);
