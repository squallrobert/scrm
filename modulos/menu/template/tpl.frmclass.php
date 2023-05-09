<span class="svg-icon svg-icon-3">
																			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																				<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"></path>
																				<path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"></path>
																				<path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"></path>
																			</svg>
																		</span>
<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Menus";
echo $objApp->GerarBreadCrumb($configTitulo);

$configModulo['titulo_card'] = "Listagem Menus";
$configModulo['id_card'] = "conteudo_menu";




$form = new GerarForm();
$form->breadcrumb =  [
    ["class" => "breadcrumb-item" , "text" => "GERADOR DE FORM"]
    ,["class" => "breadcrumb-item" , "text" => "Itens Gerados"]
    ,["class" => "breadcrumb-item active" , "text" => "Formulario Teste","href" => "javascript:void(0);"]
];
$form->titulo = "TESTE DE FORMULÁRIO GERADO";
$form->descricao_titulo = "Testando Formuário gerando por class";

$form->id_div_form = "teste_formulario";
$form->form = ["name" => "frm_form","id" => "frm_form", "method" => "_self", "action" => ""];


// exemplos de campos com um unico vetor
$campos = array(
    [
        "class_col_md" => "col-md-3 pt-2",
        "nome" => "Texto  Group Esquerda",
        "descricao" => "Descrição de Nome",
        "textgroup" => "R$",
        "parametros"=>
            [
                "type" =>"text_group_left",
                "name" => "nome",
                "id" => "nome",
                "value" => '',
                "class" => "form-control validar-obrigatorio"
            ]
    ],
    [
        "class_col_md" => "col-md-3 pt-2",
        "nome" => "Texto  Group Esquerda",
        "descricao" => "Descrição de Nome",
        "textgroup" => "R$",
        "parametros"=>
            [
                "type" =>"text_group_left",
                "name" => "nome",
                "id" => "nome",
                "value" => '',
                "class" => "form-control validar-obrigatorio"
            ]
    ],
    [
        "class_col_md" => "col-md-3 pt-2",
        "nome" => "Texto  Group Esquerda",
        "descricao" => "Descrição de Nome",
        "textgroup" => "R$",
        "parametros"=>
            [
                "type" =>"text_group_left",
                "name" => "nome",
                "id" => "nome",
                "value" => '',
                "class" => "form-control validar-obrigatorio"
            ]
    ]
);
// exemplos de campos separados por vetor.
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Texto  Group Direita", "descricao" => "Descrição de Nome","textgroup" => "R$", "parametros"=> ["type" =>"text_group_right","name" => "nome", "id" => "nome","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Texto  Icone DIreita", "descricao" => "Descrição de Nome","icone" => "fa fa-money-bill", "parametros"=> ["type" =>"text_icon_right","name" => "demo", "id" => "demo","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Texto  Icone Esquerda", "descricao" => "Descrição de Nome","icone" => "fa fa-list", "parametros"=> ["type" =>"text_icon_left","name" => "nome", "id" => "nome","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Campo Texto", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"text","name" => "nome_icon", "id" => "nome_icon","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Campo Arquivo", "label" => "Descrição de Nome", "parametros"=> ["type" =>"file","name" => "file2", "id" => "file2","value" => '',"class" => "form-control validar-obrigatorio","accept" => ".jpg, .jpeg, .png, .bmp"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "ChecBox", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"checkbox","name" => "nome_check", "id" => "nome_check","checked" => "checked","value" => '1',"class" => "custom-control-input"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Radio", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"radio","name" => "nome_radio", "id" => "nome_radio","checked" => "checked","value" => '1',"class" => "custom-control-input"]];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Switch", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"switch","name" => "nome_switch", "id" => "nome_switch","checked" => "checked","value" => '1',"class" => " custom-control-input"]];
$campos[] = ["class_col_md" => "col-md-6 pt-2","nome" => "Textarea", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"textarea","name" => "nome_textarea", "id" => "nome_textarea","rows" => "4","value" => '',"class" => "form-control validar-obrigatorio"]];

// GERANDO UM SELECT
$options[] = ["id" =>1,"nome" => "opção 1"];
$options[] = ["id" =>2,"nome" => "opção 2"];
$options[] = ["id" =>3,"nome" => "opção 3"];
$campos[] = ["class_col_md" => "col-md-6 pt-2","nome" => "Select Multiple", "descricao" => "Descrição de Nome","selecionados" => [1,3],"parametros"=> ["type" =>"select","name" => "nome_select_multiples", "id" => "nome_select_multiples","multiple" => 'multiple',"class" => "form-select validar-obrigatorio"],"options" => $options];


