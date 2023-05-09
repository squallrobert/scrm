<link rel="stylesheet" href="css/print.css">
<?php
class GerarTabelaPrint
{
	public $buscaAtiva;
	public $dados;
	public $colunas;
	public $totalRegistros;
	public $numeroRegistros;
	public $numeroRegistroIncio;
	public $pagina;
	public $nome;
	public $botao;
	public $busca;
	public $botaoAdicionar;
	public $nomeForm;
	public $caminho;
	public $paginacao;
	public $complemento;
	public $bt_adicionais;
	public $tamanho_colunas;
	public $colunas_somadas = 0; //atributo adicional para aumentar o tamanho do rodapé quando necessário
    public $filtroEspecial; //atributo adicionado para filtro extra
	public $filtro;
	public $rodape; //rodape opcional
    public $classeExtra;
    public $foto_colaborador;

	public function GerarTabelaAjax()
	{
		// definindo valores padroes para variaveis
		$this->buscaAtiva = false;
		$this->nomeForm = "grid ";
		$this->botao = "Deletar ";
		$this->paginacao = 1;
		$this->caminho = "../";
	}

	public function CriarTabela()
	{
		// pegando total de colunas para fazer o colspan no HTML
		$numeroColunas = max(@count($this->colunas[0]["dados_th"]), @count($this->colunas[1]["dados_th"]), @count($this->colunas["dados_th"])) + $this->colunas_somadas;
        $classeExtra = $this->classeExtra;
		// verificando se existem dados para começar a gerar a tabela
		if(@count($this->dados) > 0)
		{
			// Iniciando tabela principal, tabela que serve de container para o grid
			$html .= "<br><br><table width=\"99%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
			$html .= "<tr> \n";
			$html .= "<td ><div align=\"left\"> \n";
			$html .= "</div></td> \n";
			$html .= "</tr> \n";
			$html .= "<td class=\"tabelalista\"> \n";
			$html .= "<table class=\"tablesorter $classeExtra\" width=\"100%\" border=0 cellspacing=0 cellpadding=\"8\"  id=\"MYTABLE\">\n \n";
			$html .= "<thead>\n \n";
            if($this->filtroEspecial){
                $html .= $this->GerarFiltroEspecial($numeroColunas);
            }
			$html .= "<tr>\n \n";
			$html .= "<td colspan=\"$numeroColunas\" align=\"center\"> \n";

            if($this->foto_colaborador){
                $html .= "<img src=\"$this->foto_colaborador\" style=\"max-height:150px;float:left\">";
            }

			$html .= $this->GerarFiltro();
			$html .= "</td> \n";
			$html .= "</tr>\n \n \n";
			$html .= "<tr class=\"p_coluna\">\n \n";
			$html .= $this->GerarColunas($this->colunas,$this->nomeForm);
			$html .= "</tr>\n </thead>\n \n";
			$html .= "<tbody >\n  \n \n";
			$html .= $this->GerarColunaDados($this->dados);
			$html .= "</tbody> \n";
			$html .= "<tfoot> \n";
			$html .= "<tr> \n";
			$html .= "<td colspan=\"$numeroColunas\" align=\"center\" class=\"rodape\"> \n";
            if($this->rodape != ""){
                $html .= "<br><br><br>";
            }
			$html .= "............................................................ </br>";
			if($this->rodape != ""){
                $html .= "<span><strong>".$this->rodape."</strong></span>";
            }
			$html .= "</td> \n";
			$html .= "</tr> \n";
			$html .= "</tfoot> \n";
			$html .= "</table> \n";
			$html .= "</td> \n";
			$html .= "</tr> \n";
			$html .= "</table><br><br> \n";
		}
		else
		{
			$html .= "<br><br><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
			$html .= "<tr> \n";
			$html .= "<td class=\"top_tabela_grid\"> \n";
			$html .= "<table width=\"100%\"> \n";
			$html .= "<tr> \n";
			$html .= "<td class=\"textoTituloListagem\">:: $this->nome </td> \n";
			$html .= "<td align=left > \n";
			$html .= "</td> \n";
			$html .= "</tr> \n";
			$html .= "</table> \n";
			$html .= "</td> \n";
			$html .= "<td ALIGN=RIGHT class=\"top_tabela_grid\">  \n";
				$html .= "</td> \n";
				$html .= "</tr> \n";
				$html .= "<tr> \n";
				$html .= "<td class=\"tabelalista\" colspan=\"2\"> \n";
				$html .= "<table width=\"100%\" align=\"center\" border=\"0\"  cellspacing=\"0\" cellpadding=\"0\"> \n";
				$html .= "<tr> \n";
				$html .= "<td colspan=\"7\"  class=\"pformleft\" align=\"center\">".ROTULO_NENHUM_ITEM_SELECIONADO."</td> \n";
				$html .= "</tr> \n";
				$html .= "</table> \n";
				$html .= "</td> \n";
				$html .= "</tr> \n";
				$html .= "</table> <br><br>\n";
		}
		return $html;
	}
	public function CriarVariasTabelas()
	{
		// pegando total de colunas para fazer o colspan no HTML
		$numeroColunas = count($this->colunas['dados_th']) + 15;

		// verificando se existem dados para come�r a gerar a tabela
		if(count($this->dados) > 0)
		{
			// Iniciando tabela principal, tabela que server de conteiner para o grid
			$html .= "<br><br><table width=\"99%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
			$html .= "<tr> \n";
			$html .= "<td ><div align=\"left\"> \n";
			$html .= "</div></td> \n";
			$html .= "</tr> \n";
			$html .= "<td class=\"tabelalista\"> \n";
			$html .= "<table class=\"tablesorter\" width=\"100%\" border=0 cellspacing=0 cellpadding=\"8\"  id=\"MYTABLE\">\n \n";
			$html .= "<thead>\n \n";
			$html .= "<tr>\n \n";
			$html .= "<td colspan=\"$numeroColunas\" align=\"center\"> \n";
			$html .= $this->GerarFiltro();
			$html .= "</td> \n";
			$html .= "</tr>\n ";
			$html .= "</thead>\n \n \n";
			$html .= "</table>\n \n \n";

			foreach($this->dados AS $dados) {
				$html .= "<table width='100%'>\n";
				$html .= "<thead>\n";
				$html .= $this->GerarColunas($this->colunas,$this->nomeForm);
				$html .= "</thead>\n";
				$html .= "<tbody >\n  \n \n";
				$html .= $this->GerarColunaDados($dados);
				$html .= "</tbody> \n";
				$html .= "</table>\n";
				$html .= "<br/><br/>\n";
			}
		}
		else
		{
			$html .= "<br><br><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"> \n";
			$html .= "<tr> \n";
			$html .= "<td class=\"top_tabela_grid\"> \n";
			$html .= "<table width=\"100%\"> \n";
			$html .= "<tr> \n";
			$html .= "<td class=\"textoTituloListagem\">:: $this->nome </td> \n";
			$html .= "<td align=left > \n";
			$html .= "</td> \n";
			$html .= "</tr> \n";
			$html .= "</table> \n";
			$html .= "</td> \n";
			$html .= "<td ALIGN=RIGHT class=\"top_tabela_grid\">  \n";
			$html .= "</td> \n";
			$html .= "</tr> \n";
			$html .= "<tr> \n";
			$html .= "<td class=\"tabelalista\" colspan=\"2\"> \n";
			$html .= "<table width=\"100%\" align=\"center\" border=\"0\"  cellspacing=\"0\" cellpadding=\"0\"> \n";
			$html .= "<tr> \n";
			$html .= "<td colspan=\"7\"  class=\"pformleft\" align=\"center\">".ROTULO_NENHUM_ITEM_SELECIONADO."</td> \n";
			$html .= "</tr> \n";
			$html .= "</table> \n";
			$html .= "</td> \n";
			$html .= "</tr> \n";
			$html .= "</table> <br><br>\n";
		}
		return $html;
	}
	public function GerarColunas($parametros = array(),$nomeForm)
	{
		$html = " \n";
		if (isset($parametros['dados_th'])) {
			foreach ($parametros['dados_th'] as $coluna) {
				if ($coluna['nome'] == "Alterar" || $coluna['nome'] == "alterar") {
					$html .= "<th  width=\"20\" class=\"p_coluna\"><img src=\"assets/images/bts/import.gif\"></th>\n";
				} elseif ($coluna['nome'] == "img") {
					$html .= "<th width=\"20\" class=\"p_coluna\"><img src=\"assets/images/bts/hold.gif\"></th>\n ";
				} elseif ($coluna['nome'] == "box") {
					$html .= "<th width=\"20\" class=\"p_coluna\"><input type=\"checkbox\"  name=\"master\" id=\"master\" onclick=\"selecionando(document.getElementById('master'),document.getElementsByName('lista[]'))\" /></th>\n";
				} elseif ($coluna['nome'] == "id") {
					$html .= "<th width=\"70\" class=\"p_coluna\">$coluna</th>\n";
				} else {
					$tamanho = $this->tamanho_colunas;
					$html .= "<th class=\"p_coluna\" width='$tamanho'>{$coluna['nome']}</th>\n";
				}
			}
		} else {
			foreach ($parametros AS $auxCol) {
				$html .= "<tr class=\"p_coluna\">\n \n";
				foreach ($auxCol['dados_th'] as $coluna) {
					if ($coluna['nome'] == "Alterar" || $coluna['nome'] == "alterar") {
						$html .= "<th  width=\"20\" class=\"p_coluna\"><img src=\"assets/images/bts/import.gif\"></th>\n";
					} elseif ($coluna['nome'] == "img") {
						$html .= "<th width=\"20\" class=\"p_coluna\"><img src=\"assets/images/bts/hold.gif\"></th>\n ";
					} elseif ($coluna['nome'] == "box") {
						$html .= "<th width=\"20\" class=\"p_coluna\"><input type=\"checkbox\"  name=\"master\" id=\"master\" onclick=\"selecionando(document.getElementById('master'),document.getElementsByName('lista[]'))\" /></th>\n";
					} elseif ($coluna['nome'] == "id") {
						$html .= "<th width=\"70\" class=\"p_coluna\">$coluna</th>\n";
					} else {

						$tamanho = $this->tamanho_colunas;
						$html .= "<th colspan='{$coluna['colspan']}' rowspan='{$coluna['rowspan']}' style='{$coluna['style']}' class=\"p_coluna\" width='$tamanho'>{$coluna['nome']}</th>\n";
					}
				}
				$html .= "</tr>\n \n";
			}
        }

		return $html;
	}
	public function GerarColunaDados($parametros = array())
	{
		$html = "";
		foreach($parametros as $colunas )
		{
			$html .= "<tr > \n";
			foreach($colunas["dados_td"] as $coluna => $valor)
			{
				if($this->tamanho_colunas[$x] != "" && is_array($this->tamanho_colunas ))
					 $tamanho = "width=\"{$this->tamanho_colunas[$x]}\"";
				else
					$tamanho = "";

                if($valor['valor'] == "") $valor['valor'] = "&nbsp; \n";
                $html .= "<td class=\"p_linha {$valor['class']}\" style='{$valor['style']}' colspan=\"{$valor['colspan']}\" $tamanho>{$valor['valor']}</td> \n";
			}
			$html .= "</tr> \n";
		}
		return $html;
	}
	public function GerarFiltro()
	{
		if(@count($this->filtro) > 0)
		{
			$html  =  "<table width=\"80%\" border=\"0\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\">";
			$html .=  "<tr>";
			$html .=  "<td colspan=\"2\" class=\"titulo_filtro\">".$this->nome."</td>";
			$html .=  "</tr>";

			foreach($this->filtro as $col => $row)
			{
				if($row != "")
				{
					$html .=  "<tr>";
					$html .=  "<td align=\"right\" WIDTH=\"25%\"><strong>$col :</strong></td>";
					$html .=  "<td align=\"left\" >$row</td>";
					$html .=  "</tr>";
				}
			}
			$html .=  "<tr>";
			$html .=  "<td colspan=\"2\"><hr /></td>";
			$html .=  "</tr>";
			$html .=  "</table>";
		}
		return $html;
	}

