<?php

/**
 * @author    Icaro
 * @copyright 2013
 */


($_GET['app_codigo'] != "") ? $usuario = $_GET['app_codigo'] : $usuario = $_SESSION['usuario']['id'];

$objUsuarioPrioridadeEventoTipo = new UsuarioPrioridadeEventoTipo();
$listar                         = $objUsuarioPrioridadeEventoTipo->ListarPrioridades($usuario);

$todos = array();
$dados = array();

/**
 *    Define quais eventos são de prioridade alta, ou seja, quais o usuário não pode definir como prioridade Relatório
 *        1 = Pânico
 *        70 = Botão de Pânico
 *        63 = Curto antena GPS
 *        59 = Antena GPS desconectada
 *        72 = Remoção bateria principal
 *        81 = Falha sinal GPS
 *        82 = Falha GPS
 *        73 = Violação do anti-furto
 */

$dados_form             = Array();
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";

$dados_tabela          = Array();
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "TabelaSms";

$dados_coluna               = Array();
$dados_coluna["dados_th"][] = array("nome" => "#", "class" => "col-md-1");
$dados_coluna["dados_th"][] = array("nome" => ROTULO_TIPO_EVENTO, "class" => "col-md-14");
$dados_coluna["dados_th"][] = array("nome" => ROTULO_FABRICANTE, "class" => "col-md-15");

$dados_linha = Array();

$x = 0;
foreach ($listar['prioridade_usuario'] as $registro) {
	($registro['sms_ativado']) ? $checked = "checked" : $checked = "";

	$checkbox = '<input type="checkbox" class="js-switch" value="' . $registro['id_evento_tipo'] . '" ' . $checked . ' name="seleciona_sms[]"/>';

	$dados_linha[$x]["dados_td"][] = array("valor" => $checkbox);
	$dados_linha[$x]["dados_td"][] = array("valor" => $registro['nome_evento_tipo'], "class" => "uppercase");
	$dados_linha[$x]["dados_td"][] = array("valor" => $registro['fabricantes'], "class" => "uppercase");
	$x++;
}

$grid                      = new GerarGrid();
$grid->form                = $dados_form;
$grid->tabela              = $dados_tabela;
$grid->titulo              = "";
$grid->funcao_atualizar    = "AtualizarGridEventoRelatorio";
$grid->funcao_modificar    = "";
$grid->valor_campo_busca   = $busca;
$grid->nome_campo_busca    = "_busca";
$grid->filtro              = $filtro;
$grid->pagina              = $pagina;
$grid->numeroRegistros     = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem               = $_REQUEST["ordem"];
$grid->totalRegistros      = 0;
$grid->colunas             = $dados_coluna;
$grid->linhas              = $dados_linha;
$grid->permitir_adicionar  = false;
$grid->permitir_excluir    = false;
$grid->permitir_busca      = false;
$grid->permitir_paginacao  = false;
$grid->permitir_form       = false;
$grid->permitir_outros     = false;
$grid->Gerar();