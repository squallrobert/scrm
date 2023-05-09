<?php
/**
 * Classe criada para ser usada na contrução das tabelas jquerys. 
 *
 */
class ColunaTabelaJquery{
	/**
	 * Nome da coluna na grid
	 * @var string
	 */
	public $Label = "";
	/**
	 * Nome da coluna do tabela do banco de dados que faz a ordenação
	 * @var string
	 */
	public $ColToOrder = "";
	/**
	 * Propriedades html complementares da coluna. Exemplo: 'rowspan="2" width="20"'
	 * @var string
	 */
	public $Propriedades = "";
	/**
	 * Cria obejto ColunaTabelaJquery
	 * @param string $label
	 * @param string $colToOrder
	 * @param string $propriedades
	 */
	public function __construct($label, $colToOrder, $propriedades){
		$this->Label = $label;
		$this->ColToOrder = $colToOrder;
		$this->Propriedades = $propriedades;
	}
}
?>