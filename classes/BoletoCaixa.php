<?php
	/**
	 * Classe para geração código de barras e linha digitável do boleto caixa.
	 *
	 * Péricles
	 * 2015
	 */
	class BoletoCaixa
	{
		/**
		 * Número da agencia
		 * @var string
		 */
		public $Agencia = "0000";
		/**
		 * Número da conta corrente com DAC (com 6 digitos)
		 * @var string
		 */
		public $Conta = "00000";
		/**
		 * Código do Beneficiário
		 * @var string
		 */
		public $Personalizado = "000000";
		/**
		 * @var string
		 */
		public $CodigoBanco = "104";
		/**
		 * @var string
		 */
		public $CodigoMoeda = "9";
		/**
		 * CCC - Código da carteira de cobrança
		 * @var string
		 */
		public $CodigoCarteiraCobranca = "1";
		/**
		 * Número utilizado para gerar o nosso número - Deve conter pelo menos um digito e no máximo 15
		 * - Deve ser um número gerado pelo sistema como o ID da fatura ou outro valor qualquer.
		 * Caso não tenha 15 caracteres será concatenado zeros a esquerda.
		 * @var string
		 */
		public $BaseNossoNumero = "000000000000000";
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
			//echo '<br/> Valor: '.$valor;
			return $valor;
		}
		/**
		 * Retorna o nosso número com 15 caracteres -
		 */
		public function GetNossoNumero()
		{
			if($this->BaseNossoNumero == "")
				throw new Exception("Não foi possível recuperar o valor do campo 1 da linha digitavel. Nosso número não foi fornecido", 0);
			return str_repeat('0',(15-strlen($this->BaseNossoNumero))) . $this->BaseNossoNumero;
		}
		/**
		 * Retorna o campo 1 com DAC da linha digitavel do código de barras.
		 * @throws Exception
		 */
		private function GetCampo1()
		{
			/*  AAABC.CCCCX
				10490.0550X

				AAA = Código do Banco na Câmara de Compensação (Caixa = 104)
				B = Código da moeda = "9"
				CCCCC = Código do Beneficiário
				X = DAC que amarra o campo 1

				Composto por: código banco (posições 1 a 3 do código de barras), código da moeda (posição 4 do
				código de barras), as cinco primeiras posições do campo livre (posições 20 a 24 do código de barras)
			 	e digito verificador deste campo;
			 */

			$campo  = $this->CodigoBanco;
			$campo .= $this->CodigoMoeda;
			// Código do Beneficiário - 5 caracteres
			$campo .= substr($this->Personalizado, 0, 5);
			$campo .= $this->CalculoDacModulo10($campo,$this->multiplicador);

			$campo = substr($campo, 0,5) . "." . substr($campo, 5,5);
			return $campo;
		}
		/**
		 * Retorna campo 2 com DAC calculado.
		 */
		private function GetCampo2()
		{
			/*	CDEEE.FGGGHY
				77222.13334Y

				C = Restante do código do beneficiário
				D = DV do Código do beneficiário
				EEE = Nosso número - Sequência 1
				F = Constante 1
				GGG = Nosso número - Sequência 2
				H = Constante 2
				Y = DAC que amarra o campo 2

				Composto pelas posições 6 a 15 do campo livre (posições 25 a 34 do código de barras) e digito
				verificador deste campo;
			 */

			// Restante do Código do Beneficiário - 1 caractere
			$campo = substr($this->Personalizado, 5, 1);
			//[DV do Código do Beneficiário] - 1 caractere
			$campo .= $this->CalculoDacModulo11Anexo6();
			//[Nosso Número - Sequencia 1] - 3 caracteres
			$campo .= substr($this->GetNossoNumero(), 0,3);
			//[Constante 1] - 1 caractere
			$campo .= 1;
			//[Nosso Número - Sequencia 2] - 3 caracteres
			$campo .= substr($this->GetNossoNumero(), 3,3);
			//[Constante 2] - 1 caractere
			$campo .= 4;
			$campo .= $this->CalculoDacModulo10($campo,$this->multiplicador);

			$campo = substr($campo, 0,5) . "." . substr($campo, 5,6);
			return $campo;
		}
		/**
		 * Retorna Campo 3 com DAC calculado.
		 */
		private function GetCampo3()
		{
			/*	EEEEE.EEEEIZ
				77777.77771Z


				EEEEEEEEE = Nosso número - Sequência 3
				I = DV do campo livre calculado por módulo 11 com aproveitamento total (resto igual a 0 ou 1 DV cai para 0)
				Z = DAC que amarra o campo 3

				Composto pelas posições 16 a 25 do campo livre (posições 35 a 44 do código de barras) e digito
				verificador deste campo;
			 */

			//[Nosso Número - Sequencia 3] - 9 caracteres
			$campo = substr($this->GetNossoNumero(), 6,9);
			// DV do campo livre calculado por módulo 11 com aproveitamento total (resto igual a 0 ou 1 DV cai para 0)
			$campo .= $this->CalculoDacCampoLivre();
			$campo .= $this->CalculoDacModulo10($campo,$this->multiplicador);

			//echo '######'.$campo.'######</br>';

			$campo = substr($campo, 0,5) . "." . substr($campo, 5,6);

			return $campo;
		}
		/**
		 * Gera a linha digitável e retorna.
		 * @return string
		 */
		public function CalcularLinhaDigitavel()
		{
			/*  AAABC.DEEEX

				AAA = Código do Banco na Câmara de Compensação ( Bradesco=748)
				B = Código da moeda = "9"
				C = Código numérico correspondente ao tipo de cobrança: “1” – Com Registro
				D = Carteira
				X = DAC que amarra o campo 1
			 */
			$campo1 = $this->GetCampo1();

			/* EEEEE.EFFFFY

				EEEEEE = Restante do nosso número
				FFFF = Agência
				Y = DAC que amarra o campo 2
			 */
			$campo2 = $this->GetCampo2();

			/* GGHHH.HHIJKZ

				GG = Posto da cooperativa de crédito
				HHHHH = Código do beneficiário
				I = Será 1 (um) quando houver valor expresso no campo “valor do documento”
				J = Filler – zeros “0”
				K = DV do campo livre calculado por módulo 11 com aproveitamento total (resto igual a 0 ou 1 DV cai para 0)
				Z = DAC que amarra o campo 3
			 */
			$campo3 = $this->GetCampo3();

			//K
			/*
				K = DAC do Código de Barras (Anexo 2)
			 */
			$campo4 = $this->CalculoDacModulo11Anexo1();


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

			if($total < 10)
			{
				$dac = 10-$total;
			}
			else
			{
				//echo "<Br>$strDebug <br><Br>";
				// c) Divida o total encontrado por 10, a fim de determinar o resto da divisão:
				$resto = ($total%10);
				//echo "resto: $resto<br>";
				//d) Encontre o DAC através da seguinte expressão: DAC = 10 – Mod 10 (N) :
				$dac = 10-$resto;
			}

			//OBS.: Se o resultado da etapa d for 10, considere o DAC = 0.
			if($dac >= 10)
				$dac = 0;
			return $dac;
		}
		/**
		 * Calcula DAC do Campo Livre do código de barras
		 * 20 a 25 - Código do Beneficiário
		 * 26 a 26 - DV do Código do Beneficiário
		 * 27 a 29 - Nosso Número - Sequencia 1 (3ª a 5ª posição do Nosso Número)
		 * 30 a 30 - Constante 1 - 1ª posição do Nosso Número - Tipo de Cobrança (1-Registrada/2-Sem Registro)
		 * 31 a 33 - Nosso Número - Sequencia 2 (6ª a 8ª posição do Nosso Número)
		 * 34 a 34 - Constante 2 - 2ª posição do Nosso Número - Identificador de Emissão do Boleto (4-Beneficiário)
		 * 35 a 43 - Nosso Número - Sequencia 3 (9ª a 17ª posição do Nosso Número)
		 * 44 a 44 - DV do Campo Livre
		 */
		public function CalculoDacCampoLivre()
		{
			//[Código do Beneficiário] - 6 caracteres
			$campo = $this->Personalizado;
			//[DV do Código do Beneficiário] - 1 caractere
			$campo .= $this->CalculoDacModulo11Anexo6();
			//[Nosso Número - Sequencia 1] - 3 caracteres
			$campo .= substr($this->GetNossoNumero(), 0,3);
			//[Constante 1] - 1 caractere
			$campo .= 1;
			//[Nosso Número - Sequencia 2] - 3 caracteres
			$campo .= substr($this->GetNossoNumero(), 3,3);
			//[Constante 2] - 1 caractere
			$campo .= 4;
			//[Nosso Número - Sequencia 3] - 9 caracteres
			$campo .= substr($this->GetNossoNumero(), 6,9);
			//[DV do Campo Livre] - 1 caracatere
			//$campo .= $this->CalculoDacCampoLivre();
			//echo "<br/>".$campo;

			$multiplicador = '987654329876543298765432';

			$total = 0;
			$parcial = 0;

			for ($i = 0; $i <= 23; $i++ )
			{
				/*
				 * a) Tomando-se os 24 algarismos que compõem o Campo Livre,
				 * multiplique-os, iniciando-se da direita para a esquerda,
				 * pela seqüência numérica de 9 a 2 ( 9, 8, 7, 6, 5, 4, 3, 2, 9... e assim por diante);
				 */
				$parcial = $campo[$i] * $multiplicador[$i];
				//echo "<br/>Parcial[".$i."]: ".$parcial;

				// b) Soma-se o resultado dos produtos obtidos no item “a” acima:
				$total += $parcial;
			}
			//echo "<br/>Total: ".$total;

			// Quando o total da soma for MENOR que o quociente(11), o total da soma deverá ser diminuído diretamente do quociente.
			if($total < 11)
			{
				$resto = $total;
			}
			else
			{
				// c) Divida o total (N) por 11 e determine o resto obtido da divisão como Mod 11(N);
				$resto = $total%11;
			}

			//echo "<br/>Resto: ".$resto;

			//d) Calcule o dígito verificador (DAC) através da expressão: DAC = 11 - Mod 11(N)
			$dac = 11-$resto;

			//Se o resultado desta for igual a 10, 11, considere DAC = 0.
			if($dac >= 10)
			{
				$dac = 0;
			}
			return $dac;

		}

		/**
		 * Anexo 1 – Cálculo do DAC do Código de Barras - Método (Módulo 11)
		 */
		private function CalculoDacModulo11Anexo1()
		{
			//$codigoBarrasSemDac = "2379166700000123451101234567880057123457000"; // deve retornar 6

			/*
			 * O DV GERAL do Código de Barras, localizado na posição 5, é calculado através do MÓDULO 11, com peso de 2 a 9;
			 * Considerar as posições de 1 a 4 e de 6 a 44, iniciando pela posição 44 e saltando a posição 5.
			 * 01 a 03 - Código do Banco na Câmara de Compensação ex: '104'
			 * 04 a 04 - Código da Moeda ex: '9'
			 * 06 a 09 - Fator de Vencimento
			 * 10 a 19 - Valor
			 * 20 a 25 - Código do Beneficiário /=/ Inicio Campo Livre
			 * 26 a 26 - DV do Código do Beneficiário
			 * 27 a 29 - Nosso Número - Sequencia 1 (3ª a 5ª posição do Nosso Número)
			 * 30 a 30 - Constante 1 - 1ª posição do Nosso Número - Tipo de Cobrança (1-Registrada/2-Sem Registro)
			 * 31 a 33 - Nosso Número - Sequencia 2 (6ª a 8ª posição do Nosso Número)
			 * 34 a 34 - Constante 2 - 2ª posição do Nosso Número - Identificador de Emissão do Boleto (4-Beneficiário)
			 * 35 a 43 - Nosso Número - Sequencia 3 (9ª a 17ª posição do Nosso Número)
			 * 44 a 44 - DV do Campo Livre /=/ Fim do Campo Livre
			*/

			//[Código do Banco] - 3 caracteres
			$codigoBarrasSemDac  = $this->CodigoBanco;
			//[Código da Moeda] - 1 caractere
			$codigoBarrasSemDac .= $this->CodigoMoeda;
			//[Fator de Vencimento] - 4 caracteres
			$codigoBarrasSemDac .= $this->CalculaFatorVencimento();
			//[Valor do Título] - 10 caracteres
			$codigoBarrasSemDac .= $this->GetValorTitulo();
			//[Código do Beneficiário] - 6 caracteres
			$codigoBarrasSemDac .= $this->Personalizado;
			//[DV do Código do Beneficiário] - 1 caractere
			$codigoBarrasSemDac .= $this->CalculoDacModulo11Anexo6();
			//[Nosso Número - Sequencia 1] - 3 caracteres
			$codigoBarrasSemDac .= substr($this->GetNossoNumero(), 0,3);
			//[Constante 1] - 1 caractere
			$codigoBarrasSemDac .= 1;
			//[Nosso Número - Sequencia 2] - 3 caracteres
			$codigoBarrasSemDac .= substr($this->GetNossoNumero(), 3,3);
			//[Constante 2] - 1 caractere
			$codigoBarrasSemDac .= 4;
			//[Nosso Número - Sequencia 3] - 9 caracteres
			$codigoBarrasSemDac .= substr($this->GetNossoNumero(), 6,9);
			//[DV do Campo Livre] - 1 caracatere
			$codigoBarrasSemDac .= $this->CalculoDacCampoLivre();


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
				 * pela seqüência numérica de 2 a 9 ( 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7... e assim por diante);
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

			// Se o resultado da subtração for 0 (zero) ou maior que 9 (nove), o dígito verificador será 1 (um). Senão o DV é o próprio resultado da subtração.
			if (($dac > 9) || ($dac == 0))
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
			 * 01 a 03 - Código do Banco na Câmara de Compensação ex: '237'
			 * 04 a 04 - Código da Moeda ex: '9'
			 * 05 a 05 - DAC código de Barras
			 * 06 a 09 - Fator de Vencimento
			 * 10 a 19 - Valor
			 * 20 a 25 - Código do Beneficiário /=/ Inicio Campo Livre
			 * 26 a 26 - DV do Código do Beneficiário
			 * 27 a 29 - Nosso Número - Sequencia 1 (3ª a 5ª posição do Nosso Número)
			 * 30 a 30 - Constante 1 - 1ª posição do Nosso Número - Tipo de Cobrança (1-Registrada/2-Sem Registro)
			 * 31 a 33 - Nosso Número - Sequencia 2 (6ª a 8ª posição do Nosso Número)
			 * 34 a 34 - Constante 2 - 2ª posição do Nosso Número - Identificador de Emissão do Boleto (4-Beneficiário)
			 * 35 a 43 - Nosso Número - Sequencia 3 (9ª a 17ª posição do Nosso Número)
			 * 44 a 44 - DV do Campo Livre /=/ Fim do Campo Livre
			*/



			//[Código do Banco] - 3 caracteres
			$codigo  = $this->CodigoBanco;
			//[Código da Moeda] - 1 caractere
			$codigo .= $this->CodigoMoeda;
			// DAC código de Barras - 1 caractere
			$codigo .= $this->CalculoDacModulo11Anexo1();
			//[Fator de Vencimento] - 4 caracteres
			$codigo .= $this->CalculaFatorVencimento();
			//[Valor do Título] - 10 caracteres
			$codigo .= $this->GetValorTitulo();
			//[Código do Beneficiário] - 6 caracteres
			$codigo .= $this->Personalizado;
			//[DV do Código do Beneficiário] - 1 caractere
			$codigo .= $this->CalculoDacModulo11Anexo6();
			//[Nosso Número - Sequencia 1] - 3 caracteres
			$codigo .= substr($this->GetNossoNumero(), 0,3);
			//[Constante 1] - 1 caractere
			$codigo .= 1;
			//[Nosso Número - Sequencia 2] - 3 caracteres
			$codigo .= substr($this->GetNossoNumero(), 3,3);
			//[Constante 2] - 1 caractere
			$codigo .= 4;
			//[Nosso Número - Sequencia 3] - 9 caracteres
			$codigo .= substr($this->GetNossoNumero(), 6,9);
			//[DV do Campo Livre] - 1 caracatere
			$codigo .= $this->CalculoDacCampoLivre();

			//echo ' #######'.$codigo.' #######</br>';
			return $codigo;
		}
		/**
		 * Anexo 2 – Cálculo do Fator de Vencimento
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

		/*
		 * Cálculo do dígito verificador do código do beneficiário (Personalizado) - Anexo VI
		*/
		public function CalculoDacModulo11Anexo6()
		{
			$codigoBeneficiario = $this->Personalizado;

			//echo "<br/>Codigo Beneficiario: ".$codigoBeneficiario;


			$total = 0;
			$parcial = 0;
			//                591641
			$multiplicador = '765432';

			for ($i = 0; $i <= 5; $i++ )
			{
				/*
				 * a) Tomando-se os 6 algarismos que compõem o Código do beneficiário
				 * multiplique-os, iniciando-se da direita para a esquerda,
				 * pela seqüência numérica de 2 a 9 ( 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7... e assim por diante);
				 */
				$parcial = $codigoBeneficiario[$i] * $multiplicador[$i];
				//echo "<br/>Parcial[".$i."]: ".$parcial;

				// b) Soma-se o resultado dos produtos obtidos no item “a” acima:
				$total += $parcial;
			}
			//echo "<br/>Total: ".$total;

			// Quando o total da soma for MENOR que o quociente(11), o total da soma deverá ser diminuído diretamente do quociente.
			if($total < 11)
			{
				$resto = $total;
			}
			else
			{
				// c) Divida o total (N) por 11 e determine o resto obtido da divisão como Mod 11(N);
				$resto = $total%11;
			}

			//echo "<br/>Resto: ".$resto;

			//d) Calcule o dígito verificador (DAC) através da expressão: DAC = 11 - Mod 11(N)
			$dac = 11-$resto;

			// Se o resultado da subtração for maior que 9 (nove), o dígito verificador será 0 (zero). Senão o DV é o próprio resultado da subtração.
			if (($dac > 9))
			{
				$dac = 0;
			}
			return $dac;

		}

		public function VerificarNossoNumeroMod11()
		{
			/*
			- Formato: XYNNNNNNNNNNNNNNN-D, onde:
			X Modalidade/Carteira de Cobrança (1-Registrada/2-Sem Registro)
			Y Emissão do bloqueto (4-Cedente)
			NNNNNNNNNNNNNNN Nosso Número (15 posições livres do Cedente)
			D *Dígito Verificador
			* Dígito Verificador do Nosso Número calculado através do Modulo 11, conforme ANEXO IV.
			Admite 0 (zero), diferentemente do DV Geral do Código de Barras.
			*/
			$total = 0;
			$parcial = 0;
			// Tipo de Cobrança (1-Registrada / 2-Sem Registro)
			$nosso_numero = 1;
			// Identificador da Emissão do Boleto (4-Beneficiário)
			$nosso_numero .= 4;
			// Nosso Numero
			$nosso_numero .= $this->GetNossoNumero();
			//echo "<br/>Nosso_numero: ".$nosso_numero;
			$multiplicador = '29876543298765432';
			//$ano_nosso_numero = $ano.$nosso_numero;
			//echo "<br/>Nosso_numero: ".$nosso_numero;
			for ($i = 0; $i <= 16; $i++ )
			{
				$parcial = $nosso_numero[$i] * $multiplicador[$i];
				//echo "<br/>Parcial[".$i."]: ".$parcial;
				// Soma-se o resultado dos produtos obtidos no item acima:
				$total += $parcial;
			}

			//echo "<br/>Total: ".$total;
			// Divida o total (N) por 11 e determine o resto obtido da divisão como Mod 11(N);
			$resto = $total % 11;

			// Calcule o dígito verificador através da expressão: DV = 11 - Mod 11(N)
			$digito_verificador = 11-$resto;

			if($digito_verificador == 10 || $digito_verificador == 11)
				$digito_verificador = 0;

			return $digito_verificador;
		}
	}
?>