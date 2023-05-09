<?
/*
    Classe de geração das faturas dos clientes. Os métodos buscam os dados e geram a saída em HTML, que pode ser usada como impressão, pdf, etc.
    Autor: Marcelo Bruzetti
    Data: ?

    ****** Log de alterações *******************************************************************************************************************

    03/10/2013 - Marcelo Bruzetti
        Alteração: Adicionado condição para não gerar a fatura para carnês no método GerarFatura()
        Motivo: PR-002 pediu ao Daniel Motta, pois gerar carnês com os valores totais muito altos poderiam espantar o cliente
*/


/**/
class FaturaFranqueadora 
{
    private $idContaReceber;
    private $limiteVersaoDetalhada = 50;

    public function SetIdContaReceber($arg)
    {
        $this->idContaReceber = $arg;
    }

    /**
     * Bucas os dados do franqueado
     */
    public function BuscarDadosFranqueado()
    {
        if($this->idContaReceber > 0 === false)
            return false;

        $pdo = new Conexao();

        $sql = "SELECT
                contas_receber.id,

                franqueado.codigo AS franqueado_codigo,
                franqueado.nome AS franqueado_nome,
                franqueado.cnpj AS franqueado_cnpj,
                endereco_franqueado.logradouro AS franqueado_logradouro,
                endereco_franqueado.numero AS franqueado_numero,
                endereco_franqueado.complemento AS franqueado_complemento,
                endereco_franqueado.bairro AS franqueado_bairro,
                endereco_franqueado.cep AS franqueado_cep,
                endereco_franqueado.uf AS franqueado_estado,
                endereco_franqueado.cidade AS franqueado_cidade,
                endereco_franqueado.ddd_comercial AS franqueado_ddd,
                endereco_franqueado.comercial AS franqueado_telefone
                FROM contas_receber
                INNER JOIN franqueado ON (franqueado.id = contas_receber.id_franquia)
                INNER JOIN endereco AS endereco_franqueado ON (endereco_franqueado.id = franqueado.id_endereco)
                WHERE contas_receber.id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id", $this->idContaReceber, PDO::PARAM_INT);

        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }

    /**
     *  Busca os dados da fatura.
     *
     */
    public function BuscarDadosFatura()
    {
        if($this->idContaReceber > 0 === false)
            return false;

        $pdo = new Conexao();

        $sql = "SELECT
                contas_receber.id,
                contas_receber.numero_fatura AS numero_fatura,
                contas_receber.valor,
                contas_receber.desconto_geral + contas_receber.desconto_detalhado AS desconto,
                date_format(contas_receber.data_vencimento, '%d/%m/%Y') AS data_vencimento,
                date_format(min(contas_receber.data_referencia),'%m/%Y') AS mes_ano_referencia,
                contas_receber.descricao,
                contas_receber.numero_nfe AS numero_nfe,
                contas_receber.data_nfe,
                contas_receber.arquivo_nfe,
                contas_receber.data_hora_cadastro,
                contas_receber.data_impressao

                FROM contas_receber

                WHERE contas_receber.id = :id";
        //echo $sql;erp_contas_receber
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->idContaReceber, PDO::PARAM_INT);


        $stmt->execute();
        $linha = $stmt->fetch(PDO::FETCH_OBJ);

//        echo '<pre>';
//        print_r($linha);
//        echo '</pre>';

        if(is_null($linha->valor) || $linha->valor == "") $linha->valor = 0;
        if(is_null($linha->juros) || $linha->juros == "") $linha->juros = 0;
        if(is_null($linha->multa) || $linha->multa == "") $linha->multa = 0;
        if(is_null($linha->desconto) || $linha->desconto == "") $linha->desconto = 0;

        //calcula valor pago - R17
        $linha->valor_pago = $linha->valor + $linha->juros + $linha->multa - $linha->desconto;
        $linha->valor_com_desconto = $linha->valor - $linha->desconto_geral - $linha->desconto_detalhado;

