<?
//include_once("modulos/ponto_interesse/template/js.lis.atuacao.php");
$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc") ? $ordem = "asc" : $ordem = "desc";
if ($pagina == "") {
    $pagina = 0;
}
$numeroRegistros      = 50;
$numeroInicioRegistro = $pagina * $numeroRegistros ;

$veiculo      = new Veiculo();
$listar       = $veiculo->ListarPaginacaoAtuacao($_SESSION['usuario']['id_grupo'], $pagina, $numeroRegistros, $numeroInicioRegistro, $busca, $filtro, $ordem, $_SESSION['id_franquia'], false, $_REQUEST['veiculos_selecionados']);
//Conexao::pr($listar);
$grid         = new GerarGrid();
$grid->titulo = '';
//$grid->descricao = RTL_DESCRICAO_LISTAGEM_USUARIO;
//funcção de java script de atualização
$grid->funcao_atualizar    = "AtualizarGridVeiculos";
$grid->funcao_modificar    = "";
$grid->valor_campo_busca   = $busca;
$grid->filtro              = $filtro;
$grid->pagina              = $pagina;
$grid->numeroRegistros     = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem               = $_REQUEST["ordem"];
$grid->totalRegistros      = $listar[1];
//dados do formulário
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";
$grid->form             = $dados_form;
// dados da tabela
$dados_tabela["class"]      = "table table-hover";
$dados_tabela["id"]         = "listagem_veiculos";
$grid->tabela               = $dados_tabela;

$dados_coluna["dados_th"][] = ["configuracao" => "config_caixa_selecao", "nome" => "box", "class"=> "checkboxes","width" => "40"];
$dados_coluna["dados_th"][] = array("nome" => 'id',  "filtro" => "veiculo.rotulo", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => 'Veículo',  "filtro" => "veiculo.rotulo", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => 'Complemento', "filtro" => "veiculo.rotulo", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => 'Fabricante', "filtro" => "fabricante.nome", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => 'Ações', "id" => "coluna_acoes");
$x                          = 0;
if (count($listar[0]) > 0) {
    foreach ($listar[0] as $linha) {
        $acoes = '       
        <a class="fas fa-share m-l-10" href="javascript:SelecionarVeiculo(' . $linha->id . ')"  title="Adicionar"></a>
        ';
        $dados_linha[$x]["dados_td"][] = array("valor" => "$linha->id","class"=> "checkboxes","nome" => "box");
        $dados_linha[$x]["dados_td"][] = array("valor" => "$linha->id ", "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => "$linha->rotulo ($linha->numero_serie)", "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha->complemento_placa, "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => "$linha->nome_fabricante ($linha->nome_modelo_rastreador)", "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("id" => 'veiculo_' . $linha->id, "valor" => "$acoes", "class" => "uppercase");
        $x++;
    }
}
$grid->linhas             = $dados_linha;
$grid->colunas            = $dados_coluna;

$grid->permitir_excluir   = false;
$grid->permitir_adicionar = false;
$grid->permitir_paginacao = false;
$grid->permitir_paginacao_top = false;
$grid->tamanho_campo_busca = "col-md-12";
$grid->msg_campo_busca	   = TXT_MSG_BUSCA;
$grid->Gerar();
echo "<div class='row'><div class='col-md-12'><input type='button' class='btn btn-info' value='Selecionar' onclick='selecionarTodosVeiculos()'/></div></div>";