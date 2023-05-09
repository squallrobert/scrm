<?php

/**
 * Created by PhpStorm.
 * User: Arthur
 * Date: 28/08/2015
 * Time: 11:54
 */
class CalculosListagem
{
	static public function TratarCampoStatusPortas($dados, $tipo = 0, $xml = false)
	{
		//Transforma o array em objeto
		if (is_array($dados)) {
			$aux = new stdClass();
			foreach ($dados AS $key => $value) {
				$aux->$key = $value;
			}

			$dados = $aux;
		}

		$imagensEntradas = "";
		$imagensSaidas   = "";



		//Transforma as entradas e saidas em um array de binários, representando ligado/desligado
		//$entradas = str_pad(decbin($dados->entradas), 8, "0", STR_PAD_LEFT);
        //$saidas   = str_pad(decbin($dados->saidas), 8, "0", STR_PAD_LEFT);

        $entradas = str_pad(decbin($dados->entradas) ,8,0,STR_PAD_RIGHT);
        $saidas   = str_pad(decbin($dados->saidas), 8, "0", STR_PAD_RIGHT);


		//echo str_pad(decbin($dados->entradas) ,8,0,STR_PAD_RIGHT) . "-";


		$iconePortas = "";
		//7 >=0 i--
		for ($i = 0; $i <=7 ; $i++) {
			//$nome_entrada  = 'entrada' . (8 - $i);
			//$icone_entrada = 'icone_entrada' . (8 - $i);

            $nome_entrada  = 'entrada' . (1 + $i);
            $icone_entrada = 'icone_entrada' . (1 + $i);

			//$nome_saida    = 'saida' . (8 - $i);
			//$icone_saida   = 'icone_saida' . (8 - $i);

            $nome_saida    = 'saida' . (1 - $i);
            $icone_saida   = 'icone_saida' . (1 - $i);
			$cont          = 8 - $i;



			// Se o tipo for 0, gera imagens de portas, usado em listagens gerais do sistema, se o tipo for 1, gera apenas os textos para impressão
			if ($tipo == 0) {
				if ($dados->$nome_entrada == "") {
					//@$dados->$nome_entrada = substr(ucfirst($nome_entrada), 0, 7) . " " . (8 - $i);
                    @$dados->$nome_entrada = substr(ucfirst($nome_entrada), 0, 7) . " " . (1 + $i);
				}
				if ($dados->$nome_saida == "") {
					//@$dados->$nome_saida = substr(ucfirst($nome_saida), 0, 5) . " " . (8 - $i);
                    @$dados->$nome_saida = substr(ucfirst($nome_saida), 0, 5) . " " . (1 + $i);
				}

				if ($entradas[$i] == 1) {
					$imagensEntradas .= " <i class=\"fas fa-circle color-success-600\" data-toggle='tooltip' data-title=\"" . $dados->$nome_entrada . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\" alt=\"" . $dados->$nome_entrada . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\" title=\"" . $dados->$nome_entrada . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\"></i>";
					if ($dados->$icone_entrada) {
						$iconePortas .= "<img src=\"" . $dados->$icone_entrada . "\" width=\"24px\" alt=\"" . $dados->$nome_entrada . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\" title=\"" . $dados->$nome_entrada . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\"/>";
					}
				} else {
					$imagensEntradas .= " <i class=\"fas fa-circle color-fusion-600\" data-toggle='tooltip' data-title=\"" . $dados->$nome_entrada . " (" . ROTULO_OFF . " - PORTA " . (8 - $i) . ")\" alt=\"" . $dados->$nome_entrada . " (" . ROTULO_OFF . " - PORTA " . (8 - $i) . ")\" title=\"" . $dados->$nome_entrada . " (" . ROTULO_OFF . " - PORTA " . (8 - $i) . ")\"></i>";
				}

				if ($saidas[$i] == 1) {
					$imagensSaidas .= " <i class=\"fas fa-circle color-success-600\" data-toggle='tooltip' data-title=\"" . $dados->$nome_saida . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\" alt=\"" . $dados->$nome_saida . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\" title=\"" . $dados->$nome_saida . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\"></i>";
					if ($dados->$icone_saida) {
						$iconePortas .= "<img src=\"" . $dados->$icone_saida . "\" width=\"24px\" alt=\"" . $dados->$nome_saida . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\" title=\"" . $dados->$nome_saida . " (" . ROTULO_ON . " - PORTA " . (8 - $i) . ")\"/>";
					}
				} else {
					$imagensSaidas .= " <i class=\"fas fa-circle color-fusion-600\" data-toggle='tooltip' data-title=\"" . $dados->$nome_saida . " (" . ROTULO_OFF . " - PORTA " . (8 - $i) . ")\" alt=\"" . $dados->$nome_saida . " (" . ROTULO_OFF . " - PORTA " . (8 - $i) . ")\" title=\"" . $dados->$nome_saida . " (" . ROTULO_OFF . " - PORTA " . (8 - $i) . ")\"></i>";
				}
			} else {

				if ($i == 7) {
					$imagensEntradas = "Entradas: <br/>";
					$imagensSaidas   = "Saidas: <br/>";
				}
				if ($entradas[$i] == 1) {
					$imagensEntradas .= ($xml) ? "Nº $cont: " . $dados->$nome_entrada . " ON, " : "<img src='assets/images/led_on.png'/>";
				} else {
					$imagensEntradas .= ($xml) ? "Nº $cont: " . $dados->$nome_entrada . " OFF, " : "<img src='assets/images/led_off.png'/>";
				}

				if ($saidas[$i] == 1) {
					$imagensSaidas .= ($xml) ? "Nº $cont: " . $dados->$nome_saida . " ON, " : "<img src='assets/images/led_on.png'/>";
				} else {
					$imagensSaidas .= ($xml) ? "Nº $cont: " . $dados->$nome_saida . " OFF, " : "<img src='assets/images/led_off.png'/>";
				}

				if ($i == 0) {
					$imagensEntradas = substr($imagensEntradas, 0, -2);
					$imagensSaidas   = substr($imagensSaidas, 0, -2);
				}
			}
		}

		return "<span style='font-size: 8px !important;'>" . $imagensEntradas . " <br/> " . $imagensSaidas . "</span>";
	}

