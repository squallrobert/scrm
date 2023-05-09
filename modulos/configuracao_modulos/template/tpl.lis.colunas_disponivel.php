<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 09:11
 */
$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc") ? $ordem = "asc" : $ordem = "desc";
if ($pagina == "") {
    $pagina = 0;
}
$numeroRegistros      = 50;
$numeroInicioRegistro = $pagina * $numeroRegistros ;

$objConfCampos     = new ConfiguracaoCampos();
$listar       = $objConfCampos->ListarColunasDisponiveis($_REQUEST['colunas_selecionadas'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro ,$ordem );
//Conexao::pr($listar);
$grid         = new GerarGrid();
$grid->titulo = '';
//$grid->descricao = RTL_DESCRICAO_LISTAGEM_USUARIO;
//funcção de java script de atualização
$grid->funcao_atualizar    = "AtualizarGridColunas";
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

//$dados_coluna["dados_th"][] = ["configuracao" => "config_caixa_selecao", "nome" => "box", "class"=> "checkboxes","width" => "40"];
$dados_coluna["dados_th"][] = array("nome" => 'id',  "filtro" => "id", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => 'Nome',  "filtro" => "nome", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => 'NamID',  "filtro" => "name_id", "tipo" => "$ordem");
$dados_coluna["dados_th"][] = array("nome" => 'Ações', "id" => "coluna_acoes");
$x                          = 0;
if (count($listar) > 0) {
    foreach ($listar as $linha) {
        $acoes = '       
        <a class="fal fa-share m-l-10" href="javascript:SelecionarColuna(' . $linha['id'] . ')"  title="Adicionar"></a>
        ';
//        $dados_linha[$x]["dados_td"][] = array("valor" => "$linha->id","class"=> "checkboxes","nome" => "box");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['id'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['nome'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['name_id'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("id" => 'veiculo_' . $linha['id'], "valor" => "$acoes", "class" => "uppercase");
        $x++;
    }
}
$grid->linhas             = $dados_linha;
$grid->colunas            = $dados_coluna;
$grid->nome_campo_busca            = 'busca_colunas_disponiveis';

$grid->permitir_excluir   = false;
$grid->permitir_adicionar = false;
$grid->permitir_form = false;
$grid->permitir_paginacao = false;
$grid->permitir_paginacao_top = false;
$grid->tamanho_campo_busca = "col-md-12";
$grid->msg_campo_busca	= '';
$grid->Gerar();
?>
<script>
    $("#busca_colunas_disponiveis").keypress(function (e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            AtualizarGridColunas("",$("#busca_colunas_disponiveis").val());
            return false;
        } else {
            return true;
        }
    });
</script>
