<?php
$form = new GerarForm();
$form->tabs = [
    ["nome" => "Dados Usuário","id" => "tb-1", "icone" => "fa fa-user","class" => " active show  "],
    ["nome" => "Contato e Endereço","id" => "tb-2", "icone" => "fa fa-globe","class" => ""]
];
$form->breadcrumb =  [
    ["class" => "breadcrumb-item active" , "text" => "Meus Dados","href" => "javascript:void(0);"]
];

$form->titulo = "Meus Dados";
$form->icone_titulo = "fa fa-user";$form->descricao_titulo = "Alteracão Meus Dados";
$form->id_div_form = "div_meus_dados";
$form->form = ["name" => "frm_meus_dados","id" => "frm_meus_dados", "method" => "post"];

$campos[0][] = ["parametros"=> ["type" =>"hidden","name" => "id", "id" => "id","value" => $linha['id_usuario']]];
$campos[0][] = ["class_col_md" => "col-md-4 pt-2",
            "nome" => " Nome",
            "parametros" => ["type" => "text",
                            "name" => "nome_usuario",
                            "id" => "nome_usuario",
                            "value" => $linha['nome'],
                            "class" => "form-control  validar-obrigatorio "
]];
$campos[0][] = ["class_col_md" => "col-md-4 pt-2",
            "nome" => " Usuário",
            "parametros" => ["type" => "text",
                            "name" => "usuario",
                            "id" => "usuario",
                            "value" => $linha['usuario'],
                            "class" => "form-control  validar-obrigatorio "
]];
$campos[0][] = ["class_col_md" => "col-md-4 pt-2",
            "nome" => " Email",
            "parametros" => ["type" => "text",
                            "name" => "email_user",
                            "id" => "email_user",
                            "value" => $linha['email_usuario'],
                            "class" => "form-control  validar-obrigatorio "
]];
$campos[0][] = ["class_col_md" => "col-md-4 pt-2",
            "nome" => " Nova Senha",
            "parametros" => ["type" => "password",
                            "name" => "nova_senha",
                            "id" => "nova_senha",
                            "value" => "",
                            "class" => "form-control  validar-obrigatorio "
]];
$campos[0][] = ["class_col_md" => "col-md-4 pt-2",
            "nome" => " Repetir Nova Senha",
            "parametros" => ["type" => "password",
                            "name" => "repetir_nova_senha",
                            "id" => "repetir_nova_senha",
                            "value" => "",
                            "class" => "form-control  validar-obrigatorio "
]];

$arrayFusoHorario = [
    ["id" => "America/Sao_Paulo", "nome" => "ES / GO / MG / PR / RJ / RS / SP / DF / SC"],
    ["id" => "America/Fortaleza", "nome" => "CE / MA / PB / PI / RN"],
    ["id" => "America/Belem", "nome" => "Amapá / Pará"],
    ["id" => "America/Maceio", "nome" => "Sergipe / Alagoas"],
    ["id" => "America/Bahia", "nome" => "Bahia / Tocantins"],
    ["id" => "America/Rio_branco", "nome" => "Acre"],
    ["id" => "America/Manaus", "nome" => "Amazonas"],
    ["id" => "America/Cuiaba", "nome" => "Mato Grosso"],
    ["id" => "America/Campo_Grande", "nome" => "Mato Grosso do Sul"],
    ["id" => "America/Recife", "nome" => "Pernambuco"],
    ["id" => "America/Porto_Velho", "nome" => "Rondônia"],
    ["id" => "America/Boa_Vista", "nome" => "Roraima"],
];

$campos[0][] = ["class_col_md" => "col-md-4 pt-2",
            "nome" => "Fuso Horario",
            "selecionados" =>[$linha['timezone']],
            "parametros"=> [
                             "type" =>"select",
                             "name" => "usu_timezone",
                              "id" => "usu_timezone",
                              "class" => "form-control validar-obrigatorio-select2 select2"],
            "options" => 
            $arrayFusoHorario        
];

$campos[0][] = ["class_col_md" => "col-md-12 pt-2",
            "nome" => "Foto",
            "label" => "Foto",
            "parametros"=> ["type" =>"fileDropzone",
                            "name" => "foto",
                            "id" => "foto",
                            "value" => '',
                            "class" => "form-control dropify",
                            "accept" => ".jpg, .jpeg, .png, .bmp"
                            ]
            ];


/*DADOS endereco padrao*/