	/**
	 *  Verifica se a posição é de bloqueio
	 *
	 * @return boolean
	 */
	static public function VerificarBloqueio($dados)
	{
		//Transforma o array em objeto
		if (is_array($dados)) {
			$aux = new stdClass();
			foreach ($dados AS $key => $value) {
				$aux->$key = $value;
			}

			$dados = $aux;
		}

		$saidas = str_pad(decbin($dados->saidas), 8, "0", STR_PAD_LEFT);

		//Se o fabricante MAXTRACK SAIDA 1 SIGNIFICA BLOQUEIO
		//SE FOR SUNTECH BLOQUEIO É NA SAIDA 2
		if ($dados->id_fabricante != 23) {
			if ($saidas[7] == 1) {
				$retorno = true;
			} else {
				$retorno = false;
			}
		} else {

			//S210 W
			if ($dados->id_modelo_rastreador == 50) {
				if ($saidas[7] == 1) {
					$retorno = true;
				} else {
					$retorno = false;
				}
			} else {
				if ($saidas[6] == 1) {
					$retorno = true;
				} else {
					$retorno = false;
				}
			}
		}

		return $retorno;
	}

	static public function TratarCampoBateria($tensao, $id_fabricante, $id_modelo_rastreador,$sat_hibrido = 0)
	{
        if ($sat_hibrido > 0) {
            return;
        }
		if ($tensao >= 4.3) {
			return;
		}
		$bateria = "";
		if ($id_fabricante == 1) {

			//Valores para bateria interna
			$nivelMax  = 4.250;
			$nivelMin  = 3.250;
			$nivelLido = $tensao;

			if ($nivelLido <= $nivelMax) {
				$nivel = (1 - ($nivelMax - $nivelLido) / ($nivelMax - $nivelMin)) * 100;
				if ($nivel <= 0) {
					$nivel = 0;
				}
				$nivel = number_format($nivel);

				if ($nivel < 25) {
					$bateria = "<img src=\"assets/images/bts/bateria_25.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				} else if ($nivel == 25 || $nivel < 50) {
					$bateria = "<img src=\"assets/images/bts/bateria_50.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				} else if ($nivel == 50 || $nivel < 75) {
					$bateria = "<img src=\"assets/images/bts/bateria_75.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				} else if ($nivel >= 75) {
					$bateria = "<img src=\"assets/images/bts/bateria_100.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				}
			} else {
				$bateria = '';
			}
		} else if ($id_fabricante == 10 || $id_fabricante == 29 || ($id_fabricante == 38 && $id_modelo_rastreador != 104)) {
			//Valores para bateria interna
			$nivelMax  = 4.2;
			$nivelMin  = 3.5;
			$nivelLido = $tensao;

			if ($nivelLido <= $nivelMax) {
				$nivel = (1 - ($nivelMax - $nivelLido) / ($nivelMax - $nivelMin)) * 100;
				if ($nivel <= 0) {
					$nivel = 0;
				}
				$nivel = number_format($nivel);

				if ($nivel < 25) {
					$bateria = "<img src=\"assets/images/bts/bateria_25.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				} else if ($nivel == 25 || $nivel < 50) {
					$bateria = "<img src=\"assets/images/bts/bateria_50.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				} else if ($nivel == 50 || $nivel < 75) {
					$bateria = "<img src=\"assets/images/bts/bateria_75.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				} else if ($nivel >= 75) {
					$bateria = "<img src=\"assets/images/bts/bateria_100.png\" alt=\"" . $nivel . '%' . "\" title=\"" . $nivel . '%' . "\" /> " . $nivel . '%';
				}
			} else {
				$bateria = '';
			}
		} else {
			$bateria = '';
		}

		return $bateria;
	}

	static public function IntervaloDataHora($data_hora1, $data_hora2)
	{
		if ($data_hora1 == "" || $data_hora2 == "") {
			return 0;
		}
		//echo $data_hora1 ."-". $data_hora2. "<br/>";

		if (strpos($data_hora1, "/") !== false) {
			$data_hora1 = Conexao::PrepararDataBD($data_hora1);
		}

		if (strpos($data_hora2, "/") !== false) {
			$data_hora2 = Conexao::PrepararDataBD($data_hora2);
		}

		return strtotime($data_hora1) - strtotime($data_hora2);
	}

	static public function IntervaloDataHoraAbreviado($d2){

        $d1 = new DateTime(FusoHorario::ObterHoraAtualServidor());
        $d2 = new DateTime($d2);
        $diff = $d1->diff($d2);
        if ($diff->y != 0){
            if ($diff->y == 1)
                $dataDiff = $diff->y . " ano";
            else
                $dataDiff = $diff->y . " anos";
        }elseif($diff->m != 0){
            if ($diff->m == 1)
                $dataDiff = $diff->m . " mês";
            else
                $dataDiff = $diff->m . " meses";
        }elseiF($diff->d != 0){
            if ($diff->d == 1)
                $dataDiff = $diff->d . " dia";
            else
                $dataDiff = $diff->d . " dias";
        }elseif ($diff->h != 0){
            if($diff->h == 1)
                $dataDiff = $diff->h . " hora";
            else
                $dataDiff = $diff->h . " horas";
        }elseif($diff->i != 0){
            if($diff->i == 1)
                $dataDiff = $diff->i . " minuto";
            else
                $dataDiff = $diff->i . " minutos";
        }else{
            $dataDiff = $diff->s . " segundos";
        }
        return $dataDiff;
    }

	static public function secondsToTime($seconds, $extenso = false, $abreviado = false)
	{
		if ($seconds <= 0) {
			if (!$extenso) {
				return "0 " . ROTULO_SEGUNDOS;
			} else {
				return "00:00:00";
			}
		}
		// extract hours
		$hours = floor($seconds / (60 * 60));

		// extract minutes
		$divisor_for_minutes = $seconds % (60 * 60);
		$minutes             = floor($divisor_for_minutes / 60);

		// extract the remaining seconds
		$divisor_for_seconds = $divisor_for_minutes % 60;
		$seconds             = ceil($divisor_for_seconds);
		if ($abreviado == true) {
			$tmp = "";
			if ($hours != "") {
				$tmp .= (int)$hours . " h ";
			}
			if ($minutes != "") {
				$tmp .= (int)$minutes . " min ";
			}
			if ($seconds != "") {
				$tmp .= (int)$seconds . " sec ";
			}
		} else if (!$extenso) {
			$tmp = "";
			if ($hours != "") {
				$tmp .= (int)$hours . " horas ";
			}
			if ($minutes != "") {
				$tmp .= (int)$minutes . " minutos ";
			}
			if ($seconds != "") {
				$tmp .= (int)$seconds . " segundos ";
			}
		} else {
			$tmp = "";
			$tmp .= str_pad($hours, 2, "0", STR_PAD_LEFT) . ":";
			$tmp .= str_pad($minutes, 2, "0", STR_PAD_LEFT) . ":";
			$tmp .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
		}

		// return the final array
		/*$obj = array(
		 "h" => (int) $hours,
		 "m" => (int) $minutes,
		 "s" => (int) $seconds,
	 );*/

		return $tmp;
	}

	static public function secondsToDays($seconds, $extenso = false)
	{
		$days             = floor($seconds / (24 * 60 * 60));
		$divisor_for_days = $seconds % (24 * 60 * 60);

		$divisor_for_hours = $divisor_for_days % (24 * 60 * 60);

		// extract hours
		$hours = floor($divisor_for_hours / (60 * 60));

		// extract minutes
		$divisor_for_minutes = $divisor_for_hours % (60 * 60);
		$minutes             = floor($divisor_for_minutes / 60);

		// extract the remaining seconds
		$divisor_for_seconds = $divisor_for_minutes % 60;
		$seconds             = ceil($divisor_for_seconds);

		if (!$extenso) {
			$tmp = "";
			if ($hours != "") {
				$tmp .= (int)$days . " dias ";
			}
			if ($hours != "") {
				$tmp .= (int)$hours . " horas ";
			}
			if ($minutes != "") {
				$tmp .= (int)$minutes . " minutos ";
			}
			if ($seconds != "") {
				$tmp .= (int)$seconds . " segundos ";
			}
		} else {
			$tmp = "";
			$tmp .= (int)$hours . ":";
			$tmp .= (int)$minutes . ":";
			$tmp .= (int)$seconds;
		}


		// return the final array
		/* $obj = array(
		  "h" => (int) $hours,
		  "m" => (int) $minutes,
		  "s" => (int) $seconds,
		  ); */

		return $tmp;
	}

	/**
	 * Recebe uma string de horas (formato HH:MM:SS) e a retorna o seu valor em segundos
	 *
	 * @param $horas string
	 *
	 * @return int
	 * */
	static public function HorarioParaSegundos($stringHorario)
	{
		sscanf($stringHorario, "%d:%d:%d", $horas, $minutos, $segundos);

		return ($horas * 3600 + $minutos * 60 + $segundos);
	}


	/**
	 * Função com a mesma definição da in_array, mas que funciona em arrays multidimensionais
	 *
	 * @param $needle   mixed   - Valor buscado
	 * @param $haystack array   - Array multidimensional
	 * @param $strict   boolean - Testa também o tipo da variável passada em $needle
	 * */
	static public function in_array_r($needle, $haystack, $strict = false)
	{
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param stdClass $portas
	 * @return string
	 */
	static public function BuscarIconePorta($portas)
	{

		$portas = json_decode(json_encode($portas));

		$entradas = str_pad(decbin($portas->entradas), 8, "0", STR_PAD_LEFT);
		$saidas   = str_pad(decbin($portas->saidas), 8, "0", STR_PAD_LEFT);

//		if ($portas->panico == 1 && $entradas[7] == 1 && $entradas[0] == 0 && $entradas[1] == 0 && $entradas[2] == 0 && $entradas[3] == 0 && $entradas[4] == 0 && $entradas[5] == 0 && $entradas[6] == 0) {
//			$icone = "assets/images/rota_meio_panico.png";
//		} else
		    if ($entradas[7] == 1) {
			$icone = $portas->icone_entrada1;
		} else if ($entradas[6] == 1) {
			$icone = $portas->icone_entrada2;
		} else if ($entradas[5] == 1) {
			$icone = $portas->icone_entrada3;
		} else if ($entradas[4] == 1) {
			$icone = $portas->icone_entrada4;
		} else if ($entradas[3] == 1) {
			$icone = $portas->icone_entrada5;
		} else if ($entradas[2] == 1) {
			$icone = $portas->icone_entrada6;
		} else if ($entradas[1] == 1) {
			$icone = $portas->icone_entrada7;
		} else if ($saidas[7] == 1) {
			$icone = $portas->icone_saida1;
		} else if ($saidas[6] == 1) {
			$icone = $portas->icone_saida2;
		} else if ($saidas[5] == 1) {
			$icone = $portas->icone_saida3;
		} else if ($saidas[4] == 1) {
			$icone = $portas->icone_saida4;
		} else if ($saidas[3] == 1) {
			$icone = $portas->icone_saida5;
		} else if ($saidas[2] == 1) {
			$icone = $portas->icone_saida6;
		} else if ($saidas[1] == 1) {
			$icone = $portas->icone_saida7;
		} else if ($saidas[0] == 1) {
			$icone = $portas->icone_saida8;
		}

		return $icone;
	}

	/**
	 * Busca parte de uma string nos valores do array e retorna sua chave
	 *
	 * @author Squall Robert
	 *
	 * @param  $needle   string - valor buscado
	 * @param  $haystack array 	- array que guarda o valor buscado
	 *
	 * @return mixed 			- chave do array
	 * */
	static public function ArraySearchStrPos($needle, $haystack)
	{
		foreach($haystack AS $key => $value) {
			if (strpos($value, $needle) !== false) return $key;
		}

		return false;
	}

	/**
	 * Verifica se as informações de entrada e saída se aplicam ao filtro selecionado
	 *
	 * @param string $entradas  Valor base10 das portas de entrada
	 * @param string $saidas    Valor base10 das portas de saida
	 * @param string $filtro    Valor base10 do filtro das portas
	 * @param string $tipo      Tipo do filtro a ser aplicado
	 *                          Valores aceitos: {"on_inclusivo", "on_exclusivo", "off"}
	 *                          Ignora a validação se um valor diferente for informado
	 *
	 * @return boolean
	 * */
	static public function FiltrarPosicoesPorta($entradas, $saidas, $filtro, $tipo)
	{
		if (!in_array($tipo, ["on_inclusivo",
							  "on_exclusivo",
							  "off"]) || $filtro == 0
		) {
			return true;
		}

		$binEntradas = str_pad(decbin($entradas), 8, "0", STR_PAD_LEFT);
		$binSaidas   = str_pad(decbin($saidas), 8, "0", STR_PAD_LEFT);
		$binFiltro   = str_pad(decbin($filtro), 8, "0", STR_PAD_LEFT);

		/*
		 * "on_exclusivo": somente as portas selecionadas no filtro podem estar ligadas
		 * */
		if ($tipo == "on_exclusivo") {
			if ($binFiltro != $binEntradas && $binFiltro != $binSaidas) {
				return false;
			} else {
				return true;
			}
		}

		while (($pos = strpos($binFiltro, "1", $offset)) !== false) {
			$offset             = $pos + 1;
			$portasFiltro[$pos] = $pos;
		}

		/*
		 * "on_inclusivo": todas as portas selecionadas devem estar ligadas
		 * */
		if ($tipo == "on_inclusivo") {
			foreach ($portasFiltro AS $posPortaFiltro) {
				if ($binEntradas[$posPortaFiltro] != "1" && $binSaidas[$posPortaFiltro] != "1") {
					return false;
				}
			}

			return true;
		}

		/*
		 * "off": todas as portas selecionadas devem estar desligadas
		 * */
		if ($tipo == "off") {
			foreach ($portasFiltro AS $posPortaFiltro) {
				if ($binEntradas[$posPortaFiltro] == "1" || $binSaidas[$posPortaFiltro] == "1") {
					return false;
				}
			}

			return true;
		}
	}

	static public function IsHolydayOrSunday($data)
	{
		if ($data == "") {
			throw new Exception("Argument 1 missing in function IsHolydayOrSunday");
		} else {
			if (strpos($data, "/") !== false) {
				$data = Conexao::PrepararDataBD($data);
			}

			if (strpos($data, ":") !== false) {
				list($data, $hora) = explode(" ", $data);
			}

			list($ano, $mes, $dia) = explode("-", $data);

			if (!checkdate($mes, $dia, $ano)) {
				throw new Exception("Invalid date");
			}
		}

		$pascoa     = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP // consulta http://www.php.net/manual/pt_BR/function.easter-date.php
		$dia_pascoa = date('j', $pascoa);
		$mes_pascoa = date('n', $pascoa);
		$ano_pascoa = date('Y', $pascoa);

		$feriados = array(
			// Datas fixas dos feriados nacionais brasileiros
			mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
			mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei nº 662, de 06/04/49
			mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
			mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência - Lei nº 662, de 06/04/49
			mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
			mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei nº 662, de 06/04/49
			mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
			mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei nº 662, de 06/04/49

			// dias que dependem do feriado de páscoa
			mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2ºfeira Carnaval
			mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3ºfeira Carnaval
			mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa
			mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Páscoa
			mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Christ
		);

		//$isHolydayOrSunday = ;

		return in_array(mktime(0,0,0,$mes, $dia, $ano), $feriados) ?: (date("w", mktime(0,0,0,$mes, $dia, $ano)) == 0)?true:false;
	}

    static public function IsHolyday($data)
    {
        if ($data == "") {
            throw new Exception("Argument 1 missing in function IsHolydayOrSunday");
        } else {
            if (strpos($data, "/") !== false) {
                $data = Conexao::PrepararDataBD($data);
            }

            if (strpos($data, ":") !== false) {
                list($data, $hora) = explode(" ", $data);
            }

            list($ano, $mes, $dia) = explode("-", $data);

            if (!checkdate($mes, $dia, $ano)) {
                throw new Exception("Invalid date");
            }
        }

        $pascoa     = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP // consulta http://www.php.net/manual/pt_BR/function.easter-date.php
        $dia_pascoa = date('j', $pascoa);
        $mes_pascoa = date('n', $pascoa);
        $ano_pascoa = date('Y', $pascoa);

        $feriados = array(
            // Datas fixas dos feriados nacionais brasileiros
            mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
            mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
            mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei nº 662, de 06/04/49

            // dias que dependem do feriado de páscoa
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2ºfeira Carnaval
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3ºfeira Carnaval
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Páscoa
            mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Christ
        );

        //$isHolydayOrSunday = ;

        return in_array(mktime(0,0,0,$mes, $dia, $ano), $feriados) ?true:false;
    }

	public static function BuscarDiaSemana($data, $abreviado = false)
	{
		if ($data == "") {
			throw new Exception("Argument 1 missing in function BuscarDiaSemana");
		} else {
			if (strpos($data, "/") !== false) {
				$data = Conexao::PrepararDataBD($data);
			}

			if (strpos($data, ":") !== false) {
				list($data, $hora) = explode(" ", $data);
			}

			list($ano, $mes, $dia) = explode("-", $data);

			if (!checkdate($mes, $dia, $ano)) {
				throw new Exception("Invalid date");
			}
		}

		switch (date("D", mktime(0, 0, 0, $mes, $dia, $ano))) {
			case "Mon":
				($abreviado)
					? $retorno = ROTULO_SEGUNDA_FEIRA_ABREVIADO
					: $retorno = ROTULO_SEGUNDA_FEIRA;
				break;

			case "Tue":
				($abreviado)
					? $retorno = ROTULO_TERCA_FEIRA_ABREVIADO
					: $retorno = ROTULO_TERCA_FEIRA;
				break;

			case "Wed":
				($abreviado)
					? $retorno = ROTULO_QUARTA_FEIRA_ABREVIADO
					: $retorno = ROTULO_QUARTA_FEIRA;
				break;

			case "Thu":
				($abreviado)
					? $retorno = ROTULO_QUINTA_FEIRA_ABREVIADO
					: $retorno = ROTULO_QUINTA_FEIRA;
				break;

			case "Fri":
				($abreviado)
					? $retorno = ROTULO_SEXTA_FEIRA_ABREVIADO
					: $retorno = ROTULO_SEXTA_FEIRA;
				break;

			case "Sat":
				($abreviado)
					? $retorno = ROTULO_SABADO_ABREVIADO
					: $retorno = ROTULO_SABADO;
				break;

			case "Sun":
				($abreviado)
					? $retorno = ROTULO_DOMINGO_ABREVIADO
					: $retorno = ROTULO_DOMINGO;
				break;
		}


		return $retorno;


	}

	/**
	 * Cálculo de adicional noturno no intervalo
	 *
	 * @author Squall Robert
	 *
	 * @param string $inicio 	Data/Hora inicial do intervalo a ser testado
	 * @param string $fim 		Data/Hora final do intervalo a ser testado
	 *
	 * @return int   			Valor em segundos de adicional noturno do intervalo
	 * */
	public static function CalcularAdicionalNoturno($inicio, $fim)
	{
		/*echo "<pre>";
		echo $inicio . " - " . $fim . "<br/>";
		echo "</pre>";*/

		//validação formato de data
		if (strpos($inicio, "/") !== false) {
			$inicio = Conexao::PrepararDataBD($inicio, null);
		}

		//validação formato de data
		if (strpos($fim, "/") !== false) {
			$fim = Conexao::PrepararDataBD($fim, null);
		}

		//validação formato de data
		if (strpos($inicio, ":") === false || strpos($fim, ":") === false || strlen($inicio) < 8 || strlen($fim) < 8) {
			return 0;
		}

		list($dataI, $horaI) = explode(" ", $inicio);
		list($dataF, $horaF) = explode(" ", $fim);
		$intervaloTotal = self::IntervaloDataHora($fim, $inicio);

		/*
		 * Não é utilizado a estrutura if/else por questões estéticas.
		 * Qualquer condição verdadeira deve retornar o resultado, o que gera o mesmo resultado de performance de um if/else
		 * */

		//Se o intervalo começa depois das 22 horas
		if ($horaI >= "22:00:00") {
			//Intervalo começa e termina no mesmo dia
			if ($dataI == $dataF) {
				return $intervaloTotal;
			}

			//Intervalo termina no dia seguinte ao início
			if (date("Y-m-d", strtotime("+1 day", strtotime($dataI))) == $dataF) {
				//Se o intervalo termina até as 05:00:00 do dia seguinte...
				if ($horaF <= "05:00:00") {
					return $intervaloTotal;
				}

				//Se o intervalo termina entre 05:00:00 e 22:00:00 do dia seguinte...
				if ($horaF > "05:00:00" && $horaF < "22:00:00") {
					return self::IntervaloDataHora($dataF . " 05:00:00", $inicio);
				}

				//Se o intervalo termina até 23:59:59 do dia seguinte...
				if ($horaF >= "22:00:00") {
					return self::IntervaloDataHora($dataF . " 05:00:00", $inicio) + self::IntervaloDataHora($fim, $dataF . " 22:00:00");
				}
			}

			//Se o intervalo termina mais de um dia após o dia seguinte do início
			$noturno = 0;
			$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataI)));
			while ($dataFinalAux < $dataF) {
				$noturno += 25199; //referente as 06:59 possíveis de adicional noturno num dia
				$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataFinalAux)));
			}

			$noturno += self::IntervaloDataHora($dataI . " 23:59:59", $inicio);

			if ($horaF <= "05:00:00") {
				$noturno += self::IntervaloDataHora($fim, $dataF . " 00:00:00");
			} else if ($horaF >= "22:00:00") {
				$noturno += self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00");
				$noturno += self::IntervaloDataHora($fim, $dataF . " 22:00:00");
			} else {
				$noturno += 18000;
			}

			return $noturno;
		}

		//Se o intervalo começa antes das 5 horas da manhã
		if ($horaI < "05:00:00") {
			//Intervalo começa e termina no mesmo dia
			if ($dataI == $dataF) {

				//Final no mesmo dia depois das 22:00:00, atravessando o intervalo não contabilizado
				if ($horaF > "22:00:00") {
					return self::IntervaloDataHora($dataF . " 05:00:00", $inicio) + self::IntervaloDataHora($fim, $dataI . " 22:00:00");
				}

				//Final no mesmo dia dentro do horário não contabilizado
				if ($horaF > "05:00:00") {
					return self::IntervaloDataHora($dataF . " 05:00:00", $inicio);
				}

				//O intervalo inteiro é dentro do horário de adicional noturno
				return $intervaloTotal;
			}

			//Tempo de adicional noturno do primeiro dia. Sempre será o mesmo valor inicial.
			$noturno = self::IntervaloDataHora($dataI . " 05:00:00", $inicio) + self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00");

			//Intervalo termina no dia seguinte ao dia inicial
			if (date("Y-m-d", strtotime("+1 day", strtotime($dataI))) == $dataF) {
				//Se o intervalo termina até as 05:00:00 do dia seguinte...
				if ($horaF <= "05:00:00") {
					return $noturno + self::IntervaloDataHora($fim, $dataF . " 00:00:00");
				}

				//Se o intervalo termina entre 05:00:00 e 22:00:00 do dia seguinte...
				if ($horaF > "05:00:00" && $horaF < "22:00:00") {
					return $noturno + self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00");
				}

				//Se o intervalo termina até 23:59:59 do dia seguinte...
				if ($horaF >= "22:00:00") {
					return $noturno + self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00") + self::IntervaloDataHora($fim, $dataF . " 22:00:00");
				}
			}

			//Se o intervalo termina mais de um dia após o dia inicial
			$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataI)));
			while ($dataFinalAux < $dataF) {
				$noturno += 25199; //referente as 06:59 possíveis de adicional noturno num dia
				$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataFinalAux)));
			}

			//$noturno += self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00");

			if ($horaF <= "05:00:00") {
				$noturno += self::IntervaloDataHora($fim, $dataF . " 00:00:00");
			} else if ($horaF >= "22:00:00") {
				$noturno += self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00");
				$noturno += self::IntervaloDataHora($fim, $dataF . " 22:00:00");
			} else {
				$noturno += 18000;
			}

			return $noturno;
		}

		//Se o intervalo começa fora mas termina dentro do horário de adicional noturno...
		if ($horaF > "22:00:00") {
			//O intervalo começa e termina no mesmo dia
			if ($dataI == $dataF) {
				return self::IntervaloDataHora($fim, $dataF . " 22:00:00");
			}

			//O intervalo termina no dia seguinte ao dia inicial
			if (date("Y-m-d", strtotime("+1 day", strtotime($dataI))) == $dataF) {
				return
					self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00") +
					self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00") +
					self::IntervaloDataHora($fim, 				  $dataF . " 22:00:00");
			}

			//Tempo de adicional noturno do primeiro dia.
			$noturno = self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00");

			//Se o intervalo termina mais de um dia após o dia inicial
			$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataI)));
			while ($dataFinalAux < $dataF) {
				$noturno += 25199; //referente as 06:59 possíveis de adicional noturno num dia
				$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataFinalAux)));
			}

			$noturno += self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00");
			$noturno += self::IntervaloDataHora($fim, $dataF . " 22:00:00");

			return $noturno;
		}

		//Intervalo começa fora do horário, mas termina dentro do horário do dia seguinte
		if ($horaF <= "05:00:00") {
			//O intervalo termina no dia seguinte ao dia inicial
			if (date("Y-m-d", strtotime("+1 day", strtotime($dataI))) == $dataF) {
				return
					self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00") +
					self::IntervaloDataHora($fim, 				  $dataF . " 00:00:00");
			}

			//Tempo de adicional noturno do primeiro dia.
			$noturno = self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00");

			//Se o intervalo termina mais de um dia após o dia inicial
			$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataI)));
			while ($dataFinalAux < $dataF) {
				$noturno += 25199; //referente as 06:59 possíveis de adicional noturno num dia
				$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataFinalAux)));
			}

			$noturno += self::IntervaloDataHora($fim, $dataF . " 00:00:00");

			return $noturno;
		}

		//O intervalo inicia e termina fora do horário de adicional noturno, mas em dias diferentes
		if ($dataI < $dataF) {
			//O intervalo termina no dia seguinte ao inicio
			if (date("Y-m-d", strtotime("+1 day", strtotime($dataI))) == $dataF) {
				return
					self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00") +
					self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00");
			}

			//Tempo de adicional noturno do primeiro dia.
			$noturno = self::IntervaloDataHora($dataI . " 23:59:59", $dataI . " 22:00:00");

			//Se o intervalo termina mais de um dia após o dia inicial
			$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataI)));
			while ($dataFinalAux < $dataF) {
				$noturno += 25199; //referente as 06:59 possíveis de adicional noturno num dia
				$dataFinalAux = date("Y-m-d", strtotime("+1 day", strtotime($dataFinalAux)));
			}

			$noturno += self::IntervaloDataHora($dataF . " 05:00:00", $dataF . " 00:00:00");

			return $noturno;
		}

		//Caso o intervalo inicie e termine fora do horário de adicional noturno, no mesmo dia, retorna 0
		return 0;
	}
}
