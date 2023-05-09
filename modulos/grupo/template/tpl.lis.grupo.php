<?php
include_once("modulos/grupo/template/js.lis.grupo.php");

$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc")? $ordem = "asc": $ordem = "desc";
if($pagina == "") { $pagina = 0; }

$numeroRegistros = ($_REQUEST["numero_registros"] == "") ? 50 :(int) $_REQUEST["numero_registros"];
$numeroInicioRegistro = $pagina * $numeroRegistros ;

$objGrupo = new Grupo();
$listar = $objGrupo->ListarPaginacao($_SESSION['usuario']['id_grupo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro,$ordem);


//dados do formulário
$dados_form["name"] = "formgrupo";
$dados_form["id"] = "formgrupo";
$dados_form["onsubmit"] = "return false";
// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela_grupo";

$dados_coluna["dados_th"][] = ["configuracao" => "config_caixa_selecao", "nome" => "box", "class"=> "checkboxes","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'id',"filtro"=> "id", "tipo"=> "$ordem","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Nome',"filtro"=> "nome", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Pai',"filtro"=> "arvore", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Data hora cadastro',"filtro"=> "data_hora_cadastro", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "config_acoes", "nome" => "Alterar", "class"=> "","width" => "40"];

$x = 0;
if(count($listar[0])> 0){
	foreach($listar[0] as $linha){
//        $vincular = '<button type="button" class="btn btn-success btn-sm btn-icon waves-effect waves-themed" data-toggle="tooltip" onclick="VincularVeiculos(' . $linha['id'] . ')" data-placement="top" title="Vícular Veículos" data-original-title="Vícular Veículos"><i class=\'fas fa-car\'></i></button>';
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"class"=> "checkboxes","nome" => "box"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["nome"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["nome_grupo_pai"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_cadastro"], $_SESSION["usuario"]["id_fuso_horario"]),"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"nome" => "Alterar","style" => "text-align:right"];
		$x++;
	}
}

//Componente::FiltrarRelatorioConfiguracao($dados_coluna, $dados_linha, $_SESSION["configuracao_usuario"]["grupo"]);
$grid = new GerarGrid();
$grid->form = $dados_form;
$grid->tabela = $dados_tabela;
$grid->titulo = "";
$grid->funcao_atualizar = "Grupos.AtualizarGridGrupo";
$grid->funcao_modificar = "ModificarGrupo";
$grid->valor_campo_busca = $busca;
$grid->ph_campo_busca = "Busca por: Nome, pai e id";
$grid->filtro = $filtro;
$grid->pagina = $pagina;
$grid->numeroRegistros =  $numeroRegistros ;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem = $_REQUEST["ordem"];
$grid->totalRegistros =  $listar[1]['total'];
$grid->linhas  = $dados_linha;
$grid->colunas = $dados_coluna;
//$grid->retornar_html = true;
$grid->Gerar();

