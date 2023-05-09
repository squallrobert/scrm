<?php
class GerarForm
{
    public $form = ["name" => "frm_form","id" => "frm_form", "method" => "_self", "action" => ""];
    public $div_resultado = ["class" => "col-xl-12","titulo" => "Resultado", "id" => "div_resultado","conteudo" => ""];
    public $breadcrumb ;
    public $titulo = "";
    public $icone_titulo = "fa fa-globe";
    public $descricao_titulo = "Formuário de Cadastro";
    public $id_div_form = "conteudo_form";
    public $class_div_buttons = "col-md-12 mt-3";
    public $hiddens = [];
    public $campos = [];
    public $buttons = [];
    public $tabs = [];
    public $cards = [];
    public $class_tamanho_panel = 'col-xl-12';
    public $class_title_head = 'panel-hdr bg-primary-500 bg-info-gradient';


    public function GerarBreadCrumb($print = false)
    {
        $html = '    <ol class="breadcrumb page-breadcrumb"> '."\n";;
        if(@count($this->breadcrumb) > 0)
        {
            foreach($this->breadcrumb as $br)
            {
                $html .= ($br['href'] != '') ? '<li class="'.$br['class'].'"><a href="'.$br['href'].'">'.$br['text'].'</a></li>'."\n" : '<li class="'.$br['class'].'">'.$br['text'].'</li>'."\n";
            }
        }
        $html .= '<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>'."\n";;
        $html .= '</ol>'."\n";

        if($print) echo $html;
        else return $html;
    }
    public function GerarHeader($print = false)
    {
        $html = '<div class="subheader">
        <h1 class="subheader-title">
            <i class="subheader-icon '.$this->icone_titulo.'"></i> '.$this->titulo.'
            <small>
               '.$this->descricao_titulo.'
            </small>
        </h1>
    </div>'."\n";

        if($print) echo $html;
        else return $html;
    }
    public function GerarFormulario($print = false)
    {
        $this->TratarCampos();
        $html = "";
        if(!empty($this->breadcrumb)) $html .= $this->GerarBreadCrumb();
        if(!empty($this->titulo))$html .= $this->GerarHeader();
        $html .= '    <div class="row">
        <div class="'.$this->class_tamanho_panel.'">
            <div id="panel-1" class="panel">
                <div class="panel-container show" >
                    <div class="panel-content" id="'.$this->id_div_form.'">'."\n";
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
        if($print) echo $html;
        else return $html;
    }
    public function GerarFormularioTab($print = false)
    {
        $html = "";
       // $html .= $this->GerarBreadCrumb();
       // $html .= $this->GerarHeader();
        $html .= $this->GerarTagForm();
        $html .= '    <div class="row">
        <div class="'.$this->class_tamanho_panel.'">
            <div id="panel-1" class="panel">
                <div class="'.$this->class_title_head.'">
                    <h2>
                         '.$this->titulo.'
                    </h2>'."\n";
        $html .='        <div class="panel-toolbar">
                        <!--<button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>-->
                        <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                        <!--<button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button-->
                    </div>
                </div>
                <div class="panel-container show" >
                    <div class="panel-content" id="'.$this->id_div_form.'">'."\n";
        $html .= $this->GerarTabs();
        $html .= $this->GerarButtons();
        $html .= "\t\t\t</div>\n
                  \t\t</div>\n
                  \t</div>\n";
        $html .= "\t</form>\n";
        if($print) echo $html;
        else return $html;
    }
    public function GerarFormularioCard($print = false)
    {
        $html = "";
        $html .= $this->GerarBreadCrumb();
        $html .= $this->GerarHeader();
        $html .= $this->GerarTagForm();
        $html .= '<div class="row">';

        $html .= $this->GerarCard();
        $html .= "<div class='col-md-12'>\n";
        $html .= $this->GerarButtons();
        $html .= "</div>\n";
        $html .= "</div>\n";
        $html .= "\t</form>\n";
        if($print) echo $html;
        else return $html;
    }
    public function GerarButton($campo,$print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .= "\t\t\t\t\t\t\t\t\t".'<button ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "hidden" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'" ';
        }
        $html .= '><i class="'.$campo['icone'].'"></i> '.$campo['rotulo'].'</button>'."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    public function GerarButtonMultiple($campos,$print = false)
    {
//        Conexao::pr($campos);
        $html ="\t\t\t\t\t\t<div class=\"".$campos['class_col_md']."\" id=\"".$campos['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campos['nome']."\">".$campos['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t\t<div>"."\n";
        foreach($campos['buttons'] as $campo)
        {
            $html .= "\t\t\t\t\t\t\t\t\t".'<button ';
            foreach($campo['parametros'] as $col => $fr)
            {
                if($col == "hidden" && $fr == "") continue;
                if($col == "disabled" && $fr == "") continue;
                $html .= ''.$col.'="'.$fr.'" ';
            }
            $html .= '><i class="'.$campo['icone'].'"></i> '.$campo['rotulo'].'</button> '."\n";
        }
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    public function GerarButtons($print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$this->class_div_buttons." \">"."\n";
        if(count($this->buttons) > 0)
        {
            foreach($this->buttons as $button)
            {
                $html .= "\t\t\t\t\t\t".'<button ';
                foreach($button['parametros'] as $col => $fr)
                {
                    if($col == "hidden" && $fr == "") continue;
                    if($col == "disabled" && $fr == "") continue;
                    $html .= ''.$col.'="'.$fr.'" ';
                }
                $html .= '><i class="'.$button['icone'].'"></i> '.$button['rotulo'].'</button>'."\n";
            }
        }
        $html .="\t\t\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    public function GerarTagForm($print = false)
    {
        $html = "\t".'<form ';
        foreach($this->form as $col => $fr)
        {
            $html .= ' '.$col.'="'.$fr.'" ';
        }

        $html .= '>'."\n";

        if($print) echo $html;
        else return $html;
    }
    public function TratarCampos($x = '')
    {
        $campos = ($x !== '') ? $this->campos[$x] : $this->campos;
        if(count($campos) > 0)
        {
            foreach($campos as $campo)
            {
                if(strpos($campo['parametros']['class'], 'validar') !== false)
                    $campo['nome'] = "<span class='text-danger'>* </span>" .$campo['nome'];
                $novo_campo[] = $campo;
            }
        }
          ($x !== '') ? $this->campos[$x] = $novo_campo : $this->campos = $novo_campo ;
    }
    public function GerarTipoCampos($campo){
        $html = '';
        switch ($campo['parametros']['type'])
        {
            case "password":
            case "text":
                $html .= $this->GerarCampoText($campo);
                break;
            case "text_group_right":
                $html .= $this->GerarTextGroupRight($campo);
                break;
            case "text_group_left":
                $html .= $this->GerarTextGroupLeft($campo);
                break;
            case "file":
                $html .= $this->GerarCampoFile($campo);
                break;
            case "textarea":
                $html .= $this->GerarTexarea($campo);
                break;
            case "text_icon_right":
                $html .= $this->GerarTextIconRight($campo);
                break;
            case "text_button_right":
                $html .= $this->GerarButtonIconRight($campo);
                break;
            case "text_icon_left":
                $html .= $this->GerarTextIconLeft($campo);
                break;
            case "checkbox":
                $html .= $this->GerarCheckBox($campo);
                break;
            case "radio":
                $html .= $this->GerarRadios($campo);
                break;
            case "switch":
                $html .= $this->GerarSwitch($campo);
                break;
            case "select":
                $html .= $this->GerarSelect($campo);
                break;
            case "hidden":
                $html .= $this->GerarHidden($campo);
                break;
            case "radioslist":
                $html .= $this->GerarRadiosGroup($campo);
                break;
            case "checkboxlist":
                $html .= $this->GerarCheckboxGroup($campo);
                break;
            case "hr":
                $html .= $this->GerarHr($campo);
                break;
            case "button":
                $html .= $this->GerarButton($campo);
                break;
            case "number":
                $html .= $this->GerarCampoNumber($campo);
                break;
            case "fileDropzone":
                $html .= $this->GerarCampoFileDropzone($campo);
                break;
            case "":
                if(count($campo['buttons']) > 0)
                    $html .= $this->GerarButtonMultiple($campo);
                break;
        }
        return $html;
    }
    public function LoopingDivs($campo)
    {
        $html = '';
        $html .= "\t\t\t\t\t".'<div class="'.$campo['class'].'" id ="'.$campo['id'].'">';
        $html .= "\t\t\t\t\t".$campo['conteudo'];
//        if (is_countable($campo['registros']) && count($campo['registros']) > 0) {
        if (count($campo['registros']) > 0) {
            foreach ($campo['registros'] as $registro) {
                if ($registro['type'] == "div_group") {
                    if (count($registro['registros']) > 0) {
                        $html .= $this->LoopingDivs($registro);
                    }
                } else {
                    $html .= $this->GerarTipoCampos($registro);
                }
            }
        }
        $html .= "\t\t\t\t\t".'</div>';
        return $html;
    }
    public function GerarCampos($tab = false,$x= 0)
    {
        $campos = ($tab) ? $this->campos[$x] : $this->campos;
        $html = '';
        if(count($campos) > 0)
        {
            foreach($campos as $campo)
            {
                if ($campo['type'] == "div_group"){
                    $html .= $this->LoopingDivs($campo);
                }else{
                    $html .= $this->GerarTipoCampos($campo);
                }
            }
        }
        return $html;
    }
    public function GerarCamposTab($x)
    {
        return  $this->GerarCampos(true,$x);
    }
    public function GerarHidden($campo, $print = false)
    {
        $html = "\t\t\t\t\t\t";
        $html .= ' <input';
        foreach($campo['parametros'] as $col => $fr)
        {
            $html .= ' '.$col.'="'.$fr.'" ';
        }
        $html .= '/>'."\n";

        if($print) echo $html;
        else return $html;
    }
    /*
     * Gerar Campos Text
     * $campo['nome'] = 'Texto do Label';
     *  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
     *  $campo['descricao'] = 'texto abaixo do campo geralmetne descrição do campo';
     *  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
     */
    public function GerarCampoText($campo,$print = false)
    {
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

        if($print) echo $html;
        else return $html;
    }

    public function GerarCampoNumber($campo,$print = false)
    {
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

        if($print) echo $html;
        else return $html;
    }
    /*
 * Gerar Campos Text
 * $campo['nome'] = 'Texto do Label';
 *  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
 *  $campo['descricao'] = 'texto abaixo do campo geralmetne descrição do campo';
 *  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
 */
    public function GerarCampoFile($campo,$print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t\t\t<div class=\"input-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<div class=\"custom-file\">"."\n";
        $html .= "\t\t\t\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'" ';
        }
        $html .= '/>'."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<label class=\"custom-file-label\" for=\"".$campo['parametros']['name']."\" aria-describedby=\"".$campo['parametros']['name']."\">".$campo['label']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }


    public function GerarCampoFileDropzone($campo,$print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t\t\t<div class=\"input-group\">"."\n";
        $html .= "\t\t\t\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if ($col == "type") $fr = "file";
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'" ';
        }
        $html .= '/>'."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    /*
 * Gerar Campos Text com Icone
 * $campo['nome'] = 'Texto do Label';
 *  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
 *  $campo['icone'] = 'class do icone que aparecera no inputgroup';
 *  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
 */
    public function GerarTextIconRight($campo,$print = false)
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

        if($print) echo $html;
        else return $html;
    }

    public function GerarButtonIconRight($campo,$print = false)
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
        $html .="\t\t\t\t\t\t\t\t\t\t<button class=\"btn btn-default waves-effect waves-themed\" id='{$campo['parametros']['id']}_btn' type=\"button\"><i class='{$campo['icone']}'></i></button>"."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    /*
    * Gerar Campos Text com Icone
    * $campo['nome'] = 'Texto do Label';
    *  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
    *  $campo['icone'] = 'class do icone que aparecera no inputgroup';
    *  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
    */
    public function GerarTextIconLeft($campo,$print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t\t<div class=\"input-group mb-3\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<span class=\"".$campo['icone']."\"></span>"."\n";
        $html .="\t\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .= "\t\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '/>'."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }

    /*
* Gerar Campos Text com Icone
* $campo['nome'] = 'Texto do Label';
*  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
*  $campo['icone'] = 'class do icone que aparecera no inputgroup';
*  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
*/
    public function GerarTextGroupLeft($campo, $print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t\t<div class=\"input-group mb-3\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<div class=\"input-group-text\">"."\n";
        $html .="\t\t\t\t\t\t\t\t\t\t<span >".$campo['textgroup']."</span>"."\n";
        $html .="\t\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .= "\t\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '/>'."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    /*
* Gerar Campos Text com Icone
* $campo['nome'] = 'Texto do Label';
*  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
*  $campo['icone'] = 'class do icone que aparecera no inputgroup';
*  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
*/
    public function GerarTextGroupRight($campo,$print = false)
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
        $html .="\t\t\t\t\t\t\t\t\t\t<span >".$campo['textgroup']."</span>"."\n";
        $html .="\t\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";
        if($print) echo $html;
        else return $html;
    }


    /*
 * Gerar Campos SwiTch
 * $campo['nome'] = 'Texto do Label';
 *  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
 *  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
 */
    public function GerarSwitch($campo,$print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\" style=\"margin-top: 30px\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"custom-control custom-switch\">"."\n";
        $html .= "\t\t\t\t\t\t\t\t".' <input type = "checkbox"';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "switch") continue;
            if($col == "checked" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '/>'."\n";
//        $html .='        <input type="checkbox" '.$campo['checked'].'  name="'.$campo['name']."\" id=\"".$campo['id']."\" value=\"1\" class=\"custom-control-input\" />";
        $html .="\t\t\t\t\t\t\t<label class=\"custom-form-label\" for=\"".$campo['parametros']['name']."\"> ".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;

    }
    /*
* Gerar Campos CHECKBOX
* $campo['nome'] = 'Texto do Label';
*  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
*  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
*/
    public function GerarCheckBox($campo, $print  = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\" style=\"margin-top: 30px\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"custom-control custom-checkbox\">"."\n";
        $html .= "\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "checked" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '/>'."\n";
//        $html .='        <input type="checkbox" '.$campo['checked'].'  name="'.$campo['name']."\" id=\"".$campo['id']."\" value=\"1\" class=\"custom-control-input\" />";
        $html .="\t\t\t\t\t\t\t\t<label class=\"custom-form-label\" for=\"".$campo['parametros']['name']."\"> ".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;

    }
    /*
 * Gerar Campos TEXTAREA
 * $campo['nome'] = 'Texto do Label';
 *  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
 *  $campo['descricao'] = 'texto abaixo do campo geralmetne descrição do campo';
 *  $campo['value'] = 'Valor do Textarea';
 *  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" )';
 */
    public function GerarTexarea($campo, $print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"form-group\">"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"".$campo['parametros']['name']."\">".$campo['nome']."</label>"."\n";
        $html .= "\t\t\t\t\t\t\t\t".'<textarea ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '>'.$campo['parametros']['value'].'</textarea>'."\n";
//        $html .="        <textarea class=\"form-control  \" name=\"".$campo['name']."\"  id=\"".$campo['id']."\" placeholder=\"".$campo['placeholder']."\" >".$campo['value']."</textarea>";
        $html .="\t\t\t\t\t\t\t\t<small class=\"form-text text-muted\"> ".$campo['descricao']." </small>\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    /*
     *
     *  $campo['nome'] = 'Texto do Lavbel';
     *  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
     *  $campo['descricao'] = 'texto abaixo do campo geralmetne descrição do campo';
     *  $campo['icone'] = 'class do icone que aparecera no inputgroup';
     *  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
     *  $campo['otions'] = 'Array com dados do option ex: array(array("value" => "1" , "Text" => "Squall"),array("value" => "1" , "Text" => "Robert"))';
     *  $campo['selecionado'] = 'valor do option selecionado por default';
     */
    public function GerarSelect($campo,$print = false)
    {
        $html = "\t\t\t\t\t\t".'<div class="'.$campo['class_col_md'].'" id="'.$campo['id_div_pai'].'">'."\n"."\t\t\t\t\t\t\t<div class=\"form-group\">"."\n"."
        \t\t\t\t\t\t\t\t<label class=\"form-label\" for=\"nome\">".$campo['nome'].':</label>'."\n";
        $html .= "\t\t\t\t\t\t\t\t\t".'<select ';
        if($campo['primeiro']['nome'] != '') $html .= ' data-allow-clear="true" data-placeholder="'.$campo['primeiro']['nome'] . '"';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "readonly" && $fr == "") continue;
            if($col == "disabled" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '>'."\n";
        $x = 0;
        if($campo['primeiro']['nome'] != '')  $html .= "\t\t\t\t\t\t\t\t\t\t"."<option value='".$campo['primeiro']['id'] ."'>".$campo['primeiro']['nome'] ."</option>\n";
        if(@count($campo['options']) > 0)
        {
            foreach($campo['options'] as $fr)
            {
                $html .= "\t\t\t\t\t\t\t\t\t\t"."<option value='".$fr['id']."'";
                if($fr['data_value'] != "") $html .= " data-value='".$fr['data_value']."'";
                if ($fr['class'] != "") $html .= " class= '".$fr["class"]."'" ;
                if (@in_array($fr['id'], $campo['selecionados'])) $html .= " selected ";
                if ($fr['disabled'] == 1) $html .= " disabled ";
                $html .= ">".$fr['nome']."</option> \n";
                $x++;
            }
        }

        $html .= "\t\t\t\t\t\t\t\t\t"."</select> \n";
        $html .="\t\t\t\t\t\t\t\t\t".'<small class="form-text text-muted"> '.$campo['descricao'].'  </small> '."\n \t\t\t\t\t\t\t\t".'</div>'."\n \t\t\t\t\t\t".'</div>'."\n";

        if($print) echo $html;
        else return $html;
    }
    public function GerarDivResultado($conteudo = '',$print = '')
    {
           $html = "<div class='row'>\t<div class=\"".$this->div_resultado['class']."\">"."\n" ;
           $html .= "\t\t <div id=\"panel-1\" class=\"panel\">"."\n" ;
           $html .= "\t\t\t <div class=\"panel-hdr  \">"."\n" ;
           $html .= "\t\t\t\t <h2>"."\n" ;
           $html .= "".$this->div_resultado['titulo'].""."\n" ;
           $html .= "</h2>"."\n" ;
           $html .= "\t\t\t <div class=\"panel-toolbar\">"."\n" ;
           $html .= "\t\t\t\t ".'<button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>'."\n" ;
           $html .= "\t\t\t </div>"."\n" ;
           $html .= "\t\t\t </div>"."\n" ;
           $html .= "\t\t <div class=\"panel-container show\" >"."\n" ;
           $html .= "\t\t\t<div class=\"panel-content\" id=\"".$this->div_resultado['id']."\" style='{$this->div_resultado['style']}'>"."\n" ;
           $html .= ($conteudo == '') ? "\t\t\t\t ".$this->div_resultado['conteudo']."\n" : "\t\t\t\t ".$conteudo."\n" ;
           $html .= "\t\t\t </div>"."\n" ;
           $html .= "\t\t </div>"."\n" ;
           $html .= "\t </div>"."\n" ;
           $html .= "\t</div></div>"."\n" ;

            if($print) echo $html;
            else return $html;
    }
    public function GerarTabs($print = false)
    {
        if(count($this->tabs) > 0) {
            $html = "";
            $html .= "    <ul class=\"nav nav-tabs\" role=\"tablist\"> \n";
            foreach($this->tabs as $tab)
            {
                $html .= "        <li class=\"nav-item\"> \n";
                $html .= "            <a class=\" ".$tab['class']." nav-link  \" data-toggle=\"tab\" href=\"#".$tab['id']."\" role=\"tab\" aria-selected=\"false\"><i class=\"".$tab['icone']." mr-1\"></i> ".$tab['nome']."</a>\n";
                $html .= "        </li>\n";
            }

            $html .= "    </ul>\n";
            $html .= "    <div class=\"tab-content border border-top-0 p-3\">\n";
            $x = 0;
            foreach($this->tabs as $tab)
            {
                $this->TratarCampos($x);
                $html .= "        <div class=\"tab-pane ".$tab['class']." fade\" id=\"".$tab['id']."\" role=\"tabpanel\">\n";
                $html .= "\t\t\t\t".'<div class="form-body">'."\n";
                $html .= "\t\t\t\t\t".'<div class="row p-t-20">'."\n";
                $html .= $this->GerarCamposTab($x);
                $html .= "\t\t\t\t\t</div>\n
                  \t\t\t\t</div>\n";
                $html .= "        </div> \n";
                $x++;
            }
            $html .= "    </div>\n";
            $html .= "";

            if($print) echo $html;
            else return $html;
        }
    }
    public function GerarCard($print = false)
    {
        if(count($this->cards) > 0) {
            $html = "";
            $x = 0;
            foreach($this->cards as $card)
            {
                $this->TratarCampos($x);
                $html.= "<div class=\"".$card['class_col_md']."\">";
                $html.= "<div id=\"panel-3\" class=\"panel \">";
                $html.= "<div class=\"panel-hdr ".$card['class']."\">";
                $html.= "    <h2>";
                $html.= "        <i class=\"".$card['icone']." mr-1\"></i>".$card['nome']."";
                $html.= "    </h2>";
//                $html.= "    <div class=\"panel-toolbar\">";
//                $html.= "        <button class=\"btn btn-panel waves-effect waves-themed\" data-action=\"panel-collapse\" data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Collapse\"></button>";
//                $html.= "        <button class=\"btn btn-panel waves-effect waves-themed\" data-action=\"panel-fullscreen\" data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Fullscreen\"></button>";
//                $html.= "        <button class=\"btn btn-panel waves-effect waves-themed\" data-action=\"panel-close\" data-toggle=\"tooltip\" data-offset=\"0,10\" data-original-title=\"Close\"></button>";
//                $html.= "    </div>";
                $html.= "</div>";
                $html.= "<div class=\"panel-container show\">";
                $html.= "    <div class=\"panel-content\">";
                $html .= $this->GerarCamposTab($x);
                $html.= "    </div>";
                $html.= "</div>";
                $html.= "</div>";
                $html.= "</div>";
                $x++;
            }

            if($print) echo $html;
            else return $html;
        }
    }

/* Gerar Campos CHECKBOX
* $campo['nome'] = 'Texto do Label';
*  $campo['class_col_md'] = 'Class BootStrap do Tamanho Ex: col-md-12';
*  $campo['parametros'] = 'Array com atributos do campo ex: array("name" => "nome", "id" => "id" , "value" => "SQUALL")';
*/
    public function GerarRadios($campo,$print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campo['class_col_md']."\" id=\"".$campo['id_div_pai']."\" style=\"margin-top: 30px\">"."\n";
        $html .="\t\t\t\t\t\t\t<div class=\"custom-control custom-radio\">"."\n";
        $html .= "\t\t\t\t\t\t\t\t".'<input ';
        foreach($campo['parametros'] as $col => $fr)
        {
            if($col == "checked" && $fr == "") continue;
            $html .= ''.$col.'="'.$fr.'"';
        }
        $html .= '/>'."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"custom-form-label\" for=\"".$campo['parametros']['name']."\"> ".$campo['nome']."</label>"."\n";
        $html .="\t\t\t\t\t\t\t</div>"."\n";
        $html .="\t\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;

    }
    public function GerarRadiosGroup($campos,$print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campos['class_col_md']."\" id=\"".$campos['id_div_pai']."\" >"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" >".$campos['nome']."</label>"."\n";
        foreach($campos['lista'] as $campo)
        {
            $html .="\t\t\t\t\t\t\t<div class=\"custom-control custom-radio ".$campos['tipo']."\" style='margin: 0.175rem 0.5rem 0 !important'>"."\n";
            $html .= "\t\t\t\t\t\t\t\t".'<input ';
            foreach($campo as $col => $fr)
            {
                if($col == "nome") continue;
                if($col == "checked" && $fr == "") continue;
                $html .= ''.$col.'="'.$fr.'"';
            }
            $html .= '/>'."\n";
            $html .="\t\t\t\t\t\t\t\t<label class=\"custom-form-label\" for=\"".$campo['id']."\"> ".$campo['nome']."</label>"."\n";
            $html .="\t\t\t\t\t\t\t</div>"."\n";
        }
        $html .="\t\t\t\t\t\t</div>"."\n";

        if($print) echo $html;
        else return $html;
    }
    public function GerarCheckboxGroup($campos, $print = false)
    {
        $html ="\t\t\t\t\t\t<div class=\"".$campos['class_col_md']."\" id=\"".$campos['id_div_pai']."\" >"."\n";
        $html .="\t\t\t\t\t\t\t\t<label class=\"form-label\" >".$campos['nome']."</label>"."\n";
        foreach($campos['lista'] as $campo)
        {
            if($campo['style'] == "")  $campo['style'] = 'checkbox';
            $html .="\t\t\t\t\t\t\t<div class=\"custom-control custom-".$campo['style']." ".$campos['tipo']."\" style='margin: 0.175rem 0.5rem 0 !important'>"."\n";
            $html .= "\t\t\t\t\t\t\t\t".'<input ';
            foreach($campo as $col => $fr)
            {
                if($col == "nome" || $col == "style") continue;
                if($col == "checked" && $fr == "") continue;
                $html .= ''.$col.'="'.$fr.'"';
            }
            $html .= '/>'."\n";
            $html .="\t\t\t\t\t\t\t\t<label class=\"custom-form-label\" for=\"".$campo['id']."\"> ".$campo['nome']."</label>"."\n";
            $html .="\t\t\t\t\t\t\t</div>"."\n";
        }
        $html .="\t\t\t\t\t\t</div>"."\n";
        if($print) echo $html;
        else return $html;

    }

    public function GerarHr($campo,$print = false){
        $html = '<div class="'.$campo['class_col_md'].'">
                    <h2>'.$campo['titulo'].'</h2>
                    <hr style="margin-top: -5px; background-color: #eceaea">
                </div>';

        if($print) echo $html;
        else return $html;
    }
}
