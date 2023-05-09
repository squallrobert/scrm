<?php 
include("modulos/cliente_tipo_pessoa/template/js.cliente_tipo_pessoa.php");
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Cliente Tipo Pessoa";
echo $objApp->GerarBreadCrumb($configTitulo);
$configModulo['titulo_card'] = "Listagem Cliente Tipo Pessoa";
$configModulo['id_card'] = "conteudo_cliente_tipo_pessoa";
echo $objApp->GerarCardContainer($configModulo);
