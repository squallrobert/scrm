<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 10:00
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
$listar       = $objConfCampos->ListarColunasVinculadas($_REQUEST['id_modulo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro ,$ordem );
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
$dados_tabela["id"]         = "listagem_colunas_selecionados";
$grid->tabela               = $dados_tabela;

$dados_coluna["dados_th"][] = array("nome" => 'id');
$dados_coluna["dados_th"][] = array("nome" => 'Nome');
$dados_coluna["dados_th"][] = array("nome" => 'NameID');
$dados_coluna["dados_th"][] = array("nome" => 'Ações', "id" => "coluna_acoes");
$x                          = 0;
if (count($listar) > 0) {
    foreach ($listar as $linha) {
        $js.= 'ColunasSelecionados.push(' . $linha['id_veiculo'] . ');'."\n";
        $dados_linha[$x]['dados_tr  '] = array('id' => 'linha_'.$linha['id'].'');
        $acoes = ' 
        <a class="fal fa-trash text-danger m-l-10" href="javascript:RemoverSelecionado(' . $linha['id_relatorio'] . ')" title="Remover"></a>      
        ';
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['id'] , "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => "<input type=\"hidden\" name=\"vinculado[" . $linha['id'] . "]\" id=\"vinculado_" . $linha['id'] . "\" value=\"1\"><input type=\"hidden\" name=\"id_coluna[]\" id=\"id_coluna_" . $linha['id'] . "\" value=\"" . $linha['id'] . "\">".$linha['nome'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['name_id'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("id" => 'coluna_' . $linha['id'], "valor" => "$acoes", "class" => "uppercase");
        $x++;
    }
}
$grid->linhas             = $dados_linha;
$grid->colunas            = $dados_coluna;
$grid->nome_campo_busca            = 'busca_colunas_disponiveis';

$grid->permitir_excluir   = false;
$grid->permitir_adicionar = false;
$grid->permitir_busca = false;
$grid->permitir_form = false;
$grid->permitir_paginacao = false;
$grid->permitir_paginacao_top = false;
$grid->tamanho_campo_busca = "col-md-12";
$grid->msg_campo_busca	= '';
$grid->Gerar();
echo '<script> '.$js.' AtualizarGridColunas(); 

    function RemoverSelecionado(id)
    {
        $("#linha_" + id).remove();
            $.post(\'index_xml.php?app_modulo=configuracao_modulos&app_comando=deletar_configuracao_relatorios_campos\',
                {
                    registros: id
                },
                function (response)
                {
                    if (response[\'codigo\'] == 0) {
                        ToastMsg("success", response["mensagem"]);
                    }
                    else {
                        ToastMsg("error", response["mensagem"]);
                    }
                }
                , \'json\'
            );
            AtualizarGridVinculos(0, "");
            
    }
</script>';

