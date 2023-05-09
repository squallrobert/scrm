<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Módulos";
echo $objApp->GerarBreadCrumb($configTitulo);
?>

<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Formulário Módulos</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=modulo&app_comando=listar_modulo'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="formulario_modulos">
            <form action="#" name="frm_modulo" id="frm_modulo" method="post">
                <input type="hidden" name="pagina" id="pagina" value="<?=$_REQUEST['pagina'];?>">
                <input type="hidden" name="ordem" id="ordem" value="<?=$_REQUEST['ordem'];?>">
                <input type="hidden" name="filtro" id="filtro" value="<?=$_REQUEST['filtro'];?>">
                <input type="hidden" name="busca" id="filtro" value="<?=$_REQUEST['busca'];?>">
                <input type="hidden" value="<?=$_REQUEST['app_codigo'];?>" name="id" id="id"/>
                <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome"  id="nome" maxlength="100" value="<?=$linha['nome']?>"  class="form-control validar-obrigatorio" placeholder="Entre com o nome do módulo">
                                <small class="form-text text-muted"> Este nome é o que vai aparecer na arvores de permissões </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label class="form-label">Diretótio</label>
                                <select name="dir" id="dir" data-validar="select2"  class=" form-select "  >
                                    <option value="">Selecione um diretório</option>
                                    <?php
                                    foreach (glob("modulos/*") as $dir)
                                    {
                                        $diretorio = str_replace("modulos/","",$dir);
                                        echo "<option value='".$diretorio."'";
                                        if($linha['dir'] == $diretorio) echo " selected='selected' ";
                                        echo "'> ".$diretorio."</option>". "\n";
                                    }
                                    ?>

                                </select>
                                <small class="form-text text-muted"> Listagem com todos os diretórios já criados na pasta módulos </small>
                            </div>
                        </div>
                        <!--/span-->

                    </div>
            </form>
        </div>
        <div class="card-footer d-flex flex-row-reverse">
            <button type="button" class="btn btn-success ms-3" id="bt_salvar"> <i class="fas fa-check"></i> Salvar</button>
            <button type="button" class="btn btn-light " id="bt_voltar"> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</button>
        </div>
    </div>
</div>
<script src="modulos/modulo/template/js.frm.modulo.js?v4"></script>