	//funcao criada para cliente específico
	public function GerarFiltroEspecial($numeroColunas)
    {
        if(count($this->filtroEspecial)>0){
        $html = "<tr>";
        $html .= "<td colspan=\"$numeroColunas\" align=\"center\">";

            $html .=  "<table width=\"80%\" border=\"0\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\">";
            $html .=  "<tr>";
            $html .=  "<td colspan=\"8\" align=\"center\"><strong>DIARIO DE BORDO DE TRABALHO EXTERNO - LEI Nº13.103 - PORTARIA DO MTE Nº3.626 - 13/11/1991</strong></td>";
            $html .=  "</tr>";

            $html .=  "<tr >";
            $html .=  "<td align=\"right\"><strong>Empresa :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['empresa']."</td>";
            $html .=  "<td align=\"right\"><strong>CNPJ :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['cnpj']."</td>";
            $html .=  "<td align=\"right\"><strong>Endereço :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['endereco']."</td>";
            $html .=  "<td align=\"right\"><strong>Data Inicial :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['dataInicial']."</td>";
            $html .=  "</tr>";
            $html .=  "<tr>";
            $html .=  "<td align=\"right\"><strong>Motorista :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['motorista']."</td>";
            $html .=  "<td align=\"right\"><strong>CPF :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['cpf']."</td>";
            $html .=  "<td align=\"right\"><strong>Placa do Veículo :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['placa']."</td>";
            $html .=  "<td align=\"right\"><strong>Data Final :</strong></td>";
            $html .=  "<td>".$this->filtroEspecial['dataFinal']."</td>";
            $html .=  "</tr>";


            $html .=  "<tr>";
            $html .=  "<td colspan=\"8\"><hr /></td>";
            $html .=  "</tr>";
            $html .=  "</table>";

        $html .= "</td>";
        $html .= "</tr>";
        }
        return $html;
    }

}
/**
 * @author Ebecom
 * @copyright 2007
 */
?>