<?php
/**
 * Classe para geração código de barras e linha digitável do boleto.
 *
 * 2014
 * <code>
 * //para testar com dados do manual do Bradesco
 	$boleto = new BoletoBradesco("12345678","123,45","01/05/2002");
	$boleto->Agencia = "0057";
	$boleto->Conta = "123457";
	$boleto->CodigoCarteiraCobranca = "110";
	echo $boleto->CalcularLinhaDigitavel();
 * </code>
 */
class BoletoBradesco
{
	/**
	 * Número da agencia
	 * @var string
	 */
	public $Agencia = "2369";
	/**
	 * Número da conta corrente com DAC (com 6 digitos)
	 * @var string
	 */
	public $Conta = "0012168";
	/**
	 * AAA - Código do Banco na Câmara de Compensação ( Bradesco=237)
	 * @var string
	 */
	public $CodigoBanco = "237";
	/**
	 * B
	 * @var string
	 */
	public $CodigoMoeda = "9";
	/**
	 * CCC - Código da carteira de cobrança
	 * @var string
	 */
	public $CodigoCarteiraCobranca = "09";
	/**
	 * Número utilizado para gerar o nosso número - Deve conter pelo menos um digito e no máximo 8
	 * - Deve ser um número gerado pelo sistema como o ID da fatura ou outro valor qualquer.
	 * Caso não tenha 8 caracteres será concatenado zeros a esquerda.
	 * @var string
	 */
	public $BaseNossoNumero = "";
	/**
	 * Data de vencimento do boleto no formato dd/mm/aaaa
	 * @var string
	 */
	public $Vencimento = "";
	/**
	 * Valor do boleto.
	 * @var string
	 */
	private $ValorTitulo = "";
	/**
	 * Time zone utilizado nas datas para calcular o fator de vencimento
	 * - Padrão: Brazil/East.
	 * @var string
	 */
	public $TimeZone = "Brazil/East";
	/**
	 * Variável de controle do multiplicador usado no cálculo do DAC - CalculoDacModulo10.
	 * @var int
	 */
	private $multiplicador = 0;
	/**
	 * Cria um objeto para geração da linha digitável e código de barras do boleto.
	 * @param string $nossoNumero - Nosso número (número gerado e controlado pelo sistema - um ID por exemplo)
	 * @param string $valor - Valor do documento (valor sem formatação nenhuma e com 10 caracteres)
	 * @param string $vencimento - Data no formato dd/mm/aaaa
	 */
	public function __construct($nossoNumero, $valor, $vencimento)
    {
		$this->BaseNossoNumero = $nossoNumero;
		$this->ValorTitulo = strval($valor);
		$this->Vencimento = $vencimento;
	}
	/**
	 * Seta o valor do boleto.
	 * @param string $valor Valor do título (observar quantidade de decimais desejadas no caso de passar como float)
	 */
	public function SetValorTitulo($valor)
    {
		$this->ValorTitulo = strval($valor);
	}
	/**
	 * Retorna valor do título sem nenhum formatação (somente números) com 10 caracteres (será completado com zeros caso falte)
	 * - (*) Sem edição (sem ponto e vírgula), com tamanho fixo (10). Em casos de cobrança com valor em aberto
	 * (o valor a ser pago é preenchido pelo próprio sacado) ou cobrança em moeda variável, deve ser preenchido com zeros.
	 */
	public function GetValorTitulo()
    {
		//0000012345
		if($this->ValorTitulo == "")
			throw new Exception("Não foi possível recuperar o valor do título ele ainda não foi fornecido", 0);

		$valor = preg_replace("/[^0-9]/",'',$this->ValorTitulo);
		$valor = str_repeat('0',(10-strlen($valor))).$valor;
		return $valor;
	}
	/**
	 * Retorna o nosso número com 8 caracteres -
	 * Diretas: é de livre utilização pelo cedente, não podendo ser repetida se o número ainda estiver registrado
	 * no Banco Bradesco ou se transcorridos menos de 45 dias de sua baixa / liquidação no Banco Bradesco.
	 * Dependendo da carteira de cobrança utilizada a faixa de Nosso Número pode ser definida pelo Banco.
	 * Para todas as movimentações envolvendo o título, o “Nosso Número” deve ser informado.
	 *
	 * Deve ser retornado um número de 8 digitos.
	 */
	public function GetNossoNumero()
    {
		if($this->BaseNossoNumero == "")
			throw new Exception("Não foi possível recuperar o valor do campo 1 da linha digitavel. Nosso número não foi fornecido", 0);
		return str_repeat('0',(11-strlen($this->BaseNossoNumero))) . $this->BaseNossoNumero;
	}
	/**
	 * Retorna o campo 1 com DAC da linha digitavel do código de barras.
	 * @throws Exception
	 */
	private function GetCampo1()
    {
		/*  AAABC.CCCDX

			AAA = Código do Banco na Câmara de Compensação ( Bradesco=237)
			B = Código da moeda = "9"
			CCCC = Código da agencia
			D = Primeiro digito da Carteira
			X = DAC que amarra o campo 1
		 */

		$campo  = $this->CodigoBanco;
		$campo .= $this->CodigoMoeda;
        $campo .= $this->Agencia;
		$campo .= substr($this->CodigoCarteiraCobranca, 0,1);
		$campo .= $this->CalculoDacModulo10($campo,$this->multiplicador);

        $campo = substr($campo, 0,5) . "." . substr($campo, 5,6);
		return $campo;
	}
	/**
	 * Retorna campo 2 com DAC calculado.
	 */
	private function GetCampo2()
    {
		/* DEEEE.EEEEEY

			D = Restante da carteira
			EEEEEEEEE = 9 digitos do Nosso Número
			Y = DAC que amarra o campo 2
		 */

		$campo  = substr($this->CodigoCarteiraCobranca, 1,1);
		$campo .= substr($this->GetNossoNumero(), 0,9);
		$campo .= $this->CalculoDacModulo10($campo,$this->multiplicador);

		$campo = substr($campo, 0,5) . "." . substr($campo, 5,6);
		return $campo;
	}
	/**
	 * Retorna Campo 3 com DAC calculado.
	 */
	private function GetCampo3()
    {
		//EEFFF.FFFFGZ
		/*
			EE = Restante do Nosso Número
			FFFFFFF = Número da conta
			G = Zero - Fixo
			Z = DAC que amarra o campo 3
		 */

		$campo = substr($this->GetNossoNumero(), 9,2);
		$campo .= substr($this->Conta,0,7);
		$campo .= "0";
		$campo .= $this->CalculoDacModulo10($campo,$this->multiplicador);

		$campo = substr($campo, 0,5) . "." . substr($campo, 5,6);
		return $campo;
	}
	/**
	 * Gera a linha digitável e retorna.
	 * @return string
	 */
	public function CalcularLinhaDigitavel()
    {
		/*  AAABC.CCCDX

			AAA = Código do Banco na Câmara de Compensação ( Bradesco=237)
			B = Código da moeda = "9"
			CCCC = Código da agencia
			D = Primeiro digito da Carteira
			X = DAC que amarra o campo 1
		 */
		$campo1 = $this->GetCampo1();

		/* DEEEE.EEEEEY

			D = Restante da carteira
			EEEEEEEEE = 9 digitos do Nosso Número
			Y = DAC que amarra o campo 2
		 */
		$campo2 = $this->GetCampo2();

		//EEFFF.FFFFGZ
		/*
			EE = Restante do Nosso Número
			FFFFFFF = Número da conta
			G = Zero - Fixo
			Z = DAC que amarra o campo 3
		 */
		$campo3 = $this->GetCampo3();

		//K
		/*
			K = DAC do Código de Barras (Anexo 2)
		 */
		$campo4 = $this->CalculoDacModulo11Anexo2();


		//UUUUVVVVVVVVVV
		/*
			UUUU = Fator de vencimento
			VVVVVVVVVV = Valor do Título (*)
			(*) Sem edição (sem ponto e vírgula), com tamanho fixo (10). Em casos de cobrança com valor em aberto (o valor a ser pago é preenchido pelo próprio sacado) ou cobrança em moeda variável, deve ser preenchido com zeros.
		 */
		$campo5 = $this->CalculaFatorVencimento() . $this->GetValorTitulo();


		/*
		 * A representação numérica do código de barras é distribuída em cinco campos,
		 * sendo os três primeiros consistidos por DAC (Dígito de Autocontrole - Módulo 10) e,
		 * [entre cada campo, espaço equivalente a uma posição];
		 * no quarto campo, indicado, isoladamente, o DAC (Módulo 11) do Código de Barras
		 */
		$dac = "$campo1 $campo2 $campo3 $campo4 $campo5";

		return $dac;
	}
	/**
	 * Anexo 3 – Cálculo do DAC da Representação Numérica - Método (Módulo 10)
	 *
	 * a) Multiplica-se cada algarismo do campo pela seqüência de multiplicadores
	 * 2, 1, 2, 1, 2, 1..., posicionados da direita para a esquerda;
	 *
	 * b) Some individualmente, os algarismos dos resultados dos produtos, obtendo-se o total (N);
	 * c) Divida o total encontrado (N) por 10, e determine o resto da divisão como MOD 10 (N);
	 * d) Encontre o DAC através da seguinte expressão: DAC = 10 – Mod 10 (N)
	 *
	 * OBS.: Se o resultado da etapa d for 10, considere o DAC = 0.
	 *
	 *
		//teste com números do manual
		$multiplicador = 0;
		echo "DAC Campo1: ". CalculoDacModulo10("341911012",$multiplicador)."<Br><br>";
		echo "DAC Campo2: ". CalculoDacModulo10("3456788005",$multiplicador)." - multiplicador=$multiplicador <Br><br>";
		echo "DAC Campo3: ". CalculoDacModulo10("7123457000",$multiplicador)." - multiplicador=$multiplicador <Br><br>";
		@param string $CalculaDAC Dados do campo que tera o DAC gerado (1, 2 ou 3)
		@param int $multiplicador Variável para controlar e dar sequência na geração dos DAC's dos campos (1,2 e 3).
		@return string
	 */
	private function CalculoDacModulo10($CalculaDAC, &$multiplicador)
    {
		$total = 0;
        $tamanho = strlen($CalculaDAC);
		$strDebug = "Soma ";
        for($i=0;$i < $tamanho; $i++){
        	if ($multiplicador !== 2) {
	        	$multiplicador = 2;
	        }
	        else {
	        	$multiplicador = 1;
	        }

	        // a) Multiplicando a seqüência dos campos pelo módulo 10:
			$parcial = strval($CalculaDAC[$i] * $multiplicador);

			//echo "$CalculaDAC[$i] * $multiplicador = $parcial <br>";

			// b) Some individualmente, os algarismos dos resultados dos produtos, obtendo-se o total (N)
            if ($parcial >= 10) {
            	$strDebug .= "$parcial[0] + $parcial[1] +";
            	$parcial = $parcial[0] + $parcial[1];
            }else{
            	$strDebug .= "$parcial + ";
            }
            $total += $parcial;
		}
		//echo "<Br>$strDebug <br><Br>";
        // c) Divida o total encontrado por 10, a fim de determinar o resto da divisão:
        $resto = ($total%10);
        //echo "resto: $resto<br>";
       	//d) Encontre o DAC através da seguinte expressão: DAC = 10 – Mod 10 (N) :
       	$dac = 10-$resto;
       	//OBS.: Se o resultado da etapa d for 10, considere o DAC = 0.
       	if($dac >= 10)
       		$dac = 0;
		return $dac;
	}
	/**
	 * Calcula DAC do campo [Agência/Conta/Carteira/ Nosso Número] -
	 * Para a grande maioria das carteiras, são considerados para a obtenção do DAC, os dados “AGÊNCIA / CONTA (sem DAC) / CARTEIRA / NOSSO NÚMERO”, calculado pelo critério do Módulo 10 (conforme Anexo 3).
	 */
	public function CalculoDacModulo10Anexo4()
    {
		$campo = $this->Agencia . substr($this->Conta,0,5) . $this->CodigoCarteiraCobranca . $this->GetNossoNumero();
		$total = 0;
        $tamanho = strlen($campo);
        $multiplicador = 0;
		$strDebug = "Soma ";

        for($i = $tamanho-1; $i>=0; $i--)
        {
        	if ($multiplicador !== 2)
            {
	        	$multiplicador = 2;
	        }
	        else
            {
	        	$multiplicador = 1;
	        }

	        // a) Multiplicando a seqüência dos campos pelo módulo 10:
			$parcial = strval($campo[$i] * $multiplicador);

			//echo "$CalculaDAC[$i] * $multiplicador = $parcial <br>";

			// b) Some individualmente, os algarismos dos resultados dos produtos, obtendo-se o total (N)
            if ($parcial >= 10)
            {
            	$strDebug .= "$parcial[0] + $parcial[1] +";
            	$parcial = $parcial[0] + $parcial[1];
            }
            else
            {
            	$strDebug .= "$parcial + ";
            }
            $total += $parcial;
		}
		//echo "<Br>$strDebug <br><Br>";
        // c) Divida o total encontrado por 10, a fim de determinar o resto da divisão:
        $resto = ($total%10);
        //echo "resto: $resto<br>";
       	//d) Encontre o DAC através da seguinte expressão: DAC = 10 – Mod 10 (N) :
       	$dac = 10-$resto;
       	//OBS.: Se o resultado da etapa d for 10, considere o DAC = 0.
       	if($dac >= 10)
       		$dac = 0;
		return $dac;
	}

