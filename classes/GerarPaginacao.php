<?php 

class GerarPaginacao
{
	/**
	 * Lista de linhas de totais com as celulas que devem ser montadas
	 * @var array of array
	 */
	public $totais = array();	
	/**
	 * Total de registro sem o limit e offset
	 * @var int
	 */
	public $totalRegistros;
	/**
	 * Número de registro por pagina
	 * @var unknown_type
	 */
	public $numeroRegistros;
	/**
	 * Offset - qual o número do registro inicial na listagem do banco de dados.
	 * @var int
	 */	 
	public $numeroRegistroInicio;
	/**
	 * Número da pagina atual em exibição
	 * @var int
	 */
	public $pagina;
	/**
	 * Mensagem de rodapé.
	 * @var string
	 */
    public $mensagem_rodape = "";
    /**
     * Nome da função javascript de atualização da grid.
     * @var string
     */
    public $atualizar_grid = "AtualizarGrid";
    /**
	 * ID da tabela da grid
	 * @var string
	 */
    public $idTabela = "MYTABLE";
	/**
	 * ID da caixa de opções de navegação de página (valor da propriedade id)
	 * @var string
	 */
    public $id_pagina = "id_pagina";
    /**
     * Método de postagem dos forms da grid.
     * @var string
     */
    public $method = "post";
    /**
     * css para os botões da grid - Exemplo:"ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
     * @var string
     */
    public $cssBotoes = "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only";
    
	public function CriarPaginacao() 
	{
		//iniciando paginacao
	     $html .= "<table width=\"98%\" class=\"tabela_paginacao\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";
	
	     if($this->mensagem_rodape != "")
	     {
	        $html .= "<tr>";
	        $html .= "<td colspan='6' align='center'><div class='caixa_msg'>$this->mensagem_rodape</div></td>";
	        $html .= "</tr>";
	      }
	
	    	$html .= "<tr> \n";
	     	$html .= "<td align=\"left\" width=\"30%\" valign=\"middle\">&nbsp; \n";
	
	    	// gerando o link para pagina anterior
	    	if($this->pagina != 0 )
	    	{
	    		$paginas = $this->pagina - 1;
	    		$html .= "<a href=\"#?\" onclick=\"{$this->atualizar_grid}({$paginas})\">".ROTULO_ANTERIOR." </a> \n";
	    	}
	    	$html .= "</td> \n";
	    	$html .= "<td align=\"center\" width=\"40%\"> \n";
	    	//fazendo calculos para numero de registros e paginas
	    	$totalRegitrosPagina =$this->numeroRegistroInicio + $this->numeroRegistros;
	    	$total = ceil($this->totalRegistros/$this->numeroRegistros);
	    	$paginaAtual = $this->pagina + 1;
	
	    	$html .= "<table align=\"center\" width=\"400\"> \n";
	    	$html .= "<tr> \n";
	    	$html .= "<td> ".ROTULO_PAGINA." $paginaAtual de $total ($this->totalRegistros ".TEXTO_TOTALIZADOR_FINAL.")</td> \n";
	    	$html .= "<td> \n";
	    	$html .= "<select size=\"1\"  id=\"{$this->id_pagina}\" name=\"pagina\" onchange=\"{$this->atualizar_grid}(document.getElementById('{$this->id_pagina}').value,'{$this->filtro}','{$this->ordem}')\"> \n";
	    	// verificando se tem mais de uma pagina
	    	if($total > 0)
	    	{
	    		// fazendo looping com paginas para montar o list
	    		for($i = 1; $i <= $total; $i++)
	    		{
	    			$idpag = $i - 1;
	    			$html .= "<option value=\"$idpag\"  \n"; if($idpag == $this->pagina) $html .= "selected \n"; $html .= ">".ROTULO_PAGINA." $i</option> \n";
	    		}
	    	}
	    	$html .= "</select> \n";
	    	$html .= "</td> \n";
	    	$html .= "</tr> \n";
	    	$html .= "</table> \n";
	    	$html .= "</td> \n";
	    	$html .= "<td align=\"right\" width=\"30%\" valign=\"middle\">&nbsp; \n";
	
	    	// gerando link para a proxima pagina
	    	if($this->pagina < ($total - 1))
	    	{
	    		$paginas = $this->pagina + 1;
	    		$html .= "<a href=\"#?\" onclick=\"{$this->atualizar_grid}({$paginas})\">".ROTULO_PROXIMO."</a> \n";
	    	}
	
	    	$html .= "</td> \n";
	    	$html .= "</tr> \n";
	    $html .= "</table> \n";
	
	/*
	    $html .= "<table class=\"tablesortere\" width=\"100%\" cellspacing=0 cellpading=0  id=\"".$this->idTabela."s\">\n \n";
	
	    $html .= "<tr> \n";
	    $html .= "<td colspan=\"$numeroColunas\" class=\"top_tabela_grid\" align=\"LEFT\"> \n";
	    $html .= "</td> \n";
	    $html .= "</tr> \n";
	    $html .= "</table> \n";
	    $html .= "</form> \n";
	    $html .= "</tr> \n";
	    $html .= "</table> \n";*/
	    return $html;
	}
}
  ?>