$campos[] = ["class_col_md" => "col-md-6 pt-3","nome" => "Botões Multiplos","buttons" =>
    [
        ["icone"=> "fa fa-trash","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-danger remove btn-sm btn-icon rounded-circle waves-effect waves-themed"]],
        ["icone"=> "fa fa-home","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-success remove btn-sm btn-icon rounded-circle waves-effect waves-themed"]],
        ["icone"=> "fa fa-times","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-info remove btn-sm btn-icon rounded-circle waves-effect waves-themed"]],
        ["icone"=> "fa fa-plus","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-primary remove btn-sm btn-icon rounded-circle waves-effect waves-themed"]],
        ["icone"=> "fa fa-square","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-dark remove btn-sm btn-icon rounded-circle waves-effect waves-themed"]],
        ["icone"=> "fa fa-road","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-warning remove btn-sm btn-icon rounded-circle waves-effect waves-themed"]]
    ]

];
$campos[] = ["class_col_md" => "col-md-6 pt-3","nome" => "Botões Multiplos","buttons" =>
    [
        ["icone"=> "fa fa-trash","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-danger remove btn-sm btn-icon  waves-effect waves-themed"]],
        ["icone"=> "fa fa-home","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-success remove btn-sm btn-icon  waves-effect waves-themed"]],
        ["icone"=> "fa fa-times","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-info remove btn-sm btn-icon  waves-effect waves-themed"]],
        ["icone"=> "fa fa-plus","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-primary remove btn-sm btn-icon  waves-effect waves-themed"]],
        ["icone"=> "fa fa-square","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-dark remove btn-sm btn-icon  waves-effect waves-themed"]],
        ["icone"=> "fa fa-road","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-warning remove btn-sm btn-icon  waves-effect waves-themed"]]
    ]

];
$campos[] = ["class_col_md" => "col-md-6 pt-3","nome" => "Botões Multiplos","buttons" =>
    [
        ["rotulo"=>"Limpar","icone"=> "fa fa-trash","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-danger remove btn-sm   waves-effect waves-themed"]],
        ["rotulo"=>"Home","icone"=> "fa fa-home","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-success remove btn-sm   waves-effect waves-themed"]],
        ["rotulo"=>"Excluir","icone"=> "fa fa-times","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-info remove btn-sm   waves-effect waves-themed"]],
        ["rotulo"=>"Adicionar","icone"=> "fa fa-plus","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-primary remove btn-sm  waves-effect waves-themed"]],
        ["rotulo"=>"Square","icone"=> "fa fa-square","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-dark remove btn-sm   waves-effect waves-themed"]],
        ["rotulo"=>"Rua","icone"=> "fa fa-road","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-warning remove btn-sm   waves-effect waves-themed"]]
    ]

];
$campos[] = [
        "class_col_md" => "col-md-6 pt-3",
        "nome" => "Botões Multiplos",
        "buttons" => [
                [
                    "rotulo"=>"Limpar",
                    "icone"=> "fa fa-trash",
                    "parametros"=> [
                        "type" =>"button",
                        "onclick" => "",
                        "class" => "btn btn-outline-danger remove btn-sm   waves-effect waves-themed"
                    ]
                ],
                ["rotulo"=>"Home","icone"=> "fa fa-home","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-outline-success remove btn-sm   waves-effect waves-themed"]],
                ["rotulo"=>"Excluir","icone"=> "fa fa-times","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-outline-info remove btn-sm   waves-effect waves-themed"]],
                ["rotulo"=>"Adicionar","icone"=> "fa fa-plus","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-outline-primary remove btn-sm  waves-effect waves-themed"]],
                ["rotulo"=>"Square","icone"=> "fa fa-square","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-outline-dark remove btn-sm   waves-effect waves-themed"]],
                ["rotulo"=>"Rua","icone"=> "fa fa-road","parametros"=> ["type" =>"button", "onclick" => "","class" => "btn btn-outline-warning remove btn-sm   waves-effect waves-themed"]]
        ]
    ];


