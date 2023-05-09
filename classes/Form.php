<?php
class Form
{


    public $campos;
    public $fildset;
    public $breadcrumb;

    // dados do Titulo do  formulário
    public $header_icone_titulo = "";
    public $header_titulo = "";
    public $header_descricao = "";

    // dados do painel do formulário
    public $form_tamanho_class = "col-md-12";
    public $form_class_titulo = "Formulário Teste";
    public $form_id_div_form = "id_form1";
    public $form_panel_titullo = "Formulário Teste";

    // dados da TAG FORM
    public $form_permitir_tag = true;
    public $form_tag_padrao = ["name" => "form1","id" => "form1"];
    public $form_tag_atributos = [];

    // dados dos campos
    public $campos_atributos = [];
    public $campos_atributos_input_padrao = ["name" => "campo1","id" => "campo1","class" => "form-control","type" => "text"];

    public function Gerar()
    {

    }

    // Gera o caminho até este módulo
    public function GerarBreadCrumb()
    {
        $html = '    <ol class="breadcrumb page-breadcrumb"> '."\n";;
        if(count($this->breadcrumb) > 0)
        {
            foreach($this->breadcrumb as $br)
            {
                $html .= ($br['href'] != '') ? '<li class="'.$br['class'].'"><a href="'.$br['href'].'">'.$br['text'].'</a></li>'."\n" : '<li class="'.$br['class'].'">'.$br['text'].'</li>'."\n";
            }
        }
        $html .= '<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>'."\n";;
        $html .= '</ol>'."\n";

        return $html;
    }
    // Gera Titulo do Forumário
    public function GerarHeader()
    {
        $html = '<div class="subheader">
        <h1 class="subheader-title">
            <i class="subheader-icon '.$this->header_icone_titulo.'"></i> '.$this->header_titulo.'
            <small>
               '.$this->header_descricao.'
            </small>
        </h1>
        </div>'."\n";

        return $html;
    }
    public function GerarFormulario()
    {
        $html = "";
        if(!empty($this->breadcrumb)) $html .= $this->GerarBreadCrumb();
        if(!empty($this->titulo))$html .= $this->GerarHeader();
        $html .= '    <div class="row">
        <div class="'.$this->form_tamanho_class.'">
            <div id="panel-1" class="panel">
                <div class="'.$this->form_class_titulo.'">
                    <h2>
                         '.$this->form_panel_titullo.'
                    </h2>'."\n";
        $html .='<div class="panel-toolbar">
                        <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                        <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                        <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                    </div>
                </div>
                <div class="panel-container show" >
                    <div class="panel-content" id="'.$this->form_id_div_form.'">'."\n";
        $html .= $this->GerarTagForm();
        $html .= "\t\t\t\t".'<div class="form-body">';
        $html .= "\t\t\t\t\t".'<div class="row p-t-20">';
        $html .= $this->GerarCampos();
        $html .= $this->GerarButtons();
        $html .= "\t\t\t\t\t\t</form>\n
                  \t\t\t\t\t</div>\n
                  \t\t\t\t</div>\n
                  \t\t\t</div>\n
                  \t\t</div>\n
                  \t</div>\n";
        return $html;
    }
    public function TratarAtributos($vetor_padrao,$vetor_enviado)
    {
        foreach($vetor_enviado as $index => $v)
            $vetor_padrao[$index] = $v;
        return $vetor_padrao;
    }
    public function GerarTagForm()
    {
        $atributos = $this->TratarAtributos($this->form_tag_padrao ,$this->form_tag_atributos);
        $html = "\t".'<form ';
        foreach($atributos as $col => $fr)
        {
            $html .= ' '.$col.'="'.$fr.'" ';
        }

        $html .= '>'."\n";
        $html .= "\t\t\t\t".'<div class="form-body">';
        $html .= "\t\t\t\t\t".'<div class="row p-t-20">';
        $html .= $this->GerarCampos();
        $html .= $this->GerarButtons();
        $html .= "\t\t\t\t\t\t</form>\n";

        return $html;
    }
    public function GerarContainerCampo()
    {

        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t\t<div class=\"input-group mb-3\">"."\n";
        $html .= "\t\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '/>'."\n";
        $html .="\t\t\t\t\t\t\t\t\t<div class=\"input-group-append\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<span class=\"".$campo['icone']."\"></span>"."\n";
        $html .="\t\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";

        return $html;

        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .= "\t\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'" ';
        }
        $html .= '/>'."\n";
        $html .="\t\t\t\t\t\t\t\t\t<small class=\"form-text text-muted\"> ".$campo['descricao']." </small> "."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
         return $html;
    }
}