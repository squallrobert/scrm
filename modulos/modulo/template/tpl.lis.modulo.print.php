<?php
$numeroRegistros      = 20000;
$numeroInicioRegistro = 0;
$busca                = $_REQUEST["busca"];

$objModulo = new Modulo();
$listar = $objModulo->ListarPaginacao($_SESSION['usuario']['id_grupo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro,$ordem);

$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_ID);
$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_NOME);
$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_DIR);
$dados_coluna["dados_th"][]=array("configuracao" => "", "nome" => RTL_STATUS);

$x = 0;
if (@count($listar[0]) > 0) {
	foreach ($listar[0] as $linha) {
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["id"],"class"=> "uppercase");
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["nome"],"class"=> "uppercase");
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["dir"],"class"=> "uppercase");
	     $dados_linha[$x]["dados_td"][]=array("valor" => $linha["status"],"class"=> "uppercase");
		$x++;
	}
}
//Componente::FiltrarRelatorioConfiguracao($dados_coluna, $dados_linha, $_SESSION["configuracao_usuario"]["modulo"]);

$filtro[ROTULO_LISTAGEM] = RTL_MODULO;
//$filtro[ROTULO_RELATORIO] = RTL_MODULO;
if ($_REQUEST["id_grupo"] > 0) {
	$filtro[ROTULO_GRUPO] = $_REQUEST["nome_grupo"];
}
if ($_REQUEST["id_veiculo"] > 0) {
	$filtro[ROTULO_VEICULO] = $_REQUEST["nome_veiculo"];
}
$filtro[ROTULO_DATA_INICIAL] = $_REQUEST["data_hora_inicio"];
$filtro[ROTULO_DATA_FINAL]   = $_REQUEST["data_hora_fim"];

$tabela                 = new GerarTabelaPrint();
$tabela->buscaAtiva     = false;
$tabela->nome           = "";
$tabela->totalRegistros = $listar[1]->total;
$tabela->dados          = $dados_linha;
$tabela->center         = $center;
$tabela->colunas        = $dados_coluna;
$tabela->botaoAdicionar = false;
$tabela->botao          = false;
$tabela->paginacao      = false;
$tabela->filtro         = $filtro;
echo $tabela->CriarTabela();