$campos[] = ["class_col_md" => "col-md-6 pt-2","nome" => "Select", "descricao" => "Descrição de Nome","selecionados" => [2],"parametros"=> ["type" =>"select","name" => "nome_select", "id" => "nome_select","class" => "form-select validar-obrigatorio"],"options" => [["id" =>1,"nome" => "opção 1"],["id" =>2,"nome" => "opção 2"],["id" =>3,"nome" => "opção 3"]]];
$campos[] = ["class_col_md" => "col-md-2 pt-2","nome" => "Select vazio", "descricao" => "Descrição de Nome","parametros"=> ["type" =>"select","name" => "nome_select_vazio", "id" => "nome_select_vazio","value" => '',"class" => "form-select validar-obrigatorio"]];

// GERANDO UMA LISTA DE RADIOS VERTICAL
$radios[] = ["nome" =>"Opção 1","type" =>"radio","name" => "nome_radio", "id" => "nome_radio1","value" => '1',"class" => "custom-control-input"];
$radios[] = ["nome" =>"Opção 2","type" =>"radio","name" => "nome_radio", "id" => "nome_radio2","value" => '2',"class" => "custom-control-input"];
$radios[] = ["nome" =>"Opção 3","type" =>"radio","name" => "nome_radio", "id" => "nome_radio3","value" => '3',"class" => "custom-control-input"];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Radio Lista Vertical","parametros"=> ["type" =>"radioslist"],"lista" => $radios];

// GERANDO UM LISTA DE CHECKBOX NA VERTICAL
$checkbox[] = ["nome" =>"Opção 1","type" =>"checkbox","name" => "nome_checkbox1", "id" => "nome_checkbox1","value" => '1',"class" => "custom-control-input"];
$checkbox[] = ["nome" =>"Opção 2","type" =>"checkbox","name" => "nome_checkbox2", "id" => "nome_checkbox2","value" => '2',"class" => "custom-control-input"];
$checkbox[] = ["nome" =>"Opção 3","type" =>"checkbox","name" => "nome_checkbox3", "id" => "nome_checkbox3","value" => '3',"class" => "custom-control-input"];
$campos[] = ["class_col_md" => "col-md-3 pt-2","nome" => "Checkbox Lista Vertical","parametros"=> ["type" =>"checkboxlist"],"lista" => $checkbox];

// GERANDO UM LISTA DE RADIOS NA HORIZONTAL
$radiosinline[] = ["nome" =>"Opção 1","type" =>"radio","name" => "nome_radioinline", "id" => "nome_radio1_inline","value" => '1',"class" => "custom-control-input"];
$radiosinline[] = ["nome" =>"Opção 2","type" =>"radio","name" => "nome_radioinline", "id" => "nome_radio2_inline","value" => '2',"class" => "custom-control-input"];
$radiosinline[] = ["nome" =>"Opção 3","type" =>"radio","name" => "nome_radioinline", "id" => "nome_radio3_inline","value" => '3',"class" => "custom-control-input"];
$campos[] = ["class_col_md" => "col-md-12 pt-4","nome" => "Radio Lista Inline","tipo" => "custom-control-inline","parametros"=> ["type" =>"radioslist"],"lista" => $radiosinline];

// GERANDO UM LISTA DE CHECKBOX NA HORIZONTAL
$checkboxinline[] = ["nome" =>"Opção 1","type" =>"checkbox","name" => "nome_checkbox12", "id" => "nome_checkbox12","value" => '1',"class" => "custom-control-input"];
$checkboxinline[] = ["nome" =>"Opção 2","type" =>"checkbox","name" => "nome_checkbox22", "id" => "nome_checkbox22","value" => '2',"class" => "custom-control-input"];
$checkboxinline[] = ["nome" =>"Opção 3","type" =>"checkbox","name" => "nome_checkbox32", "id" => "nome_checkbox32","value" => '3',"class" => "custom-control-input"];
$campos[] = ["class_col_md" => "col-md-12 pt-4","nome" => "Checkbox Lista Inline","tipo" => "custom-control-inline","parametros"=> ["type" =>"checkboxlist"],"lista" => $checkboxinline];