$campos[1][] = ["class_col_md" => "col-md-2 pb-2 pt-3",
                "nome" => "CEP",
                "parametros" => ["type" => "text",
                                "name" => "cep",
                                "id" => "cep",
                                "value" => "{$linha['cep']} ",
                                "class" => "form-control  validar-obrigatorio mask-cep",
                                "onblur"=> "BuscarCep(this.value)"
]];
$campos[1][] = ["class_col_md" => "col-md-8 pb-2 pt-3",
                "nome" => "Logradouro",
                "parametros" => ["type" => "text",
                                "name" => "logradouro",
                                "id" => "logradouro",
                                "value" => "{$linha['logradouro']} ",
                                "class" => "form-control",
                                "maxlength" => "255"
]];
$campos[1][] = ["class_col_md" => "col-md-2 pb-2 pt-3",
                "nome" => "Número",
                "parametros" => ["type" => "text",
                                "name" => "numero",
                                "id" => "numero",
                                "value" => "{$linha['numero']} ",
                                "class" => "form-control mask-numeros",
                                "maxlength" => "6"
]];
$campos[1][] = ["class_col_md" => "col-md-3 pb-2 pt-3",
                "nome" => "Bairro",
                "parametros" => ["type" => "text",
                                "name" => "bairro",
                                "id" => "bairro",
                                "value" => "{$linha['bairro']} ",
                                "class" => "form-control",
                                "maxlength" => "255"
]];


$estado    = new Uf();
$registrosEstado = $estado->ComboUf();
$campos[1][] = ["class_col_md" => "col-md-3 pb-2 pt-3",
            "nome" => "UF",
             "selecionados" =>[$linha['id_estado']],
              "parametros"=> [
                             "type" =>"select",
                             "name" => "id_estado",
                              "id" => "id_estado",
                              "onchange" => "ListarSelect("."'index_xml.php?app_modulo=cidades&app_comando=filtrar_cidade&app_codigo='".",'#id_cidade',this.value)",
                              "class" => "form-control select2 m-b-20 m-r-10"],
            "options" => 
            $registrosEstado        
];
$cidades = new Cidades();
$cidades->setIdEstado($linha['id_estado']);
$registrosCidade = $cidades->ComboCidade();

$campos[1][] = ["class_col_md" => "col-md-3 pb-2 pt-3",
            "nome" => "Cidade",
             "selecionados" =>[$linha['id_cidade']],
              "parametros"=> [
                             "type" =>"select",
                             "name" => "id_cidade",
                              "id" => "id_cidade",
                              "class" => "form-control select2 m-b-20 m-r-10"],
            "options" => 
            $registrosCidade        
];

$campos[1][] = ["class_col_md" => "col-md-3 pb-2 pt-3",
                "nome" => "Complemento",
                "parametros" => ["type" => "text",
                                "name" => "complemento",
                                "id" => "complemento",
                                "value" => "{$linha['complemento']} ",
                                "class" => "form-control",
                                "maxlength" => "50"
]];
$campos[1][] = ["class_col_md" => "col-md-4 pb-2 pt-3",
                "nome" => "Falar com",
                "parametros" => ["type" => "text",
                                "name" => "falar_com",
                                "id" => "falar_com",
                                "value" => "{$linha['falar_com']} ",
                                "class" => "form-control",
                                "maxlength" => "50"
]];
$campos[1][] = ["class_col_md" => "col-md-4 pb-2 pt-3",
                "nome" => "Telefone",
                "icone" => "fa fa-phone-square",
                "parametros" => ["type" =>"text_icon_right",
                                "name" => "comercial",
                                "id" => "comercial",
                                "value" => ($linha['ddd_comercial']  == '')? '':"(".$linha['ddd_comercial'] .") ".$linha['comercial'] ,
                                "class" => "form-control",
                                "maxlength" => "50"
]];

$campos[1][] = ["class_col_md" => "col-md-4 pb-2 pt-3",
                "nome" => "Celular",
                "icone" => "fa fa-mobile",
                "parametros" => ["type" =>"text_icon_right",
                                "name" => "celular",
                                "id" => "celular",
                                "value" =>(empty($linha['ddd_celular'])) ? '':"(".$linha['ddd_celular'] .") ".$linha['celular'] ,
                                "class" => "form-control",
                                "maxlength" => "20"
]];

$campos[1][] = ["class_col_md" => "col-md-6 pb-2 ",
                "nome" => "Referência",
                "parametros" => ["type" => "text",
                                "name" => "referencia",
                                "id" => "referencia",
                                "value" => "{$linha['referencia']} ",
                                "class" => "form-control",
                                "maxlength" => "255"
]];

$campos[1][] = ["class_col_md" => "col-md-6 pb-2",
                "nome" => "Observações",
                "parametros" => ["type" => "text",
                                "name" => "observacao",
                                "id" => "observacao",
                                "value" => "{$linha['observacao']} ",
                                "class" => "form-control"
]];

$buttons[] = ["rotulo" => "Salvar", "icone" => "fa fa-check", "parametros"=> [ "id" => "bt_salvar","class" => "btn btn-success","type" => "button"]];
$form->campos = $campos;
$form->buttons = $buttons;
echo $form->GerarFormularioTab(true);

include_once("modulos/usuario/template/js.frm.meus_dados.php");
 ?>
