<?php

/** 
 * Classe que represenata uma célula de uma tabela Jquery
 */
class CellTabelaJquery {
	/**
	 * Propriedades html complementares da coluna. Exemplo: 'rowspan="2" width="20"'
	 * @var string
	 */
	public $Propriedades = "";
	/**
	 * Texto que será mostrado na célula.
	 * @var string
	 */
	public $Valor = "";
	
	/**
	 * Cria objeto cell para ser usado em uma tabela jquery.
	 * @param string $valor - Texto que será mostrado na célula.
	 * @param string $propriedades - Propriedades html complementares da coluna. Exemplo: 'rowspan="2" width="20"'
	 */
	function __construct($valor="",$propriedades="") {
		$this->Valor = $valor;
		$this->Propriedades = $propriedades;
	}
}

?>