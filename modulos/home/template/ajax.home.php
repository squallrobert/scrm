<?
//setlocale(LC_TIME, 'portuguese');
//setlocale(LC_ALL, 'pt_BR');
switch($app_comando) {
    case "atualizar_contadores":
        $home = new Home();
        $contadores = $home->ContadorOcorrencias($_SESSION['usuario']['id_grupo']);
        echo json_encode($contadores);
        break;
    case "ocorrencias_barrassss":
        $home = new Home();
        $contadores = $home->ContadoresBarra($_SESSION['usuario']['id_grupo']);
        Conexao::pr($contadores);
        echo json_encode($contadores);
        break;
    case "ocorrencias_barra_dia":
        $home = new Home();
        $contadores = $home->ContadoresBarraDia($_SESSION['usuario']['id_grupo']);
        echo json_encode($contadores);
        break;
    case "ocorrencias_barras":
        $home = new Home();
        $d = $home->OccorenciasMes($_SESSION['usuario']['id_grupo']);
        echo json_encode($d);
        break;
    case "ocorrencias_pizza":
        $home = new Home();
        $contadores = $home->ContadoresPizza($_SESSION['usuario']['id_grupo']);
        echo json_encode($contadores);
        break;
}
