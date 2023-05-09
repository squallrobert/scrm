<?

class BoletoFranqueadora 
{
    private $idContaReceber;
    private $idContaReceberOcorrencia;

    /*public function GetIdNotaFiscal()
    {
        return $this->idNotaFiscal;
    }*/

    public function SetIdContaReceber($arg)
    {
        $this->idContaReceber = $arg;
    }

    /*public function GetIdContaReceber()
    {
        return $this->idContaReceber;
    }*/

    public function SetIdContaReceberOcorrencia($arg)
    {
        $this->idContaReceberOcorrencia = $arg;
    }

    /**
     *  Busca os dados dos boletos.
     *
     *  Para buscar os dados de todos os boletos de uma fatura defina somente o idNotaFiscal
     *  Para buscar os dados de um boleto específico defina o idContaReceber
     *
     */
    public function BuscarDadosBoletos()
    {
        if($this->idContaReceber > 0 === false && $this->idContaReceberOcorrencia > 0 === false)
            return false;

        $pdo = new Conexao();

		$sql = "SELECT contas_receber.numero_fatura,
                date_format(contas_receber.data_hora_cadastro,'%d/%m/%Y') AS data_hora_cadastro_nfe,
                date_format(contas_receber_ocorrencias.data_hora_cadastro,'%d/%m/%Y') AS datacadastro,
                date_format(contas_receber_ocorrencias.data_vencimento,'%d/%m/%Y') AS vencimento,
                franqueado.`nome` AS cliente,
                franqueado.`cnpj` AS cliente_documento,
                contas_receber_ocorrencias.*,
                (contas_receber_ocorrencias.valor - COALESCE(contas_receber_ocorrencias.desconto_geral,0) - COALESCE(contas_receber_ocorrencias.desconto_detalhado,0)) AS valor_com_desconto,

                endereco.id AS id_endereco_cobranca,
                endereco.email AS endcobranca_email, endereco.logradouro AS endcobranca_logradouro, endereco.numero AS endcobranca_numero,
                endereco.complemento AS endcobranca_complemento, endereco.bairro AS endcobranca_bairro, endereco.cep AS endcobranca_cep,
                endereco.uf AS endcobranca_estado, endereco.cidade AS endcobranca_cidade, endereco.ddd_telefone AS endcobranca_ddd,
                endereco.telefone AS endcobranca_telefone,

                erp_parametro_financeiro.percentual_multa, erp_parametro_financeiro.percentual_juros_dia,
                erp_parametro_financeiro.texto_linha1_boleto, erp_parametro_financeiro.texto_linha2_boleto,

                conta_corrente.agencia, conta_corrente.conta AS conta_corrente, conta_corrente.carteira, conta_corrente.personalizado, conta_corrente.numero_sequencial,
                conta_corrente.id_erp_banco AS banco,
                erp_banco.url_atualizacao_boleto

                FROM contas_receber
                INNER JOIN contas_receber_ocorrencias ON (contas_receber_ocorrencias.id_contas_receber = contas_receber.id)
                INNER JOIN franqueado ON (contas_receber.id_franquia = franqueado.id)
                LEFT JOIN endereco ON (franqueado.id_endereco = endereco.id)
                LEFT JOIN erp_parametro_financeiro ON (erp_parametro_financeiro.id_franqueado = franqueado.id AND erp_parametro_financeiro.excluido IS NULL)
                LEFT JOIN conta_corrente ON (conta_corrente.id = contas_receber_ocorrencias.id_conta_corrente)
                LEFT JOIN erp_banco ON (erp_banco.id = conta_corrente.id_erp_banco)
                WHERE contas_receber_ocorrencias.data_hora_cancelamento IS NULL";

        if($this->idContaReceber > 0) $sql .= " AND contas_receber.id = :id_conta_receber";
        if($this->idContaReceberOcorrencia > 0) $sql .= " AND contas_receber_ocorrencias.id = :id_conta_receber_ocorrencia";

		$stmt = $pdo->prepare($sql);

        if($this->idContaReceber > 0) $stmt->bindParam(":id_conta_receber", $this->idContaReceber, PDO::PARAM_INT);
        if($this->idContaReceberOcorrencia > 0) $stmt->bindParam(":id_conta_receber_ocorrencia", $this->idContaReceberOcorrencia, PDO::PARAM_INT);

		//echo '<pre>';
		//echo $sql;
		//echo '</pre>';

		$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function GerarLinhaDigitavelURLSegundaVia($idContaReceber)
    {
        $this->SetIdContaReceberOcorrencia($idContaReceber);
        $dadosBoletos = $this->BuscarDadosBoletos();
        $dadosBoleto = $dadosBoletos[0];

        if($dadosBoleto->banco == '30')
        {
            $boleto = new BoletoBradesco($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento);
            $boleto->Agencia = $dadosBoleto->agencia;
            $boleto->Conta = $dadosBoleto->conta_corrente;
        }
        // Sicredi
        else if($dadosBoleto->banco == '42')
        {
            $boleto = new BoletoSicredi($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento, $dadosBoleto->datacadastr);
            $boleto->Agencia = $dadosBoleto->agencia;
            $boleto->Conta = $dadosBoleto->conta_corrente;
        }
        // Caixa
        else if($dadosBoleto->banco == '120')
        {
            $boleto = new BoletoCaixa($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento);
            $boleto->Agencia = $dadosBoleto->agencia;
            $boleto->Conta = $dadosBoleto->conta_corrente;
            $boleto->Personalizado = $dadosBoleto->personalizado;
        }
        // Itaú
        else
        {
            $boleto = new BoletoItau($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento);
            $boleto->Agencia = $dadosBoleto->agencia;
            $boleto->Conta = $dadosBoleto->conta_corrente;
        }


        return array('linha_digitavel' => $boleto->CalcularLinhaDigitavel(), 'url_atualizacao_boleto' => $dadosBoleto->url_atualizacao_boleto);
    }

    /**
     *  Gera o HTML dos boletos.
     *
     *  Para gerar todos os boletos de uma fatura defina somente o idNotaFiscal
     *  Para gerar um boleto específico defina o idContaReceber
     *
     */
    public function GerarBoletos($NovoVencimento = NULL, $NovoValor = NULL, $comFatura = false, $qtdBoletos = 1)
    {

        if($this->idContaReceber > 0 === false && $this->idContaReceberOcorrencia > 0 === false)
            return false;

        $html = '';
        $dadosBoletos = $this->BuscarDadosBoletos();
        //echo '<pre>'; print_r($dadosBoletos); echo '</pre>';
        $FinalBoleto = '';
        if(count($dadosBoletos) > 0)
        {
            //if($this->idContaReceber > 0 === false)
            if($comFatura)
                $html .= '
                <div style="page-break-before: always"></div>';
            else
                $html .= '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="content-type" content="text/html; charset=UTF-8">
                <!--<title>'.ROTULO_IMPRESSAO_BOLETO.'</title>-->
                </head>
                <body>';


            foreach($dadosBoletos as $dadosBoleto)
            {

                if($dadosBoleto->id_forma_pagamento != 1) // Se a forma de pagamento não for boleto não gerará o HTML
                    continue;

                if($NovoVencimento != NULL)
                    $dadosBoleto->vencimento = $NovoVencimento;
                if($NovoValor != NULL)
                    $dadosBoleto->valor_com_desconto = $NovoValor;



                //echo '<pre>'; print_r($dadosBoletos); echo '</pre>';
                //echo $html;
                if($NovoVencimento != NULL)
                    $html .= $this->GerarCorpoBoleto($dadosBoleto, true, true);
                else
                    $html .= $this->GerarCorpoBoleto($dadosBoleto, true, false);


                $html .= $this->GerarCorpoBoleto($dadosBoleto, false, false);
                if($qtdBoletos > 1)
                    $html .= '<div style="page-break-after: always"></div>';
                else if ($FinalBoleto != (count($dadosBoletos) - 1))
                    $html .= '<div style="page-break-after: always"></div>';
                
                $FinalBoleto++;

            }


            if($this->idContaReceber > 0)
                $html .= '
                <body>
                </html>';
        }


        return $html;
    }

    public function GerarCorpoBoleto($dadosBoleto, $viaSacado = false, $SegundaVia = false)
    {


        if(count($dadosBoleto) > 0 === false)
            return false;
//echo 'VALORRRR'.Utils::MaskFloat($dadosBoleto->valor_com_desconto).'<BR><br>';
		// Bradesco
        if($dadosBoleto->banco == '30')
        {
            $boleto = new BoletoBradesco($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento);
            $boleto->Agencia = str_pad($dadosBoleto->agencia,4,"0",STR_PAD_LEFT);
            $boleto->Conta = str_pad($dadosBoleto->conta_corrente,8,"0",STR_PAD_LEFT);
        }
		// Sicredi
		else if($dadosBoleto->banco == '42')
		{
			$boleto = new BoletoSicredi($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento, $dadosBoleto->datacadastr);
            $boleto->Agencia = str_pad($dadosBoleto->agencia,4,"0",STR_PAD_LEFT);
            $boleto->Conta = str_pad($dadosBoleto->conta_corrente,5,"0",STR_PAD_LEFT);
            $boleto->Personalizado = str_pad($dadosBoleto->personalizado,2,"0",STR_PAD_LEFT);
		}
		// Caixa
		else if($dadosBoleto->banco == '120')
		{
			$boleto = new BoletoCaixa($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento);
			$boleto->Agencia = $dadosBoleto->agencia;
			$boleto->Conta = $dadosBoleto->conta_corrente;
			$boleto->Personalizado = $dadosBoleto->personalizado;
		}
		// Itaú
        else
        {
            $boleto = new BoletoItau($dadosBoleto->nosso_numero, Utils::MaskFloat($dadosBoleto->valor_com_desconto), $dadosBoleto->vencimento);
            $boleto->Agencia = str_pad($dadosBoleto->agencia,4,"0",STR_PAD_LEFT);
            $boleto->Conta = str_pad($dadosBoleto->conta_corrente,6,"0",STR_PAD_LEFT);
        }
//echo'<br><BR><BR><BR>'; print_r($boleto);


        // Define qual endereço do cliente será exibido no boleto
        $tmpLogradouro = trim($dadosBoleto->endcobranca_logradouro);
        $tmpNumero = trim($dadosBoleto->endcobranca_numero);
        $tmpComplemento = trim($dadosBoleto->endcobranca_complemento);
        $tmpBairro = trim($dadosBoleto->cliente_bairro);
        $tmpCidade = trim($dadosBoleto->endcobranca_cidade);
        $tmpUF = trim($dadosBoleto->endcobranca_estado);
        $tmpCEP = trim($dadosBoleto->endcobranca_cep);

        $cidEstCepCobranca = trim($tmpCidade)."/".trim($tmpUF)." - ".ROTULO_CEP." ".trim($tmpCEP);
        $enderecoCobranca  = trim($tmpLogradouro).", ".trim($tmpNumero)." ".trim($tmpComplemento);
        if($tmpBairro != '')
            $enderecoCobranca .= " - ".$tmpBairro;


        $html = '
        <style>
        <!--
        /* deixa todos os descendentes sem borda e padding */


        body {
        	font-family: Verdana, Arial, Helvetica, sans-serif;
        	font-size: 12px;
        }
        table {
            border-spacing:0;
            border-collapse:collapse;
        }
        .box
        {
            width:816px;
        }
        /*
        .fichaCompensacao * {
        	border:0px;
        	padding:0px;
        }
        .headerBoleto th{
        	border-bottom: 2px solid #000;
        }
        .bdHeaderButtom{
        	border-bottom: 2px solid #000
        }
        */
        .bdRight{
        	border-right: 2px solid #000;
        }
        /*
        .tbBoleto .noBorderTop {
        	border:0px;
        }
        .tbBoleto td{
        	padding-left:5px;
        }
        .linhaItemBoleto td{
        	vertical-align: top;
        	text-align: left;
        }
        .textCenter{
        	text-align: center;
        }
        */
        .lbItemBoleto {
        	font-size: 12px;

        }
        .itemBoleto{
        	padding-top:2px;
        	padding-bottom:2px;
        	padding-left:5px;
        	font-weight: bold;
        }
        /*
        .tbItens2{

        }
        .tbItens2 .itemBoleto{
        	text-align: center;
        }
        */
        tr .borderBottom{
        	border-bottom: 1px solid #000;
        }
        td .borderBottom{
        	border-bottom: 1px solid #000;
        }
        tr .borderTop{
        	border-top: 1px solid #000;
        }
        td .borderTop{
        	border-top: 1px solid #000;
        }
        tr .borderRight{
        	border-right: 1px solid #000;
        }
        tr .borderLeft{
        	border-left: 1px solid #000;
        }

        .boletos td
        {
            border: 1px solid #000;
            padding: 2px;
        }

        -->
        </style>';

        if(!$viaSacado)
            $html .= '
        <br />
        <br />
        <div style="border-bottom: 1px dashed #000; margin-bottom: 10px;"></div>
        <br />';

        $html .= '
        <div class="box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>';
					// Bradesco
                    if($dadosBoleto->banco == 30)
                    {
                       $html .= '<td class="bdRight" width="130" style="font-size: 12px; padding-bottom: 2px;"><img src="imagens/logobradesco.jpg" align="absmiddle" /></td>
                        <th class="bdRight" width="75" style="font-size: 14px; font-weight: bold;">237-2</th>
                        <td align="right" style="font-size: 14px; font-weight: bold;">';
                    }
					// Sicredi
					else if($dadosBoleto->banco == 42)
					{
						$html .= '<td class="bdRight" width="130" style="font-size: 12px; padding-bottom: 2px;"><img src="imagens/logosicredi.jpg" align="absmiddle" /></td>
                                    <th class="bdRight" width="75" style="font-size: 14px; font-weight: bold;">748-X</th>
                                    <td align="right" style="font-size: 14px; font-weight: bold;">';
					}
					// Caixa
					else if($dadosBoleto->banco == 120)
					{
						$html .= '<td class="bdRight" width="130" style="font-size: 12px; padding-bottom: 2px;"><img src="imagens/logocaixa.jpg" align="absmiddle" /></td>
                                    <th class="bdRight" width="75" style="font-size: 14px; font-weight: bold;">104-0</th>
                                    <td align="right" style="font-size: 14px; font-weight: bold;">';
					}
					// Itaú
                    else
                    {
                        $html .= '<td class="bdRight" width="130" style="font-size: 12px; padding-bottom: 2px;"><img src="imagens/logo-itau.jpg" align="absmiddle" /> '.ROTULO_BANCO_ITAU.'</td>
                        <th class="bdRight" width="75" style="font-size: 14px; font-weight: bold;">341-7</th>
                        <td align="right" style="font-size: 14px; font-weight: bold;">';
                    }

                    if($viaSacado && $SegundaVia)
                        $html .= ROTULO_SEGUNDA_VIA_EXTENSO. ' - ' .ROTULO_RECIBO_PAGADOR;
                    elseif($viaSacado)
                        $html .= ROTULO_RECIBO_PAGADOR;
                    else
                        $html .= $boleto->CalcularLinhaDigitavel();

                    $html .= '
                    </td>
                </tr>
            </table>
            <table class="boletos" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td colspan="6">';
						// Bradesco
                        if($dadosBoleto->banco == 30)
                        {
                            $html .= '<div class="lbItemBoleto">'.ROTULO_LOCAL_PAGAMENTO.': '.ROTULO_PG_SOMENTE_BRADESCO.'<br></div>
        				    <div class="itemBoleto" style="text-align: left;">'.ROTULO_PG_APOS_VENCIMENTO_BRADESCO.'</div>';
                        }
						// Sicredi
						else if($dadosBoleto->banco == 42)
						{
							$html .= '<div class="lbItemBoleto">'.ROTULO_LOCAL_PAGAMENTO.':
							<div class="itemBoleto" style="text-align: left;">'.ROTULO_PG_SOMENTO_SICREDI.'<br></div>';
						}
						// Caixa
						else if($dadosBoleto->banco == 120)
						{
							$html .= '<div class="lbItemBoleto">'.ROTULO_LOCAL_PAGAMENTO.':
							<div class="itemBoleto" style="text-align: left;">'.ROTULO_PG_SOMENTE_CAIXA.'<br></div>';
						}
						// Itaú
                        else
                        {
                            $html .= '<div class="lbItemBoleto">'.ROTULO_LOCAL_PAGAMENTO.': '.ROTULO_PG_SOMENTE_ITAU.'<br></div>
        				    <div class="itemBoleto" style="text-align: left;">'.ROTULO_PG_APOS_VENCIMENTO_ITAU.'</div>';
                        }
                    $html .='
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">'.ROTULO_VENCIMENTO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">'.$dadosBoleto->vencimento.'</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <table cellspacing="0" cellpadding="0" width="100%" border="0">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;" colspan="2">'.ROTULO_BENEFICIARIO.'</td></tr>
                            <tr>
                            	<!--<td class="itemBoleto" style="border: 0; padding: 0;">'.$dadosBoleto->franqueado.'</td>
                            	<td class="itemBoleto" style="border: 0; padding: 0; float: right;" >'.ROTULO_CNPJ.': '.$dadosBoleto->franqueado_cnpj.'</td>-->

                            	<td class="lbItemBoleto" width="350" style="border: 0; padding: 0;">
                                    <div class="lbItemBoleto" style="float: left; width: 350px;">LINK MONITORAMENTO LTDA</div>
                                </td>';
                                if($dadosBoleto->banco == 30)
								{
									$html .= '<td align="right" valign="top" style="border: 0; padding: 0;">
                                    			<div style="float: right; width: 150px;" >'.ROTULO_CNPJ.': 10.974.997/001-34</div>
                                			 </td>';
								}
                            $html .= '
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">'.ROTULO_AG_CODIGO_BENEFICIARIO.'</td></tr>';
							if($dadosBoleto->banco == 42)
							{
								$html .= '<tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center; white-space: nowrap;">' . $boleto->Agencia . '.'.$dadosBoleto->Personalizado.'.' . $boleto->Conta . '</td></tr>';
							}
                            elseif($dadosBoleto->banco == 120)
                            {
                                $html .= '<tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">' . $boleto->Agencia . '/' . $boleto->Conta . '-' . $boleto->CalculoDacModulo11Anexo6(). '</td></tr>';
                            }
							elseif($dadosBoleto->banco == 30)
							{
								$html .= '<tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">' . $boleto->Agencia . '-'. $boleto->CalculoDigitoVerificadorAgencia(). '/' . $boleto->Conta . '</td></tr>';
							}
							else
								$html .= '<tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center; white-space: nowrap;">' . $boleto->Agencia . '/' . $boleto->Conta . '</td></tr>';
						$html .='
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0; white-space: nowrap;">'.ROTULO_DTA_DOCUMENTO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">';

							//DATA DOC - campo DATA_HORA_CADASTRO da tabela ERP_CONTAS_RECEBER.
							$html .= $dadosBoleto->datacadastro.'</td></tr>
                        </table>
                    </td>
                    <td colspan="2">
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0; white-space: nowrap;">'.ROTULO_NUM_DOCUMENTO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">';

							//Nº DO DOCUMENTO - campo NUMERO_FATURA da tabela ERP_NOTA_FISCAL.
							$html .= $dadosBoleto->numero_fatura.'</td></tr>
                        </table>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0; white-space: nowrap;">'.ROTULO_ESPECIE_DOC.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">DM</td></tr>
                        </table>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0; white-space: nowrap;">'.ROTUL_ACEITE.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">N</td></tr>
                        </table>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0; white-space: nowrap;">'.ROTULO_DTA_PROCESSAMENTO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">';

							//DATA PROCES - campo DATA_HORA_CADASTRO da tabela ERP_CONTAS_RECEBER.
							$html .= $dadosBoleto->datacadastro.'</td></tr>
                        </table>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0; white-space: nowrap;">'.ROTULO_NOSSO_NUMERO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">';
        					/*
        					 * Carteira 109 -> DIRETA ELETRÔNICA SEM EMISSÃO – SIMPLES
        					 *
        					 * Diretas (com registro): é de livre utilização pelo cedente,
        					 * não podendo ser repetida se o número ainda estiver registrado
        					 * no Banco Itaú ou se transcorridos menos de 45 dias de sua baixa / liquidação no Banco Itaú.
        					 * Dependendo da carteira de cobrança utilizada a faixa de Nosso Número pode ser definida pelo Banco.
        					 * Para todas as movimentações envolvendo o título, o “Nosso Número” deve ser informado.
        					 *
        					 * (E) Somente utilizar nosso número dentro de faixa numérica definida pelo Banco Itaú.
        					 */
							// Bradesco
                            if($dadosBoleto->banco == 30)
                            {
        					   $html .= $boleto->CodigoCarteiraCobranca.'/'.$boleto->GetNossoNumero().'-'.$boleto->VerificarNossoNumeroMod11().'</td></tr>';
                            }
							// Sicredi
							else if($dadosBoleto->banco == 42)
							{
								$html .= date("y").'/'.$boleto->GetNossoNumero().'-'.$boleto->VerificarNossoNumeroMod11().'</td></tr>';
							}
							// Caixa
							else if($dadosBoleto->banco == 120)
							{
								$html .= '14/'.$boleto->GetNossoNumero().'-'.$boleto->VerificarNossoNumeroMod11().'</td></tr>';
							}
							// Itaú
                            else
                            {
                                $html .= $boleto->CodigoCarteiraCobranca.'/'.$boleto->GetNossoNumero().'-'.$boleto->CalculoDacModulo10Anexo4().'</td></tr>';
                            }

                        $html .= '</table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="lbItemBoleto" style="white-space: nowrap;">'.ROTULO_USO_BANCO.'</div>
                        <div class="itemBoleto">&nbsp;</div>
                    </td>
                    <td>
						<div class="lbItemBoleto">'.ROTULO_CARTEIRA.'</div>
						<div class="itemBoleto">&nbsp;  '.$boleto->CodigoCarteiraCobranca.'</div>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">'.ROTULO_ESPECIE.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;">R$</td></tr>
                        </table>
                    </td>
                    <td colspan="2">
						<div class="lbItemBoleto">'.ROTULO_QTDA.'</div>
						<div class="itemBoleto">&nbsp;</div>
                    </td>
                    <td>
						<div class="lbItemBoleto" style="white-space: nowrap;">'.ROTULO_VALOR.'</div>
						<div class="itemBoleto">&nbsp;</div>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0; white-space: nowrap;">(=) '.ROTULO_VALOR_DOCUMENTO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0; text-align: center;"><b>'.str_replace('.', ',', $dadosBoleto->valor_com_desconto ).'</td></tr>
                        </table>
        			</td>
                </tr>
                <tr>
                    <td colspan="6" rowspan="5" valign="top">
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0;">'.ROTULO_INSTRUCOES_BOLETO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0;">';

        					if($dadosBoleto->texto_linha1_boleto != null && $dadosBoleto->texto_linha1_boleto != "")
    							$html .= mb_strtoupper($dadosBoleto->texto_linha1_boleto)."<br>";

    						if($dadosBoleto->texto_linha2_boleto != null && $dadosBoleto->texto_linha2_boleto != "")
    							$html .= mb_strtoupper($dadosBoleto->texto_linha2_boleto)."<br>";
                                
                            if($dadosBoleto->placas != "")
                                $html .= "Placa: ".$dadosBoleto->placas."<br>";

    						if($dadosBoleto->percentual_multa != null && $dadosBoleto->percentual_multa != "")
    							$html .= ROTULO_APOSVENCIMENTO_CBMULTA.": ". Utils::MaskFloat($dadosBoleto->percentual_multa) ."%<br>";

    						if($dadosBoleto->percentual_juros_dia != null && $dadosBoleto->percentual_juros_dia != "")
    							$html .= ROTULO_JUROS_AODIA.": ".Utils::MaskFloat($dadosBoleto->percentual_juros_dia,3) ."%";

        				    $html .= '&nbsp;</td></tr>
                        </table>
                    </td>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">(-) '.ROTULO_DESCONTO_ABATIMENTO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0;">';

        					/**
        					 * DESCONTO/ABATIMENTOS - campo DESCONTO da tabela ERP_CONTAS_RECEBER (ou R21 – não apresentar desconto).
        					 *
        					 * UC10 - R21. Se foi informado um NOVO VENCIMENTO e o valor a receber (com cálculo de juros/multa)
        					 * for diferente do campo VALOR da tabela ERP_CONTAS_RECEBER,
        					 * considerar como valor do boleto o valor a receber (valor calculado com juros e multa).
        					 *
        					 * Na tela que permite informar novo vencimento não existe opção para informar um novo desconto.
        					 * Foi considerado o campo DESCONTO da tabela ERP_CONTAS_RECEBER em função disto.
        					 */

        					//a norma do banco (COBRANCA 400 BYTES CNAB-VERSAO7 0-OUTUBRO-2010 (2)) diz para não preencher por isto ficou em branco.
        					//R$ '.Utils::MaskFloat($linha->desconto)? >


        				/*
        				 * 9 – CAMPOS SITUADOS ABAIXO DO CAMPO “VALOR DO DOCUMENTO” (COBRANCA 400 BYTES CNAB-VERSAO7 0-OUTUBRO-2010 (2))
        				 * Não deverão ser preenchidos (uso exclusivo do funcionário-caixa).
        				 * Eventuais valores que o cedente queira cobrar deverão ser indicados no campo “Instruções” do BOLETO.
        				 */
        				$html .= '&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">&nbsp;</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0;">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">(=) '.ROTULO_MORA_MULTA.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0;">';
        				/*
        				 * 9 – CAMPOS SITUADOS ABAIXO DO CAMPO “VALOR DO DOCUMENTO” (COBRANCA 400 BYTES CNAB-VERSAO7 0-OUTUBRO-2010 (2))
        				 * Não deverão ser preenchidos (uso exclusivo do funcionário-caixa).
        				 * Eventuais valores que o cedente queira cobrar deverão ser indicados no campo “Instruções” do BOLETO.
        				 */
        				$html .= '&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">&nbsp;</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0;">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="100%">
                            <tr><td class="lbItemBoleto" style="border: 0; padding: 0;">(=) '.ROTULO_VALOR_COBRADO.'</td></tr>
                            <tr><td class="itemBoleto" style="border: 0; padding: 0;">';
        				/*
        				 * 9 – CAMPOS SITUADOS ABAIXO DO CAMPO “VALOR DO DOCUMENTO” (COBRANCA 400 BYTES CNAB-VERSAO7 0-OUTUBRO-2010 (2))
        				 * Não deverão ser preenchidos (uso exclusivo do funcionário-caixa).
        				 * Eventuais valores que o cedente queira cobrar deverão ser indicados no campo “Instruções” do BOLETO.
        				 */
        				$html .= '&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <table cellspacing="0" cellpadding="0" width="100%" border="0">
                            <tr>
                                <td valign="top" width="500" style="border: 0; padding: 0;">
                                    <div class="lbItemBoleto" style="float: left; width: 350px;">'.ROTULO_SACADO.' - '.$dadosBoleto->cliente.'</div>
                                    <div style="height: 43px ;clear: both">
                    					'.$enderecoCobranca .'<br>
                    					'.$cidEstCepCobranca.'
                    				</div>
                                    '.ROTULO_SACADOR_AVALISTA.'
                                </td>
                                <td align="right" valign="top" style="border: 0; padding: 0;">
                                    <div style="float: right; width: 250px;" >'.ROTULO_CPF_CNPJ.': '.$dadosBoleto->cliente_documento.'</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>';

                if($viaSacado)
                    $html .= '
                    <td valign="top" style="border: 0; text-align: right;">'.ROTULO_AUTENTICACAO_MECANICA.'</td>';
                else
                {
                    $html .= '
                    <td style="padding-top: 2px; padding-left: 0px" width="490">
                        <img src="'.URL_SITE.'barcode.php?codigoBarras='.$boleto->GetCodigoBarras().'">
                    </td>
                    <td valign="top" width="326">
                        <table cellspacing="0" cellpadding="0" width="326">
                            <tr><td style="border: 0; padding: 0; text-transform: uppercase; text-align: right;" align="right"><b>'.ROTULO_FICHA_DE_COMPENSACAO.'</b></td></tr>
                            <tr><td style="border: 0; padding: 0; text-align: right;" align="right">'.ROTULO_AUTENTICACAO_MECANICA.'</td></tr>
                        </table>
                    </td>';
				}

            $html .= '
                </tr>
            </table>
        </div>';

        return $html;
    }
}


define('FR_DATA', 'd/m/Y');

function proDiaUtil($data)
{
	// Separa a data
	$dt = explode('/', $data);
	$dia = $dt[0];
	$mes = $dt[1];
	$ano = $dt[2];

	/*
	(1) Pega uma data de referência (variável), compara com o datas definidas pelo sistema (feriados e finais de semana)
		e retorna a próxima data um dia útil
	(2) As datas do sistema são: [1] sábados; [2] domingos; [3] feriados fixos; [4] feriados veriáveis; [5] dias opcionais (ex: quarta de cinza)
	(3) Retorno o próximo/imediato dia útil.
	*/

	// 1 - verifica se a data referente é um final de semana (sábado ou domingo);
	// se sábado acrescenta mais 1 dia e faz nova verificação
	// se domingo acrescenta mais 1 dia e faz nova verificação
    $fsem = date('D', mktime(0,0,0,$mes,$dia+1,$ano));
    $novadata = date(FR_DATA, mktime(0,0,0,$mes,$dia+1,$ano));

	switch($fsem)
	{

        case 'Sat':
		case 'Sun':
			return proDiaUtil($novadata);
		break;

		default:
		    // 2 - verifica se a data referente é um feriado
			if(in_array($novadata, Feriados($ano))== true)
			{
				return proDiaUtil($novadata);
			}
			else
			{
				// Retorna o dia útil
				return $novadata;
			}
		break;
	}
}

/*
 * Feriados()
 * Gera um array com as datas dos feriados com referência no ano da data pesquisada.
 *
 * @ano   -> Variável que recebe o ano base para o cálculo;
 */
function Feriados($ano)
{
	$feriados = array
	(
        // Armazena feriados fíxos
        date(FR_DATA, mktime(0,0,0,'01','01',$ano)), // 01/01 Ano novo
        date(FR_DATA, mktime(0,0,0,'04','21',$ano)), // 21/04 Tiradentes
        date(FR_DATA, mktime(0,0,0,'05','01',$ano)), // 01/05 Dia do trabalho
        date(FR_DATA, mktime(0,0,0,'09','07',$ano)), // 07/09 Independencia
        date(FR_DATA, mktime(0,0,0,'10','12',$ano)), // 12/10 Aparecida
        date(FR_DATA, mktime(0,0,0,'11','02',$ano)), // 02/11 Finados
        date(FR_DATA, mktime(0,0,0,'11','15',$ano)), // 15/11 Proclamação
        //date(FR_DATA, mktime(0,0,0,'12','24',$ano)), // 24/12 Véspera de Natal
        date(FR_DATA, mktime(0,0,0,'12','25',$ano)), // 25/12 Natal
        //date(FR_DATA, mktime(0,0,0,'12','31',$ano)), // 31/12 Véspera de Ano novo

        // Armazena feriados variáveis
        //flxFeriado($ano, 'pascoa', $r = 1), // Páscoa - Sempre domingo
        flxFeriado($ano, 'carn_sab', $r = 1), // Carnaval - Sempre sábado
        flxFeriado($ano, 'carn_dom', $r = 1), // Carnaval - Sempre domingo
        flxFeriado($ano, 'carn_seg', $r = 1), // Carnaval - Segunda
        flxFeriado($ano, 'carn_ter', $r = 1), // Carnaval - Terça
        //strtoupper(flxFeriado($ano, 'carn_qua', $r = 1)), // Carnaval - Quarta de cinza
        flxFeriado($ano, 'sant_sex', $r = 1), // Sexta Santa
        flxFeriado($ano, 'corp_chr', $r = 1)  // Corpus Christi
	);
	return $feriados;
}

/*
 * flxFeriado()
 * Calcula os dias de feriados variáveis. Com base na páscoa.
 *
 * @ano   -> Variável que recebe o ano base para o cálculo;
 * @tipo  -> Tipo de dados
 * 			[carn_sab]: Sábado de carnaval;
 * 			[carn_dom]: Domingo de carnaval;
 * 			[carn_seg]: Segunda-feira de carnaval;
 * 			[carn_ter]: Terça-feira de carnaval;
 * 			[carn_qua]: Quarta-feira de carnaval;
 * 			[sant_sex]: Sexta-feira santa;
 * 			[corp_chr]: Corpus Christi;
 */

function flxFeriado($ano, $tipo = NULL)
{
	$a=explode("/", calPascoa($ano));
	switch($tipo)
	{
		case 'carn_sab': $d = $a[0]-50; break;
		case 'carn_dom': $d = $a[0]-49; break;
		case 'carn_seg': $d = $a[0]-48; break;
		case 'carn_ter': $d = $a[0]-47; break;
		case 'carn_qua': $d = $a[0]-46; break;
		case 'sant_sex': $d = $a[0]-2; break;
		case 'corp_chr': $d = $a[0]+60; break;
		case NULL:
		case 'pascoa': $d = $a[0]; break;
	}
	return date(FR_DATA, mktime(0,0,0,$a[1],$d,$a[2]));
}

/*
 * calPascoa()
 * Calcula o domingo da pascoa. Base para todos os feriádos móveis.
 *
 * @ano   -> Variável que recebe o ano base para o cálculo ;
 */

function calPascoa($ano)
{
	$A = ($ano % 19);
    $B = (int)($ano / 100);
    $C = ($ano % 100);
    $D = (int)($B / 4);
    $E = ($B % 4);
    $F = (int)(($B + 8) / 25);
    $G = (int)(($B - $F + 1) / 3);
    $H = ((19 * $A + $B - $D - $G + 15) % 30);
    $I = (int)($C / 4);
    $K = ($C % 4);
    $L = ((32 + 2 * $E + 2 * $I - $H - $K) % 7);
    $M = (int)(($A + 11 * $H + 22 * $L) / 451);
    $P = (int)(($H + $L - 7 * $M + 114) / 31);
    $Q = (($H + $L - 7 * $M + 114) % 31) + 1;
    return date('d/m/Y', mktime(0,0,0,$P,$Q,$ano));
}
