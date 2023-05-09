<?php

switch($app_comando) {
    case "frm_calendario":
        $template = "tpl.geral.calendario.php";
        break;
    case "listar_itens_calendario":
        $objCalendario= new Calendario();

        $parametros['busca'] = $_REQUEST['busca'];
        $parametros['data_hora_inicio'] = $_REQUEST['data_hora_inicio'];
        $parametros['data_hora_fim'] = $_REQUEST['data_hora_fim'];
        $parametros['fotografo'] = ($_SESSION['usuario']['id_usuario_tipo'] == 2) ? $_SESSION['usuario']['id'] : $_REQUEST['fotografo'];
        $parametros['status'] = $_REQUEST['status'];
        $parametros['cancelados'] = ($_SESSION['usuario']['id_usuario_tipo'] == 2 && $_SESSION['usuario']['id_usuario_tipo'] == 3) ? true : false;

        $resultados = $objCalendario->ListarEventos($_SESSION['usuario']['id_grupo'],$parametros);
        echo json_encode($resultados);
        $template = "ajax.calendario.php";
        break;
}