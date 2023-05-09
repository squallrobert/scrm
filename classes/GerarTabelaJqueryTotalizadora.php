<?php 
/**
 * Cria grid Jquery com opção para totalização e colunas multilinhas.
 * @author 
 * <code>
 * //exemplo de uso teste
	include_once (URL_ERP . "classes/ColunaTabelaJquery.php");
	include_once (URL_ERP . "classes/CellTabelaJquery.php");
	include_once (URL_ERP . "classes/GerarTabelaJqueryTotalizadora.php");
 	$tabela = new GerarTabelaJqueryTotalizadora(true);
	$tabela->pagina = $pagina;
	$tabela->numeroRegistros = 10;
	$tabela->numeroRegistroInicio = $pagina * $tabela->numeroRegistros;
	$tabela->totalRegistros = 2;
	echo $tabela->CriarTabela();
 * </code>
 */
class GerarTabelaJqueryTotalizadora{
/**
	 * Lista com os dados das colunas da tabela (cada item do array deve conter um array de ColunaTabelaJquery para cada tr que será gerada para as colunas da grid).
	 * @var array of ColunaTabelaJquery[]
	 */
	public $colunas = array();
	/**
	 * Lista de dados de cada linha da tabela - cada chave deve conter um array of array com os dados de cada linha do cabeçalho
	 * - os dados devem estar na mesma linha o ordem das colunas exemplo: $dados[] = array(array("dado da linha 1 e col 1"," dado da linha 1 e dado col2"));
	 * @var array of array
	 */
	public $dados = array();
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
	 * Valor do campo input que foi/será usado para buscar os registros no banco de dados.
	 * @var string
	 */
	public $busca;
	/**
	 * Bool que indica se mostra opção de busca
	 * @var bool
	 */
	public $buscaAtiva = true;
    /**
     * Nome do campo input usado em busca
     * @var string
     */
    public $campo_busca = "busca";	
	/**
	 * ID do botão usado para disparar a busca de registros.
	 * @var string
	 */
	public $idBotaoBuscar = "btnBuscar";	
	/**
	 * Valor da propriedade id e name do formulário que contém as linhas de dados da grid
	 * @var string
	 */
	public $nomeForm = "grid_formulario";
	/**
	 * caminho dos aquivos de css e js ex: o padrão "js/table/jquery-1.1.3.js" (usa isso para adicionar mais coisas no inicio)
	 * @var string  - ex: "../js/table/jquery-1.1.3.js"
	 */
	public $caminho = URL_GERADOR;
	/**
	 * Bool que indica se deve mostrar menu de paginação ou não
	 * @var bool
	 */
	public $paginacao = true;
	/**
	 * HTML de botões que serão adicionados entre o de adicionar e o de remover
	 * @var string
	 */
	public $bt_adicionais;
	/**
	 * Indica se mostra botão adicionar
	 * @var bool
	 */
	public $exibirBtAdicionar = true;
	/**
	 * Indica se mostra botão excluir
	 * @var bool
	 */	
	public $exibirBtRemover = true;
	/**
	 * Label do botão excluir
	 * @var string
	 */
	public $labelBtRemover = "Excluir";
	/**
	 * Mensagem de rodapé.
	 * @var string
	 */
    public $mensagem_rodape = "";
    /**
     * Título da grid - se for diferente de "" mostra título antes dos botões.
     * @var string
     */
    public $tituloGrid = "";
    /**
     * Nome da coluna da tabela do banco de dados que foi usada para ordenação - caso tenha sido ordenado.
     * @var string
     */
    public $filtro;
    /**
     * Tipo de ordenação atual
     * @var string asc | desc
     */
    public $ordem;
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

