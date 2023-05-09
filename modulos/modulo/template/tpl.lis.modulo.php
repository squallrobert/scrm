<script src="modulos/modulo/template/js.lis.modulo.js"></script>
<?php
$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc")? $ordem = "asc": $ordem = "desc";
if($pagina == "") { $pagina = 0; }

$numeroRegistros = ($_REQUEST["numero_registros"] == "") ? 50 :(int) $_REQUEST["numero_registros"];
$numeroInicioRegistro = $pagina * $numeroRegistros ;

$objModulo = new Modulo();
$listar = $objModulo->ListarPaginacao($_SESSION['usuario']['id_grupo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro,$ordem);

//dados do formulário
$dados_form["name"] = "form";
$dados_form["id"] = "form";
$dados_form["onsubmit"] = "return false";
// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela_modulo";

$dados_coluna["dados_th"][]=array("configuracao" => "config_caixa_selecao", "nome" => "box","class"=> "checkboxes","width" => "50");
$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_ID,"filtro"=> "id","tipo"=> "$ordem","width" => "50");
$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_NOME,"filtro"=> "nome","tipo"=> "$ordem");
$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_DIR,"filtro"=> "dir","tipo"=> "$ordem");
$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_STATUS,"filtro"=> "status","tipo"=> "$ordem","width" => "100");
$dados_coluna["dados_th"][]=array("configuracao" => "config_acoes", "nome" => "Alterar","class"=> "","width" => "50");

$x = 0;
if(count($listar[0])> 0)
{
   foreach($listar[0] as $linha)
   {
         $dados_linha[$x]["dados_td"][]=array("valor" => $linha["id"],"class"=> "checkboxes","nome" => "box");
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["id"],"class"=> "uppercase");
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["nome"],"class"=> "uppercase");
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["dir"],"class"=> "uppercase");
	     $dados_linha[$x]["dados_td"][]=array("valor" => ($linha["status"] == 1)?'Inativo':'Ativo',"class"=> "uppercase");
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["id"],"nome" => "Alterar");
        $x++;
   }
}
//Componente::FiltrarRelatorioConfiguracao($dados_coluna, $dados_linha, $_SESSION["configuracao_usuario"]["modulo"]);
$grid = new GerarGrid();
$grid->form = $dados_form;
$grid->tabela = $dados_tabela;
$grid->titulo = "";
$grid->funcao_atualizar = "Modulos.AtualizarGridModulo";
$grid->funcao_modificar = "ModulosListagem.ModificarModulo";
$grid->valor_campo_busca = $busca;
$grid->filtro = $filtro;
$grid->pagina = $pagina;
$grid->numeroRegistros =  $numeroRegistros ;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem = $_REQUEST["ordem"];
$grid->totalRegistros = $listar[1];
$grid->linhas  = $dados_linha;
$grid->colunas = $dados_coluna;
$grid->filtros_form = $_REQUEST;
$grid->Gerar();
