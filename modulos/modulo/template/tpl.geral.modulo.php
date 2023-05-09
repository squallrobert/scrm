<script src="modulos/modulo/template/js.modulo.js"></script>
<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Módulos";
echo $objApp->GerarBreadCrumb($configTitulo);

$configModulo['titulo_card'] = "Listagem Módulos";
$configModulo['id_card'] = "conteudo_modulo";
echo $objApp->GerarCardContainer($configModulo);





