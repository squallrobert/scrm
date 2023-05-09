<?php

/**
 * Created by PhpStorm.
 * User: Arthur Valente
 * Date: 04/11/2015
 * Time: 11:15
 */
class FuncoesMapa
{
    /**
     * @var $linha = Array ou STDObject com os dados do ponto de interesse
     */
    public static function GerarBalaoPontoInteresse($linha)
    {
        if ($linha->telefone) {
            $telefone = "<b>" . ROTULO_TELEFONE . ":</b> $linha->ddd_telefone - $linha->telefone ";
        } else {
            $telefone = "";
        }
        if ($linha->cidade) {
            $cidade = "<b> " . ROTULO_CIDADE . ":</b> $linha->cidade ";
        } else {
            $cidade = "";
        }
        if ($linha->uf) {
            $uf = "" . ROTULO_UF . ": $linha->uf";
        } else {
            $uf = "";
        }

        $html = "<div style='font: bold 12px verdana,arial,sans-serif;color:#EEA908;margin:10px;white-space:nowrap'>";
        $html .= "".addslashes($linha->nome)."";
        $html .= "</div>";
        $margin = "style='margin:5px;'";
        $html .= "<div>";
        $html .= "<p $margin ><b>". ROTULO_ENDERECO .":</b>" . ROTULO_ENDERECO.": ".addslashes($linha->logradouro).", $linha->numero $linha->complemento</p>";
        $html .= "<p $margin >".addslashes($linha->bairro)."</p>";
        $html .= "<p>$cidade $uf</p>";
        $html .= "<p $margin ><b>". ROTULO_CATEGORIA .":</b> $linha->nome_categoria </p>";
        $html .= "<p $margin >$telefone</p>";
        $html .= "<p $margin ><b>". ROTULO_LATITUDE .":</b> $linha->latitude <b>". ROTULO_LONGITUDE .":</b> $linha->longitude </p>";
        $html .= "</div>";

        return $html;
    }

    /**
     * @var $linha = Array ou STDObject com os dados da posição
     */
    public static function GerarBalaoVeiculo($linha, $numeroPosicao = 0)
    {



        if(is_array($linha)) {
            $linha = (object) $linha;
        }
        // Tratando a direção do veículo com arrowHeads
        $direcao = "";
        if ($linha->direcao) {
            if ($linha->direcao >= 339 && $linha->direcao <= 360 || ($linha->direcao >= 0 && $linha->direcao <= 22)) {
                $direcao = "<img src='assets/images/bts/norte.png' alt='" . ROTULO_NORTE . "' title='" . ROTULO_NORTE . "' style='cursor:pointer'/>";
            } else if ($linha->direcao >= 23 && $linha->direcao <= 67) {
                $direcao = "<img src='assets/images/bts/nordeste.png' alt='" . ROTULO_NORDESTE . "' title='" . ROTULO_NORDESTE . "' style='cursor:pointer'/>";
            } else if ($linha->direcao >= 68 && $linha->direcao <= 112) {
                $direcao = "<img src='assets/images/bts/leste.png' alt='" . ROTULO_LESTE . "' title='" . ROTULO_LESTE . "' style='cursor:pointer'/>";
            } else if ($linha->direcao >= 113 && $linha->direcao <= 158) {
                $direcao = "<img src='assets/images/bts/sudeste.png' alt='" . ROTULO_SUDESTE . "' title='" . ROTULO_SUDESTE . "' style='cursor:pointer'/>";
            } else if ($linha->direcao >= 159 && $linha->direcao <= 203) {
                $direcao = "<img src='assets/images/bts/sul.png' alt='" . ROTULO_SUL . "' title='" . ROTULO_SUL . "' style='cursor:pointer'/>";
            } else if ($linha->direcao >= 203 && $linha->direcao <= 248) {
                $direcao = "<img src='assets/images/bts/sudoeste.png' alt='" . ROTULO_SUDOESTE . "' title='" . ROTULO_SUDOESTE . "' style='cursor:pointer'/>";
            } else if ($linha->direcao >= 249 && $linha->direcao <= 293) {
                $direcao = "<img src='assets/images/bts/oeste.png' alt='" . ROTULO_OESTE . "' title='" . ROTULO_OESTE . "' style='cursor:pointer'/>";
            } else if ($linha->direcao >= 294 && $linha->direcao <= 338) {
                $direcao = "<img src='assets/images/bts/noroeste.png' alt='" . ROTULO_NOROESTE . "' title='" . ROTULO_NOROESTE . "' style='cursor:pointer'/>";
            }
        }
        if($linha->panico == 1) {
            $panico = ROTULO_EM_PANICO;
        } else {
            $panico = ROTULO_NORMAL;
        }
        // Preparando o HTML com as informações da posição\
        $addPonto = "onclick='AdicionarPontoDiretoMapa({$linha->latitude}, {$linha->longitude})'";
        $ponto = "<span class='icon icon-mapa' $addPonto></span>";
        //$ponto = "<input type='button' id='adicionar_ponto' class='ui-button  ui-widget ui-state-default ui-corner-all' value='Adicionar ponto' $addPonto/>";
        $html = "<div style='font: bold 12px verdana,arial,sans-serif;color:#EEA908;margin:10px;white-space:nowrap'>";
        $html .= "{$linha->nome_cliente}";
        $html .= " - ( ";
        $html .= " {$linha->rotulo} ($numeroPosicao)";
        $html .= " ) </div>";

        $margin = "style='margin:5px;'";
        $html .= "<div>";
        if ($linha->nome_motorista != '') {
            $html .= "<p $margin><b>" . ROTULO_MOTORISTA . ":</b> {$linha->nome_motorista}</p>";
        }

		if ($linha->data_hora != '') {
			if (strpos($linha->data_hora, "/") !== false) {
				$html .= "<p $margin><b>" . ROTULO_DATA_HORA . ":</b> " . $linha->data_hora . "</p>";
			} else {
				$html .= "<p $margin><b>" . ROTULO_DATA_HORA . ":</b> " . Conexao::PrepararDataPHP($linha->data_hora, $_SESSION['usuario']['timezone']) . "</p>";
			}
        }else
        {
            $html .= "<p $margin><b>" . ROTULO_DATA_HORA . ":</b> ".Conexao::PrepararDataPHP($linha->data, null, "d/m/Y")."</p>";
        }

        $html .= "<p $margin><b>" . ROTULO_LATITUDE . ": </b>{$linha->latitude} ";
        $html .= "<b> " . ROTULO_LONGITUDE . ": </b>{$linha->longitude}</p>";
        if($linha->logradouro != '')
        {
            $html .= "<p $margin><b>" . ROTULO_LOGRADOURO . ":</b> " . addslashes($linha->logradouro) . "</p>";
        }
        $html .= "<p $margin><b>" . ROTULO_VELOCIDADE . ":</b> ".(($linha->embarcacao) ? number_format($linha->velocidade * 0.539957, 2, ",", ".") . " Mn/h" : $linha->velocidade . " Km/h")." </p>";
        if ($linha->origem != '' & $linha->destino != '') {
            $html .= "<p $margin ><b>" . ROTULO_ORIGEM . ":</b> {$linha->origem} </p>";
            $html .= "<p $margin ><b>" . ROTULO_DESTINO . ":</b> {$linha->destino} </p>";
        }
        if(trim($linha->tempo_desligado) != '') {
            $html .= "<p $margin><b>".ROTULO_TEMPO.":</b> {$linha->tempo_desligado}</p>";
        }
        if ($linha->veiculo_observacao != '') {
            $html .= "<p $margin ><b>" . ROTULO_OBSERVACAO . ":</b> " . str_replace("\r", "",
                    str_replace("\n", "", $linha->veiculo_observacao)) . " </p>";
        }
        if(trim($linha->panico) != "") {
            $html .= "<p $margin><b>".ROTULO_PANICO.":</b> $panico</p>";
        }
        $html .= $ponto;
        $html .= "<p>$direcao</p>";
        $html .= "</div>";

        return $html;
    }


