<?php

/**
 * @author squall
 * @copyright 2009
 */
class Formulario
{
	public $id;
	public $nome;
	public $value;
	public $onclick;
	public $onchange;
	public $modelo;
	public $padrao;
    public $tipo;
    public $nome_unidade;
    public $col_md = "placeholder-col-md"; //Adiciona a classe para manipulação via js para campos de tamanho dinâmico

	public function __construct()
	{

	}
	public function __set( $propriedade, $valor )
	{
	   $this->$propriedade = $valor;
	}

	public function __get( $propriedade )
	{
	  return $this->$propriedade;
	}

	public function Text()
	{

        $html = '    <div class="col-md-12">'."\n".'
                        <div class="form-group">'."\n".'
                            <label class="form-label" for="nome">'.$this->nome.':</label>'."\n".'
                            <input class="form-control" type="text" placeholder="'.$this->tipo.'"  name="'.$this->modelo. '[' . $this->identificador .']" ';
        if($this->id != "")
            $html .= " id='".$this->id."'";
        else
            $html .= " id='".$this->nome."'";

        if($this->onclick != "")  $html .= " onclick='".$this->onclick."'";
        if($this->onclick != "")  $html .= " onchange='".$this->onchange."'";

        $html .= "value='".$this->padrao."' /\n";

        $html .='                             <small class="form-text text-muted"> '.$this->nome_unidade.' </small> '."\n".'
                             </div>'."\n".'
                       </div>'."\n";
        return $html;
	}
    public function Hidden()
	{
	    $html = '    <div class="col-md-12">'."\n".'
                        <div class="form-group">'."\n".'
                            <label class="form-label" for="nome">'.$this->nome.':</label>'."\n".'
                            <input type="text"  class="form-control" readonly="readonly"  name="'.$this->modelo. '[' . $this->identificador .']" ';
        if($this->id != "")
            $html .= " id='".$this->id."'";
        else
            $html .= " id='".$this->nome."'";
        $html .= "value='".$this->padrao."' />\n";

        $html .='                             <small class="form-text text-muted"> '.$this->nome.' </small> '."\n".'
                             </div>'."\n".'
                       </div>'."\n";
		return $html;
	}
	public function RadioCheckbox()
    {
		$dados = explode("\n",$this->value);
        $x = 0;

        $html = "";
        if (count($dados) != 1)
        {
            $html .= "<div class=' p-20'>";
            $html .= "<label>" . $this->nome . ": </label></div><div class='col-md-8'>";
            $x = 0;
            foreach($dados AS $row)
            {
                list($valor,$rotulo) = explode ("|",$row);

                (defined(trim($rotulo)))
                    ? $rotulo = constant(trim($rotulo))
                    : null;

                $html .= "<input type='radio' name=\"".$this->modelo. "[" . $this->identificador ."]\"";
                $html .= " id='".$rotulo."'";
                if($valor == $this->padrao) $html .= " checked ";
                if($this->onclick[$x] != "")  $html .= " onclick='".$this->onclick[$x]."'";
                if($this->onclick[$x] != "")  $html .= " onchange='".$this->onclick[$x]."'";
                $html .= "value='".$valor."' /> $rotulo <br>";
                $x++;
            }
            $html .= "</div>";
        }
        else
        {
            $html = "<div class=' p-20'>";
            $html .=     '<div class="funkyradio">';
            $html .=        '<div class="funkyradio-primary col-md-12" style="margin: -10px 20px 0 20px;">';
            foreach($dados as $row)
            {
                list($valor,$rotulo) = explode ("|",$row);

                ($rotulo == "")
                    ? $rotulo = $x
                    : null;

                $html .= '<input type="checkbox" id="_'. $this->modelo .'" name="'.$this->modelo. "[" . $this->identificador.']" ';
                if($valor == $this->padrao) $html .= " checked ";
                if($this->onclick[$x] != "")  $html .= " onclick='".$this->onclick[$x]."'";
                if($this->onclick[$x] != "")  $html .= " onchange='".$this->onclick[$x]."'";
                $html .= "value='".$valor."' /> ";
                $html .= '<label for="_'. $this->modelo .'" style="padding-bottom: 0px;">'.$this->nome.'</label>';
                $x++;
            }
            $html .=          '</div>';
            $html .=        '</div>';
            $html .=    '</div>';
        }

        return $html;
	}
	public function Select()
	{

        $html = '    <div class="col-md-12">'."\n".'
                        <div class="form-group">'."\n".'
                            <label class="form-label" for="nome">'.$this->nome.':</label>'."\n";
        $html .= "<select class='form-control select2' name=\"".$this->modelo. "[" . $this->identificador ."]\"";
        if($this->onclick != "")  $html .= " onchange='".$this->onclick."'";
        $html .= ">";

        $x = 0;

        $dados = explode("\n",$this->value);

        foreach($dados as $row)
        {
            list($valor,$rotulo) = explode ("|",$row);

            $html .= "<option value='$valor'";
            if($valor == $this->padrao) $html .= " selected ";

            (defined(trim($rotulo)))
                ? $html .= ">".constant(trim($rotulo))."</option>"
                : $html .= ">{$rotulo}</option>";

            $x++;
        }
        $html .= "</select>";

        $html .='                             <small class="form-text text-muted"> '.$this->nome_unidade.' </small> '."\n".'
                             </div>'."\n".'
                       </div>'."\n";
        return $html;
	}
	public function Checkbox()
	{
		$html  = "<tr><td align='right'>";
		$html .= "" . $this->nome . ": </td><td>";
		$x = 0;
		$dados = explode("\n",$this->value);
		foreach($dados as $row)
		{
			list($valor,$rotulo) = explode ("|",$row);
			$html .= "<input type='checkbox' name=\"".$this->modelo. "[" . $this->identificador ."][]\"";
			$html .= " id='".$rotulo."'";
			if($valor == $this->padrao) $html .= " checked ";
			if($this->onclick[$x] != "")  $html .= " onclick='".$this->onclick[$x]."'";
			if($this->onclick[$x] != "")  $html .= " onchange='".$this->onclick[$x]."'";
			$html .= "value='".$valor."' /> $rotulo <br>";
			$x++;

		}
		$html .= "</td><td><em>".$this->tipo."</em></td></tr>";
		return $html;
	}
	public function Textarea()
	{
	    $html = "<div class='p-20'>";
            $html  .= "<tr><td align='right'>" . $this->nome . ": </td><td>";
            $html .= "<textarea name=\"".$this->modelo. "[" . $this->identificador ."]\"";

            if($this->id != "")
                $html .= " id='".$this->id."'";
            else
                $html .= " id='".$this->nome."'";

            if($this->onclick != "")  $html .= " onclick='".$this->onclick."'";
            if($this->onclick != "")  $html .= " onchange='".$this->onchange."'";
            $html .= ">".$this->padrao."</textarea></td><td><em>".$this->tipo."</em></td></tr>";
        $html .= '</div>';
		return $html;
	}
    
   	public function Macro($id_grupo)
	{

        $html = "<div class='p-20'>";
        $html .= "<label>" . $this->nome . ": </label></div><div class='col-md-10'>";
		$html .= "<select class='form-control' name=\"".$this->modelo. "[" . $this->identificador ."]\"";
		if($this->onclick != "")  $html .= " onchange='".$this->onclick."'";
		$html .= ">";
        $html .= "</div>";
		$x = 0;
        $macro = new Macro();
        $value = $macro->ListaMacrosTela($id_grupo);
	

		foreach($value as $row)
		{
			$html .= "<option value=\"".$row->id."\"";
			if($valor == $this->padrao) $html .= " selected ";
			$html .= ">".$row->nome."</option>";
			$x++;
		}
		$html .= "</select>";
		$html .= "</td><td><em>".$this->tipo."</em></td></tr>";
		return $html;
	} 
}

?>