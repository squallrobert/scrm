<?php
/**
 * @author Flavio Freitas
 * @copyright 2012
 */

class GerarTabelaPdf {

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
    public $center;
    public $mensagem_rodape;
    public $idTabela = "MYTABLE";
    public $multiTabelas = false;
    public $acaoAdicionar;
    public $acaoDeletar;
    public $acaoModificar;
    public $acaoListar;
    public $acaoVizualizar;
    public $modulo;
    public $comando;
    public $codigo;
    public $method;
    public $mensagem_bt_busca;
    public $cabecalho;
    public $id_cliente;
	public $style;
	public $tableFontSize = "13px";

    public function __construct()
    {
        ini_set("memory_limit", "6144M");
    }

    public function GerarTabela() {
        // definindo valores padr�es para variaveis
        $this->buscaAtiva = false;
        $this->nomeForm = "grid_formulario";
        $this->paginacao = 1;
        $this->caminho = URL_GERADOR;
        $this->method = "post";
        $this->mensagem_rodape = "";
        $this->mensagem_bt_busca = "";
    }

    public function CriarTabela() {

        //  echo "<pre>Inicio</pre><br>";
        // pegando total de colunas para fazer o colspan no HTML

		if (isset($this->colunas["dados_th"])){
			$numeroColunas = count($this->colunas["dados_th"]);
		} else {
			$numeroColunas = 0;
			foreach ($this->colunas AS $auxColuna) {
				$total = count($auxColuna["dados_th"]);

				if ($total > $numeroColunas) {
					$numeroColunas = $total;
				}
			}

		}

		$cabecalho = $this->montarCabecalho($numeroColunas);


        $html = '';
        $html .= '<div>';
        $html .= '<table class="print_tabela" border="1" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: '.$this->tableFontSize.'; border-collapse: collapse; border-color: E3E3E3; width: 100%; '.$this->style.'">';
        $html .= '<thead>
                    <tr>
                        '.$cabecalho.'
                    </tr>
                  </thead>';

        // verificando se existem dados para come�r a gerar a tabela
        if (count($this->dados) > 0) {

            $html .= '<tbody>';

			if (isset($this->colunas["dados_th"])){
				$html .= '<tr>';

				foreach ($this->colunas['dados_th'] as $coluna) {
					if ($contColspan > 0) {
						$contColspan--;
						continue;
					}

					if ($coluna['colspan'] > 0) {
						$contColspan = $coluna['colspan'] - 1;
					}

					$widthFinal = (strlen($coluna['nome']) * 5.75) + 3;
					if ($widthFinal > 150.5) {
						$widthFinal = 150.5;
					} else if ($widthFinal < 35) {
						$widthFinal = 35;
					}

					$html .= "<td colspan='{$coluna['colspan']}' style='background-color: #e3e3e3; text-align: center; width: $widthFinal px'><b><font size=1>" . $coluna['nome'] . "</font></b></td>\n";
				}
				$html .= "</tr>";
			} else {
				foreach ($this->colunas AS $col) {
					$html .= '<tr>';

					foreach ($col['dados_th'] as $coluna) {
						$html .= "<td colspan='{$coluna['colspan']}' style='background-color: #e3e3e3; text-align: center; '><b>" . $coluna['nome'] . "</b></td>\n";
					}
					$html .= "</tr>";

				}
			}

			/*die();*/

            foreach($this->dados as $colunas )
            {
                $html .= "<tr>";
                foreach($colunas['dados_td'] as $celula)
                {
                    //Caso o colspan seja 0, traduz para o numero de colunas total do relatório (solução crossbrowser para colspan = 0)
                    ($celula['colspan'] != "")
                        ? ($celula['colspan'] == "0")
                            ? $colspan = 'colspan="'.count($this->colunas["dados_th"]).'"'
                            : $colspan = 'colspan="'.$celula['colspan'].'"'
                        : $colspan = "";

                    ($celula['style'] != "")
                        ? $style = 'style="'.$celula['style'].'"'
                        : $style = "";

                    ($celula['class'] != "")
                        ? $class = 'class="'.$celula['class'].'"'
                        : $class = "";

                    if($celula['valor']=="" || $celula['valor']==null)
                    {
                        $html .= "<td style='background-color: white;' $colspan $style $class></td>\n";
                    } else {
                        $html .= "<td style='background-color: white; text-align: center;' $colspan $style $class>" . $celula['valor'] . "</td>\n";
                    }
                }

                $html .= "</tr>";
            }
            $html .= '</tbody>';

            $html .= "</table>\n";
            $html .= '</div>';

            //$html .= "</body>\n";
            //$html .= "</html>\n";
        }

        //echo '<pre>'; print_r($_REQUEST); echo '</pre>';die();
        return $html;
    }

