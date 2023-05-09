<?
include_once("modulos/endereco/template/js.lis.endereco.php");
$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc")? $ordem = "asc": $ordem = "desc";
if($pagina == "") { $pagina = 0; }

$numeroRegistros = 50;
$numeroInicioRegistro = $pagina * $numeroRegistros;

$objEndereco = new Endereco();
$listar = $objEndereco->ListarPaginacao($_SESSION['usuario']['id_grupo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro,$ordem);

//dados do formulário
$dados_form["name"] = "form";
$dados_form["id"] = "form";
$dados_form["onsubmit"] = "return false";
// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela_endereco";

$dados_coluna["dados_th"][] = ["configuracao" => "config_caixa_selecao", "nome" => "box", "class"=> "checkboxes","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "ID","filtro"=> "id", "tipo"=> "$ordem","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "LOGRADOURO","filtro"=> "logradouro", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "NUMERO","filtro"=> "numero", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "COMPLEMENTO","filtro"=> "complemento", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "BAIRRO","filtro"=> "bairro", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "CIDADE","filtro"=> "cidade", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "ESTADO","filtro"=> "estado", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "CEP","filtro"=> "cep", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "REFERENCIA","filtro"=> "referencia", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "OBSERVACAO","filtro"=> "observacao", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "TELEFONE","filtro"=> "telefone", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "COMERCIAL","filtro"=> "comercial", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "CELULAR","filtro"=> "celular", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "EMAIL","filtro"=> "email", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "EMAIL_MKT","filtro"=> "email_mkt", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "EMAIL_MKT2","filtro"=> "email_mkt2", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "LATITUDE","filtro"=> "latitude", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "LONGITUDE","filtro"=> "longitude", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "DATA_HORA_CADASTRO","filtro"=> "data_hora_cadastro", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "config_acoes", "nome" => "Alterar", "class"=> "","width" => "40"];

$x = 0;
if(@count($listar[0])> 0){
	foreach($listar[0] as $linha){
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"class"=> "checkboxes","nome" => "box"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["logradouro"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["numero"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["complemento"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["bairro"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["cidade"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["estado"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["cep"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["referencia"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["observacao"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["telefone"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["comercial"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["celular"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["email"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["email_mkt"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["email_mkt2"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["latitude"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["longitude"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_cadastro"], $_SESSION["usuario"]["id_fuso_horario"])];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"nome" => "Alterar","style" => "text-align:right"];
		$x++;
	}
}
//Componente::FiltrarRelatorioConfiguracao($dados_coluna, $dados_linha, $_SESSION["configuracao_usuario"]["endereco"]);
$grid = new GerarGrid();
$grid->form = $dados_form;
$grid->tabela = $dados_tabela;
$grid->titulo = "";
$grid->funcao_atualizar = "AtualizarGridEndereco";
$grid->funcao_modificar = "ModificarEndereco";
$grid->valor_campo_busca = $busca;
$grid->filtro = $filtro;
$grid->pagina = $pagina;
$grid->numeroRegistros = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem = $_REQUEST["ordem"];
$grid->totalRegistros = $listar[1];
$grid->linhas  = $dados_linha;
$grid->colunas = $dados_coluna;
$grid->Gerar();