        return $linha;
    }

    /**
     * Buscar os itens da fatura
     */
    public function BuscarItensFatura()
    {
        if($this->idContaReceber > 0 === false)
            return false;

        $pdo = new Conexao();

        $sql = "SELECT
                contas_receber.id,

                servico.nome AS servico,
                produto.nome AS produto,

                contas_receber_item.id,
                contas_receber_item.id_servico,
                contas_receber_item.id_produto,
                contas_receber_item.quantidade,
                contas_receber_item.valor_unitario,
                contas_receber_item.juros,
                contas_receber_item.desconto,
                contas_receber_item.data_hora_cadastro

                FROM contas_receber

                INNER JOIN contas_receber_item ON (contas_receber_item.id_contas_receber = contas_receber.id)
                LEFT JOIN servico ON (servico.id = contas_receber_item.id_servico)
                LEFT JOIN produto ON (produto.id = contas_receber_item.id_produto)

                WHERE contas_receber.id = :id";
        //echo $sql;erp_contas_receber
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->idContaReceber, PDO::PARAM_INT);


        $stmt->execute();
        $linha = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $linha;
    }

    public function AgruparDados()
    {
        $dadosFranqueado = $this->BuscarDadosFranqueado();
        $dadosFatura = $this->BuscarDadosFatura();



        $itensFatura = array();
        $dadosItem = array();
        $total = 0;
        $z = 0;

        foreach($dadosFranqueado as $dados) {
            $i = 0;

            // Dados da franquia
            $dadosFranquia = array(
                'nome' 			=> $dados->franqueado_nome,
                'logradouro'	=> $dados->franqueado_logradouro,
                'numero'		=> $dados->franqueado_numero,
                'complemento'	=> $dados->franqueado_complemento,
                'bairro'		=> $dados->franqueado_bairro,
                'cidade' 		=> $dados->franqueado_cidade,
                'uf'			=> $dados->franqueado_estado,
                'cep' 			=> $dados->franqueado_cep,
                'cnpj' 			=> $dados->franqueado_cnpj
            );

            // Cabeçalho da fatura
            $cabecalho = array(
                'faturas_origem'		=> '',
                'numero_fatura'			=> $dadosFatura->numero_fatura,
                'mes_referencia'		=> $dadosFatura->mes_ano_referencia,
                'mes_vencimento'		=> $dadosFatura->data_vencimento,
                'data_emissao'			=> FusoHorario::PrepararDataPHP(FusoHorario::ObterHoraAtualServidor(), $_SESSION['usuario']['id_fuso_horario'], 'd/m/Y'),
                'numero_nfe'			=> $dadosFatura->numero_nfe,
                'data_hora_cadastro_nfe'=> $dadosFatura->data_nfe,
                'valor_total'			=> $dadosFatura->valor - $dadosFatura->desconto
            );

            //Lista os serviços da Fatura
            $listarItem = $this->BuscarItensFatura();
            //echo '<pre>'; print_r($listarItem); echo '</pre>';

            foreach ($listarItem as $itens)
            {
                if($itens->id_servico != '')
                {
                    $dadosItem[$i] = array(
                        'data' 					=> FusoHorario::PrepararDataPHP($itens->data_hora_cadastro, $_SESSION['usuario']['id_fuso_horario'],'d/m/Y'),
                        'item' 					=> $itens->servico,
                        'quantidade'			=> $itens->quantidade,
                        'valor_unitario'		=> $itens->valor_unitario,
                        'valor_desconto'        => $itens->desconto,
                        'valor_total' 			=> $itens->valor_unitario * $itens->quantidade,
                        'valor_total_desconto' 	=> $itens->valor_unitario * $itens->quantidade - $itens->desconto
                    );
                    $i++;
                }
                else
                {
                    $dadosItem[$i] = array(
                        'data' 					=> FusoHorario::PrepararDataPHP($itens->data_hora_cadastro, $_SESSION['usuario']['id_fuso_horario'],'d/m/Y'),
                        'item' 					=> $itens->produto,
                        'quantidade'			=> $itens->quantidade,
                        'valor_unitario'		=> $itens->valor_unitario,
                        'valor_desconto'        => $itens->desconto,
                        'valor_total' 			=> $itens->valor_unitario * $itens->quantidade,
                        'valor_total_desconto' 	=> $itens->valor_unitario * $itens->quantidade - $itens->desconto
                    );
                    $i++;
                }

            }

            $desconto = array(
                'desconto'=> $dadosFatura->desconto
            );
            $total_fatura = array(
                'total_fatura'=> $dadosFatura->valor,
                'total_fatura_desconto'=> $dadosFatura->valor - $dadosFatura->desconto
            );

            $itensFatura[$z] = (object)array(
                'cabecalho' 		=> $cabecalho,
                'dadosFranquia' 	=> $dadosFranquia,
                'dadosItem' 		=> $dadosItem,
                'desconto' 			=> $desconto,
                'total_fatura' 		=> $total_fatura

            );

            $z++;
        }
        return $itensFatura;
    }


    public function GerarFatura($imprimirFatura = true, $dadosPreFatura = null, $qtdBoletos = 1)
    {
        if($dadosPreFatura)
            $dadosFatura = $dadosPreFatura;
        else
        {
            $agruparDados = $this->AgruparDados();
            $dadosFatura = $agruparDados[0];
        }

        //echo '<pre>'; print_r($dadosFatura); echo '</pre>';

        $html = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
            <title>'.ROTULO_IMPRESSAO_BOLETO.'</title>
            <style type="text/css">
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
                #tabela_mae
                {
                    border-color: #000;
                }
                .fatura_bloco1, .fatura_bloco2, .fatura_bloco3, .fatura_bloco4, .fatura_totais
                {
                    border: 1px solid #000;
                }
                .fatura_bloco5
                {
                    border-top: 1px solid #000;
                }
                .fatura_bloco1 td
                {
                    border: 0;
                    padding: 2px;
                }
                .fatura_bloco2 td
                {
                    border: 1px solid #000;
                    font-weight: bold;
                    padding: 2px;
                    text-align: center;
                }
                .fatura_bloco3 td
                {
                    border: 0;
                    padding: 2px;
                	text-transform: uppercase;
                }
                .fatura_bloco4 td
                {
                    border: 1px solid #000;
                    padding: 2px;
                }
                .fatura_bloco5 td
                {
                    border-left: 1px solid #000;
                    border-right: 1px solid #000;
                    padding: 2px;
                }
                .fatura_bloco5 th
                {
                    border-left: 1px solid #000;
                    border-right: 1px solid #000;
                    border-bottom: 1px solid #000;
                    text-align: center;
                    padding: 2px;
                }
                .fatura_totais td
                {
                    border-left: 1px solid #000;
                    border-right: 1px solid #000;
                    border-bottom: 1px solid #000;
                    padding: 2px;
                }
                label
                {
                	margin-right: 10px;
                	text-transform: uppercase;
                }
                .rotulo_upper
                {
                    text-transform: uppercase;
                }
                .sem_formatacao, .sem_formatacao td
                {
                    padding: 0;
                    margin: 0;
                    border: 1px solid #FF0000;
                }

            </style>
        </head>
        <body>';

        if($dadosFatura->cabecalho['faturas_origem'] != '' )
            $imprimirFatura = false;
        //else
        //    $imprimirFatura = true;

        if($imprimirFatura)
        {
            $comFatura = true;
            $html .= '
            <div class="box">
                <div class="table_container">
    				<table class="fatura_bloco1" width="100%" cellpadding="0" cellspacing="0" border="0">
    					<tr>
    						<td align="center" rowspan="6" width="180"><img src="imagens/logo_fatura.png" align="absmiddle"></td>
                            <td colspan="2" class="sem_formatacao" height="10"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Link Monitoramento Franqueadora</td>
    					</tr>
    					<tr>
    						<td colspan="2">Rua Alferes Poli, 2208 - Parolim</td>
    					</tr>
    					<tr>
    						<td width="330">Curitiba/PR - CEP 80220-050</td>
    						<td align="center" width="200"><b>'.ROTULO_CNPJ.': 10.974.997/0001-34</b></td>
    					</tr>
    					<tr>
    						<td>'.ROTULO_TELEFONE.': <b>(41)3078-1700</b></td>
    						<td align="center">www.linkmonitoramento.com.br</td>
    					</tr>
                        <tr><td colspan="2" class="sem_formatacao" height="10"></td></tr>
    				</table>
                </div>
                <div class="table_container">
    				<table class="fatura_bloco2" cellpadding="0" cellspacing="0" width="100%" border="0">
    					<tr>
    						<td class="rotulo_upper" width="138">'.ROTULO_NUMERO_DA_FATURA.'</td>
                            <td class="rotulo_upper" width="138">'.ROTULO_MES_REFERENCIA_UPPER.'</td>
                            <td class="rotulo_upper" width="138">'.ROTULO_DATA_EMISSAO_UPPER.'</td>
    						<td class="rotulo_upper" width="138">'.ROTULO_VALOR_UPPER.'</td>
                            <td class="rotulo_upper" width="138">'.ROTULO_VENCIMENTO_UPPER.'</td>';
            if($dadosFatura->cabecalho['numero_nfe'] != '')
                $html .= '
                            <td class="rotulo_upper" width="138">Numero NFE</td>';

            $html .= '
                        </tr>

    					<tr>
    						<td>'.$dadosFatura->cabecalho['numero_fatura'].'</td>
                            <td>'.$dadosFatura->cabecalho['mes_referencia'].'</td>
                            <td>'.$dadosFatura->cabecalho['data_emissao'].'</td>
    						<td>R$ '.Utils::MaskFloat($dadosFatura->cabecalho['valor_total']).'</td>
                            <td>'.$dadosFatura->cabecalho['mes_vencimento'].'</td>';

            if($dadosFatura->cabecalho['numero_nfe'] != '')
                $html .= '
                            <td>'.$dadosFatura->cabecalho['numero_nfe'].'</td>';

            $html .= '

                        </tr>
    				</table>
                </div>
        		<!-- fim quadro 2 -->

        		<!-- Quadro 3 -->
        		<div class="table_container">
    				<table class="fatura_bloco3" cellpadding="0" cellspacing="0" width="100%" border="0">
    					<tr>
    						<td colspan="3">'.ROTULO_FRANQUIA.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->dadosFranquia['nome'].'</b>
    						</td>
    					</tr>
    					<tr>
    						<td colspan="3">'.ROTULO_ENDERECO.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->dadosFranquia['logradouro'].", ".$dadosFatura->dadosFranquia['numero']." ".$dadosFatura->dadosFranquia['complemento']." ".$dadosFatura->dadosFranquia['bairro'].'</b></td>
    					</tr>
    					<tr>
    						<td style="width: 35%;">'.ROTULO_CIDADE.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->dadosFranquia['cidade'].'</b></td>
    						<td style="min-width: 100px;">'.ROTULO_ESTADO.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->dadosFranquia['uf'].'</b></td>
    						<td style="min-width: 110px;">'.ROTULO_CEP.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->dadosFranquia['cep'].'</b></td>
    					</tr>
    					<tr>
    						<td colspan="3">'.ROTULO_CPF_CNPJ.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->dadosFranquia['cnpj'].'</b></td>
    					</tr>
    				</table>
    			</div>
        		<!-- Fim Quadro 3 -->

        		<!-- quadro 4 -->
        		<div class="table_container">
    				<table class="fatura_bloco4" cellpadding="0" cellspacing="0" width="100%" border="0">
    					<tr>
    						<th class="rotulo_upper" style="width: 70px;">'.ROTULO_VALOR_EXTENSO.'</th>
    						<td>';
            $extenso = $this->extenso($dadosFatura->cabecalho['valor_total']);
            $html .= Conexao::ConverterMaiusculo($extenso);
            $html .= '</td>
    					</tr>
    				</table>
    			</div>
        		<!-- fim quadro 4 -->';

            // Itens da fatura
            $html .= $this->GerarItens($dadosFatura);

            // Totais
            $html .= '
                        <!-- totais -->
    					<tr>
    					    <td style="border: 0px; border-top: 1px #000; border-style: solid ;">&nbsp;</td>
                            <td style="border: 0px; border-top: 1px #000; border-style: solid ;">&nbsp;</td>
    					    <td style="text-transform: uppercase;" class="fatura_totais" align="right">'.ROTULO_TOTAL.'</td>
    					    <td class="fatura_totais" align="right">R$ '.Utils::MaskFloat($dadosFatura->total_fatura['total_fatura']).'</td>
    					    <td class="fatura_totais" align="right">R$ '.Utils::MaskFloat($dadosFatura->desconto['desconto']).'</td>
    					    <td class="fatura_totais" align="right">R$ '.Utils::MaskFloat($dadosFatura->total_fatura['total_fatura_desconto']).'</td>
    					</tr>
    					<!-- fim totais -->
    		        </table>
                </div>
            </div>';
        }

        if($dadosPreFatura == null)
        {
            if($dadosFatura->total_fatura['total_fatura_desconto'] > 0)
            {
                // Boletos
                $boleto = new BoletoFranqueadora();
                $boleto->SetIdContaReceber($this->idContaReceber);
                $html .= $boleto->GerarBoletos(null, null, $comFatura, $qtdBoletos);
            }
        }

        $html .= '
        </body>
        </html>';

        return $html;
    }

    /**
     *  Gera todos os Item de Faturamento (< 50 itens)
     */
    public function GerarItens($dadosFatura)
    {
        //echo '<pre>'; print_r($itensFatura); echo '</pre>';
        $html .= '
            <div class="table_container">
    			<table class="fatura_bloco5" cellpadding="0" cellspacing="0" width="100%" height="220px" border="0">
    				<tr>
    					<th style="width: 15%;" class="rotulo_upper">'.ROTULO_DATA.'</th>
    					<th style="width: 40%;" class="rotulo_upper">'.ROTULO_ITEM.'</th>
    					<th style="width: 10%;" class="rotulo_upper">'.ROTULO_QUANTIDADE.'</th>
    					<th style="width: 10%;" class="rotulo_upper">'.ROTULO_VALOR_UNITARIO.'</th>
    					<th style="width: 10%;" class="rotulo_upper">'.ROTULO_DESCONTO.'</th>
    					<th style="width: 15%;" class="rotulo_upper">'.ROTULO_VALOR_TOTAL.'</th>
    				</tr>';
        if(count($dadosFatura->dadosItem) > 0)
        {
            for($j = 0; $j < count($dadosFatura->dadosItem); $j++)
            {
                $html .= '<tr>';
                $html .= '
                            <td height="20px" style="white-space:nowrap; font-size: 10px;">'.$dadosFatura->dadosItem[$j]['data'].'</td>
                            <td style="font-size: 10px;">'.Conexao::ConverterMaiusculo($dadosFatura->dadosItem[$j]['item']).'</td>
                            <td style="font-size: 10px;">'.$dadosFatura->dadosItem[$j]['quantidade'].'</td>
                            <td style="font-size: 10px;">'.Utils::MaskFloat($dadosFatura->dadosItem[$j]['valor_unitario']).'</td>
                            <td style="font-size: 10px;">'.Utils::MaskFloat($dadosFatura->dadosItem[$j]['valor_desconto']).'</td>
                            <td style="font-size: 10px;">'.Utils::MaskFloat($dadosFatura->dadosItem[$j]['valor_total_desconto']).'</td>';
                $html .= '</tr>';
            }
            // Descontos
//            for($j = 0; $j < count($dadosFatura->dadosItem); $j++)
//            {
//                if($dadosFatura->dadosItem[$j]['valor_total_desconto'] > 0)
//                {
//                    $html .= '<tr style="font-size: 12px;">';
//                    $html .= '
//                                <td height="20px" style="white-space:nowrap;">'.$dadosFatura->dadosItem[$j]['data'].'</td>
//                                <td style="font-size: 10px;">'.Conexao::ConverterMaiusculo($dadosFatura->dadosItem[$j]['item']).'(DESCONTO)</td>
//                                <td style="font-size: 10px;">'.$dadosFatura->dadosItem[$j]['quantidade'].'</td>
//                                <td style="font-size: 10px;">'.Utils::MaskFloat($dadosFatura->dadosItem[$j]['valor_unitario']).'</td>
//                                <td style="font-size: 10px;">-'.Utils::MaskFloat($dadosFatura->dadosItem[$j]['valor_total_desconto']).'</td>';
//                    $html .= '</tr>';
//                }
//            }

            for($i = $j; $i <= 9; $i++)
            {
                $html .= '<tr>';
                $html .= '
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>';
                $html .= '</tr>';
            }
        }

        return $html;
    }




    function extenso($valor = 0, $maiusculas = false) {

        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
            "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
            "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
            "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
            "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
            "sete", "oito", "nove");

        $z = 0;
        $rt = "";

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for($i=0;$i<count($inteiro);$i++)
            for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
                $inteiro[$i] = "0".$inteiro[$i];

        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
        for ($i=0;$i<count($inteiro);$i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
                    $ru) ? " e " : "").$ru;
            $t = count($inteiro)-1-$i;
            $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")$z++; elseif ($z > 0) $z--;
            if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                    ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if(!$maiusculas){
            return($rt ? $rt : "zero");
        } else {

            if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
            return (($rt) ? ($rt) : "Zero");
        }

    }
}