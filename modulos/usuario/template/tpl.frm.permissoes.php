<?php
if ($linha['id_usuario'] != "") {$_SESSION['id_usuario_temp'] = $linha['id_usuario'];}
//Conexao::pr($_SESSION);

$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Segurança";
$configTitulo['titulo_modulo'] = "Usuários";
echo $objApp->GerarBreadCrumb($configTitulo);

?>
    <div id="kt_app_content_container" class="app-container  p-0">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title"> Permissões de Usuário : (<?=$linha['nome']?>)</h3>
                <div class="card-toolbar">
                    <a href='#index_xml.php?app_modulo=usuario&app_comando=listar_usuario'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
                </div>
            </div>
            <div class="card-body" id="formulario_modulos">
                <form action="#" name="frm_usuario_permissao" id="frm_usuario_permissao" method="post">
                    <div class="form-body">
                        <!--    <input type="hidden" value="--><?//=$linha['id'];?><!--" name="id" id="id"/>-->
                        <input type="hidden" value="<?=$app_codigo;?>" name="id_usuario" id="id_usuario"/>
                        <div class="row">
                            <div class="col-md-12 mt-3" >
                                <div class="form-group">
                                    <label class="form-label" >Copiar Permissões</label>
                                    <!--begin::Default example-->
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text cursor-pointer" onclick="BuscarFuncionalidades()"><i class="fas fa-copy fs-4"></i> &nbsp;Copiar permissões de &nbsp; <i class="fas fa-arrow-right fs-4"></i></span>
                                        <div class="flex-grow-1">
                                            <select name="id_grupo_copiar" id="id_grupo_copiar"  class=" form-select " ></select>
                                        </div>
                                    </div>
                                    <!--end::Default example-->
                                    <small class="form-text text-muted"> Selecione o Grupo que deseja copiar  as permissões </small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3" id="permissao">
                                <?
                                $grupo = new Grupo();
                                $row = $grupo->ListarFuncionalidadesUsuario($_REQUEST['app_codigo'], $linha['id_grupo']);
                                //                                        echo  'dqwesad';
                                //                                        Conexao::pr($teste);
                                $html = "<div id='treeAcoes' ><ul>";
                                if(@count($row) > 0)
                                {
                                    foreach ($row AS $modulo => $acoes) {
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
                </form>
            </div>

            <div class="card-body" id="formulario_modulos">
            </div>
            <div class="card-footer d-flex flex-row-reverse">
                <button type="button" class="btn btn-success ms-3" id="bt_salvar"> <i class="fas fa-check"></i> Salvar</button>
                <a href='#index_xml.php?app_modulo=usuario&app_comando=listar_usuario'  class="btn btn-light"> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
    </div>


<?
include_once("modulos/usuario/template/js.frm.permissoes.php");
