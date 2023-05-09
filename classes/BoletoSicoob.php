<?php
/**
 * Created by PhpStorm.
 * User: Cássio
 * Date: 11/07/2019
 * Time: 09:54
 */


class BoletoSicoob
{
    private $dadosBoleto;
    private $fatorVencimento;
    private $digitoVerificadorNossoNumero;

    public function __construct($dadosBoleto = null)
    {
        if ($dadosBoleto) {
            $this->dadosBoleto = $dadosBoleto;
            $this->dadosBoleto->codigo_cliente = trim($this->dadosBoleto->codigo_cliente);
        }

    }

    public function getDadosBoleto()
    {
        return $this->dadosBoleto;
    }

    public function CalcularLinhaDigitavel()
    {
        $campo1 = '756';
        $campo1 .= '9';
        $campo1 .= $this->dadosBoleto->carteira;
        $campo1 .= '.';
        $campo1 .= str_pad(substr($this->dadosBoleto->agencia, 0, 4), 4, '0', STR_PAD_LEFT);
        $campo1 .= $this->calcularDigitoVerificadorLinhaDigitavel($campo1);

        $campo2 = $this->dadosBoleto->personalizado;
        $codigoCliente = str_pad(substr($this->dadosBoleto->codigo_cliente, 0, 7), 7, '0', STR_PAD_LEFT);

        $campo2 .= substr($codigoCliente, 0, 3);
        $campo2 .= '.';
        $campo2 .= substr($codigoCliente, 3, 4);

        /**
         * Quebra o nosso número para inserir o dígito entre a primeira posição do nosso numero e as posições restantes do nosso numero
         * Ex.: Se nosso número = 01234567 e dígito verificador = X Então: 0X1234567
         */
        $nossoNumero = str_pad($this->dadosBoleto->nosso_numero, 7, 0, STR_PAD_LEFT);

        /**
         * Insere a primeira posição do nosso numero no campo2
         */
        $campo2 .= substr($nossoNumero, 0, 1);
        $campo2 .= $this->calcularDigitoVerificadorLinhaDigitavel($campo2);

        $campo3 = substr($nossoNumero, 1, 5);
        $campo3 .= '.';
        $campo3 .= substr($nossoNumero, 6, 2);
        $campo3 .= $this->calcularDigitoVerificadorNossoNumero($this->dadosBoleto->agencia, $this->dadosBoleto->codigo_cliente, $this->dadosBoleto->nosso_numero);
        $campo3 .= '001';
        $campo3 .= $this->calcularDigitoVerificadorLinhaDigitavel($campo3);

        $this->calcularFatorVencimento($this->dadosBoleto->data_vencimento);
        $campo5 = $this->fatorVencimento;
        $campo5 .= str_pad(str_replace('.', '', $this->dadosBoleto->valor_com_desconto), 10, '0', STR_PAD_LEFT);

        /**
         * Digito verificador do código de barras
         */
        $codigoDeBarras= $this->GetCodigoBarras();
        $digitoVerificadorCampo4 = strval($codigoDeBarras[4]);

        /**
         * Monta a linha digitável com os dígitos verificadores
         */
        $linhaDigitavel = $campo1 . ' ' . $campo2 . ' ' . $campo3 . ' ' . $digitoVerificadorCampo4 . ' ' . $campo5;

        return $linhaDigitavel;
    }

    /**
     * Formata a linha de acordo com os padrões necessários e retorna a linha do código de barras
     * @return string
     */
    public function GetCodigoBarras()
    {
        $linha = '756';
        $linha .= '9';
        $linha .= $this->fatorVencimento;
        $linha .= str_pad(str_replace('.', '', $this->dadosBoleto->valor_com_desconto), 10, '0', STR_PAD_LEFT);;
        $linha .= $this->dadosBoleto->carteira;
        $linha .= substr($this->dadosBoleto->agencia, 0, 4);
        $linha .= $this->dadosBoleto->personalizado;
        $linha .= str_pad($this->dadosBoleto->codigo_cliente, 7, '0', STR_PAD_LEFT);
        $linha .= str_pad($this->dadosBoleto->nosso_numero, 7, 0, STR_PAD_LEFT);
        $linha .= $this->digitoVerificadorNossoNumero;
        $linha .= '001';

        $digitoVerificador = $this->calcularDigitoVerificadorCodigoBarras($linha);
        $linhaCodigoBarras = substr($linha, 0, 4);
        $linhaCodigoBarras .= $digitoVerificador;
        $linhaCodigoBarras .= substr($linha, 4);

        return $linhaCodigoBarras;
    }

    /**
     * Recebe um número e o tamanho e retorna o número formatado no tamanho solicitado
     * Considera que números virão em formato com duas casas decimais e a separação será por ponto ('.')
     * Formata utilizando substr e str_pad
     * @param int $numero
     * @param int $tamanho
     * @return string
     */
    private function formatarNumero($numero, $tamanho)
    {
        $substring = substr($numero, 0, $tamanho);
        $replace = str_replace(['.', '-'], '', $substring);
        $rs = str_pad($replace, $tamanho, '0', STR_PAD_LEFT);
        return $rs;
    }

