<?
switch($app_comando) {
    case "filtrar_cidade":
        $cidade = new Cidades();
        $cidade->setIdEstado($app_codigo);
        $listar = $cidade->ComboCidade();
        echo json_encode($listar);
        break;
    case "filtrar_cidade_id":
        $cidade = new Cidades();
        $cidade->setIdEstado($app_codigo);
        $listar = $cidade->CidadeId($_REQUEST['cidade']);
        echo json_encode($listar);
        break;
}
