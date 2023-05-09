<?php
($_GET['app_codigo'] != "") ? $usuario = $_GET['app_codigo'] : $usuario = $_SESSION['usuario']['id'];

$objUsuarioPrioridadeEventoTipo = new UsuarioPrioridadeEventoTipo();
$listar                         = $objUsuarioPrioridadeEventoTipo->ListarPrioridades($usuario);

$Sms = new Sms($_SESSION['usuario']['id'], $_SESSION['usuario']['id_franqueado_cliente']);
if ($Sms->isAutenticado()) {
	$mostrarSms = true;
}

$auxUsuario = new Usuario();
$auxUsuario->setId($usuario);
$auxUsuario = $auxUsuario->Editar();

$todos = array();
$dados = array();
/**
 *	Define quais eventos são de prioridade alta, ou seja, quais o usuário não pode definir como prioridade Relatório
 *  	1 = Pânico
 *      59 = Antena GPS desconectada
 *      63 = Curto antena GPS
 *      70 = Botão de Pânico
 * 		71 = Em movimento com ignição desligada
 *      72 = Remoção bateria principal
 *      73 = Violação do anti-furto
 *      81 = Falha sinal GPS
 *      82 = Falha GPS
 * 		95 = Movimento Indevido
 *
 */


//Flag sinalizando a primeira tentativa de liberar um Sms para mandar um aviso
$primeiroSms = true;
foreach ($listar['prioridade_usuario'] as $registro) {
	if ($registro['sms'] == "1") {
		$primeiroSms = false;
		break;
	}
}

$eventosPrioridadeAlta   = array(1, 70, 71, 95, 63, 59, 72, 81, 73);
$eventosPrioridadeSirene = array(1, 59, 70, 72, 104);

$dados_form             = Array();
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";

$dados_tabela          = Array();
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "TabelaPrioridade";

$dados_coluna               = Array();
$dados_coluna["dados_th"][] = array("nome" => RTL_EVENTO);
$dados_coluna["dados_th"][] = array("nome" => ROTULO_FABRICANTE);
$dados_coluna["dados_th"][] = array("nome" => ROTULO_RELATORIO);
$dados_coluna["dados_th"][] = array("nome" => ROTULO_AVISO);
$dados_coluna["dados_th"][] = array("nome" => ROTULO_EMAIL);
$dados_coluna["dados_th"][] = array("nome" => ROTULO_SIRENE);
if ($mostrarSms) {
	$dados_coluna["dados_th"][] = array("nome" => RTL_SMS);
}

$dados_linha = Array();

$x = 0;
foreach ($listar['prioridade_usuario'] as $registro) {

	if ($registro['critico'] == 1) {
		$dados_linha[$x]['classe_panel'] = 'danger';
		$dados_linha[$x]["dados_tr"]     = array("class" => "danger");
		//$icone = '<i class="ico-action aprovado" data-toggle="popover" data-placement="top" data-content="'.TXT_CNH_VENCIDA.'"></i>';
	}

	$dados_linha[$x]["dados_td"][] = array("valor" => $registro['nome_evento_tipo'], "class" => "uppercase");
	$dados_linha[$x]["dados_td"][] = array("valor" => $registro['fabricantes'], "class" => "uppercase");
	$i                             = 0;
	// Define a menor prioridade como padrao
	if (!($registro['id_prioridade'] > 0)) {
		$registro['id_prioridade'] = 1;
	}
	if (($registro['id_prioridade'] == 1 || $registro['id_prioridade'] == "") && in_array($registro['id_evento_tipo'], $eventosPrioridadeAlta) && $_SESSION['id_franquia'] != "") {
		$registro['id_prioridade'] = 2;
	}

	foreach ($listar['prioridade'] as $registro2) {

		if ($registro2['id'] == 3) continue;

		if ($registro['id_prioridade'] == $registro2['id']) {
			$checked = "checked";
		} else {
			$checked = "";
		}
		// Checa se o cara marcou 1 (sem aviso) em eventos de alta prioridade, se sim
		if ($registro2['id'] == 1 && in_array($registro['id_evento_tipo'], $eventosPrioridadeAlta) && $_SESSION['id_franquia'] != "")//COMPARAÇÃO FEITA PARA EVITAR QUE SE POSSA SELECIONAR RELATORIO NA PRIORIDADE DE EVENTO VEICULO EM PANICO E BOTÃO DE PANICO
		{
			$dados_linha[$x]["dados_td"][] = array("valor" => "", "class" => "uppercase");
		} else {
			$dados_linha[$x]["dados_td"][] = array("valor" => "<input type=\"radio\" class=\"radio\" id=\"id_evento_tipo_{$registro['id_evento_tipo']}_$i\" name=\"id_evento_tipo_{$registro['id_evento_tipo']}\" value=\"{$registro2['id']}\" $checked/>", "class" => "uppercase");
		}
	}
	if ($registro['email'] == "" || $registro['email'] == 0) {
		$checked = "";
	} else {
		$checked = "checked";
	}

	$dados_linha[$x]["dados_td"][] = array("valor" => "<input type=\"checkbox\" class=\"js-switch\" id=\"email[]\" name=\"email[]\" value=\"{$registro['id_evento_tipo']}\" $checked/>", "class" => "uppercase");
	$checked                       = "";
	$disabled                      = "";
	if (in_array($registro['id_evento_tipo'], $eventosPrioridadeSirene)) {

		if ($auxUsuario->id_franqueado != "" && $auxUsuario->master != 1) {
			$disabled = 'disabled';
			$checked  = "checked";
		}
	}
	/*if ($registro['sirene'] == "") {
		if (in_array($registro['id_evento_tipo'], $eventosPrioridadeSirene)) {
			$checked = "checked";
		}
	} else */if ($registro['sirene'] == 1) {
		$checked = "checked";
	}

	$dados_linha[$x]["dados_td"][] = array("valor" => "<input type=\"checkbox\" class=\"js-switch\" id=\"sirene[]\" name=\"sirene[]\" value=\"{$registro['id_evento_tipo']}\" $checked $disabled/>", "class" => "uppercase");


	if ($mostrarSms) {
		if ($registro['sms'] == "" || $registro['sms'] == 0) {
			$checked = "";
		} else {
			$checked = "checked";
		}

		if ($registro['sms_ativado'] == "" || $registro['sms_ativado'] == 0) {
			$disabled = "disabled";
		} else {
			$disabled = "";
		}

		if ($primeiroSms) {
			$dados_linha[$x]["dados_td"][] = array("valor" => "<input type=\"checkbox\" onchange=\"PrimeiroSms()\"  class=\"js-switch\" id=\"sms\" name=\"sms[]\" value=\"{$registro['id_evento_tipo']}\" $checked  $disabled/>", "class" => "uppercase");
		} else {
			$dados_linha[$x]["dados_td"][] = array("valor" => "<input type=\"checkbox\" class=\"js-switch\" id=\"sms\" name=\"sms[]\" value=\"{$registro['id_evento_tipo']}\" $checked  $disabled/>", "class" => "uppercase");
		}
		$checked = "";
	}

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