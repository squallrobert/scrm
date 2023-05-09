<?php
unset($conf);
$usuarioConfiguracao = new UsuarioConfiguracao();
$usuarioConfiguracao->setIdUsuario($app_codigo?:$_SESSION['usuario']['id']);
$usuarioConfiguracao->setIdSessao(16);
$listar   = $usuarioConfiguracao->ListaUsuarioConfiguracao();
$relatorios = @unserialize($listar->configuracao);

if(!$relatorios)
	$relatorio = array('nenhum');
else
	$relatorio = $relatorios;

$conf['placa_sem_hifen'] = 'Placa Sem Hífen';

$dados_tabela          = Array();
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela";

$dados_coluna               = Array();
$dados_coluna["dados_th"][] = array("nome" => "#", "class" => "col-md-1");
$dados_coluna["dados_th"][] = array("nome" => RTL_COLUNAS, "class" => "col-md-29");

$dados_linha = Array();
$x           = 0;
foreach ($conf as $col => $valor) {
	(array_search($col, $relatorio) !== false) ? $checked = "checked" : $checked = "";

	$checkbox = '<input type="checkbox" class="js-switch" value="' . $col . '" ' . $checked . ' name="seleciona_placa_sem_hifen[]"/>';

	$dados_linha[$x]["dados_td"][] = array("valor" => $checkbox);
	$dados_linha[$x]["dados_td"][] = array("valor" => $valor, "class" => "uppercase");
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