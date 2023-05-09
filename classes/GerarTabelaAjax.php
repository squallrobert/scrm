    <?php
class GerarTabelaAjax
{
	// define  se vai aparecer o form de busca no grid
	public $buscaAtiva;
	// array de dados vindo do banco ex: $dados['nome'] = $nome;
	public $dados;
	// array com as colunas referentes as colunas do array de dados ex: $coluna[] = "Nome";
	public $colunas;
	// total de registro sem o limit e offset
	public $totalRegistros;
	//numero de registro por pagina
	public $numeroRegistros;
	//offset
	public $numeroRegistroIncio;
	//pagina atual
	public $pagina;
	// nome da lista de dados (titulo da pagina)
	public $nome;
	// botao deletar (ex: null n�o mostra bot�o)
	public $botao;
	// valor do request['busca'];
	public $busca;
	// define se ira aparecer o bot�o adicionar
	public $botaoAdicionar;
	//nome do formul�rio para ac��o de java script
	public $nomeForm;
	// caminho dos aquivos de css e js ex: o padr�o js/table/jquery-1.1.3.js , vc usa isso para dicionar mais coisas no inico
	// ex: $this->caminho js/table/jquery-1.1.3.js == ../js/table/jquery-1.1.3.js caminho =
	public $caminho;
	// define se vai aparecer paginacao (ex : $this->paginacao != "" mostra paginacao)
	public $paginacao;
	// complemente serve para adicionar novos itens a url de paginacao
	// ex: ?acao=listar&pagina=$paginas&busca=$this->busca&{$this->complemento}
	public $complemento;
	//bt_adicionais .. server para adicionar bot�es entre o de adicionar e o de remover
	public $bt_adicionais;
	//vetor tamanho de caca coluna na mesma ordem do \$this->colunas
	public $tamanho_colunas;
	// vetor com tipo de alinhamento de acordo com this->dados
	public $center;

	public $divisor;

    public $mensagem_rodape;

    public $idTabela = "MYTABLE";

    public $multiTabelas = false;

	// variaveis de ação
	public $acaoAdicionar;
	public $acaoDeletar ;
	public $acaoModificar;
	public $acaoListar ;
	public $acaoVizualizar;

	public $modulo ;
	public $comando ;
	public $codigo ;

    public $method;
    public $mensagem_bt_busca;


	public function GerarTabelaAjax()
	{
		// definindo valores padr�es para variaveis
		$this->buscaAtiva = false;
		$this->nomeForm = "grid_formulario";
		$this->botao = ucfirst(ROTULO_EXCLUIR);
		$this->paginacao = 1;
		$this->caminho = URL_GERADOR;
        $this->method = "post";
        $this->mensagem_rodape = "";
        $this->mensagem_bt_busca = "";
	}

