<?php
switch ($app_comando) {
    case "inicial":
    case "home":
    case "default":
//        Conexao::pr($_SESSION);X
        $template = "tpl.home.php";
        break;
    case "sair":
        $template = "tpl.sair.php";
        break;
    case "excluir_conta":
        $usuario = new Usuario();
        $usuario->Inativar($_SESSION['usuario']['id']);
        $template = "tpl.inativar.php";
        break;
    case "contador_diario":
        $objServicos = new Servicos();
        $parametros['data_hora_inicio'] = date('Y-m-d')." 00:00:00";
        $parametros['data_hora_fim'] = date('Y-m-d'). " 23:59:59";
        if($_SESSION['usuario']['id_usuario_tipo'] == 2) $parametros['fotografo'] = $_SESSION['usuario']['id'];
        if($_SESSION['usuario']['id_usuario_tipo'] == 3) $parametros['editor'] = $_SESSION['usuario']['id'];
        if($_SESSION['usuario']['id_usuario_tipo'] == 4) $parametros['corretor'] = $_SESSION['usuario']['id'];
//        Conexao::pr($_SESSION);
        $rs = $objServicos->GetQuantitativosStatus($_SESSION['usuario']['id_grupo'],$parametros);

        foreach ($rs as $r) {
            $vetor[$r['status']] = $r['total'];
        }
        echo json_encode($vetor);
        $template = "ajax.home.php";
        break;
    case "listar_contador":
        $objServicos = new Servicos();
        $parametros['data_hora_inicio'] = date('Y-m-')."01 00:00:00";
        $parametros['data_hora_fim'] = date('Y-m-t'). " 23:59:59";
        if($_SESSION['usuario']['id_usuario_tipo'] == 2) $parametros['fotografo'] = $_SESSION['usuario']['id'];
        if($_SESSION['usuario']['id_usuario_tipo'] == 3) $parametros['editor'] = $_SESSION['usuario']['id'];
        if($_SESSION['usuario']['id_usuario_tipo'] == 4) $parametros['corretor'] = $_SESSION['usuario']['id'];

        $rs = $objServicos->GetQuantitativosStatus($_SESSION['usuario']['id_grupo'],$parametros);

        $template = "tpl.contador_servicos.php";
        break;
    case "listar_ultimas":
        $parametros['fotografo'] = ($_SESSION['usuario']['id_usuario_tipo'] == 2) ? $_SESSION['usuario']['id'] :  $_REQUEST['fotografo'];
        $parametros['corretor'] =  ($_SESSION['usuario']['id_usuario_tipo'] == 4 && $_SESSION['usuario']['master'] != 1) ? $_SESSION['usuario']['id'] :  $_REQUEST['corretor'];

        $objServicos = new Servicos();
        $listar = $objServicos->ListarPaginacao($_SESSION['usuario']['id_grupo'],10,0,'','id','desc',$parametros);
        $template = "tpl.lis.ultimos.php";
        break;
}