	/**
	 * Anexo 2 – Cálculo do DAC do Código de Barras - Método (Módulo 11)
	 */
	private function CalculoDacModulo11Anexo2()
    {
		//$codigoBarrasSemDac = "2379166700000123451101234567880057123457000"; // deve retornar 6

		/* Componentes do código de barras:
		 * [Código do Banco]
		 * [Código da Moeda]
		 * [DAC do Código de Barras] (não é considerado neste método)
		 * [Fator de Vencimento]
		 * [Valor do Título]
		 * [Agência / Carteira]
		 * [Nosso Número / Conta Corrente]
		 * [Posição Livre (zero)]
		 */
		//[Código do Banco] - 3 caracteres
		$codigoBarrasSemDac  = $this->CodigoBanco;
		//[Código da Moeda] - 1 caracteres
		$codigoBarrasSemDac .= $this->CodigoMoeda;
		//[Fator de Vencimento] - 4 caracteres
		$codigoBarrasSemDac .= $this->CalculaFatorVencimento();
		//[Valor do Título] - 10 caracteres
		$codigoBarrasSemDac .= $this->GetValorTitulo();
		//Agência - 4 caracteres // Carteira - 2 caracteres
		$codigoBarrasSemDac .= $this->Agencia . $this->CodigoCarteiraCobranca;
		//Nosso número - 11 caracteres // Conta - 7 caracteres
		$codigoBarrasSemDac .= $this->GetNossoNumero() . substr($this->Conta,0,7);
		//0 = Posição Livre (zero) - 1 caractere
		$codigoBarrasSemDac .= "0";

        //echo "<br/>Código de Barras: ".$codigoBarrasSemDac;

		$total = 0;
		$parcial = 0;
		//                123456789 123456789 123456789 123456789 123
		$multiplicador = '4329876543298765432987654329876543298765432';

		for ($i = 0; $i <= 42; $i++ )
        {
			/*
			 * a) Tomando-se os 43 algarismos que compõem o Código de Barras (sem considerar a 5ª posição),
			 * multiplique-os, iniciando-se da direita para a esquerda,
			 * pela seqüência numérica de 2 a 9 ( 2, 3, 4, 5, 6, 7, 8, 9, 2, 3, 4... e assim por diante);
			 */
			$parcial = $codigoBarrasSemDac[$i] * $multiplicador[$i];
            //echo "<br/>Parcial[".$i."]: ".$parcial;

			// b) Soma-se o resultado dos produtos obtidos no item “a” acima:
            $total += $parcial;
		}
        //echo "<br/>Total: ".$total;

		// c) Divida o total (N) por 11 e determine o resto obtido da divisão como Mod 11(N);
		$resto = $total%11;

        //echo "<br/>Resto: ".$resto;

       	//d) Calcule o dígito verificador (DAC) através da expressão: DAC = 11 - Mod 11(N)
       	$dac = 11-$resto;

		//Se o resultado desta for igual a 0, 1, 10 ou 11, considere DAC = 1.
		if (($dac >= 10) || ($dac == 0))
        {
        	$dac = 1;
        }
		return $dac;
	}
	/**
	 * Calcula e retorna o código de barras
	 * @return string
	 */
	public function GetCodigoBarras()
    {
		/*
			01 a 03 - Código do Banco na Câmara de Compensação ex: '237'
			04 a 04 - Código da Moeda ex: '9'
			05 a 05 - DAC código de Barras
			06 a 09 - Fator de Vencimento
			10 a 19 - Valor
			20 a 23 - Agência /=/ Inicio Campo Livre
			24 a 25 - Carteira
            26 a 36 - Nosso número(11 Dígitos sem o dígito verificador)
            37 a 43 - Conta do Beneficiário(Sem o dígito verificador, completar com zeros a esquerda quando necessário)
            44 a 44 - Zero /=/ Fim Campo Livre
		 */
		$codigo  = str_pad($this->CodigoBanco,3,"0",STR_PAD_LEFT);
		$codigo .= str_pad($this->CodigoMoeda,1,"0",STR_PAD_LEFT);
		$codigo .= str_pad($this->CalculoDacModulo11Anexo2(),1,"0",STR_PAD_LEFT);
		$codigo .= str_pad($this->CalculaFatorVencimento(),4,"0",STR_PAD_LEFT);
		$codigo .= str_pad($this->GetValorTitulo(),10,"0",STR_PAD_LEFT);
        $codigo .= str_pad($this->Agencia,4,"0",STR_PAD_LEFT);
		$codigo .= str_pad($this->CodigoCarteiraCobranca,2,"0",STR_PAD_LEFT);
		$codigo .= str_pad($this->GetNossoNumero(),11,"0",STR_PAD_LEFT);
		$codigo .= substr($this->Conta,0,7);
		$codigo .= "0";
		return $codigo;
	}
	/**
	 * Anexo 6 – Cálculo do Fator de Vencimento
	 * @return int
	 */
	private function CalculaFatorVencimento()
    {
		try
        {
			/*
			 * Forma 1: Calcula-se o número de dias corridos entre a data base
			 * (“Fixada” em 07.10.1997) e a do vencimento desejado
			 */

			/* a solução com o DIFF só funciona no PHP5.3 (ou superior)
			date_default_timezone_set($this->TimeZone);// Sets the default timezone used by all date/time functions in a script
			$dIni = new DateTime('1997-10-07 23:59:59');//07/10/1997
			$dadosData = explode("/", $this->Vencimento);
			$dFim = new DateTime("$dadosData[2]-$dadosData[1]-$dadosData[0] 23:59:59");
			$interval = $dIni->diff($dFim);
			*/

			$dIni = '1997-10-07 23:59:59';
			$dadosData = explode("/", $this->Vencimento);
			$dFim = $dadosData[2].'-'.$dadosData[1].'-'.$dadosData[0].' 23:59:59';

			$dias = abs(floor((strtotime($dFim)-strtotime($dIni))/86400));

			return $dias;
		}
		catch (Exception $e)
        {
			echo "Houve um erro ao calcular Fator do vencimento: $e->getMessage()<Br>";
			throw $e;
		}
	}

