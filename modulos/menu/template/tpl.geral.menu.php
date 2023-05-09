<script src="modulos/menu/template/js.menu.js"></script>
<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Menus";
echo $objApp->GerarBreadCrumb($configTitulo);

$configModulo['titulo_card'] = "Listagem Menus";
$configModulo['id_card'] = "conteudo_menu";
echo $objApp->GerarCardContainer($configModulo);


