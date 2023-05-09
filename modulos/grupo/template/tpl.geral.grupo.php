<script src="modulos/grupo/template/js.grupo.js"></script>
<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Segurança";
$configTitulo['titulo_modulo'] = "Grupos";
echo $objApp->GerarBreadCrumb($configTitulo);

$configModulo['titulo_card'] = "Listagem de Grupos";
$configModulo['id_card'] = "conteudo_grupo";
echo $objApp->GerarCardContainer($configModulo);

