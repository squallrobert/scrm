<?php
// fazendo incluzão de globais e constantes  de configurações do sistema.
include_once("includes/config.inc.php");
// tratamento de tudo que chega por POST E GET E REQUEST
Utils::TratarRequest();
$objAPP = new App();
$objAPP->layout_show = false;
$objAPP->modulo = $_REQUEST['app_modulo'];
$objAPP->acao = $_REQUEST['app_comando'];
$objAPP->codigo = $_REQUEST['app_codigo'];
$objAPP->sessao = $_SESSION;
$objAPP->ExecutarModulo();

$objLogAcesso = new LogAcesso(1);
$objLogAcesso->Gravar($_REQUEST['app_comando']);


