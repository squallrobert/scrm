<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Menu Completo";
echo $objApp->GerarBreadCrumb($configTitulo);
?>
<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Formulário Menu Completo</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=menu&app_comando=listar_menu'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="formulario_modulos">
            <form action="#" name="frm_menu_completo" id="frm_menu_completo" method="post">
                <input type="hidden" name="id"  id="id"   value="<?=$linha['id'];?>"/>
                <div class="form-body">
                    <div class="row p-t-20">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="nome">* Nome</label>
                                <input type="text" name="nome"  id="nome" maxlength="100" class="form-control validar-obrigatorio " value="<?=$linha['nome'];?>"/>
                                <small class="form-text text-muted"> Escreva um nome para o Menu </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="descricao">Descrição</label>
                                <input type="text" name="descricao"  id="descricao" maxlength="100" class="form-control  " value="<?=$linha['descricao'];?>"/>
                                <!--                                    <textarea class="form-control  " name="descricao"  id="descricao" placeholder="Insira o texto" >--><?//=$linha['descricao'];?><!--</textarea>-->
                                <small class="form-text text-muted"> Descreva o que é o menu </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label class="form-label">Diretótio</label>
                                <select  data-allow-clear="true" data-placeholder="Selecione um diretório" name="dir" id="dir" class=" form-select " data-validar="select2" > >
                                    <option ></option>
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


                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label" for="id_pai"> Menu Pai</label>
                                <?
                                $menu      = new Menu();
                                $registros = $menu->GerarSelectPai();
                                echo Componente::GerarSelectPDO("id_pai", "id_pai", "", $registros, array($linha['id_pai']), array('','Selecione um Pai'), array("id", "nome"), false, 'form-select  m-b-20 m-r-10','');
                                ?>
                                <!--                                    <input type="text" name="nome"  id="nome" maxlength="100" class="form-control validar-obrigatorio " value="--><?//=$linha['nome'];?><!--"/>-->
                                <small class="form-text text-muted"> Selecione um menu pai </small> </div>
                        </div>
                        <!--/span-->

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label" for="ordem">*Ordem</label>
                                <input type="text" name="ordem"  id="ordem" maxlength="11" class="form-control validar-obrigatorio mask-numero" value="<?=$linha['ordem'];?>"/>
                                <small class="form-text text-muted"> Selecione a ordem emque o menu vai ficar </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label" for="acao_primaria">Ação Primária</label>
                                <input type="text" name="acao_primaria"  id="acao_primaria"  class="form-control  " value="<?=$linha['acao_primaria'];?>"/>
                                <!--                                    <textarea class="form-control  " name="acao"  id="acao" placeholder="Insira o texto" >--><?//=$linha['acao'];?><!--</textarea>-->
                                <small class="form-text text-muted"> adicione a ação primária do menu</small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label class="form-label" for="index">Index</label>
                                <select  data-allow-clear="true" data-placeholder="Selecione um diretório" data-validar="select2"  name="index" class="  form-select " id="index"  data-validar="select2"   >
                                    <option value="" <?if($linha['index'] == "") echo"selected"; ?>>Selecione o Index</option>
                                    <option value="index.php" <?if($linha['index'] == "index.php" ) echo"selected"; ?>>Index.php</option>
                                    <option value="index_xml.php" <?if($linha['index'] == "index.xml" ) echo"selected"; ?>>index_xml.php</option>
                                    <option value="index_ajax.php" <?if($linha['index'] == "index_ajax.php") echo"selected"; ?>>index_ajax.php</option>
                                    <option value="index_print.php" <?if($linha['index'] == "index_print.php") echo"selected"; ?>>index_print.php</option>
                                </select>
                                <!--                                    <input type="text" name="index"  id="index" maxlength="20" class="form-control  " value="--><?//=$linha['index'];?><!--"/>-->
                                <small class="form-text text-muted"> Selecione o index que será aberto a página </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="target">Target</label>
                                <select  data-allow-clear="true"  name="target" id="target" class=" form-select "  data-placeholder="Selecione um diretório" data-validar="select2"    >
                                    <option value="" <?if($linha['target'] == "") echo"selected"; ?>>Nenhum Target</option>
                                    <option value="_self" <?if($linha['target'] == "_self") echo"selected"; ?>>Mesma Janela</option>
                                    <option value="_blank" <?if($linha['target'] == "_blank") echo"selected"; ?>>Nova Janela</option>
                                    <option value="_open" <?if($linha['target'] == "_open") echo"selected"; ?>>Janela Pop Up</option>
                                </select>
                                <!--                                    <input type="text" name="target"  id="target" maxlength="20" class="form-control  " value="--><?//=$linha['target'];?><!--"/>-->
                                <small class="form-text text-muted"> Selecione como vai ser aberto o icone. </small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="icone">Icone</label>
                                <select  data-allow-clear="true" data-placeholder="Selecione um ícone"  name="icone" id="icone" class="  form-control " data-validar="select2"    >

                                </select>
                                <!--                                    <input type="text" name="icone"  id="icone" maxlength="50" class="form-control  " value="--><?//=$linha['icone'];?><!--"/>-->
                                <small class="form-text text-muted"> Coloque aqui a classe de CSS referente ao ícone</small> </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="acao">Ações</label><br>
                                <!--                                    <textarea class="form-control  " name="acao"  id="acao" placeholder="Insira o texto" >--><?//=str_replace('|',',',$linha['acao']);?><!--</textarea>-->
                                <input type="text" name="acao"  id="acao" maxlength="100" class="form-control"   value="<?=str_replace('|',',',$linha['acao']);?>"/>
                                <small class="form-text text-muted"> Precione a tecla "enter" para completar cada ação </small> </div>
                        </div>
                        <!--/span-->
                    </div>
            </form>
        </div>
        <div class="card-footer d-flex flex-row-reverse">
            <button id="bt_salvar2" class="btn btn-success ms-3" type="button"><i class="fa fa-check "></i> Salvar</button>
            <a href='#index_xml.php?app_modulo=menu&app_comando=listar_menu' type="button" class="btn btn-light"> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
        </div>
    </div>
</div>
<script>
    var id_icone = '<?=$linha['icone'];?>';
</script>
<script src="modulos/menu/template/js.frm.menu_completo.js?v2"></script>