	/**
	 * Cria grid
	 * @param bool $gerarTabelaExemplo - Se true cria configurações para imprimir uma tabela de exemplo que mostra como usar esta classe.
	 */
	public function __construct($gerarTabelaExemplo=false)
	{
        if($gerarTabelaExemplo==true){
	        //exemplo de de criação de colunas
	        $colunasLinha1 = array();
	        $selTodosPadrao = '<input type="checkbox"  name="master" id="master" onclick="selecionando(document.getElementById(\'master\'),document.getElementsByName(\'lista[]\'))" />';
			$colunasLinha1[] = new ColunaTabelaJquery($selTodosPadrao,'','rowspan="2"');
	        $colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_CLIENTE,'cli.nome','colspan="2"');
	        $colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_DATACADASTRO,'c.DATA_HORA_CADASTRO','width="110"');
	        $colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_VALORPARHAB,'','rowspan="2"');
			$colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_TOTPARCFAT_TOTPARC,'','rowspan="2"');
			$colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_VALORSERV,'','rowspan="2"');
			$colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_VALORPARCACESS,'','rowspan="2"');
			$colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_VALORSERVACESS,'','rowspan="2"');
			$colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_VALORSERVICEXEC,'','rowspan="2"');
			$colunasLinha1[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_DESCSERVEXEC,'','rowspan="2"');
			
			$colunasLinha1[] = new ColunaTabelaJquery('','','rowspan="2"');//col vazia
			
			$colunasLinha2[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_CONTRATO,'NUMERO_CONTRATO','');
			$colunasLinha2[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_VEICULO,'ROTULO','');
			$colunasLinha2[] = new ColunaTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_DATAATIVACAO,'c.DATA_ATIVACAO','');
			
			$this->colunas[] = $colunasLinha1;
			$this->colunas[] = $colunasLinha2;
			
			//////// exemplo de dados
			$tr1 = array();
			$cellCheckBox = new CellTabelaJquery('<input name="lista[]" id="lista" type="checkbox" value="id_registro" />','rowspan="2"');
			$tr1[] = $cellCheckBox;
			$cellNomeCli = new CellTabelaJquery('nome do cliente"','colspan="2"');
			$tr1[] = $cellNomeCli;
			$cellDataCad = new CellTabelaJquery('data cadastro','');
			$tr1[] = $cellDataCad;
			$cellValorHab = new CellTabelaJquery('valor par hab','rowspan="2"');
			$tr1[] = $cellValorHab;
			$cellTotalPar = new CellTabelaJquery('total parce fat','rowspan="2"');
			$tr1[] = $cellTotalPar;
			$cellValorSer = new CellTabelaJquery('valor serv','rowspan="2"');
			$tr1[] = $cellValorSer;
			$cellVlrParAc = new CellTabelaJquery('valor parc acess','rowspan="2"');
			$tr1[] = $cellVlrParAc;
			$cellVlrSerAc = new CellTabelaJquery('valor serv acess','rowspan="2"');
			$tr1[] = $cellVlrSerAc;
			$cellVlrSerEx = new CellTabelaJquery('VALORSERVICEXEC','rowspan="2"');
			$tr1[] = $cellVlrSerEx;
			$cellDesSerEx = new CellTabelaJquery('DESCSERVEXEC','rowspan="2"');
			$tr1[] = $cellDesSerEx;		
			
			$tr1[] = new CellTabelaJquery('Editar','rowspan="2"');
			
			$tr2 = array();
			$tr2[] = new CellTabelaJquery('NUMERO_CONTRATO','');
			$tr2[] = new CellTabelaJquery('placa/rotulo','');
			$tr2[] = new CellTabelaJquery('data ativação','');
			
			$dadosLinha = array();
			$dadosLinha[] = $tr1;
			$dadosLinha[] = $tr2;	
			$dadosLinha2[] = $tr1;
			$dadosLinha2[] = $tr2;		
			
			$this->dados[] = $dadosLinha;	
			$this->dados[] = $dadosLinha2;
			
			//monta linhas de totais
			$totalPagina = array();
			$totalPagina[] = new CellTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_TOTPAG,'colspan="4"');
			$totalPagina[] = new CellTabelaJquery('2','');
			$totalPagina[] = new CellTabelaJquery('','');//coluna sem total
			$totalPagina[] = new CellTabelaJquery('4','');
			$totalPagina[] = new CellTabelaJquery('5','');
			$totalPagina[] = new CellTabelaJquery('6','');
			$totalPagina[] = new CellTabelaJquery('7','');
			$totalPagina[] = new CellTabelaJquery('8','');
			$totalPagina[] = new CellTabelaJquery('','');
			
			$totalGeral = array();
			$totalGeral[] = new CellTabelaJquery(ROTULO_FATURAMENTO_PREVISAO_GDLB_TOTCONTRATOS,'colspan="4"');
			$totalGeral[] = new CellTabelaJquery('2','');
			$totalGeral[] = new CellTabelaJquery('','');//coluna sem total
			$totalGeral[] = new CellTabelaJquery('4','');
			$totalGeral[] = new CellTabelaJquery('5','');
			$totalGeral[] = new CellTabelaJquery('6','');
			$totalGeral[] = new CellTabelaJquery('7','');
			$totalGeral[] = new CellTabelaJquery('8','');
			$totalGeral[] = new CellTabelaJquery('','');	
			
			$this->totais[] = $totalPagina;
			$this->totais[] = $totalGeral;
			
			//var_dump($this->colunas);
			//var_dump($this->dados);
        }
	}

	public function CriarTabela()
	{
		// pegando total de colunas para fazer o colspan no HTML
		$numeroColunas = count($this->colunas) + 1;

		// verificando se existem dados para começar a gerar a tabela
		if(count($this->dados) > 0)
		{
			// Iniciando tabela principal, tabela que server de conteiner para o grid
			$html .= "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
			$html .= "<tr> \n";
    		$html .= "<td class=\"tabelalista\"> \n";
            $html .= "<table width=\"100%\" class=\"table_buscas\" cellspacing=3 cellpading=18> \n";
            
            /*if($this->nome){
	            $html .= "<td class=\"top_tabela_grid\"> \n";
				$html .= "<table width=\"100%\"> \n";
				$html .= "<tr> \n";
				$html .= "<td class=\"textoTituloListagem\">:: $this->nome </td> \n";
				$html .= "<td align=left > \n";
				$html .= "</td> \n";
				$html .= "</tr> \n";
				$html .= "</table> \n";
				$html .= "</td> \n";
            }*/
            
            $html .= "<tr> \n";
          		$html .= "<td> \n";
         		
          	if($this->tituloGrid != ""){
          		$html .= "<div class='tituloGrid'>".$this->tituloGrid."</div>";    		
          	}
          		
		    if($this->exibirBtAdicionar == true)
    		{
    			$html .= "<input type=\"button\" value=\"".ROTULO_ADICIONAR."\"  ";    		
    			$html .= "name=\"botao_adicionar\" id=\"botao_adicionar\" class=\"{$this->cssBotoes}\" />  \n";         			
    		}       		
          		
    		if($this->bt_adicionais != "")
    		{
    			$html .= "$this->bt_adicionais \n";
    		}

		    if($this->exibirBtRemover == true)
    		{
    			$html .= "<input name=\"\" id=\"botao_deletar\" class=\"{$this->cssBotoes}\" type=\"button\" value=\"$this->labelBtRemover\" />\n";
    		}    		
    		
            $html .= "</td><td> \n";
            
		    if($this->buscaAtiva)
    		{
    			$cpBusca = "''";
    			if($this->buscaAtiva == true)
    				$cpBusca = "document.getElementById('".$this->campo_busca."').value";
    				
    			$html .= "<form method=\"{$this->method}\" onsubmit=\"{$this->atualizar_grid}('',$cpBusca);return false;\" style=\"margin:0px\"  name=\"form_busca\" > \n";
    			$html .= "<table width=\"\" align='right' cellspacing=3 cellpading=18> \n";
    			$html .= "<tr> \n";
                $html .= "<td>{$this->mensagem_bt_busca}</td>";
    			$html .= "<td width=\"150\"><input class=\"text ui-widget-content ui-corner-all ui-autocomplete-input\" type=\"text\" name=\"{$this->campo_busca}\" id=\"{$this->campo_busca}\"  size=\"50\" value=\"$this->busca\" /></td> \n";
    			$html .= "<td width=\"15\"><input type=\"button\"";
                $html .= "onclick=\"{$this->atualizar_grid}('',$cpBusca)\"";
                $html .= "class=\"botoees_new\" id=\"{$this->idBotaoBuscar}\" value=\"".ROTULO_BUSCAR."\" /></td> \n";
    			$html .= "</tr> \n";
    			$html .= "</table> \n";
    			$html .= "</form> \n";
    		}            
            
            $html .= "</td></tr></table>\n";
            
    		$html .= "<form id=\"$this->nomeForm\" name=\"$this->nomeForm\" style=\"margin:0px\" method=\"{$this->method}\" >\n";
            $html .= "<div style='overflow:auto'><table class=\"tablesorter\" width=\"100%\" cellspacing=0 cellpading=0  id=\"".$this->idTabela."\">\n \n";
    		$html .= "<thead>\n \n";
    		
    		$cssZebra = 'odd';
    		$incluirZebra = false;
    		
    		$html .= $this->GerarColunas();
    		
    		$html .= "<tbody id=\"grid_gerar_tabela\">\n  \n \n";

			//gera registros	
			$html .= $this->GerarLinhasDados($cssZebra,$incluirZebra);  
    		
			//gerar linhas de totais			
			$html .= $this->GerarLinhasTotais($cssZebra,$incluirZebra); 
			
    		$html .= "</tbody> \n";
    		$html .= "</table></div> \n";

    	 	//iniciando paginacao
    	 	$html .= "<table width=\"98%\" class=\"tabela_paginacao\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";

    	 	if($this->mensagem_rodape != "")
    	 	{
	        	$html .= "<tr>";
	        	$html .= "<td colspan='6' align='center'><div class='caixa_msg'>$this->mensagem_rodape</div></td>";
	        	$html .= "</tr>";
      		}

    	 	// VERIFICANDO SE é PARA EXIBIR PAGINACAO
    		if($this->paginacao == true)
    		{
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
    		}
    		$html .= "</table> \n";


    		$html .= "<table class=\"tablesortere\" width=\"100%\" cellspacing=0 cellpading=0  id=\"".$this->idTabela."s\">\n \n";

    		$html .= "<tr> \n";
    		$html .= "<td colspan=\"$numeroColunas\" class=\"top_tabela_grid\" align=\"LEFT\"> \n";
    		$html .= "</td> \n";
    		$html .= "</tr> \n";
    		$html .= "</table> \n";
    		$html .= "</form> \n";
    		$html .= "</tr> \n";
    		$html .= "</table> \n";
            $html .= "<input type=\"hidden\" id=\"ordem_tabela\" name=\"ordem_tabela\" value=\"{$this->ordem}\" /> \n";
            $html .= "<input type=\"hidden\" id=\"filtro_tabela\" name=\"filtro_tabela\" value=\"{$this->filtro}\" /> \n";
    		}
    		else
    		{
    			//monta tabela sem dados para apresentar
    			
    			$html = "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
				$html .= "<tr> \n";
				$html .= "<td class=\"tabelalista\" colspan=\"2\"> \n";
				
    		    if($this->tituloGrid != ""){
          			$html .= "<div class='tituloGrid'>".$this->tituloGrid."</div>";    		
          		}				
				
                $html .= "<table width=\"100%\" class=\"table_busca\" cellspacing=3 cellpading=18> \n";
                $html .= "<tr> \n";
                $html .= "<td> \n";

                $html .= "</td><td> \n";
                $html .= "</td></tr></table>\n";

				$html .= "<form id=\"$this->nomeForm\" name=\"$this->nomeForm\" onsubmit='return false;' style=\"margin:0px\" method=\"{$this->method}\" >\n";


				$html .= "<table width=\"100%\" align=\"center\" border=\"0\" id=\"".$this->idTabela."\"  cellspacing=\"0\" cellpadding=\"0\" > \n";
				$html .= "<tr> \n";
				$html .= "<td colspan=\"7\"  class=\"pformleft\" align=\"center\">".RETULO_NENHUM_ITEM_SELECIONADO."</td> \n";
				$html .= "</tr> \n";
				$html .= "</table> \n";
				$html .= "</td> \n";
				$html .= "</tr> \n";
				$html .= "</table> \n";
              
    		}
              
		return $html;
	}
	/**
	 * Gera html que define as colunas da grid
	 */
	public function GerarColunas(){
		try{
			
			if(is_array($this->colunas) && count($this->colunas) > 0){
				foreach ($this->colunas as $colunas) {	
					if(is_array($colunas) && count($colunas) > 0){
						//cria linha de título
						$html .= "<tr class='odd'>\n \n";
						foreach ($colunas as $indexCol => $coluna) {	
							$theme = "";
							$ordem = "";	
							if($coluna->ColToOrder != ""){
								$theme = "class='header'";//ColToOrder='".$coluna->ColToOrder."'
								$ordem = "asc";
								
								if($this->filtro == $coluna->ColToOrder){
									if($this->ordem == "asc"){
										$theme = "class='header headerSortDown'";
										$ordem = "desc";
									}else{ 
										$theme = "class='header headerSortUp'";
										$ordem = "asc";
									}
								}
							}
							//cria coluna
							$html .= '<th '. $coluna->Propriedades . " ";
				
							if($coluna->ColToOrder != ""){
								$busca = "''";
								
								if($this->buscaAtiva==true){
									$busca = "document.getElementById('{$this->campo_busca}').value";
								}
								//passa página 1, ColToOrder, tipo ordenação
								$html .= " onClick=\"{$this->atualizar_grid}('',$busca,'{$coluna->ColToOrder}','$ordem')\" ";
							}
							
							$html .= " $theme>";
							$html .= $coluna->Label;
							$html .= '</th>';		
							//fecha coluna					
						}
						//fecha linha de título
						$html .= '</tr>';
					}
				}
			}		
			
		}
		catch (Exception $e){
			//echo $e->getMessage()."<br>";
		}
    	return $html;
	}
	/**
	 * Controla o css que está sendo impresso nas trs.
	 * @param string $cssZebra - Nome do css a ser usado na linha
	 * @param bool $incluirZebra - Controla se deve incluir o css de zebra ou não
	 * @param array $dados - Lista de dados a ser impressa (Se informado será usado ao invés de $this->dados).
	 */
	private function GerarLinhasDados(&$cssZebra="odd",&$incluirZebra,$dados=array()){
		$css  = "";	
		$html = "";

		$lista = $this->dados;
		if(is_array($dados) && count($dados) > 0){
			$lista = $dados;
		}
		
		if(isset($lista) && is_array($lista) && count($lista) > 0){
			foreach ($lista as $idLinha => $dadosLinha) {				
				if(is_array($dadosLinha) && count($dadosLinha) > 0){
					$css = "";	
					if($incluirZebra==true){
						$css = $cssZebra;
					}					
					//define se próxima linha tera css de zebra
					$incluirZebra = !$incluirZebra;							
					
					foreach ($dadosLinha as $idxTr => $tr) {
						//monta html da tr
						$html .= '<tr class="'.$css.'">';
						foreach ($tr as $idxColuna => $cell) {
							//$coluna = $this->colunas[$idxTr][$idxColuna];
							$html .= '<td '. $cell->Propriedades . "> ";
							$html .= $cell->Valor;
							$html .= '</td>';
						}
						$html .= '</tr>';		
						//fim do html da tr
					}
				}
			}
		}
		
		return $html;
	}
	/**
	 * Gera html das linhas de totais
	 * @param string $cssZebra - Nome do css a ser usado na linha
	 * @param bool $incluirZebra - Controla se deve incluir o css de zebra ou não
	 * @param array $dados - Lista de dados a ser impressa (Se informado será usado ao invés de $this->dados).
	 */
	private function GerarLinhasTotais(&$cssZebra="odd",&$incluirZebra){
		$css  = "";	
		$html = "";
		
		if(isset($this->totais) && is_array($this->totais) && count($this->totais) > 0){
			foreach ($this->totais as $idxTr => $tr) {
				$css  = "";	
				if(is_array($tr) && count($tr) > 0){
					if($incluirZebra==true){
						$css = $cssZebra;
					}
					//define se próxima linha tera css de zebra
					$incluirZebra = !$incluirZebra;
					
					//monta html da tr
					$html .= '<tr class="'.$css.'">';
					foreach ($tr as $idxColuna => $cell) {
						$html .= '<td '. $cell->Propriedades . "> ";
						$html .= $cell->Valor;
						$html .= '</td>';
					}
					$html .= '</tr>';		
					//fim do html da tr						
				}
			}
		}
		return $html;
	}
}

?>