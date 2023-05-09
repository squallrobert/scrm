<?php
// namespace Classes;

//use DateInterval;
// use DateTime;
// use Exception;
// use Iterator;

class ContaRecorrenteMensal implements Iterator
{
    const PERIODO_MAX_RECORRENCIAS = 'P1Y'; // 1 ano (formato aceito pela classe DateInterval)

    private $ocorrencia = 1;
    private $diaVencEscolhido;
    private $dataInicial;
    private $intervalo;
    private $termino;

    // Armazena a data da compra atual
    private $diaCompra;
    private $mesCompra;
    private $anoCompra;

    // Armazena a data de vencimento atual
    private $diaVenc;
    private $mesVenc;
    private $anoVenc;

    public function __construct($dataReferencia, $diaVencimento, $intervalo, $termino = null)
    {
        // Verifica se o intervalo não é um valor inteiro
        if (!is_int($intervalo))
            throw new Exception('Intervalo deve ser um valor inteiro');

        $this->dataInicial = new DateTime($dataReferencia);
        $this->diaVencEscolhido = $diaVencimento;
        $this->intervalo = $intervalo;
        $this->termino = $termino;

        if ($this->termino === null) { // Recorrências infinitas
            $this->termino = clone $this->dataInicial;
            $this->termino->add(new DateInterval(self::PERIODO_MAX_RECORRENCIAS));
        }

        if (is_string($this->termino)) // Até data limite
            $this->termino = new DateTime($termino);

        // Verifica se a data de término é menor que a data da compra
        if ($this->termino instanceof DateTime && $this->termino < $this->dataInicial)
            throw new Exception('Data de término não pode ser inferior a data da compra');

        $this->rewind();
    }

    public function getDataVencimentoCurrent()
    {
        return "{$this->anoVenc}-{$this->mesVenc}-{$this->diaVenc}";
    }

    public function getNumeroOcorrenciaAtual ()
    {
        return $this->ocorrencia;
    }

    private function calcularProximaCompra ()
    {
        $mes = $this->mesCompra;
        $intervalo = $this->intervalo;
        $novoMes = $mes + $intervalo;

        if ($novoMes > 12) {
            // Corrige o ano
            $this->anoCompra = $this->anoCompra + ceil($novoMes / 12) - 1;
            // Corrige o mês
            $this->mesCompra = $novoMes % 12 > 0 ? $novoMes % 12 : 12;
        } else {
            $this->mesCompra = $novoMes;
        }

        $diaCompra = intval($this->dataInicial->format('j'));
        $ultimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $this->mesCompra, $this->anoCompra);

        // Corrige o dia da compra se necessário
        $this->diaCompra = str_pad($diaCompra > $ultimoDiaMes ? $ultimoDiaMes : $diaCompra, 2, '0', STR_PAD_LEFT );
    }

    private function calcularProximoVencimento ()
    {
        $diaCompra = $this->diaCompra;
        $diaVenc = $this->diaVencEscolhido;
        $mesVenc = $this->mesCompra;
        $anoVenc = $this->anoCompra;

        if ($diaCompra > $diaVenc) {
            $mesVenc++;
            if ($mesVenc > 12) {
                $mesVenc = 1;
                $anoVenc++;
            }
        }

        $ultimoDiaMes = cal_days_in_month(CAL_GREGORIAN, $mesVenc, $anoVenc);

        // Corrige o dia do vencimento se necessário
        $this->diaVenc = $diaVenc > $ultimoDiaMes ? $ultimoDiaMes : $diaVenc;
        $this->mesVenc = $mesVenc;
        $this->anoVenc = $anoVenc;
    }

    public function current()
    {
        $mes = str_pad($this->mesVenc, 2, '0', STR_PAD_LEFT);
        $dia = str_pad($this->diaVenc, 2, '0', STR_PAD_LEFT);
        return $this->anoVenc . '-' . $mes . '-' . $dia;
    }

    public function key()
    {
        $mes = str_pad($this->mesCompra, 2, '0', STR_PAD_LEFT);
        $dia = str_pad($this->diaCompra, 2, '0', STR_PAD_LEFT);
        return $this->anoCompra . '-' . $mes . '-' . $dia;
    }

    public function next()
    {
        $this->calcularProximaCompra();
        $this->calcularProximoVencimento();
        $this->ocorrencia++;
    }

    public function rewind()
    {
        $this->diaCompra = intval($this->dataInicial->format('j'));
        $this->mesCompra = intval($this->dataInicial->format('n'));
        $this->anoCompra = intval($this->dataInicial->format('Y'));

        $this->diaVenc = $this->diaVencEscolhido;
        $this->mesVenc = $this->mesCompra;
        $this->anoVenc = $this->anoCompra;

        $this->calcularProximoVencimento();
        $this->ocorrencia = 1;
    }

    public function valid()
    {
        if (is_int($this->termino)) // Até um limite de ocorrências
            return $this->ocorrencia <= $this->termino;

        $ano = $this->anoCompra;
        $mes = $this->mesCompra;
        $dia = $this->diaCompra;
        $data = $ano . '-' . $mes . '-' . $dia;

        return new DateTime($data) <= $this->termino;
    }
}