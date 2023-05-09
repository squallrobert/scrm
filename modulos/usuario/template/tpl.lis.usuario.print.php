<?php
$numeroRegistros      = 20000;
$numeroInicioRegistro = 0;
$busca                = $_REQUEST["busca"];

$objUsuario = new Usuario();
$listar = $objUsuario->ListarPaginacao($_SESSION['usuario']['id_grupo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro,$ordem);

$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_ID];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_NOME];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_CPF_CNPJ];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_USUARIO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_SENHA];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_EMAIL];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_PERGUNTA_SENHA];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_RESPOSTA_SENHA];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_SENHA_PROVISORIA];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_ATIVO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_TIPO_ACESSO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_DATA_HORA_CADASTRO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_DATA_HORA_EXPIRADO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_DATA_HORA_ULTIMO_LOGIN];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_DATA_HORA_ATUALIZACAO_SENHA];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_MASTER];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_PATCH_ATUALIZACAO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_FOTO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_TUTORIAL];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_VALIDACAO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_CODIGO_VALIDADOR];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_USUARIO_ANTIGO];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_CELULAR_VALIDADOR];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_IDIOMA];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => RTL_TIMEZONE];

$x = 0;
if (@count($listar[0]) > 0) {
	foreach ($listar[0] as $linha) {
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["nome"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["cpf_cnpj"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["usuario"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["senha"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["email"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["pergunta_senha"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["resposta_senha"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["senha_provisoria"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["ativo"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["tipo_acesso"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_cadastro"], $_SESSION["usuario"]["id_fuso_horario"]), "class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_expirado"], $_SESSION["usuario"]["id_fuso_horario"]), "class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_ultimo_login"], $_SESSION["usuario"]["id_fuso_horario"]), "class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_atualizacao_senha"], $_SESSION["usuario"]["id_fuso_horario"]), "class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["master"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["patch_atualizacao"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["foto"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["tutorial"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["validacao"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["codigo_validador"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["usuario_antigo"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["celular_validador"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["idioma"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["timezone"],"class"=> "uppercase"];
		$x++;
	}
}
//Componente::FiltrarRelatorioConfiguracao($dados_coluna, $dados_linha, $_SESSION["configuracao_usuario"]["usuario"]);

$filtro[ROTULO_LISTAGEM] = RTL_USUARIO;
//$filtro[ROTULO_RELATORIO] = RTL_USUARIO;
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
