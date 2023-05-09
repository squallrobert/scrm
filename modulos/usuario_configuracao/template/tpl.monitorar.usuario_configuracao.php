<?php

$usuarioConfiguracao = new UsuarioConfiguracao();
$usuarioConfiguracao->setIdUsuario($app_codigo);
$usuarioConfiguracao->setIdSessao(2);
$listar      = $usuarioConfiguracao->ListaUsuarioConfiguracao();
$monitorados = unserialize($listar->configuracao);

if (!$monitorados) {
	$relatorio = array('nenhum', 'rastro', 'grupo', 'rotulo', 'data_hora', 'velocidade', 'odometro', 'horimetro', 'info', 'historico_atendimento', 'tela_central', 'logradouro', 'atuacao', 'remover', 'dados_veiculo', 'roubo');
} else {
	$relatorio = $monitorados;
}

//echo"<pre>";print_r($relatorio);"<pre>";

$conf                          = Array();
$conf['velocidade']            = 'Velocidade';
$conf['rastro']                = "Rastro";
$conf['rotulo']                = "Rótulo(Número Série)";
$conf['data_hora']             = "Data Hora";
$conf['status_porta']          = "Status porta";
$conf['info']                  = "Info";
$conf['historico_atendimento'] = "Historico de Atendimento";
$conf['tela_central']          = 'Tela de central';
$conf['latitude']              = "latitude";
$conf['longitude']             = "Longitude";
$conf['atuacao']               = 'Atuação';
$conf['ip_gateway']            = "Ip Gateway";
$conf['dados_veiculo']         = 'Dados do Veiculo';
$conf['roubo']                 = 'Roubo';
$conf['remover']               = 'Remover';
$conf['horimetro']             = "Horimetro";
$conf['odometro']              = "Ôdometro";
$conf['logradouro']            = "Logradouro";
$conf['acoes']                 = "Ações";
$conf['motorista']             = "Motorista";
$conf['grupo']                 = "Grupo";

$dados_form             = Array();
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";

$dados_tabela          = Array();
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela";

$dados_coluna               = Array();
$dados_coluna["dados_th"][] = array("nome" => "#", "class" => "col-md-1");
$dados_coluna["dados_th"][] = array("nome" => RTL_COLUNAS, "class" => "col-md-29");

$dados_linha = Array();

$x = 0;
foreach ($conf as $col => $valor) {
	(array_search($col, $relatorio) !== false) ? $checked = "checked" : $checked = "";

	$checkbox = '<input type="checkbox" class="js-switch" value="' . $col . '" ' . $checked . ' name="seleciona_monitorar[]"/>';

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