	public function CriarTabela()
	{
		// pegando total de colunas para fazer o colspan no HTML
		$numeroColunas = count($this->colunas) + 1;

		// incluindo JS necesarios
		if($this->multiTabelas == false)
		{
			//$html .= '<script type="text/javascript" src="'.$this->caminho.'js/table/jquery-1.1.3.js"></script>' . " \n";
			//$html .= '<script type="text/javascript" src="'.$this->caminho.'js/table/jquery.dimensions.pack.js"></script>' . " \n";
			$html .= '<script type="text/javascript" src="js/table/jquery.tablesorter.pack.js"></script>' . " \n";
		}
		// fun��o necessaria para checar selecionados
		$html .= '<script type="text/javascript">' . " \n";
		$html .= 'function SendForm(nomeform)' . " \n";
		$html .= '{' . " \n";
		$html .= '		var ok =0;' . " \n";
		$html .= '		var lista = document.getElementsByName(\'lista[]\');' . " \n";
		$html .= '		for (cont=0; cont<lista.length;cont++)' . " \n";
		$html .= '		{' . " \n";
		$html .= '			var tis = lista[cont].checked' . " \n";
		$html .= '			if(tis == true)' . " \n";
		$html .= '			{' . " \n";
		$html .= '				ok = 1;' . " \n";
		$html .= '			}' . " \n";
		$html .= '		}' . " \n";
		$html .= '		' . " \n";
		$html .= '		if(ok == 1)	' . " \n";
		$html .= '		{' . " \n";
		$html .= '			if(window.confirm("'.ROTULO_DESEJA_REMOVER_REGISTROS.'"))';
		$html .= '			{' . " \n";
		$html .= '				document.getElementById(\''.$this->nomeForm.'\').submit();' . " \n";
		$html .= '			}' . " \n";
		$html .= '		}' . " \n";
		$html .= '		else' . " \n";
		$html .= '		{' . " \n";
		$html .= '			alert("'.RETULO_NENHUM_ITEM_SELECIONADO.'");' . " \n";
		$html .= '		}' . " \n";
		$html .= '}' . " \n";
		$html .= '</script>' . " \n";
		// Fim do bloco de checagem dos seleciondos

		// verificando se existem dados para come�r a gerar a tabela
		if(count($this->dados) > 0)
		{
			if($this->multiTabelas == false)
			{
				//Iniciando bloco de JS para determinar colunas que poder ser ordenadas
				$html .= '<script type="text/javascript">' . " \n";
				$html .= 'jQuery(document).ready(function() {' . " \n";
				$html .= 'jQuery("#'.$this->idTabela.'").tablesorter({' . " \n";
				$html .= 'widgets: [\'zebra\'],' . " \n";
				$html .= "headers:
				{ \n";

				$dd=0;
				$virgula = "";
				$sorter = "";
				//fazendo looping com as colunas
				foreach($this->colunas as $tipo)
				{
                    if (!is_array($tipo))
                    {
                        if($tipo == "img" || $tipo == "IMG" || $tipo == "Img")
                        {
                            $sorter .= "$virgula $dd: { sorter: false } \n";
                            $virgula = ",";
                        }
                        elseif($tipo == "box" || $tipo == "BOX" || $tipo == "Box")
                        {
                            $sorter .= "$virgula $dd: { sorter: false } \n";
                            $virgula = ",";
                        }
                        elseif($tipo == ROTULO_ALTERAR|| $tipo == "alterar" || $tipo == ROTULO_SELECIONE)
                        {
                            $sorter .= "$virgula $dd: { sorter: false } \n";
                            $virgula = ",";
                        }
                        elseif( strpos($tipo, "checkbox") !== false )
                        {
                            $sorter .= "$virgula $dd: { sorter: false } \n";
                            $virgula = ",";
                        }
                        elseif( strpos($tipo, "img") !== false )
                        {
                            $sorter .= "$virgula $dd: { sorter: false } \n";
                            $virgula = ",";
                        }
                        elseif($tipo == "#" )
                        {
                            $sorter .= "$virgula $dd: { sorter: false } \n";
                            $virgula = ",";
                        }
                    } else {
                        if ($tipo['sorter'] == "digit") {
                            $sorter .= "$virgula $dd: { sorter: 'digit' } \n";
                            $virgula = ",";
                        }
                    }

                    $dd++;
                }

	            //echo $sorter;

				$sorter = substr($sorter,0,-1);
				$html .= "$sorter \n";
				$html .= '	}' . " \n";
				$html .= '});' . " \n";
				$html .= '});' . " \n";
				$html .= '</script>' . " \n";
			}
			$titulo = ($this->nome)?(":: {$this->nome}"):"";

            $html .= "<pre id=\"msg\" style=\"display:none\"></pre>";
            $html .= "<!--CONTEUDO INTERNO DA CAIXA  -->";
			// Iniciando tabela principal, tabela que server de conteiner para o grid
			$html .= "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
			$html .= "<tr> \n";
    		$html .= "<td class=\"tabelalista\"> \n";
            $html .= "<table width=\"100%\" class=\"table_buscas\" cellspacing=3 cellpading=18> \n";
            $html .= "<tr> \n";
          		$html .= "<td class=\"titulo_tabela_ajax\"> $titulo \n";

            $html .= "</td><td> \n";
            $html .= "</td></tr></table>\n";
    		$html .= "<form id=\"$this->nomeForm\" name=\"$this->nomeForm\" style=\"margin:0px\" method=\"{$this->method}\" action=\"?app_modulo={$this->modulo}&app_comando={$this->acaoDeletar}&app_codigo={$this->codigo}&{$this->complemento}\">\n";
            if($this->method == "get")
            {
                $html .="<input type=\"hidden\" name=\"app_comando\" id=\"app_comando\" value=\"{$this->acaoListar}\" />";
                $html .="<input type=\"hidden\" name=\"app_modulo\" id=\"app_modulo\" value=\"{$this->modulo}\" />";
                $html .="<input type=\"hidden\" name=\"app_codigo\" id=\"app_codigo\" value=\"{$this->codigo}\" />";
                $html .="<input type=\"hidden\" name=\"complemento\" value=\"{$this->complemento}\" />";
            }
            $html .= "<table class=\"tablesorter\" width=\"100%\" cellspacing=0 cellpading=0  id=\"".$this->idTabela."\">\n \n";
    		$html .= "<thead>\n \n";
    		$html .= "<tr>\n \n";
    		$html .= $this->GerarColunas($this->colunas,$this->nomeForm);
    		$html .= "</tr>\n </thead>\n \n";
    		$html .= "<tbody id=\"grid_gerar_tabela\">\n  \n \n";
    		$html .= $this->GerarColunaDados($this->dados);
    		$html .= "</tbody> \n";
    		$html .= "</table> \n";

    	 	//iniciando paginacao
    	 	$html .= "<table width=\"98%\" class=\"tabela_paginacao\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";

    	 	if($this->mensagem_rodape != "")
    	 	{
	        	$html .= "<tr>";
	        	$html .= "<td colspan='6' align='center'><div class='caixa_msg'>$this->mensagem_rodape</div></td>";
	        	$html .= "</tr>";
      		}

    	 	// VERIFICANDO SE � PARA EXIBIR PAGINACAO
    		if($this->paginacao != "" && $this->paginacao != false)
    		{
    			$html .= "<tr> \n";
	    	 	$html .= "<td align=\"left\" width=\"30%\" valign=\"middle\">&nbsp; \n";

    			// gerando o link para pagina anterior
    			if($this->pagina != 0 )
    			{
    				$paginas = $this->pagina - 1;
    				$html .= "<a href=\"?app_modulo={$this->modulo}&app_comando={$this->acaoListar}&app_codigo={$this->codigo}&pagina=$paginas&busca=$this->busca&{$this->complemento}\">".ROTULO_ANTERIOR." </a> \n";
    			}
    			$html .= "</td> \n";
    			$html .= "<td align=\"center\" width=\"40%\"> \n";
    			//fazendo calculos para numero de registros e paginas
    			$totalRegitrosPagina =$this->numeroRegistroIncio + $this->numeroRegistros;
    			$total = ceil($this->totalRegistros/$this->numeroRegistros);
    			$paginaAtual = $this->pagina + 1;

    			$html .= "<table align=\"center\" width=\"400\"> \n";
    			$html .= "<tr> \n";
    			$html .= "<td> ".ROTULO_PAGINA." $paginaAtual de $total ($this->totalRegistros ".TEXTO_TOTALIZADOR_FINAL.")</td> \n";
    			$html .= "<td> \n";
    			$html .= "<select size=\"1\"  id=\"id_pagina\" name=\"paginacao\" onchange=\"window.location='?app_modulo={$this->modulo}&app_comando={$this->acaoListar}&app_codigo={$this->codigo}&pagina='+document.getElementById('id_pagina').value+'&busca=$this->busca&{$this->complemento}'\"> \n";
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
    				$html .= "<a href=\"?app_modulo={$this->modulo}&app_comando={$this->acaoListar}&app_codigo={$this->codigo}&pagina=$paginas&busca=$this->busca&{$this->complemento}\">".ROTULO_PROXIMO."</a> \n";
    			}

    			$html .= "</td> \n";
    			$html .= "</tr> \n";
    		}
    		$html .= "</table> \n";


    		$html .= "<table class=\"tablesortere\" width=\"100%\" cellspacing=0 cellpading=0  id=\"".$this->idTabela."s\">\n \n";

    		$html .= "<tr> \n";
    		$html .= "<td colspan=\"$numeroColunas\" class=\"top_tabela_grid\" align=\"LEFT\"> \n";

    		if($this->botaoAdicionar == 1)
    		{
    			$html .= "
    			<input type=\"button\" value=\"".ROTULO_ADICIONAR."\"
    			onclick=\"window.location='?app_modulo={$this->modulo}&app_comando={$this->acaoAdicionar}&app_codigo={$this->codigo}&{$this->complemento}'\"
    			name=\"boto\" id=\"boto\"  class=\"\"/>  \n";
    		}
    		if($this->bt_adicionais != "")
    		{
    			$html .= "$this->bt_adicionais \n";
    		}
    		if($this->botao != "" || $this->botao != null)
    		{
  				$html .= "<input name=\"\" id=\"boto\" class=\"\" onclick=\"SendForm(document.$this->nomeForm)\" type=\"button\" value=\"$this->botao\" /></td> \n";
    		}
    		$html .= "</td> \n";
    		$html .= "</tr> \n";
    		$html .= "</table> \n";
    		$html .= "</form> \n";
    		$html .= "</tr> \n";
    		$html .= "</table> \n";

            $html .= "<!-- FINAL DA CELULA DE CONTEUDO DA CAIXA -->";
            $html .= "</td>";
            $html .= "<td class=\"caixa_direita\">&nbsp;</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td class=\"baixo_esquerda\">&nbsp;</td>";
            $html .= "<td class=\"baixo_centro\">&nbsp;</td>";
            $html .= "<td class=\"baixo_direita\">&nbsp;</td>";
            $html .= "</tr>";
            $html .= "</table>";
		}
		else
		{
			$titulo = ($this->nome)?(":: {$this->nome}"):"";
			$html .= "<pre id=\"msg\" style=\"display:none\"></pre>";
			$html .= "<!--CONTEUDO INTERNO DA CAIXA  -->";

			$html .= "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
			$html .= "<tr> \n";
			$html .= "<td class=\"tabelalista\" colspan=\"2\"> \n";
			$html .= "<table width=\"100%\" class=\"table_busca\" cellspacing=3 cellpading=18> \n";
			$html .= "<tr> \n";
			$html .= "<td class=\"titulo_tabela_ajax\"> $titulo</td> \n";
			$html .= "<td> \n";
			if($this->botaoAdicionar == 1)
			{
				$html .= "<input type=\"button\" value=\"".ROTULO_ADICIONAR."\"  ";
				$html .= "onclick=\"window.location='?app_modulo={$this->modulo}&app_comando={$this->acaoAdicionar}&app_codigo={$this->codigo}&{$this->complemento}'\"  ";
				$html .= "name=\"boto\" id=\"boto\" class=\"\" />  \n";
			}
			if($this->bt_adicionais != "")
			{
				$html .= "$this->bt_adicionais \n";
			}
			if($this->botao != "" && $this->botao != null)
			{
				$html .= "<input name=\"\" id=\"botao_deletar\" class=\"\" onclick=\"SendForm(document.{$this->nomeForm})\" type=\"button\" value=\"$this->botao\" />\n";
			}
			$html .= "</td><td> \n";
			if($this->buscaAtiva)
			{
				$html .= "<form method=\"{$this->method}\" style=\"margin:0px\" action=\"?app_modulo={$this->modulo}&app_comando={$this->acaoListar}&app_codigo={$this->codigo}&{$this->complemento}\" name=\"form_busca\"  \n";
				$html .= "> \n";
				$html .= "<table  align='right' cellspacing=3 cellpading=18> \n";
				$html .= "<tr> \n";
				$html .= "<td>{$this->mensagem_bt_busca}</td>";
				$html .= "<td width=\"150\"><input class=\"input_texto_busca\" type=\"text\" name=\"busca\" size=\"50\" value=\"$this->busca\" /></td> \n";
				$html .= "<td width=\"15\"><input type=\"submit\" class=\"\" id=\"botoww\" value=\"".ROTULO_BUSCAR."\" /></td> \n";
				$html .= "</tr> \n";
				$html .= "</table> \n";
				$html .= "</form> \n";
			}
			$html .= "</td></tr></table>\n";

			$html .= "<form id=\"$this->nomeForm\" name=\"$this->nomeForm\" style=\"margin:0px\" method=\"{$this->method}\" action=\"?app_modulo={$this->modulo}&app_comando={$this->acaoDeletar}&app_codigo={$this->codigo}&{$this->complemento}\">\n";
			if($this->method == "get")
			{
				$html .="<input type=\"hidden\" name=\"app_comando\" id=\"app_comando\" value=\"{$this->acaoListar}\" />";
				$html .="<input type=\"hidden\" name=\"app_modulo\" id=\"app_modulo\" value=\"{$this->modulo}\" />";
				$html .="<input type=\"hidden\" name=\"app_codigo\" id=\"app_codigo\" value=\"{$this->codigo}\" />";
				$html .="<input type=\"hidden\" name=\"complemento\" value=\"{$this->complemento}\" />";
			}

			$html .= "<table width=\"100%\" align=\"center\" border=\"0\"  cellspacing=\"0\" cellpadding=\"0\"> \n";
			$html .= "<tr> \n";
			$html .= "<td colspan=\"7\"  class=\"pformleft\" align=\"center\">".RETULO_NENHUM_ITEM_SELECIONADO."</td> \n";
			$html .= "</tr> \n";
			$html .= "</table> \n";
			$html .= "</td> \n";
			$html .= "</tr> \n";
			$html .= "</table> <br>\n";

			$html .= "<!-- FINAL DA CELULA DE CONTEUDO DA CAIXA -->";
		}
		return $html;
	}
	public function GerarColunas($parametros = array(),$nomeForm)
	{
		$html = " \n";
		$x = 0;

		foreach ($parametros as  $coluna)
		{
            $atributos = '';
            
  
            
            if(is_array($coluna))
            {
                $colunaTmp = $coluna;
                foreach($colunaTmp as $indice => $valor)
                {
                    if($indice == 'nome')
                    {
                        $coluna = $valor;
                    }
                    else
                    {
                        $atributos .= ' '.$indice.'="'.$valor.'"';

                    }
                }
            }
            else{
                $nome_coluna = strtolower($coluna);
                $nome_coluna = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $nome_coluna ) );
                $nome_coluna = str_replace(' ', '_', $nome_coluna);
            }

