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

$tabela               = new GerarTabelaXml();
$tabela->dados        = $dados_linha;
$tabela->colunas      = $dados_coluna;
$tabela->filtro       = $filtro;
$tabela->logo_cliente = $_SESSION["logo_cliente"];
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".RTL_MODULO.".xls");
header("Cache-Control: max-age=0");
$tabela->CriarTabelaClasse();