    public function CriarVariasTabelas()
    {
        $cabecalho = $this->montarCabecalho(count($this->colunas["dados_th"]));

        // if (count($this->colunas["dados_th"]) <= 2)
        // {
        //     $html = "
        //     <div>
        //         ".TXT_ERRO_COLUNAS_INSUFICIENTES."
        //     </div>";

        //     return $html;
        // }

        $html = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="content-type" content="text/html; charset=UTF-8">
                <title>Usebens Seguradora - PDF Report</title>
                </head>
                <body>';

        /*echo "<pre>";
        echo print_r($this->dados);
        die();*/

        if (count($this->dados) > 0) {
            $primeiro = true;
            foreach($this->dados AS $dadosTabela) {
                if ($primeiro) {
                    $primeiro = false;
                    $html .= "<div>";
                } else {
                    $html .= "<div style='page-break-before: always'>";
                }
                $html .= '<table class="print_tabela" width="100%" border="1" cellpadding="0" cellspacing="0" style="page-break-before: always;font-family: arial; font-size: 13px; border-collapse: collapse; border-color: E3E3E3">';
                $html .= '<thead>
                    <tr>
                        '.$cabecalho.'
                    </tr>
                  </thead>';
                $html .= '<tbody>';
                $html .= '<tr>';


                foreach($this->colunas['dados_th'] as $coluna) {
                    $widthFinal = (strlen($coluna['nome']) * 5.75) + 3;
                    if ($widthFinal > 150.5)
                        $widthFinal = 150.5;
                    else if ($widthFinal < 35)
                        $widthFinal = 35;


                    $html .= "<td style='background-color: #e3e3e3; text-align: center; width: $widthFinal px'><b><font size=1>".$coluna['nome']."</font></b></td>\n";
                }
                $html .= "</tr>";

                foreach($dadosTabela as $colunas )
                {
                    $html .= "<tr>";
                    foreach($colunas['dados_td'] as $celula)
                    {
                        //Caso o colspan seja 0, traduz para o numero de colunas total do relatório (solução crossbrowser para colspan = 0)
                        ($celula['colspan'] != "")
                            ? ($celula['colspan'] == "0")
                            ? $colspan = 'colspan="'.count($this->colunas["dados_th"]).'"'
                            : $colspan = 'colspan="'.$celula['colspan'].'"'
                            : $colspan = "";

                        ($celula['style'] != "")
                            ? $style = 'style="'.$celula['style'].'"'
                            : $style = "";

                        ($celula['class'] != "")
                            ? $class = 'class="'.$celula['class'].'"'
                            : $class = "";

                        if($celula['valor']=="" || $celula['valor']==null)
                        {
                            $html .= "<td style='background-color: white;' $colspan $style $class></td>\n";
                        } else {
                            $html .= "<td style='background-color: white; text-align: center;' $colspan $style $class>" . $celula['valor'] . "</td>\n";
                        }
                    }

                    $html .= "</tr>";
                }
                $html .= '</tbody>';

                $html .= "</table>\n</div>";
            }
        }

        $html .= "</body>\n";
        $html .= "</html>\n";

        return $html;
    }
    /*
     * retorna o HTML do cabeçalho
     */
    private function montarCabecalho($colunas)
    {
        $objCliente = new Cliente();
        $objCliente->setId($this->id_cliente);
        $cliente = $objCliente->Editar();

        if ($cliente->logo != "" && $cliente->logo != null)
        {
            //Garante um número homogênio de colspan dos headers
            if ($colunas > 4)
            {
                $colunasImg = floor(ceil($colunas/2)/2);
                $colunasLogo = floor(ceil($colunas/2)/2);
                $colunasRotulosFiltro = floor(ceil($colunas/2)/2);
                $colunasValoresFiltro = ceil($colunas/4);
            }
            else if ($colunas == 4)
            {
                $colunasImg = 1;
                $colunasLogo = 1;
                $colunasRotulosFiltro = 1;
                $colunasValoresFiltro = 1;
            } else {
                $colunasImg = 1;
                $colunasLogo = 1;
                $colunasRotulosFiltro = 0;
                $colunasValoresFiltro = 0;
            }
            $logoCliente = '<td colspan="'.$colunasLogo.'" width="50" height="50" style="border: 1px solid; text-align: left; border-top: none; border-right: none; border-left: none; ">
            <img src="'.$cliente->logo.'" width="100px"/>
        </td>';
        }
        else
        {
            if ($colunas > 3)
            {
                $colunasImg = floor($colunas/2);
                $colunasRotulosFiltro = floor(ceil($colunas/2)/2);
                $colunasValoresFiltro = ceil(ceil($colunas/2)/2);
            }
            else if ($colunas == 3)
            {
                $colunasImg = 1;
                $colunasRotulosFiltro = 1;
                $colunasValoresFiltro = 1;
            } else {
                $colunasImg = 1;
                $colunasRotulosFiltro = 0;
                $colunasValoresFiltro = 0;
            }

            $logoCliente = '';
        }


        $countFiltros = count($this->cabecalho);
        $x = 1;


        //Trata o tamanho da string no cabeçalho
        foreach($this->cabecalho as $nomeFiltro => $valorFiltro)
        {
            if (is_array($valorFiltro))
            {
                foreach($valorFiltro as $nestedNomeFiltro => $nestedValorFiltro)
                {
                    $htmlNomeFiltro .= "$nestedNomeFiltro:";

                    if (strlen($nestedValorFiltro) > 30)
                        $htmlValorFiltro .= substr($nestedValorFiltro, 0, 15)."...".substr($nestedValorFiltro, strlen($nestedValorFiltro)-15);
                    else
                        $htmlValorFiltro .= "$nestedValorFiltro";
                }
            }
            else
            {
                $htmlNomeFiltro .= "$nomeFiltro:";
                if (strlen($valorFiltro) > 30)
                    $htmlValorFiltro .= substr($valorFiltro, 0, 15)."...".substr($valorFiltro, strlen($valorFiltro)-15);
                else
                    $htmlValorFiltro .= "$valorFiltro";
            }

            if ($x >= $countFiltros){break;}
            else
            {
                $x++;
                $htmlNomeFiltro .= " <br/>";
                $htmlValorFiltro .= " <br/>";
            }
        }


        if ($colunasRotulosFiltro == 0 && $colunasValoresFiltro == 0) {
            $html = '
            <td colspan="'.$colunasImg.'" style="border: 1px solid; text-align: left; border-top: none; border-right: none; border-left: none; ">
                <img src="assets/images/logoico.png" width="100px"/>
            </td>
            '.$logoCliente.'
            ';
        } else {
            $html = '
            <td colspan="'.$colunasImg.'" style="border: 1px solid; text-align: left; border-top: none; border-right: none; border-left: none; ">
                <img src="assets/images/logoico.png" width="100px"/>
            </td>
            '.$logoCliente.'
            <td colspan="'.$colunasRotulosFiltro.'" style="border: 1px solid; font-size: 12px; text-align: right; width: 150px; border-top: none; border-right: none; border-left: none;">
            '.$htmlNomeFiltro.'
            </td>
            <td colspan="'.$colunasValoresFiltro.'" style="border: 1px solid; font-size: 12px; text-align: left; width: 250px; border-top: none; border-right: none; border-left: none;">
            '.$htmlValorFiltro.'
            </td>
            ';
        }


        return $html;
    }

}