<?php
include("modulos/grupo_acao/template/js.grupo_acao.php");
?>
<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Segurança";
$configTitulo['titulo_modulo'] = "Grupo Ações";
echo $objApp->GerarBreadCrumb($configTitulo);
?>

<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Grupo Ações</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=grupo&app_comando=listar_grupo'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="conteudo_grupo">
            <form action="#" name="frm_grupo_acao" id="frm_grupo_acao" method="post">
                <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group" id="div_acoes">
                                <h3 class="form-label pb-2" for="treeAcoes"> <i class="fa fa-lock"></i> Permissões</h3>
                                <?
                                $grupo = new Grupo();
                                $grupoPai = $linha['id_grupo_pai'] ?: $_SESSION['usuario']['id_grupo'];
                                $teste = $grupo->ListarFuncionalidades($linha['id_grupo'], $grupoPai);
                                //                                    Conexao::pr($teste);
                                $html = "<div id='treeAcoes' ><ul>";
                                foreach ($teste AS $modulo => $acoes) {
                                    $html .= "<li  class='jstree-open' >$modulo<ul>";
                                    foreach ($acoes AS $acao) {
                                        $selecionado = "";
                                        if ($acao['selecionado'] > 0) {
                                            $selecionado = 'data-checkstate="checked"';
                                        }
                                        $html .= '<li ' . $selecionado . ' id=' . $acao['id_acao'] . ' class="tree">' . $acao['nome_acao'] . '</li>';
                                    }
                                    $html .= "</ul></li>";
                                }
                                $html .= '</ul></div>';
                                echo $html;
                                ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" id="div_acoes">
                                <h3 class="form-label pb-2" for="treeAcoes2"><i class="fa fa-users"></i> Grupos</h3>
                                <?php
                                $grupo = new Grupo();
                                $grupoPai = $linha['id_grupo_pai'] ?: $_SESSION['usuario']['id_grupo'];
                                $grupos = $grupo->GruposAcoesTree();

                                $html = "<div id='treeAcoes2' ><ul>";
                                $html .= $grupo->MontarMenuArvore($grupos);
                                $html .= '</ul></div>';
                                echo $html;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex flex-row-reverse">
            <button type="button" class="btn btn-success" id="bt_salvar"> <i class="fas fa-check"></i> Salvar</button>
            <a href="#index_xml.php?app_modulo=grupo&app_comando=listar_grupo&pagina=<?=$_REQUEST['pagina']?>&filtro=<?=$_REQUEST['filtro']?>&busca=<?=$_REQUEST['busca']?>&ordem=<?=$_REQUEST['ordem']?>" type="button" class="btn btn-light"> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
        </div>
    </div>
</div>




