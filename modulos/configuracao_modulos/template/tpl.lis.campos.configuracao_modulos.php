<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:09
 */
include_once("js.lis.configuracao_modulos.php");
$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc") ? $ordem = "asc" : $ordem = "desc";
if ($pagina == "") {
    $pagina = 0;
}
$numeroRegistros      = 50;
$numeroInicioRegistro = $pagina * $numeroRegistros ;

$objConfiguracaoModulos = new ConfiguracaoModulos();
$listar                 = $objConfiguracaoModulos->ListarPaginacaoCampos($numeroRegistros, $numeroInicioRegistro, $filtro, $ordem, $busca);

//dados do formulário
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";

// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela";

$dados_coluna["dados_th"][] = array("nome" => "box", "class" => "checkboxes col-md-1");
$dados_coluna["dados_th"][] = array("nome" => "Nome", "filtro" => "nome", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => "Padrão", "filtro" => "padrao", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => "Obrigatório", "filtro" => "padrao", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => "Name/id HTML", "filtro" => "padrao", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => "Alterar", "class" => "col-md-1");

$x = 0;
if (count($listar[0]) > 0) {
    foreach ($listar[0] as $linha) {
        if($linha['obrigatorio'] == "1") {
            $obrigatorio = "<a href='javascript:void(0);'  style='color:white;' class=\"btn btn-success btn-sm btn-icon rounded-circle waves-effect waves-themed\"  data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Sim\"><b>S</b</a>";
        }else{
            $obrigatorio = "<a href='javascript:void(0);'  style='color:white;' class=\"btn btn-danger btn-sm btn-icon rounded-circle waves-effect waves-themed\"  data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Não\"><b>N</b</a>";
        }

        if($linha['padrao'] == "1") {
            $padrao = "<a href='javascript:void(0);'  style='color:white;' class=\"btn btn-info btn-sm btn-icon rounded-circle waves-effect waves-themed\"  data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Habilitado\"><b>H</b</a>";
        }else{
            $padrao = "<a href='javascript:void(0);'  style='color:white;' class=\"btn btn-warning btn-sm btn-icon rounded-circle waves-effect waves-themed\"  data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Desabilitado\"><b>D</b</a>";
        }

        $nameId = "<a href='javascript:void(0);'  style='color:white;' class=\"btn btn-info btn-sm btn-icon rounded-circle waves-effect waves-themed\"  data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"". $linha['name_id']."\"><b>ID</b</a>";

        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['id'], "class" => "checkboxes", "nome" => "box");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['nome'], "class" => "");
        $dados_linha[$x]["dados_td"][] = array("valor" => $padrao, "class" => "");
        $dados_linha[$x]["dados_td"][] = array("valor" => $obrigatorio, "class" => "");
        $dados_linha[$x]["dados_td"][] = array("valor" => $nameId, "class" => "");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['id'], "nome" => "Alterar");
        $x++;
    }
}

$grid                      = new GerarGrid();
$grid->form                = $dados_form;
$grid->tabela              = $dados_tabela;
$grid->titulo              = "";
$grid->funcao_atualizar    = "AtualizarGridConfiguracaoCampos";
$grid->funcao_modificar    = "ModificarCampo";
$grid->valor_campo_busca   = $busca;
$grid->filtro              = $filtro;
$grid->pagina              = $pagina;
$grid->numeroRegistros     = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem               = $_REQUEST["ordem"];
$grid->totalRegistros      = $listar[1]['total'];
$grid->colunas             = $dados_coluna;
$grid->msg_campo_busca	= '';
$grid->linhas              = $dados_linha;
$grid->Gerar();