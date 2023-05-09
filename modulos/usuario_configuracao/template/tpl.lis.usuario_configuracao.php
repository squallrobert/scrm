<?
include_once("modulos/usuario_configuracao/template/js.lis.usuario_configuracao.php");

$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc") ? $ordem = "asc" : $ordem = "desc";
if ($pagina == "") {
	$pagina = 0;
}
$numeroRegistros      = 50;
$numeroInicioRegistro = $pagina * $numeroRegistros ;

$usuarioConfiguracao = new UsuarioConfiguracao();
$listar              = $usuarioConfiguracao->ListarPaginacaoUsuarioConfiguracao($pagina, $numeroRegistros, $numeroInicioRegistro, $_SESSION['usuario']['id_grupo'], $busca, $filtro, $ordem);

//dados do formulário
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";

// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela";

$dados_coluna["dados_th"][] = array("nome" => ROTULO_USUARIO, "class" => "col-md-9", "filtro" => "usuario.usuario", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => ROTULO_GRUPO, "class" => "col-md-10", "filtro" => "grupo.nome", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => ROTULO_NOME, "class" => "col-md-10", "filtro" => "usuario.nome", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => "Alterar", "class" => "col-md-1");

$x = 0;
if (count($listar[0]) > 0) {
	foreach ($listar[0] as $linha) {
		$dados_linha[$x]["dados_td"][] = array("valor" => "$linha->usuario", "class" => "uppercase");
		$dados_linha[$x]["dados_td"][] = array("valor" => "$linha->nome_grupo", "class" => "uppercase");
		$dados_linha[$x]["dados_td"][] = array("valor" => "$linha->nome", "class" => "uppercase");
		$dados_linha[$x]["dados_td"][] = array("valor" => "$linha->id", "nome" => "Alterar");
		$x++;
	}
}

$grid                      = new GerarGrid();
$grid->form                = $dados_form;
$grid->tabela              = $dados_tabela;
$grid->titulo              = "";
$grid->funcao_atualizar    = "AtualizarGridUsuarioConfiguracao";
$grid->funcao_modificar    = "ModificarUsuarioConfiguracao";
$grid->valor_campo_busca   = $busca;
$grid->filtro              = $filtro;
$grid->pagina              = $pagina;
$grid->numeroRegistros     = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem               = $_REQUEST["ordem"];
$grid->totalRegistros      = $listar[1]->total;
$grid->colunas             = $dados_coluna;
$grid->linhas              = $dados_linha;
$grid->permitir_adicionar  = false;
$grid->permitir_excluir    = false;

$grid->Gerar();