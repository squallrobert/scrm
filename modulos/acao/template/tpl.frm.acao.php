<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Acões do Sistema";
echo $objApp->GerarBreadCrumb($configTitulo);
?>

    <div id="kt_app_content_container" class="app-container  p-0">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title"> Formulário Acões do Sistema</h3>
                <div class="card-toolbar">
                    <a href='#index_xml.php?app_modulo=acao&app_comando=listar_acao'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
                </div>
            </div>
            <div class="card-body" id="formulario_modulos">
                <?php

                $form = new GerarForm();

                $form->id_div_form = "uf_formulario";
                $form->form = ["name" => "frm_acao", "id" => "frm_acao", "method" => "post"];

                $modulos = New Modulo();
                $rs = $modulos->ListarCombo();

                //campo nome
                $campos[] = [
                    "class_col_md" => "col-md-6 pt-2",
                    "nome" => "Nome",
                    "descricao" => "Preencha o campo nome",
                    "parametros" => [
                        "type" => "text",
                        "name" => "nome",
                        "id" => "nome",
                        "value" => $linha['nome'],
                        "class" => "form-control validar-obrigatorio"
                    ]
                ];
                // campo HIDDEN  do ID
                $campos[] = [
                    "parametros" =>
                        ["type" => "hidden",
                            "name" => "id",
                            "id" => "id",
                            "value" => $linha['id'
                            ]
                        ]
                ];
                // Campo Select COM TODOS OS MODULOS
                $campos[] = [
                    "class_col_md" => "col-md-6 pt-2",
                    "nome" => "Módulo",
                    "selecionados" => [$linha['modulo']],
                    "descricao" => "Selecione uma região",
                    "parametros" => ["type" => "select" , "name" => "modulo" , "id" => "modulo","class" => "form-control m-b-20 m-r-10 ","data-allow-clear" => "true","data-placeholder" => "Selecione um Módulo","data-validar" => "select2"],
                    "options" => $rs
                ];
                // Campos Ações TAGIT
                $campos[] = [
                    "class_col_md" => "col-md-12 pt-2",
                    "nome" => "Açoes",
                    "descricao" => "Preencha o campo nome",
                    "parametros" => ["type" => "textarea","name" => "acao","id" => "acao","value" => str_replace('|',',',$linha['acao']), "class" => "form-control" ,"data-validar" => "tagit"]
                ];

                // botões
//                $buttons[] = ["rotulo" => "Salvar", "icone" => "fa fa-check", "parametros" => ["id" => "bt_salvar", "class" => "btn btn-success", "type" => "button"]];
//                $buttons[] = ["rotulo" => "Voltar para Listagem", "icone" => "fa fa-arrow-left", "parametros" => ["id" => "bt_voltar", "class" => "btn btn-default", "type" => "button"]];

                $form->campos = $campos;
//                $form->buttons = $buttons;
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
<script src="modulos/acao/template/js.frm.acao.js?v=2"></script>




