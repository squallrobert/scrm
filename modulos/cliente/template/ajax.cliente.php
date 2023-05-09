<?php
switch($app_comando) {
    case "popup_localizar_clientes":
        $objCliente = new Cliente();
        $busca = $_REQUEST['term'] ?: $_REQUEST['buscar'] ?: $_REQUEST['busca'];
        echo json_encode($objCliente->BuscarClientes($_SESSION['usuario']['id_grupo'], $busca));
        break;

}