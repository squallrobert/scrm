<script src="modulos/acao/template/js.acao.js?v=2"></script>
<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Acões do Sistema";
echo $objApp->GerarBreadCrumb($configTitulo);

$configModulo['titulo_card'] = "Cadastro de Ações";
$configModulo['id_card'] = "conteudo_acao";
echo $objApp->GerarCardContainer($configModulo);

