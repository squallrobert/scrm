<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Segurança";
$configTitulo['titulo_modulo'] = "Grupos";
echo $objApp->GerarBreadCrumb($configTitulo);
?>

<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Formulário Grupos</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=grupo&app_comando=listar_grupo'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="conteudo_grupo">
            <form action="#" name="frm_grupo" id="frm_grupo" method="post">
                <input type="hidden" name="id"  id="id"   value="<?=$linha['id_grupo'];?>"/>
                <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="nome">*Nome</label>
                                <input type="text" name="nome"  id="nome" maxlength="100" class="form-control validar-obrigatorio " value="<?=$linha['nome_grupo'];?>"/>
                                <small class="form-text text-muted"> Digite um nome para o grupo que deseja criar </small>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="id_grupo_pai">* Grupo Pai</label>
                                <!--begin::Default example-->
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text"><i class="fas fa-users fs-4"></i></span>
                                    <div class="flex-grow-1">
                                        <select name="id_grupo_pai" id="id_grupo_pai" class="form-select" data-validar="select2-input-group" >
                                            <?php
                                            $objGrupo = New Grupo();
                                            $rs = $objGrupo->ListarCombo($linha['id_grupo_pai']);
                                            echo Componente::GerarCombo($rs,'id','nome',$linha['id_grupo_pai'],'','--Selecione um Módulo--')
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!--end::Default example-->
                                <small class="form-text text-muted"> Selecione o Grup Pai </small>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12 mt-3" >
                            <div class="form-group">
                                <label >Copiar Permissões</label>
                                <!--begin::Default example-->
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text cursor-pointer" onclick="BuscarFuncionalidades()"><i class="fas fa-copy fs-4"></i> &nbsp;Copiar permissões de &nbsp; <i class="fas fa-arrow-right fs-4"></i></span>
                                    <div class="flex-grow-1">
                                        <select name="id_grupo_copiar" id="id_grupo_copiar" data-style="btn-default btn-outline-default" class=" form-select " ></select>
                                    </div>
                                </div>
                                <!--end::Default example-->
                                <small class="form-text text-muted"> Selecione o Grup Pai </small>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group" id="div_acoes">
                                <label class="form-label" for="treeAcoes">Permissões</label>
                                <?
                                $grupo = new Grupo();
                                $grupoPai = $linha['id_grupo_pai'] ?: $_SESSION['usuario']['id_grupo'];
                                $teste = $grupo->ListarFuncionalidades($linha['id_grupo'], $grupoPai);
                                //                                        echo  'dqwesad';
                                //                                        Conexao::pr($teste);
                                $html = "<div id='treeAcoes' ><ul>";
                                if(count($teste) > 0)
                                {
                                    foreach ($teste AS $modulo => $acoes) {
                                        $html .= "<li class='jstree-open'>$modulo<ul>";
                                        foreach ($acoes AS $acao) {
                                            $selecionado = "";
                                            if ($acao['selecionado'] > 0) {
                                                $selecionado = 'data-checkstate="checked"';
                                            }
                                            $html .= '<li ' . $selecionado . ' id=' . $acao['id_acao'] . ' class="tree">' . $acao['nome_acao'] . '</li>';
                                        }
                                        $html .= "</ul></li>";
                                    }
                                }

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

<?php
include_once("modulos/grupo/template/js.frm.grupo.php");
