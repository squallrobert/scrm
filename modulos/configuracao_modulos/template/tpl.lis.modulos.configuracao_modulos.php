<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:16
 */
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
$listar                 = $objConfiguracaoModulos->ListarPaginacaoModulos($numeroRegistros, $numeroInicioRegistro, $filtro, $ordem, $busca);

//dados do formulário
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";

// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "tabela_modulos";

$dados_coluna["dados_th"][] = array("nome" => "Id", "filtro" => "id", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => "Nome", "filtro" => "nome", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => "Alterar");

$x = 0;
if (count($listar[0]) > 0) {
    foreach ($listar[0] as $linha) {
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['id'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['nome'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['id'], "nome" => "Alterar");
        $x++;
    }
}

$grid                      = new GerarGrid();
$grid->form                = $dados_form;
$grid->tabela              = $dados_tabela;
$grid->titulo              = "";
$grid->funcao_atualizar    = "AtualizarGridConfiguracaoRelatoriosCampos";
$grid->funcao_modificar    = "AtribuirCampo";
$grid->nome_campo_busca    = "busca_modulos";

$grid->valor_campo_busca   = $busca;
$grid->filtro              = $filtro;
$grid->pagina              = $pagina;
$grid->numeroRegistros     = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem               = $_REQUEST["ordem"];
$grid->totalRegistros      = $listar[1]['total'];
$grid->colunas             = $dados_coluna;
$grid->linhas              = $dados_linha;
$grid->permitir_adicionar  = false;
$grid->permitir_excluir    = false;
$grid->msg_campo_busca	= '';
$grid->Gerar();