$buttons[] = ["rotulo" => "Salvar", "icone" => "fa fa-check", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-success","type" => "button"]];
$buttons[] = ["rotulo" => "cancelar", "icone" => "fa fa-times", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-warning","type" => "button"]];
$buttons[] = ["rotulo" => "dinheiro", "icone" => "fa fa-money-bill", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-danger","type" => "button"]];
$buttons[] = ["rotulo" => "Esquerda", "icone" => "fa fa-arrow-left", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-info","type" => "button"]];
$buttons[] = ["rotulo" => "Cima", "icone" => "fa fa-arrow-up", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-dark","type" => "button"]];
$buttons[] = ["rotulo" => "Direita", "icone" => "fa fa-arrow-right", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-primary","type" => "button"]];
$buttons[] = ["rotulo" => "Baixo", "icone" => "fa fa-arrow-down", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-default","type" => "button"]];
$buttons[] = ["rotulo" => "Sa", "icone" => "fa fa-check", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-outline-success","type" => "button"]];
$buttons[] = ["rotulo" => "Ca", "icone" => "fa fa-times", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-outline-warning","type" => "button"]];
$buttons[] = ["rotulo" => "Di", "icone" => "fa fa-money-bill", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-outline-danger","type" => "button"]];
$buttons[] = ["rotulo" => "Es", "icone" => "fa fa-arrow-left", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-outline-info","type" => "button"]];
$buttons[] = ["rotulo" => "Cima", "icone" => "fa fa-arrow-up", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-outline-dark","type" => "button"]];
$buttons[] = ["rotulo" => "Di", "icone" => "fa fa-arrow-right", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-outline-primary","type" => "button"]];
$buttons[] = ["rotulo" => "Ba", "icone" => "fa fa-arrow-down", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-outline-default","type" => "button"]];

$form->campos = $campos;
$form->buttons = $buttons;
$html .= $form->GerarFormulario();



//---------------------MONTANDO FORMULÁRIO COM TABS
$form2 = new GerarForm();
$form2->tabs = [
    ["nome" => "TAB 1","id" => "tb-1", "icone" => "fa fa-home","class" => " active show  "],
    ["nome" => "TAB 2","id" => "tb-2", "icone" => "fa fa-user","class" => ""],
    ["nome" => "TAB 3","id" => "tb-3", "icone" => "fa fa-cogs","class" => ""]
];
$form2->titulo = "TESTE DE FORMULÁRIO COM TABS";
$form2->descricao_titulo = "Testando Formuário gerando por class";

$form2->id_div_form = "teste_formulario";
$form2->form = ["name" => "frm_form","id" => "frm_form", "method" => "_self", "action" => ""];

$campos = [];

$campos[0][] = ["class_col_md" => "col-md-3 pt-2","nome" => "Campo Texto com Icone DIreita", "descricao" => "Descrição de Nome","icone" => "fa fa-list", "parametros"=> ["type" =>"text_icon_right","name" => "nome", "id" => "nome2","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[0][] = ["class_col_md" => "col-md-3 pt-2","nome" => "Campo Texto com Icone Esquerda", "descricao" => "Descrição de Nome","icone" => "fa fa-list", "parametros"=> ["type" =>"text_icon_left","name" => "nome", "id" => "nome2","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[0][] = ["class_col_md" => "col-md-2 pt-2","nome" => "Campo Texto", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"text","name" => "nome_icon2", "id" => "nome_icon2","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[0][] = ["class_col_md" => "col-md-2 pt-2","nome" => "Campo Arquivo", "label" => "Descrição de Nome", "parametros"=> ["type" =>"file","name" => "file2", "id" => "file2","value" => '',"class" => "form-control validar-obrigatorio","accept" => ".jpg, .jpeg, .png, .bmp"]];
$campos[0][] = ["class_col_md" => "col-md-2 pt-2","nome" => "ChecBox", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"checkbox","name" => "nome_check2", "id" => "nome_check2","value" => '',"class" => "custom-control-input"]];
$campos[0][] = ["class_col_md" => "col-md-2 pt-2","nome" => "Switch", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"switch","name" => "nome_switch2", "id" => "nome_switch2","value" => '',"class" => " custom-control-input"]];
$campos[1][] = ["class_col_md" => "col-md-6 pt-2","nome" => "Textarea", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"textarea","name" => "nome_textarea", "id" => "nome_textarea2","rows" => "4","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[1][] = ["class_col_md" => "col-md-6 pt-2","nome" => "Select Multiple","selecionados" => [1,2], "descricao" => "Descrição de Nome","parametros"=> ["type" =>"select","name" => "nome_select_multiples", "id" => "nome_select_multiples2","multiple" => 'multiple',"class" => "form-select validar-obrigatorio"],"options" => [["id" =>1,"nome" => "opção 1"],["id" =>2,"nome" => "opção 2"],["id" =>3,"nome" => "opção 3"]]];
$campos[2][] = ["class_col_md" => "col-md-6 pt-2","nome" => "Select", "descricao" => "Descrição de Nome","parametros"=> ["type" =>"select","name" => "nome_select", "id" => "nome_select2","class" => "form-select validar-obrigatorio"],"options" => [["id" =>1,"nome" => "opção 1"],["id" =>2,"nome" => "opção 2"],["id" =>3,"nome" => "opção 3"]]];
$campos[2][] = ["class_col_md" => "col-md-6 pt-2","nome" => "Select vazio", "descricao" => "Descrição de Nome","parametros"=> ["type" =>"select","name" => "nome_select_vazio", "id" => "nome_select_vazio2","value" => '',"class" => "form-select validar-obrigatorio"]];

// GERANDO UMA LISTA DE RADIOS VERTICAL
$radios = [];
$radios[] = ["nome" =>"Opção 1","type" =>"radio","name" => "nome_radio3", "id" => "nome_radio13","value" => '1',"class" => "custom-control-input"];
$radios[] = ["nome" =>"Opção 2","type" =>"radio","name" => "nome_radio3", "id" => "nome_radio23","value" => '2',"class" => "custom-control-input"];
$radios[] = ["nome" =>"Opção 3","type" =>"radio","name" => "nome_radio3", "id" => "nome_radio33","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-3 pt-2","nome" => "Radio Lista Vertical","parametros"=> ["type" =>"radioslist"],"lista" => $radios];

// GERANDO UM LISTA DE CHECKBOX NA VERTICAL
$checkbox = [];
$checkbox[] = ["nome" =>"Opção 1","type" =>"checkbox","name" => "nome_checkbox14", "id" => "nome_checkbox14","value" => '1',"class" => "custom-control-input"];
$checkbox[] = ["nome" =>"Opção 2","type" =>"checkbox","name" => "nome_checkbox24", "id" => "nome_checkbox24","value" => '2',"class" => "custom-control-input"];
$checkbox[] = ["nome" =>"Opção 3","type" =>"checkbox","name" => "nome_checkbox34", "id" => "nome_checkbox34","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-3 pt-2","nome" => "Checkbox Lista Vertical","parametros"=> ["type" =>"checkboxlist"],"lista" => $checkbox];

// GERANDO UM LISTA DE RADIOS NA HORIZONTAL
$radiosinline = [];
$radiosinline[] = ["nome" =>"Opção 1","type" =>"radio","name" => "nome_radioinline5", "id" => "nome_radio1_inline5","value" => '1',"class" => "custom-control-input"];
$radiosinline[] = ["nome" =>"Opção 2","type" =>"radio","name" => "nome_radioinline5", "id" => "nome_radio2_inline5","value" => '2',"class" => "custom-control-input"];
$radiosinline[] = ["nome" =>"Opção 3","type" =>"radio","name" => "nome_radioinline5", "id" => "nome_radio3_inline5","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-12 pt-4","nome" => "Radio Lista Inline","tipo" => "custom-control-inline","parametros"=> ["type" =>"radioslist"],"lista" => $radiosinline];

// GERANDO UM LISTA DE CHECKBOX NA HORIZONTAL
$checkboxinline = [];
$checkboxinline[] = ["nome" =>"Opção 1","type" =>"checkbox","name" => "nome_checkbox125", "id" => "nome_checkbox125","value" => '1',"class" => "custom-control-input"];
$checkboxinline[] = ["nome" =>"Opção 2","type" =>"checkbox","name" => "nome_checkbox225", "id" => "nome_checkbox225","value" => '2',"class" => "custom-control-input"];
$checkboxinline[] = ["nome" =>"Opção 3","type" =>"checkbox","name" => "nome_checkbox325", "id" => "nome_checkbox325","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-12 pt-4","nome" => "Checkbox Lista Inline","tipo" => "custom-control-inline","parametros"=> ["type" =>"checkboxlist"],"lista" => $checkboxinline];



$buttons = [];
$buttons[] = ["rotulo" => "Salvar", "icone" => "fa fa-check", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-success","type" => "button"]];
$buttons[] = ["rotulo" => "cancelar", "icone" => "fa fa-times", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-warning","type" => "button"]];
$buttons[] = ["rotulo" => "dinheiro", "icone" => "fa fa-money-bill", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-danger","type" => "button"]];
$buttons[] = ["rotulo" => "Esquerda", "icone" => "fa fa-arrow-left", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-info","type" => "button"]];
$buttons[] = ["rotulo" => "Cima", "icone" => "fa fa-arrow-up", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-dark","type" => "button"]];
$buttons[] = ["rotulo" => "Direita", "icone" => "fa fa-arrow-right", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-primary","type" => "button"]];

$form2->campos = $campos;
$form2->buttons = $buttons;
$html .= $form2->GerarFormularioTab();






//---------------------MONTANDO FORMULÁRIO COM CARDS
$form3 = new GerarForm();
$form3->cards = [
    ["nome" => "CARD 1","id" => "card-1", "icone" => "fa fa-home","class" => " bg-primary-500 bg-info-gradient  ","class_col_md" => " col-md-6 "],
    ["nome" => "CARD 2","id" => "card-2", "icone" => "fa fa-user","class" => " bg-primary-500 bg-info-gradient","class_col_md" => " col-md-6 "],
    ["nome" => "CARD 3","id" => "card-3", "icone" => "fa fa-cogs","class" => " bg-primary-500 bg-info-gradient","class_col_md" => " col-md-6 "]
];
$form3->titulo = "TESTE DE FORMULÁRIO COM CARDS";
$form3->descricao_titulo = "Testando Formuário gerando por class";

$form3->id_div_form = "teste_formulario";
$form3->form = ["name" => "frm_form","id" => "frm_form", "method" => "_self", "action" => ""];

$campos = [];

$campos[0][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Campo Texto com Icone DIreita", "descricao" => "Descrição de Nome","icone" => "fa fa-list", "parametros"=> ["type" =>"text_icon_right","name" => "nome", "id" => "nome2","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[0][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Campo Texto com Icone Esquerda", "descricao" => "Descrição de Nome","icone" => "fa fa-list", "parametros"=> ["type" =>"text_icon_left","name" => "nome", "id" => "nome2","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[0][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Campo Texto", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"text","name" => "nome_icon2", "id" => "nome_icon2","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[0][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Campo Arquivo", "label" => "Descrição de Nome", "parametros"=> ["type" =>"file","name" => "file2", "id" => "file2","value" => '',"class" => "form-control validar-obrigatorio","accept" => ".jpg, .jpeg, .png, .bmp"]];
$campos[0][] = ["class_col_md" => "col-md-12 pt-2","nome" => "ChecBox", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"checkbox","name" => "nome_check2", "id" => "nome_check2","value" => '',"class" => "custom-control-input"]];
$campos[0][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Switch", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"switch","name" => "nome_switch2", "id" => "nome_switch2","value" => '',"class" => " custom-control-input"]];
$campos[1][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Textarea", "descricao" => "Descrição de Nome", "parametros"=> ["type" =>"textarea","name" => "nome_textarea", "id" => "nome_textarea2","rows" => "4","value" => '',"class" => "form-control validar-obrigatorio"]];
$campos[1][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Select Multiple","selecionados" => [1,2], "descricao" => "Descrição de Nome","parametros"=> ["type" =>"select","name" => "nome_select_multiples", "id" => "nome_select_multiples2","multiple" => 'multiple',"class" => "form-select validar-obrigatorio"],"options" => [["id" =>1,"nome" => "opção 1"],["id" =>2,"nome" => "opção 2"],["id" =>3,"nome" => "opção 3"]]];
$campos[2][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Select", "descricao" => "Descrição de Nome","parametros"=> ["type" =>"select","name" => "nome_select", "id" => "nome_select2","class" => "form-select validar-obrigatorio"],"options" => [["id" =>1,"nome" => "opção 1"],["id" =>2,"nome" => "opção 2"],["id" =>3,"nome" => "opção 3"]]];
$campos[2][] = ["class_col_md" => "col-md-1212 pt-2","nome" => "Select vazio", "descricao" => "Descrição de Nome","parametros"=> ["type" =>"select","name" => "nome_select_vazio", "id" => "nome_select_vazio2","value" => '',"class" => "form-select validar-obrigatorio"]];

// GERANDO UMA LISTA DE RADIOS VERTICAL
$radios = [];
$radios[] = ["nome" =>"Opção 1","type" =>"radio","name" => "nome_radio3", "id" => "nome_radio13","value" => '1',"class" => "custom-control-input"];
$radios[] = ["nome" =>"Opção 2","type" =>"radio","name" => "nome_radio3", "id" => "nome_radio23","value" => '2',"class" => "custom-control-input"];
$radios[] = ["nome" =>"Opção 3","type" =>"radio","name" => "nome_radio3", "id" => "nome_radio33","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Radio Lista Vertical","parametros"=> ["type" =>"radioslist"],"lista" => $radios];

// GERANDO UM LISTA DE CHECKBOX NA VERTICAL
$checkbox = [];
$checkbox[] = ["nome" =>"Opção 1","type" =>"checkbox","name" => "nome_checkbox14", "id" => "nome_checkbox14","value" => '1',"class" => "custom-control-input"];
$checkbox[] = ["nome" =>"Opção 2","type" =>"checkbox","name" => "nome_checkbox24", "id" => "nome_checkbox24","value" => '2',"class" => "custom-control-input"];
$checkbox[] = ["nome" =>"Opção 3","type" =>"checkbox","name" => "nome_checkbox34", "id" => "nome_checkbox34","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-12 pt-2","nome" => "Checkbox Lista Vertical","parametros"=> ["type" =>"checkboxlist"],"lista" => $checkbox];

// GERANDO UM LISTA DE RADIOS NA HORIZONTAL
$radiosinline = [];
$radiosinline[] = ["nome" =>"Opção 1","type" =>"radio","name" => "nome_radioinline5", "id" => "nome_radio1_inline5","value" => '1',"class" => "custom-control-input"];
$radiosinline[] = ["nome" =>"Opção 2","type" =>"radio","name" => "nome_radioinline5", "id" => "nome_radio2_inline5","value" => '2',"class" => "custom-control-input"];
$radiosinline[] = ["nome" =>"Opção 3","type" =>"radio","name" => "nome_radioinline5", "id" => "nome_radio3_inline5","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-12 pt-4","nome" => "Radio Lista Inline","tipo" => "custom-control-inline","parametros"=> ["type" =>"radioslist"],"lista" => $radiosinline];

// GERANDO UM LISTA DE CHECKBOX NA HORIZONTAL
$checkboxinline = [];
$checkboxinline[] = ["nome" =>"Opção 1","type" =>"checkbox","name" => "nome_checkbox125", "id" => "nome_checkbox125","value" => '1',"class" => "custom-control-input"];
$checkboxinline[] = ["nome" =>"Opção 2","type" =>"checkbox","name" => "nome_checkbox225", "id" => "nome_checkbox225","value" => '2',"class" => "custom-control-input"];
$checkboxinline[] = ["nome" =>"Opção 3","type" =>"checkbox","name" => "nome_checkbox325", "id" => "nome_checkbox325","value" => '3',"class" => "custom-control-input"];
$campos[2][] = ["class_col_md" => "col-md-12 pt-4","nome" => "Checkbox Lista Inline","tipo" => "custom-control-inline","parametros"=> ["type" =>"checkboxlist"],"lista" => $checkboxinline];



$buttons = [];
$buttons[] = ["rotulo" => "Salvar", "icone" => "fa fa-check", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-success","type" => "button"]];
$buttons[] = ["rotulo" => "cancelar", "icone" => "fa fa-times", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-warning","type" => "button"]];
$buttons[] = ["rotulo" => "dinheiro", "icone" => "fa fa-money-bill", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-danger","type" => "button"]];
$buttons[] = ["rotulo" => "Esquerda", "icone" => "fa fa-arrow-left", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-info","type" => "button"]];
$buttons[] = ["rotulo" => "Cima", "icone" => "fa fa-arrow-up", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-dark","type" => "button"]];
$buttons[] = ["rotulo" => "Direita", "icone" => "fa fa-arrow-right", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-primary","type" => "button"]];

$form3->campos = $campos;
$form3->buttons = $buttons;
$html .= $form3->GerarFormularioCard();

echo $objApp->GerarCardContainer($configModulo,$html);
?>

<script>
    $('document').ready(function (){
        $('#demo').daterangepicker({
            "showDropdowns": true,
            "showWeekNumbers": true,
            "showISOWeekNumbers": true,
            "timePicker24Hour": true,
            "startDate": "01/07/2021",
            "endDate": "01/07/2021",
            "minDate": "01/07/2015",
            "maxDate": "01/08/2029",
            "opens": "center",
            "drops": "auto"
        }, function(start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });
    });
</script>
