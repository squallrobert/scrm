<?
switch($app_comando) {
	case "filtrar_uf":
	    $uf = new Uf();
        $listar = $uf->ListarTodosUf($_REQUEST['term']);
        echo json_encode($listar);
		break;
}
