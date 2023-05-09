<?php

$usuarioConfiguracao = new UsuarioConfiguracao();
$usuarioConfiguracao->setIdUsuario($app_codigo);
$usuarioConfiguracao->setIdSessao(1);
$listar   = $usuarioConfiguracao->ListaUsuarioConfiguracao();
$posicoes = @unserialize($listar->configuracao);

$GruposExcecao = array(1, 157, 6464);

if (count($posicoes) == 0 && ($_SESSION['id_franquia'] != '' || array_search($_SESSION['usuario']['id_grupo'], $GruposExcecao))) {
	$relatorio = array('nenhum', 'veiculo', 'velocidade', 'data_hora', 'logradouro', 'ignicao', 'panico', 'bloqueio', 'horimetro', 'odometro', 'memoria', 'motivo', 'horimetro_parcial', 'contador', 'observacao');
} else {
	$relatorio = $posicoes;
}

if (!$relatorio) {
	$relatorio = Array();
}

$conf                       = Array();
$conf['veiculo']            = ROTULO_VEICULO;
$conf['velocidade']         = ROTULO_VELOCIDADE;
$conf['data_hora']          = ROTULO_DATA_HORA;
$conf['data_hora_gravacao'] = ROTULO_DATA_HORA_GRAVACAO;
$conf['logradouro']         = ROTULO_LOGRADOURO;
$conf['ignicao']            = ROTULO_IGNICAO;
$conf['panico']             = ROTULO_PANICO;
$conf['bloqueio']           = ROTULO_BLOQUEIO;
$conf['horimetro']          = ROTULO_HORIMETRO;
$conf['odometro']           = ROTULO_ODOMETRO;
$conf['memoria']            = ROTULO_MEMORIA;
$conf['motivo']             = ROTULO_MOTIVO;
$conf['tensao']             = ROTULO_TENSAO;
$conf['bateria']            = ROTULO_NIVEL_BATERIA;
$conf['contador']           = ROTULO_CONTADOR;
$conf['latitude']           = ROTULO_LATITUDE;
$conf['longitude']          = ROTULO_LONGITUDE;
$conf['milhas_nauticas']    = ROTULO_MILHAS_NAUTICAS;
$conf['horimetro_parcial']  = ROTULO_HORIMETRO_PARCIAL;
$conf['intervalo']          = ROTULO_INTERVALO;
$conf['cercas']             = ROTULO_CERCAS;
$conf['telemetria']         = ROTULO_TELEMETRIA;
$conf['observacao']         = ROTULO_OBSERVACAO;
$conf['motorista']          = ROTULO_MOTORISTA;
$conf['origem_destino']     = ROTULO_ORIGEM_DESTINO;

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

	$checkbox = '<input type="checkbox" class="js-switch" value="' . $col . '" ' . $checked . ' name="seleciona_posicao[]"/>';

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