			if($coluna == "Alterar" || $coluna == "alterar")
			{
				$html .= "<th $atributos class=\"{sorter: false}\" width=\"20\"><img src=\"imagens/bts/import.png\"></th>\n";
			}
			elseif($coluna == "img")
			{
				$html .= "<th $atributos class=\"{sorter: false}\" width=\"20\"><img src=\"imagens/bts/hold.gif\"></th>\n ";
			}
			elseif($coluna == "box")
			{
				$html .= "<th $atributos class=\"{sorter: false}\" width=\"20\"><input type=\"checkbox\"  name=\"master\" id=\"master\" onclick=\"selecionando(document.getElementById('master'),document.getElementsByName('lista[]'))\" /></th>\n";
			}
			elseif($coluna == "id")
			{
				$html .= "<th id=\"th_$nome_coluna\" $atributos width=\"70\">$coluna</th>\n";
			}
			else
			{
				if($this->tamanho_colunas[$x] != "" && is_array($this->tamanho_colunas ))
					 $tamanho = "width=\"{$this->tamanho_colunas[$x]}\"";
				else
					$tamanho = "";

				if($this->center[$x] != "" && is_array($this->center ))
					$center = "$this->center[$x]";
				else
					$center = "left";

				$html .= "<th id=\"th_$nome_coluna\" $atributos align=\"$center\" $tamanho >$coluna</th>\n";
			}
			$x++;
		}
		return $html;
	}
	public function GerarColunaDados($parametros = array())
	{
		$html = "";
		if(count($parametros[0]) > 0 && is_array($parametros))
		{
			$j = 0;
			foreach($parametros as $colunas )
			{
				$html .= "<tr> \n";
				$i = 0;
				$qtdColunas = count($colunas);
				foreach($colunas as $coluna => $valor)
				{
				    $atributos = '';
                    if(is_array($valor))
                    {
                        $colunaTmp = $valor;
                        foreach($colunaTmp as $indice => $val)
                        {
                            if($indice == 'valor')
                            {
                                $valor = $val;
                            }
                            else
                            {
                                $atributos .= ' '.$indice.'="'.$val.'"';
                            }
                        }
                    }

				    if( $this->center[$i] )
						$alinhamento = "align=\"{$this->center[$i]}\"";
					else
						$alinhamento = "";
					if($this->divisor[$j] == true)
					{
						$html .= "<td $atributos class=\"divisor\" colspan=\"$qtdColunas\">$valor</td> \n";
						break;
					}
					elseif($coluna === "img")
					{
						$html .= "<td $atributos $alinhamento><img src=\"imagens/icones/hold.gif\" border=\"0\" /></td> \n";
					}
					elseif($coluna === "box")
					{
					   if($valor == "")
                            $disabled = "disabled = 'disabled'";
                        else
                            $disabled = "";

						  $html .= "<td $atributos $alinhamento><input name=\"lista[]\" type=\"checkbox\" value=\"$valor\" $disabled /></td> \n";
					}
					elseif ($coluna === "Alterar")
					{
						if($valor == "")
                        	$html .= "<td $atributos $alinhamento><a href=\"#\"><img src=\"imagens/bts/notepad2.png\" border=\"0\" alt=\"".ROTULO_ALTERAR."\" title=\"".ROTULO_ALTERAR."\"/></a></td> \n";
                        else
                            $html .= "<td $atributos $alinhamento><a href=\"?app_modulo={$this->modulo}&app_comando={$this->acaoModificar}&app_codigo=$valor&{$this->complemento}\"><img src=\"imagens/bts/notepad.png\" border=\"0\" alt=\"".ROTULO_ALTERAR."\" title=\"".ROTULO_ALTERAR."\"/></a></td> \n";
                    }
					else
					{
						if($valor == "") $valor = "&nbsp; \n";
						$html .= "<td $atributos $alinhamento>$valor</td> \n";
					}
					$i++;
				}
				$j++;
				$html .= "</tr> \n";

			}
		}
		return $html;
	}


}
/**
 * @author Ebecom
 * @copyright 2007
 */
?>