    /**
     * Concatena agencia . codigoCliente . nossoNumero e calcula o digito verificador
     * @param $agencia
     * @param $codigoCliente
     * @param $nossoNumero
     * return int
     */
    public function calcularDigitoVerificadorNossoNumero($agencia, $codigoCliente, $nossoNumero)
    {
        $sequencia = $this->formatarNumero($agencia,4) .
            $this->formatarNumero($codigoCliente,10) .
            $this->formatarNumero($nossoNumero,7);

        $x = 1;
        $calculoDigitoVerificador = '';

        for($num = 0; $num < strlen($sequencia); $num++) {

            /**
             * Constante fixa Sicoob =  3197
             */
            if($x == 1) {
                $constanteSicoob = 3;
            }
            if($x == 2) {
                $constanteSicoob = 1;
            }
            if($x == 3) {
                $constanteSicoob = 9;
            }
            if($x == 4) {
                $constanteSicoob = 7;
                $x = 0;
            }

            $calculoDigitoVerificador += (substr($sequencia, $num,1) * $constanteSicoob);
            $x++;
        }

        $restoDivisao = $calculoDigitoVerificador % 11;
        $digitoVerificador = ($restoDivisao == 0 || $restoDivisao == 1) ? 0 : 11 - $restoDivisao;

        $this->digitoVerificadorNossoNumero = $digitoVerificador;
        return $digitoVerificador;
    }

    /**
     * Calcula o fator de vencimento de acordo com as especificações da documentação
     * Fórmula: (data de vencimento - data base) + 1000
     * @param $dataVencimentoTitulo
     * @return int
     */
    private function calcularFatorVencimento($dataVencimentoTitulo)
    {
        $dataVencimento = strtotime($dataVencimentoTitulo);

        /**
         * Segundo a documentação recebida, em 22/02/2025 o fator de vencimento deverá retornar para 1000 e a data base
         * deverá ser 22/02/2025
         */
        if(Date('Y-m-d') >= '2025-02-22')
            $dataBase = strtotime('2025-02-22');
        else
            $dataBase = strtotime('2000-07-03');

        /**
         * Calcula a diferença de dias entre as datas
         */
        $diasDiferenca = ($dataVencimento - $dataBase) /60 / 60 / 24;

        $fatorVencimento = $diasDiferenca + 1000;
        $this->fatorVencimento = $fatorVencimento;
    }

    /**
     * Calcula o dígito verificador dos campos 1, 2 e 3 utilizando o módulo 10 (multiplos de 10)
     * Utiliza os multiplicadores  2 e 1 da direita para a esquerda
     * @param $numero
     */
    private function calcularDigitoVerificadorLinhaDigitavel($numero)
    {
        $numero = str_replace('.', '', $numero);
        $lengthNumero = strlen($numero) - 1;
        $multiplicador = 2;
        $valor = 0;

        for($i = $lengthNumero; $i >= 0; $i --){
           $resultadoMultiplicacao = strval($numero[$i]) * $multiplicador;

           /**
            * Se o resultado da multiplicação for  maior que 9, devemos somar os algarismos para obter um único digito
            */
            if($resultadoMultiplicacao > 9) {
                $arrayResultadoMultiplicacao = strval($resultadoMultiplicacao);
                $valorParcial = $arrayResultadoMultiplicacao[0] + $arrayResultadoMultiplicacao[1];
            }
            else {
                $valorParcial = $resultadoMultiplicacao;
            }

            $valor += $valorParcial;

           /**
            * Alterna o multiplicador
            */
           $multiplicador = $multiplicador == 2 ? 1 : 2;
        }

        /**
         * Se $valor não for multiplo de 10, buscará o próximo número múltiplo de 10 que seja maior do que $valor
         */
        if(($valor % 10) != 0)
            $multiploDe10 = $this->getProximoMultiploDe10($valor);
        else
            $multiploDe10 = $valor;

        $digitoVerficador = $multiploDe10 - $valor;

        return $digitoVerficador;
    }

    /**
     * Recebe a linha com os campos na ordem adequada para cálculo do dígito verificador do código de barras
     * Multiplicador inicia em 2 e vai até 9, da direita para a esquerda.
     * @param string $linhaCodigoDeBarras
     * return int
     */
    private function calcularDigitoVerificadorCodigoBarras($linhaCodigoDeBarras)
    {
        $linhaCodigoDeBarras = str_replace('.', '', $linhaCodigoDeBarras);
        $linhaDigitavelLength = strlen($linhaCodigoDeBarras) - 1;
        $multiplicador = 2;
        $valor = 0;

        for($i = $linhaDigitavelLength; $i >= 0; $i --) {
            $resultadoMultiplicacao = strval($linhaCodigoDeBarras[$i]) * $multiplicador;
            $valor += $resultadoMultiplicacao;
            $multiplicador == 9 ? $multiplicador = 2 : $multiplicador++;
        }

        $digito = 11 - ($valor % 11);
        $digitoVerificador = ($digito <= 1 || $digito > 9) ? 1 : $digito;

        return $digitoVerificador;
    }

    /**
     * Itera sobre o valor informado para encontrar o próximo múltiplo de 10
     * @param $valor
     * @return int
     */
    private function getProximoMultiploDe10($valor)
    {
        while (($valor % 10) != 0)
            $valor++;

        return $valor;
    }
}