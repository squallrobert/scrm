<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Menus";
echo $objApp->GerarBreadCrumb($configTitulo);
?>

<div id="kt_app_content_container" class="app-container  p-0">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title"> Formulário Menus</h3>
            <div class="card-toolbar">
                <a href='#index_xml.php?app_modulo=menu&app_comando=listar_menu'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
            </div>
        </div>
        <div class="card-body" id="formulario_modulos">
            <?php

            $form = new GerarForm();
            $form->id_div_form = "uf_formulario";
            $form->form = ["name" => "frm_menu", "id" => "frm_menu", "method" => "post"];

            $modulos = New Modulo();
            $rs = $modulos->ListarCombo();
            $menu      = new Menu();
            $registros = $menu->GerarSelectIdAcao();
            $registros2 = $menu->GerarSelectPai();
            // Campos
            $campos = array(
                [
                    "parametros" =>
                        [
                            "type" => "hidden",
                            "name" => "id",
                            "id" => "id",
                            "value" => $linha['id']
                        ]
                ],[
                    "class_col_md" => "col-md-4 pt-2",
                    "nome" => "Nome",
                    "descricao" => "Preencha o campo nome",
                    "parametros" =>
                        [
                            "type" => "text",
                            "name" => "nome",
                            "id" => "nome",
                            "value" => $linha['nome'],
                            "class" => "form-control validar-obrigatorio"
                        ]
                ], [
                    "class_col_md" => "col-md-4 pt-2",
                    "nome" => "Descrição",
                    "descricao" => "Preencha a Descrição",
                    "parametros" =>
                        [
                            "type" => "text",
                            "name" => "descricao",
                            "id" => "descricao",
                            "value" => $linha['descricao'],
                            "class" => "form-control"
                        ]
                ],[
                    "class_col_md" => "col-md-4 pt-2",
                    "nome" => "Ação",
                    "descricao" => "Selecione uma Ação",
                    "primeiro" =>
                        [
                            "id" => "",
                            "nome" => "Nenhum Selecionado"
                        ],
                    "selecionados" =>
                        [
                            $linha['id_acao']
                        ],
                    "parametros"=>
                        [
                            "type" =>"select",
                            "name" => "id_acao",
                            "id" => "id_acao",
                            "class" => "form-select "
                        ],
                    "options" => $registros
                ],[
                    "class_col_md" => "col-md-5 pt-2",
                    "nome" => "Menu Pai",
                    "descricao" => "Selecione um pai",
                    "primeiro" =>
                        [
                            "id" => "",
                            "nome" => "Nenhum Selecionado"
                        ],
                    "selecionados" =>
                        [
                            $linha['id_pai']
                        ],
                    "parametros"=>
                        [
                            "type" =>"select",
                            "name" => "id_pai",
                            "id" => "id_pai",
                            "class" => "form-select "
                        ],
                    "options" => $registros2
                ],[
                    "class_col_md" => "col-md-2 pt-2",
                    "nome" => "Ordem",
                    "descricao" => "Defina a Ordem",
                    "parametros" =>
                        [
                            "type" => "text",
                            "name" => "ordem",
                            "id" => "ordem",
                            "value" => $linha['ordem'],
                            "class" => "form-control validar-obrigatorio"
                        ]
                ],[
                    "class_col_md" => "col-md-5 pt-2",
                    "nome" => "Ação Primária",
                    "descricao" => "entre com a ação que será executada",
                    "parametros" =>
                        [
                            "type" => "text",
                            "name" => "acao",
                            "id" => "acao",
                            "value" => $linha['acao'],
                            "class" => "form-control "
                        ]
                ]);


            $index[] = ["id" =>'index.php',"nome" => "index.php"];
            $index[] = ["id" =>'index_xml.php',"nome" => "index_xml.php"];
            $index[] = ["id" =>'index_ajax.php',"nome" => "index_ajax.php"];
            $index[] = ["id" =>'index_print.php',"nome" => "index_print.php"];
            $campos[] = ["class_col_md" => "col-md-4 pt-2","nome" => "Index", "descricao" => "Selecione um Index","selecionados" => [$linha['index']],"parametros"=> ["type" =>"select","name" => "index", "id" => "index","class" => "form-select "],"options" => $index];

            $target[] = ["id" =>'_self',"nome" => "Mesma Janela"];
            $target[] = ["id" =>'_blank',"nome" => "Nova Janela"];
            $target[] = ["id" =>'_open',"nome" => "Janela Pop Up"];
            $campos[] = ["class_col_md" => "col-md-4 pt-2","nome" => "Target", "descricao" => "Selecione um Target","selecionados" => [$linha['target']],"parametros"=> ["type" =>"select","name" => "target", "id" => "target","class" => "form-select "],"options" => $target];

            $campos[] = ["class_col_md" => "col-md-4 pt-2","nome" => "Ícone", "descricao" => "Selecione um ícone","selecionados" => [$linha['icone']],"parametros"=> ["type" =>"select","name" => "icone", "id" => "icone","class" => "form-select ","data-validar"=>"select2" ],"options" => []];

            // botões
            //$buttons[] = ["rotulo" => "Salvar", "icone" => "fa fa-check", "parametros" => ["id" => "bt_salvar", "class" => "btn btn-success", "type" => "button"]];
            //$buttons[] = ["rotulo" => "Voltar para Listagem", "icone" => "fa fa-arrow-left", "parametros" => ["id" => "bt_voltar", "class" => "btn btn-default", "type" => "button"]];

            $form->campos = $campos;
           // $form->buttons = $buttons;
            echo $form->GerarFormulario();

            ?>

        </div>
        <div class="card-footer d-flex flex-row-reverse">
                <button id="bt_salvar" class="btn btn-success ms-3" type="button"><i class="fa fa-check "></i> Salvar</button>
                <button id="bt_voltar" class="btn btn-light" type="button"><i class="fas fa-arrow-circle-left"></i> Voltar para Listagem</button>
        </div>
    </div>
</div>
<script>
    var id_icone = '<?=$linha['icone'];?>';
</script>
<script src="modulos/menu/template/js.frm.menu.js?v=2"></script>


