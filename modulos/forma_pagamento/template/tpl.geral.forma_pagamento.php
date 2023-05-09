<?php 
include("modulos/forma_pagamento/template/js.forma_pagamento.php");
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Forma Pagamento";
echo $objApp->GerarBreadCrumb($configTitulo);
$configModulo['titulo_card'] = "Listagem Forma Pagamento";
$configModulo['id_card'] = "conteudo_forma_pagamento";
echo $objApp->GerarCardContainer($configModulo);