    public static function GerarBalaoVeiculoAPPepa($linha)
    {
        if(is_array($linha)) {
            $linha = (object) $linha;
        }
        // Preparando o HTML com as informações da posição\
        $addPonto = "onclick='AdicionarPontoDiretoMapa({$linha->latitude}, {$linha->longitude})'";
        $ponto = "<span class='icon icon-mapa' $addPonto></span>";
        //$ponto = "<input type='button' id='adicionar_ponto' class='ui-button  ui-widget ui-state-default ui-corner-all' value='Adicionar ponto' $addPonto/>";
        $html = "<div style='font: bold 12px verdana,arial,sans-serif;color:#EEA908;margin:10px;white-space:nowrap'>";
        $html .= " {$linha->rotulo} {$linha->marca_modelo}";
        $html .= "</div>";

        $html .= "<div style='font: bold 12px verdana,arial,sans-serif;color:#FF0000;margin:10px;white-space:nowrap; text-transform: uppercase;'>";
        $html .= " {$linha->titulo_evento1}";
        $html .= "</div>";
        $html .= "<div style='font: bold 12px verdana,arial,sans-serif;color:#FF0000;margin:10px;white-space:nowrap; text-transform: uppercase;'>";
        $html .= " {$linha->titulo_evento2}";
        $html .= "</div>";

        $margin = "style='margin:5px;'";
        $html .= "<div>";
        if ($linha->nome_motorista != '') {
            $html .= "<p $margin><b>" . ROTULO_MOTORISTA . ":</b> {$linha->nome_motorista}</p>";
        }
        if ($linha->data_hora != '') {
            if (strpos($linha->data_hora, "/") !== false) {
                $html .= "<p $margin><b>" . ROTULO_DATA_HORA . ":</b> " . $linha->data_hora . "</p>";
            } else {
                $html .= "<p $margin><b>" . ROTULO_DATA_HORA . ":</b> " . Conexao::PrepararDataPHP($linha->data_hora, $_SESSION['usuario']['timezone']) . "</p>";
            }
        }else
        {
            $html .= "<p $margin><b>" . ROTULO_DATA_HORA . ":</b> ".Conexao::PrepararDataPHP($linha->data, null, "d/m/Y")."</p>";
        }
        $html .= "<p $margin><b>" . ROTULO_LATITUDE . ": </b>{$linha->latitude} ";
        $html .= "<b> " . ROTULO_LONGITUDE . ": </b>{$linha->longitude}</p>";
        if($linha->logradouro != '')
        {
            $html .= "<p $margin><b>" . ROTULO_LOGRADOURO . ":</b> " . addslashes($linha->logradouro) . "</p>";
        }
        $html .= "<p $margin><b>" . ROTULO_VELOCIDADE . ":</b> ".(($linha->embarcacao) ? number_format($linha->velocidade * 0.539957, 2, ",", ".") . " Mn/h" : $linha->velocidade . " Km/h")." </p>";
        if ($linha->origem != '' & $linha->destino != '') {
            $html .= "<p $margin ><b>" . ROTULO_ORIGEM . ":</b> {$linha->origem} </p>";
            $html .= "<p $margin ><b>" . ROTULO_DESTINO . ":</b> {$linha->destino} </p>";
        }

        $html .= $ponto;
        $html .= "</div>";

        return $html;
    }
}