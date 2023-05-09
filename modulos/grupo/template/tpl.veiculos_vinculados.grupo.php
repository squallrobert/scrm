<?
//include_once("modulos/atuacao/template/js.lis.atuacao.php");
$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc") ? $ordem = "asc" : $ordem = "desc";
if ($pagina == "") {
    $pagina = 0;
}
$numeroRegistros      = 1000;
$numeroInicioRegistro = $pagina * $numeroRegistros ;

//$veiculo      = new Veiculo();
$objGrupoVeiculo = new GrupoVeiculo();
//echo "teste = ". $app_codigo . "|";
$objGrupoVeiculo->setId_grupo($app_codigo);
$listar = $objGrupoVeiculo->VeiculosVinculadoGrupo();
//$listar       = $veiculo->ListarPaginacaoAtuacao($_SESSION['usuario']['id_grupo'], $pagina, $numeroRegistros, $numeroInicioRegistro, $busca, $filtro, $ordem, $_SESSION['id_franquia'], false, $_REQUEST['veiculos_selecionados']);
//Conexao::pr($listar);
$grid         = new GerarGrid();
$grid->titulo = '';
//$grid->descricao = RTL_DESCRICAO_LISTAGEM_USUARIO;
//funcção de java script de atualização
$grid->funcao_atualizar    = "AtualizarGridAtuacao";
$grid->funcao_modificar    = "";
$grid->valor_campo_busca   = $busca;
$grid->filtro              = $filtro;
$grid->pagina              = $pagina;
$grid->numeroRegistros     = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem               = $_REQUEST["ordem"];
$grid->totalRegistros      = $listar[1];
//dados do formulário
$dados_form["name"]     = "frm_veiculo_vinculados";
$dados_form["id"]       = "frm_veiculo_vinculados";
$dados_form["onsubmit"] = "return false";
$grid->form             = $dados_form;

$campos[0]['name'] = 'id_grupo';
$campos[0]['id'] = 'id_grupo';
$campos[0]['value'] = $app_codigo;
$grid->campos_hidden = $campos;
// dados da tabela
$dados_tabela["class"]      = "table table-hover";
$dados_tabela["id"]         = "listagem_veiculos_selecionados";
$grid->tabela               = $dados_tabela;

$dados_coluna["dados_th"][] = array("nome" => 'ID');
$dados_coluna["dados_th"][] = array("nome" => 'Veículo');
$dados_coluna["dados_th"][] = array("nome" => 'Complemento');
$dados_coluna["dados_th"][] = array("nome" => 'Ações', "id" => "coluna_acoes");
$x                          = 0;
if (count($listar) > 0) {
    $js = '';
    foreach ($listar as $linha) {
        $js.= 'veiculosSelecionados.push(' . $linha['id_veiculo'] . ');'."\n";

        $acoes = ' 
        <a class="fas fa-trash text-danger m-l-10" href="javascript:RemoverSelecionado(' . $linha['id_veiculo'] . ')" title="Remover"></a>      
        ';
        $dados_linha[$x]['dados_tr'] = array('id' => 'linha_'.$linha['id_veiculo'].'');
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['id_veiculo'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => "<input type=\"hidden\" name=\"vinculado[" . $linha['id_veiculo'] . "]\" id=\"vinculado_" . $linha['id_veiculo'] . "\" value=\"1\"><input type=\"hidden\" name=\"id_veiculo[]\" id=\"id_veiculo_" . $linha['id_veiculo'] . "\" value=\"" . $linha['id_veiculo'] . "\">".$linha['rotulo']." (".$linha['numero_serie'].")", "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("valor" => $linha['complemento_placa'], "class" => "uppercase");
        $dados_linha[$x]["dados_td"][] = array("id" => 'veiculo_' . $linha['id_veiculo'], "valor" => "$acoes", "class" => "uppercase");
        $x++;
    }
}
$grid->linhas             = $dados_linha;
$grid->colunas            = $dados_coluna;

$grid->permitir_excluir   = false;
$grid->permitir_adicionar = false;
$grid->permitir_paginacao = false;
$grid->permitir_msg_nenhum_registro = false;
$grid->permitir_busca = false;
$grid->permitir_paginacao_top = false;
$grid->msg_campo_busca	   = 'Busca';
$grid->Gerar();

echo '<script> '.$js.' AtualizarGridVeiculos(); </script>';