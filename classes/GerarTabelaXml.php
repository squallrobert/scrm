<?php

/**
 * @author Flavio Freitas
 * @copyright 2012
 */

class GerarTabelaXml {

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
    public $mostrar_cabeçalho = true;


    public function GerarTabela() {
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

    public function CriarTabela() {
      //  echo "<pre>Inicio</pre><br>";
        // pegando total de colunas para fazer o colspan no HTML
        $numeroColunas = count($this->colunas['dados_th']) + 1;

        $numeroLinhas = count($this->dados) + 1;

        // Criando Header do XML
        $html = "<?xml version=\"1.0\"?>\n";
        $html .= "<?mso-application progid=\"Excel.Sheet\"?>\n";

        $html .= "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
        $html .= "xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n";
        $html .= "xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n";
        $html .= "xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
        $html .= "xmlns:html=\"http://www.w3.org/TR/REC-html40\">\n";
        $html .= "<DocumentProperties \n";
        $html .= "xmlns=\"urn:schemas-microsoft-com:office:office\">\n";
        $html .= "<Author>Sheephouse</Author>\n";
        $html .= "<LastAuthor>Sheephouse</LastAuthor>\n";
        $html .= "<Created>2012-08-02T04:06:26Z</Created>\n";
        $html .= "<LastSaved>2012-08-02T04:30:11Z</LastSaved>\n";
        $html .= "<Company>Sheephouse</Company>\n";
        $html .= "<Version>11.6360</Version>\n";
        $html .= "</DocumentProperties>\n";
        $html .= "<ExcelWorkbook \n";
        $html .= "xmlns=\"urn:schemas-microsoft-com:office:excel\">\n";
        $html .= "<WindowHeight>8535</WindowHeight>\n";
        $html .= "<WindowWidth>12345</WindowWidth>\n";
        $html .= "<WindowTopX>480</WindowTopX>\n";
        $html .= "<WindowTopY>90</WindowTopY>\n";
        $html .= "<ProtectStructure>False</ProtectStructure>\n";
        $html .= "<ProtectWindows>False</ProtectWindows>\n";
        $html .= "</ExcelWorkbook>\n";

        $html .= "<Styles>\n";
        $html .= "<Style ss:ID=\"Default\" ss:Name=\"Normal\">\n";
        $html .= "<Alignment ss:Vertical=\"Center\" ss:WrapText=\"1\" ss:Horizontal=\"Center\"/>\n";
        $html .= "<Borders/>\n";
        $html .= "<Font/>\n";
        $html .= "<Interior/>\n";
        $html .= "<NumberFormat/>\n";
        $html .= "<Protection/>\n";
        $html .= "</Style>\n";
        $html .= "<Style ss:ID=\"s21\" ss:Name=\"Hyperlink\">\n";
        $html .= "<Font ss:Color=\"#0000FF\" ss:Underline=\"Single\"/>\n";
        $html .= "</Style>\n";
        $html .= "<Style ss:ID=\"s23\">\n";
        $html .= "<Font x:Family=\"Swiss\" ss:Bold=\"1\"/>\n";
        $html .= "</Style>\n";
        $html .= "</Styles>\n";


      //  echo "<pre>Verificando dados</pre><br>";
        // verificando se existem dados para come�r a gerar a tabela
        if (count($this->dados) > 0) {

            $html .= "<Worksheet ss:Name=\"Names\">\n";
            $html .= "<Table ss:ExpandedColumnCount=\"" . $numeroColunas . "\" ss:ExpandedRowCount=\"" . ($numeroLinhas) . "\" x:FullColumns=\"1\" x:FullRows=\"1\">\n";
//            $html .= "<Column ss:Index=\"" . $numeroColunas . "\" ss:AutoFitWidth=\"0\" ss:Width=\"154.5\"/>";
            $colCount = 1;
            $x = 0;
            $width = array();
            $widthFinal = array();

            //Recebe o número de caracteres do nome da coluna para cálculo de largura
            foreach($this->colunas["dados_th"] as $coluna)
            {
                if(strlen($coluna['nome']) > $width[$x])
                    $width[$x] = strlen($coluna['nome']);

                $x++;
            }

            $x = 0;

            //Recebe o número de caracteres do valor da célula para cálculo de largura
            foreach($this->dados as $colunas )
            {
                foreach($colunas["dados_td"] as $coluna => $valor)
                {
                    if(strlen($valor['valor']) > $width[$x])
                        $width[$x] = strlen($valor['valor']);
                    $x++;
                }
                $x = 0;
            }

            //Para cada coluna, calcula uma largura de acordo com o maior conteúdo encontrado nela, até um limite de 150.5px
            while($colCount <= $numeroColunas) {
                $widthFinal = ($width[$colCount - 1] * 5.75) + 3;

                if ($widthFinal > 150.5)
                    $widthFinal = 150.5;
                else if ($widthFinal < 35)
                    $widthFinal = 35;

                $html .= "<Column ss:Index=\"" . $colCount . "\" ss:AutoFitWidth=\"1\" ss:Width=\"$widthFinal\"/>";
                //$html .= "<Column ss:Index=\"" . $colCount . "\" ss:AutoFitWidth=\"1\" ss:Width=\"150.5\"/>";
                $colCount++;
            }

            $html .= "<Row ss:StyleID=\"s23\">";

            //Cria a linha de headers
            foreach($this->colunas["dados_th"] as $coluna) {
                $html .= "<Cell><Data ss:Type=\"String\">".$coluna['nome']."</Data></Cell>";
            }
            $html .= "</Row>";

            //Cria as linhas de dados
            foreach($this->dados as $colunas )
            {
                    $html .= "<Row>\n";

                    foreach($colunas["dados_td"] as $coluna => $valor)
                    {
                        $html .= "<Cell><Data ss:Type=\"String\">" . $valor['valor'] . "</Data></Cell>\n";
                    }

                    $html .= "</Row>\n";
            }



            // EOF do XML

           // echo "<pre>Fim do arquivo</pre><br>";

            $html .= "</Table>\n";
            $html .= "<WorksheetOptions xmlns=\"urn:schemas-microsoft-com:office:excel\">\n";
            $html .= "<Print>\n";
            $html .= "<ValidPrinterInfo/>\n";
            $html .= "<HorizontalResolution>300</HorizontalResolution>\n";
            $html .= "<VerticalResolution>300</VerticalResolution>\n";
            $html .= "</Print>\n";
            $html .= "<Selected/>\n";
            $html .= "<Panes>\n";
            $html .= "<Pane>\n";
            $html .= "<Number>3</Number>\n";
            $html .= "<ActiveRow>1</ActiveRow>\n";
            $html .= "</Pane>\n";
            $html .= "</Panes>\n";
            $html .= "<ProtectObjects>False</ProtectObjects>\n";
            $html .= "<ProtectScenarios>False</ProtectScenarios>\n";
            $html .= "</WorksheetOptions>\n";
            $html .= "</Worksheet>\n";
            $html .= "</Workbook>\n";



        }
        return $html;
    }

    public function CriarTabelaClasse() {
        require_once dirname(__FILE__) . '/../classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        // Propriedades do documento
        $objPHPExcel->getProperties()->setCreator("Sheephouse")
            ->setLastModifiedBy("Sheephouse")
            ->setTitle("Sheephouse XLSX Report")
            ->setSubject("Sheephouse XLSX Report")
            ->setDescription("Sheephouse XLSX Report")
            ->setKeywords("Sheephouse XLSX Report")
            ->setCategory("Sheephouse XLSX Report");


        if ($this->mostrar_cabeçalho){
        //cabecalho
        $cellNumber = 1;
        if (@count($this->filtro) > 0 && is_array($this->filtro))
            foreach($this->filtro AS $key=>$filtro) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("C".$cellNumber, $key . ": ")
                    ->setCellValue("D".$cellNumber, $filtro);

                //$objPHPExcel->getActiveSheet()->getStyle("C".$cellNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle("C".$cellNumber)->getFont()->setBold(true);
                $cellNumber++;

                if ($cellNumber > 6) break;//No máximo 6 linhas de cabeçalho para evitar que se misture com os dados da tabela
            }
        }

        if($this->mostrar_cabeçalho){
            $cellNumber = 8;
        }else{
            $cellNumber = 1;
        }
        // header das colunas
        if (count($this->colunas) > 0 && is_array($this->colunas)) {


			if (isset($this->colunas["dados_th"])) { //aqui monta os headers quando chega só uma linha de dados_th exemplo '$dados_coluna["dados_th"][] = ...'
				$cellLetter = "A";

				foreach($this->colunas["dados_th"] as $coluna) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellLetter.$cellNumber, $coluna['nome']);

					if ($coluna["width"] != "") {
						$objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setWidth((int)$coluna["width"]);
					} else {
						$objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setAutoSize(true);

					}
					$objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('000000');
					$cellLetter++;
				}

                if($this->mostrar_cabeçalho){
                    $cellNumber = 9;
                }else{
                    $cellNumber = 2;
                }

			} else { //aqui monta os headers quando chega mais de uma linha de dados_th exemplo '$dados_coluna[0]["dados_th"][] = ...'
				foreach($this->colunas AS $col) {
					$cellLetter = "A";
					foreach($col["dados_th"] as $coluna) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellLetter.$cellNumber, $coluna['nome']);

						$objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                        $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                        $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('000000');



						if ($coluna["colspan"]) {
							$celulaInicial = $cellLetter.$cellNumber;
							for($a = 0; $a < $coluna['colspan']-1; $a++) {
								$cellLetter++;
							}
							$objPHPExcel->setActiveSheetIndex(0)->mergeCells($celulaInicial.':'.$cellLetter.$cellNumber);
						}

                        if ($coluna["width"] != "") {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setWidth((int)$coluna["width"]);
                        } else {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setAutoSize(true);
                        }


						$cellLetter++;
					}
					$cellNumber++;
				}
			}

		}


        //dados
        $cellLetter = "A";
        if (count($this->dados) > 0 && is_array($this->dados))
            foreach($this->dados as $colunas ) {
                foreach($colunas["dados_td"] as $coluna => $valor) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($cellLetter.$cellNumber, strip_tags($valor['valor']));
                    $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getAlignment()->setWrapText(true);
					if ($valor['type'] == "monetary") {
						$objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
					}
                    $cellLetter++;
                }
                $cellNumber++;      //Passa para a próxima linha
                $cellLetter = "A";  //Reseta a coluna para o início
            }

        //Adiciona a logo do cliente, se ela existir
        if ($this->logo_cliente != "" && $this->logo_cliente != null && file_exists($this->logo_cliente)) {
            $objDrawingCliente = new PHPExcel_Worksheet_Drawing();
            $objDrawingCliente->setWorksheet($objPHPExcel->getActiveSheet());
            $objDrawingCliente->setName('logo_cliente');
            $objDrawingCliente->setDescription('logo_cliente');
            $objDrawingCliente->setPath($this->logo_cliente);
            $objDrawingCliente->setCoordinates("E1");
            $objDrawingCliente->setHeight(120);
        }

        if($this->mostrar_cabeçalho){
            //Logo da Sheephouse
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            $objDrawing->setName('logo_link');
            $objDrawing->setDescription('logo_link');
            $objDrawing->setPath("assets/images/logoico.png");
            $objDrawing->setCoordinates("A1");

            //Estilos gerais
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B6');
            $objPHPExcel->getActiveSheet()->calculateColumnWidths();

            if($objPHPExcel->getActiveSheet()->getColumnDimension("A")->getWidth()<16){
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(16);
            }else{
                $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
            }

            if($objPHPExcel->getActiveSheet()->getColumnDimension("B")->getWidth()<16){
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(16);
            }else{
                $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function CriarVariasTabelas()
    {
        require_once dirname(__FILE__) . '/../classes/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        // Propriedades do documento
        $objPHPExcel->getProperties()->setCreator("Sheephouse")
            ->setLastModifiedBy("Sheephouse")
            ->setTitle("Sheephouse XLSX Report")
            ->setSubject("Sheephouse XLSX Report")
            ->setDescription("Sheephouse XLSX Report")
            ->setKeywords("Sheephouse XLSX Report")
            ->setCategory("Sheephouse XLSX Report");

        //cabecalho
        $cellNumber = 1;
        if (count($this->filtro) > 0 && is_array($this->filtro))
            foreach($this->filtro AS $key=>$filtro) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("C".$cellNumber, $key . ": ")
                    ->setCellValue("D".$cellNumber, $filtro);

                //$objPHPExcel->getActiveSheet()->getStyle("C".$cellNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle("C".$cellNumber)->getFont()->setBold(true);
                $cellNumber++;

                if ($cellNumber > 6) break;//No máximo 6 linhas de cabeçalho para evitar que se misture com os dados da tabela
            }


        // header das colunas
        $cellLetter = "A";
        $cellNumber = 8;

        foreach($this->dados AS $dados) {
            if (count($this->colunas) > 0 && is_array($this->colunas))
                foreach($this->colunas["dados_th"] as $coluna) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($cellLetter.$cellNumber, $coluna['nome']);

                    $objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFont()->setBold(true);
                    $cellLetter++;
                }

            //dados
            $cellLetter = "A";
            $cellNumber++;
            if (count($dados) > 0 && is_array($dados))
                foreach($dados as $colunas ) {
                    foreach($colunas["dados_td"] as $coluna => $valor) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($cellLetter.$cellNumber, strip_tags($valor['valor']));
                        $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getAlignment()->setWrapText(true);
                        $cellLetter++;
                    }
                    $cellNumber++;      //Passa para a próxima linha
                    $cellLetter = "A";  //Reseta a coluna para o início
                }

            $cellNumber += 2;      //Passa para a próxima linha
        }


        //Adiciona a logo do cliente, se ela existir
        if ($this->logo_cliente != "" && $this->logo_cliente != null && file_exists("erp/".$this->logo_cliente)) {
            $objDrawingCliente = new PHPExcel_Worksheet_Drawing();
            $objDrawingCliente->setWorksheet($objPHPExcel->getActiveSheet());
            $objDrawingCliente->setName('logo_cliente');
            $objDrawingCliente->setDescription('logo_cliente');
            $objDrawingCliente->setPath("erp/".$this->logo_cliente);
            $objDrawingCliente->setCoordinates("E1");
            $objDrawingCliente->setHeight(120);
        }

        //Logo da Sheephouse
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        $objDrawing->setName('logo_link');
        $objDrawing->setDescription('logo_link');
        $objDrawing->setPath("assets/images/logoico.png");
        $objDrawing->setCoordinates("A1");

        //Estilos gerais
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B6');
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(20);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    public function CriarRelatorioTemperatura($nome_arquivo) {
        require_once dirname(__FILE__) . '/../classes/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        // Propriedades do documento
        $objPHPExcel->getProperties()->setCreator("Sheephouse")
            ->setLastModifiedBy("Sheephouse")
            ->setTitle("Sheephouse XLSX Report")
            ->setSubject("Sheephouse XLSX Report")
            ->setDescription("Sheephouse XLSX Report")
            ->setKeywords("Sheephouse XLSX Report")
            ->setCategory("Sheephouse XLSX Report");

        //cabecalho
        $cellNumber = 1;
        if (count($this->filtro) > 0 && is_array($this->filtro))
            foreach($this->filtro AS $key=>$filtro) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("C".$cellNumber, $key . ": ")
                    ->setCellValue("D".$cellNumber, $filtro);

                //$objPHPExcel->getActiveSheet()->getStyle("C".$cellNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle("C".$cellNumber)->getFont()->setBold(true);
                $cellNumber++;

                if ($cellNumber > 6) break;//No máximo 6 linhas de cabeçalho para evitar que se misture com os dados da tabela
            }

        $cellNumber = 8;

        // header das colunas
        if (count($this->colunas) > 0 && is_array($this->colunas)) {
            if (isset($this->colunas["dados_th"])) {
                $cellLetter = "A";
                foreach($this->colunas["dados_th"] as $coluna) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($cellLetter.$cellNumber, $coluna['nome']);

                    if ($coluna["width"] != "") {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setWidth((int)$coluna["width"]);
                    } else {
                        $objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setAutoSize(true);

                    }
                    $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                    $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('000000');
                    $cellLetter++;
                }

                $cellNumber = 9;

            } else {
                foreach($this->colunas AS $col) {
                    $cellLetter = "A";
                    foreach($col["dados_th"] as $coluna) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($cellLetter.$cellNumber, $coluna['nome']);

                        $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        if ($coluna["colspan"]) {
                            $celulaInicial = $cellLetter.$cellNumber;
                            for($a = 0; $a < $coluna['colspan']-1; $a++) {
                                $cellLetter++;
                            }
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($celulaInicial.':'.$cellLetter.$cellNumber);
                        }

                        if ($coluna["width"] != "") {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setWidth((int)$coluna["width"]);
                        } else {
                            $objPHPExcel->getActiveSheet()->getColumnDimension($cellLetter)->setAutoSize(true);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                        $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('000000');

                        $cellLetter++;
                    }
                    $cellNumber++;
                }
            }
        }

        //dados
        $cellLetter = "A";
        if (count($this->dados) > 0 && is_array($this->dados))
            foreach($this->dados as $colunas ) {
                foreach($colunas["dados_td"] as $coluna => $valor) {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($cellLetter.$cellNumber, strip_tags($valor['valor']));
                    $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getAlignment()->setWrapText(true);
                    if ($valor['type'] == "monetary") {
                        $objPHPExcel->getActiveSheet()->getStyle($cellLetter.$cellNumber)->getNumberFormat()->setFormatCode('_("$"* #,##0.00_);_("$"* \(#,##0.00\);_("$"* "-"??_);_(@_)');
                    }
                    $cellLetter++;
                }
                $cellNumber++;      //Passa para a próxima linha
                $cellLetter = "A";  //Reseta a coluna para o início
            }


        //Logo da Sheephouse
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        $objDrawing->setName('logo_link');
        $objDrawing->setDescription('logo_link');
        $objDrawing->setPath("img/logoico.png");
        $objDrawing->setCoordinates("A1");

        //Estilos gerais
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B6');
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();

        if($objPHPExcel->getActiveSheet()->getColumnDimension("A")->getWidth()<16){
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(16);
        }else{
            $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
        }

        if($objPHPExcel->getActiveSheet()->getColumnDimension("B")->getWidth()<16){
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(false);
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(16);
        }else{
            $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $nome_arquivo = '../assets/arquivos_solicitacao_temperatura/'.$nome_arquivo;
        $objWriter->save($nome_arquivo);

        return $nome_arquivo;
    }

}