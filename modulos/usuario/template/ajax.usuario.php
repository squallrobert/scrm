<?
switch($app_comando) {
	case "":
		break;
    case "popup_localizar_usuarios":
        $objUsuario = new Usuario;
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];
        echo json_encode($objUsuario->BuscarUsuarios($busca));
        break;
    case "popup_localizar_usuarios_mapas":
        $objUsuario = new Usuario();
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];
        echo json_encode($objUsuario->listarUsuariosFotografos($busca));
        break;
    case "popup_localizar_usuarios_corretores":
        $objUsuario = new Usuario();
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];
        echo json_encode($objUsuario->listarUsuariosCorretores($_SESSION['usuario']['id_grupo'], $busca));
        break;
}