    public function VerificarNossoNumeroMod11()
    {
        /*
        Para o cálculo do dígito, será necessário acrescentar o número da
        carteira  à  esquerda  antes  do  Nosso  Número,  e  aplicar  o  módulo  11,
        com base 7.
        */
        $total = 0;
        $parcial = 0;
        $carteira = $this->CodigoCarteiraCobranca;
        $nosso_numero = $this->GetNossoNumero();
        $multiplicador = '2765432765432';
        $carteira_nosso_numero = $carteira.$nosso_numero;
		for ($i = 0; $i <= 12; $i++ )
        {
			$parcial = $carteira_nosso_numero[$i] * $multiplicador[$i];

			// Soma-se o resultado dos produtos obtidos no item acima:
            $total += $parcial;
		}

		// Divida o total (N) por 11 e determine o resto obtido da divisão como Mod 11(N);
		$resto = $total % 11;

		//Se o resto for igual a 1 então retorne P, senão se resto for igual a 0 então retorne 0, senão retorne 11-$resto
		if($resto == 1)
        {
            $digito_verificador = 'P';
        }
        elseif($resto == 0)
        {
            $digito_verificador = 0;
        }
        else
        {
            // Calcule o dígito verificador através da expressão: DV = 11 - Mod 11(N)
            $digito_verificador = 11-$resto;
        }


		return $digito_verificador;
    }

