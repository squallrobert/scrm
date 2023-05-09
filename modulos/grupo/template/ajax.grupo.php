<?php

/**
 * @author    Squall
 * @copyright 2019
 */

switch ($app_comando) {
    case "popup_localizar_grupo_permissoes":
        $grupo = new Grupo;
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];
        $usuario = new Usuario();
        $usuario->setId($_REQUEST['id_usuario']);
        $row = $usuario->Editar();
        echo json_encode($grupo->ListarJsonGrupo($row['id_grupo'], $busca, $_REQUEST['omitir']));
        break;
    case "popup_localizar_grupo":
        $grupo = new Grupo;
        //todas as variacoes de busca? ...
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];
        if ($_REQUEST['mselect'])
            echo json_encode($grupo->ListarJsonGrupoMultiSelect($_SESSION['usuario']['id_grupo'], $busca, $_REQUEST['omitir']));
        else
            echo json_encode($grupo->ListarJsonGrupo($_SESSION['usuario']['id_grupo'], $busca, $_REQUEST['omitir']));
        break;
    case "popup_localizar_grupo_franqueado":
        $grupo = new Grupo;
        //todas as variacoes de busca? ...
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];

        echo json_encode($grupo->ListarJsonGrupoFranquias($_SESSION['usuario']['id_grupo'], $busca, $_REQUEST['omitir']));

        break;

    case "popup_localizar_grupo_franqueado_franqueadora":
        $grupo = new Grupo;

        //todas as variacoes de busca? ...
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];

        echo json_encode($grupo->ListarJsonGrupoFranquias($_SESSION['usuario']['id_grupo'], $busca, $_REQUEST['omitir'], true));

        break;
    case "popup_localizar_grupo_franqueado_todos":
        $grupo = new Grupo;

        //todas as variacoes de busca? ...
        $busca = $_REQUEST['term']?:$_REQUEST['buscar']?:$_REQUEST['busca'];

        echo json_encode($grupo->ListarJsonGrupoFranquias('', $busca, $_REQUEST['omitir']));

        break;

    case "veiculos_ratreadores_grupo":
        $grupo = new Grupo();
        echo json_encode($grupo->ListaRastreadoresVeiculosCombo($_REQUEST['id_grupo']?:$_SESSION['usuario']['id_grupo'], $_SESSION['usuario']['id_grupo'], $_REQUEST['ocultar']?0:1, $_REQUEST['selecionados'], $_REQUEST['buscar']));
        break;

}
