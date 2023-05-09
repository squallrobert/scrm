<?php
// namespace Classes;

// use DateInterval;
// use DateTime;
// use Exception;
// use Iterator;

class ContaRecorrenteSemanal implements Iterator
{
    const PERIODO_MAX_RECORRENCIAS = 'P1Y'; // 1 ano (formato aceito pela classe DateInterval)

    private $ocorrencia = 1;
    private $dataInicial;
    private $dataVencInicial;
    private $termino;

    // Período a ser incrementado a cada iteração
    private $periodo;

    // Armazena a data da compra atual
    private $diaSemanaCompra;
    private $dataReferencia;

    // Armazena a data de vencimento atual
    private $diaSemanaVenc;
    private $dataVenc;

    public function __construct($dataReferencia, $diaVencimento, $intervalo, $termino = null)
    {
        if (!is_int($intervalo)) throw new Exception();

        $this->dataInicial = new DateTime($dataReferencia);
        $this->termino = $termino;
        $this->periodo = new DateInterval('P7D');

        $this->diaSemanaCompra = date('N', strtotime($dataReferencia));
        $this->dataReferencia = clone $this->dataInicial;

        $dataVenc = clone $this->dataReferencia;
        $diferencaDias = new DateInterval('P'. abs($this->diaSemanaCompra - $diaVencimento) . 'D');

        if ($this->diaSemanaCompra > $diaVencimento) {
            $dataVenc->sub($diferencaDias)->add($this->periodo);
        } else {
            $dataVenc->add($diferencaDias);
        }

        $this->dataVencInicial = clone $dataVenc;
        $this->diaSemanaVenc = $diaVencimento;
        $this->dataVenc = $dataVenc;

        $this->termino = $termino;

        if ($this->termino === null) { // Recorrências infinitas
            $this->termino = clone $this->dataInicial;
            $this->termino->add(new DateInterval(self::PERIODO_MAX_RECORRENCIAS));
        }

        if (is_string($this->termino)) // Até data limite
            $this->termino = new DateTime($termino);
    }

    public function getNumeroOcorrenciaAtual ()
    {
        return $this->ocorrencia;
    }

    public function current()
    {
        return $this->dataVenc->format('Y-m-d');
    }

    public function key()
    {
        return $this->dataReferencia->format('Y-m-d');
    }

    public function next()
    {
        $this->dataReferencia->add($this->periodo);
        $this->dataVenc->add($this->periodo);
        $this->ocorrencia++;
    }

    public function rewind()
    {
        $this->dataReferencia = clone $this->dataInicial;
        $this->dataVenc = clone $this->dataVencInicial;
        $this->ocorrencia = 1;
    }

    public function valid()
    {
        if (is_int($this->termino)) // Até um limite de ocorrências
            return $this->ocorrencia <= $this->termino;

        return $this->dataReferencia <= $this->termino;
    }
}