	public function CalculoDigitoVerificadorAgencia()
	{
		/*
        Tamanho da Agência – 4 Dígitos + 1 DV

		Para obter o DV da agência, multiplica-se o nº da agência sem o DV formatado com
		4 posições pelos multiplicadores 5,4,3,2, e soma-se as unidades das dezenas.

		OBS. Se o Resultado = 10 o digito será P.
     	Se o Resultado = 11 o digito será 0.
        */
		$total = 0;
		$parcial = 0;
		$agencia = str_pad($this->Agencia,4,"0",STR_PAD_LEFT);
		$multiplicador = '5432';
		for ($i = 0; $i <= 4; $i++ )
		{
			$parcial = $agencia[$i] * $multiplicador[$i];

			// Soma-se o resultado dos produtos obtidos no item acima:
			$total += $parcial;
		}

		// Divida o total (N) por 11 e determine o resto obtido da divisão como Mod 11(N);
		$resto = $total % 11;

		//Se o resto for igual a 1 então retorne P, senão se resto for igual a 0 então retorne 0, senão retorne 11-$resto
		if($resto == 10)
		{
			$digito_verificador = 'P';
		}
		elseif($resto == 11)
		{
			$digito_verificador = 0;
		}
		else
		{
			// Calcule o dígito verificador através da expressão: DV = 11 - Mod 11(N)
			$digito_verificador = 11-$resto;
		}


		return $digito_verificador;

	}
}
?>