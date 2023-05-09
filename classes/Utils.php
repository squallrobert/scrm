<?php

/**
 * Objeto com funções utilitárias utilizadas em várias áreas do sistema.
 *
 */
abstract class Utils
{
    /**
     * Trata um valor monetário para ser inserido num campo do tipo float do mysql
     *
     * @param string $valor Exemplo 1.123,45
     */
    static function TrataFloat($valor)
    {
        return str_replace(",", ".", str_replace(".", "", $valor));
    }

    /**
     * Formata um float com duas decimais, separador de decimal com vírgula e milhar com ponto.
     *
     * @param mixed $valor    (String ou float)
     * @param int   $decimais Número de decimais desejados
     */
    static function MaskFloat($valor, $decimais = 2)
    {
        if (isset($decimais) == false || $decimais == NULL || trim($decimais) == "" || $decimais < 0) {
            $decimais = 2;
        }

        return number_format($valor, $decimais, ',', '.');
    }

    /**
     * Função para calcular o próximo dia útil de uma data - Se a data for um dia útil retorna a data informada.
     *
     * @param string $data  Data no formato Y-m-d
     * @param string $saida Formato de saída - padrão "Y-m-d"
     *
     * @return string
     */
    static function ProximoDiaUtil($data, $saida = 'Y-m-d')
    {
        // Converte $data em um UNIX TIMESTAMP
        $timestamp = strtotime($data);

        // Calcula qual o dia da semana de $data
        // O resultado será um valor numérico:
        // 1 -> Segunda ... 7 -> Domingo
        $dia = date('N', $timestamp);

        // Se for sábado (6) ou domingo (7), calcula a próxima segunda-feira
        if ($dia >= 6) {
            $timestamp_final = $timestamp + ((8 - $dia) * 3600 * 24);
        } else {
            // Não é sábado nem domingo, mantém a data de entrada
            $timestamp_final = $timestamp;
        }

        return date($saida, $timestamp_final);
    }

    /**
     * Retorna o útimo dia do mês
     *
     * @param int $mes
     * @param int $ano
     */
    static function UltimoDiaMes($mes, $ano)
    {
        $dias   = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $ultimo = mktime(0, 0, 0, $mes, $dias, $ano);

        return date("j", $ultimo);
    }

    /**
     *
     * Retorna um objeto Datetime da data formatada
     *
     * @param string $data
     *
     * @return DateTime
     */
    static public function createDate($data)
    {
        if ($data == '') {
            return NULL;
        }

        list($data, $hora) = explode(" ", $data);
        $data = explode("/", $data);
        $data = $data[2] . "-" . $data[1] . "-" . $data[0] . " " . $hora;

        return new DateTime($data);
    }

    /**
     * Trata todos os requests feitos ao servidor
     *
     * @author Squall Robert
     * */
    static public function TratarRequest()
    {
        $toEscape = Array("SELECT ", "INSERT ", "DELETE ", "UPDATE ", "DROP ", " FROM ", "UNION", " DATABASE ", " TABLE ", " LIKE");

        if (is_array($_POST) && count($_POST) > 0) {
            foreach ($_POST AS $itemPost => $valorPost) {
                if (!is_array($valorPost)) {
                    $_POST[$itemPost] = trim(addslashes(str_ireplace($toEscape, chr(92), $valorPost)));
                } else {
                    $_POST[$itemPost] = Utils::TratarArray($valorPost);
                }
            }
        }

        if (is_array($_GET) && count($_GET) > 0) {
            foreach ($_GET AS $itemGet => $valorGet) {
                if (!is_array($valorGet)) {
                    $_GET[$itemGet] = trim(addslashes(str_ireplace($toEscape, chr(92), $valorGet)));
                } else {
                    $_GET[$itemGet] = Utils::TratarArray($valorGet);
                }
            }
        }

        if (is_array($_REQUEST) && count($_REQUEST) > 0) {
            foreach ($_REQUEST AS $itemRequest => $valorRequest) {
                if (!is_array($valorRequest)) {
                    $_REQUEST[$itemRequest] = trim(addslashes(str_ireplace($toEscape, chr(92), $valorRequest)));
                } else {
                    $_REQUEST[$itemRequest] = Utils::TratarArray($valorRequest);
                }
            }
        }
    }

    /**
     * Trata arrays nos requests, recursivamente
     *
     * @author Squall Robert
     * @param array $array Array a ser tratado
     *
     * @return array $array
     * */
    static public function TratarArray($array)
    {
        $toEscape = Array("SELECT ", "INSERT ", "DELETE ", "UPDATE ", "DROP ", " FROM ", "UNION", " DATABASE ", " TABLE ", " LIKE");

        if (is_array($array)) {
            foreach ($array AS $chave => $valor) {
                if (!is_array($valor)) {
                    $array[$chave] = trim(addslashes(str_ireplace($toEscape, chr(92), $valor)));
                } else {
                    $array[$chave] = self::TratarArray($valor);
                }
            }
        }
        return $array;
    }

    /**
     * Envio de email padrão
     *
     * @parametros['conteudo'] -> conteúdo html a ser enviado
     * @parametros['assunto'] -> Assunto do email
     * @parametros['email'][] -> emails
     * */
    static public function EnviarEmail($parametros)
    {
        $html =  "<html>";
        $html .= "\t<head>";
        $html .= "\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
        $html .= "\t\t<title>Usebens Seguradora</title>";
        $html .= "\t</head>";
        $html .= "\t<body>";
        $html .= "\t\t<table width=\"560\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:-8px;\">";
        $html .= "\t\t\t<tr>";
        $html .= "\t\t\t\t<td height=\"150\" valign=\"top\"><img src=\"http://www.usebens.com.br/app/news/assets/images/topo.png\" width=\"560\" height=\"117\" /></td>";
        $html .= "\t\t\t</tr>";
        $html .= "\t\t\t<tr>";
        $html .= "\t\t\t\t<td>";
        $html .= "\t\t\t\t\t<p style=\"color:#555555;font:20px 'Arial',sans-serif;line-height:26px;\">{$parametros['conteudo']}</p>";
        $html .= "\t\t\t\t</td>";
        $html .= "\t\t\t</tr>";
        $html .= "\t\t\t<tr>";
        $html .= "\t\t\t\t<td align=\"center\" height=\"80\" style=\"color:#555555;font:12px 'Arial',sans-serif;border-top:1px dotted #9c9c9c;\">Copyright 2015. Usebens Seguradora. Todos os direitos reservados.</td>";
        $html .= "\t\t\t</tr>";
        $html .= "\t\t</table>";
        $html .= "\t</body>";
        $html .= "</html>";

        //$dados['email']['gerencia'] = "francisco@sheephouse.com.br";
        $enviar = new Email();
        $enviar->EnviarEmail($parametros['assunto'],$html,$parametros['email']);

    }
    static public function SegundoParaHora($seconds, $extenso = false)
    {
        // extract hours
        $hours = floor($seconds / (60 * 60));

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);

        if (!$extenso) {
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
            $tmp .= (int)$hours . ":";
            $tmp .= str_pad((int)$minutes, 2, "0", STR_PAD_LEFT);
            if($seconds > 0) $tmp .= ":" .(int)$seconds;
        }


        // return the final array
        /*$obj = array(
         "h" => (int) $hours,
         "m" => (int) $minutes,
         "s" => (int) $seconds,
     );*/

        return $tmp;
    }

    /* creates a compressed zip file */
    static public function create_zip($files = array(),$destination = '',$overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite) { return false; }
        //vars
        $valid_files = array();
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if(count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if($zip->open($destination,($overwrite && file_exists($destination)) ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,pathinfo($file,PATHINFO_BASENAME));
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        }
        else
        {
            return false;
        }
    }

    // Sign a URL with a given crypto key
    // Note that this URL must be properly URL-encoded
    static public function signUrl($myUrlToSign, $privateKey)
    {
        // parse the url
        $url = parse_url($myUrlToSign);

        $urlPartToSign = $url['path'] . "?" . $url['query'];

        // Decode the private key into its binary format
        $decodedKey = base64_decode(str_replace(array('-', '_'), array('+', '/'), $privateKey));

        // Create a signature using the private key and the URL-encoded
        // string using HMAC SHA1. This signature will be binary.
        $signature = hash_hmac("sha1",$urlPartToSign, $decodedKey,  true);

        $encodedSignature = str_replace(array('+', '/'), array('-', '_'), base64_encode($signature));

        return $myUrlToSign."&signature=".$encodedSignature;
    }

    static public function CorteVar($string, $corte)
    {
        if($corte == '') return $string;
        if(strlen($string) > $corte)
            return substr($string,0,$corte) . '...';
        else
            return  $string;
    }
    /**
     * Gera uma senha aleatoria criptografada com MD5
     * @param int $tamanho
     * @param bool $maiusculas
     * @param bool $minusculas
     * @param bool $numeros
     * @param bool $simbolos
     * @return string
     */

    public static function gerarSenha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
        $nu = "0123456789"; // $nu contem os números
        $si = "!@#$%¨&*()_+="; // $si contem os símbolos

        if ($maiusculas){
            // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($ma);
        }

        if ($minusculas){
            // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($mi);
        }

        if ($numeros){
            // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($nu);
        }

        if ($simbolos){
            // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($si);
        }

        // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
        return substr(str_shuffle($senha),0,$tamanho);
    }
    static function BuscarNomeMes($numMes)
    {
        switch ($numMes)
        {
            case 1:
                return 'Janeiro';
                break;
            case 2:
                return 'Fevereiro';
                break;
            case 3:
                return 'Março';
                break;
            case 4:
                return 'Abril';
                break;
            case 5:
                return 'Maio';
                break;
            case 6:
                return 'Junho';
                break;
            case 7:
                return 'Julho';
                break;
            case 8:
                return 'Agosto';
                break;
            case 9:
                return 'Setembro';
                break;
            case 10:
                return 'Outubro';
                break;
            case 11:
                return 'Novembro';
                break;
            case 12:
                return 'Dezembro';
                break;
        }
    }
    public static function CompararDatasIntervalo($data_atual,$data_posicao,$intervalo)
    {
        list($da,$ho) =  explode(" ",$data_atual);
        list($y,$m,$d) = explode("-",$da);
        list($hor,$min,$seg) = explode(":",$ho);
        $rs = date('Y-m-d H:i:s', mktime($hor - $intervalo, $min , $seg, $m, $d, $y));
//       echo  strtotime($data_posicao) - strtotime($rs);
//       echo "<br>";
        return (strtotime($data_posicao) < strtotime($rs)) ? true : false;
    }

    public static function FormatarTelefone($ddd,$numero)
    {
        if (!strpos($numero,'-')){
            $formatado = $numero ? "(".$ddd.")".substr($numero,-13,-4)."-".substr($numero,-4):" ";
        }else{
            $formatado = $numero ? "(".$ddd.")".$numero : " ";
        }
        return $formatado;
    }
    static function converterMaiusculoSemAcento($str)
    {
        $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');
        $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');

        return strtoupper(str_replace($comAcentos, $semAcentos, $str));
    }

    public static function TratarNomes($texto){
        return htmlentities(str_replace('<br>','',str_replace('/r','',str_replace('/n','',$texto))));
    }
    public static  function  DebugSistema($msg){
        $msg["debug"]["SESSION"]['PERMISSAO'] = '';
        $enviar = new Email();
        $html = '<pre>';
        $html .= print_r($msg,1);
        $html .= '</pre>';
        $destinatarios[] = 'felipe@linkmonitoramento.com.br';
        $destinatarios[] = 'francisco@linkmonitoramento.com.br';
        $enviar->EnviarEmail("DEBUG - LINK REPORT - ".strtoupper($msg["debug"]["modulo"]), $html, $destinatarios);
    }

    public static function IntervaloEntreDatas($dataIni,$dataFim)
    {
        if($dataIni){
            $firstDate  = new DateTime($dataIni);
            $secondDate = new DateTime($dataFim);
            $intvl = $firstDate->diff($secondDate);

            $stringAno = $intvl->y > 1 ? 'Anos' : 'Ano';
            $stringMes = $intvl->m > 1 ? 'Meses' : 'Mês';
            $stringDia = $intvl->d > 1 ? 'Dias' : 'Dia';
            $anos = $intvl->y > 0 ? $intvl->y.' '.$stringAno.',' : '';
            $meses = $intvl->m > 0 ? $intvl->m.' '.$stringMes.',' : '';
            $dias = $intvl->d > 0 ? $intvl->d.' '.$stringDia : '';

            if ($anos != 0 || $meses != 0 || $dias != 0)
            {
                $espacoTempo = $anos." ".$meses." ".$dias;
            }else
            {
                $espacoTempo = "0 dia";
            }
            if($intvl->y > 2 && $intvl->m > 0 && $intvl->d > 0){
                $cor = 'btn-danger';
                $chek = 0;
            }else if ($intvl->y > 2 && $intvl->m > 0){
                $cor = 'btn-danger';
                $chek = 0;
            }else if ($intvl->y > 2 && $intvl->d > 0){
                $cor = 'btn-danger';
                $chek = 0;
            } else{
                $cor = 'btn-success';
                $chek = 1;
            }
            $resultado = [$espacoTempo,$cor,$chek];
            return $resultado;
        }else{
            $cor = 'btn-danger';
            $chek = 1;
            $espacoTempo = 'Sem registro de troca da bateria interna';
            $resultado = [$espacoTempo,$cor,$chek];
            return $resultado;
        }

    }
    public static  function PegarVetorTagIt( $value ) {

        // Because the $value is an array of json objects
        // we need this helper function.

        // First check if is not empty
        if( empty( $value ) ) {

            return $output = array();

        } else {

            // Remove squarebrackets
            $value = str_replace( array('[',']') , '' , $value );

            // Fix escaped double quotes
            $value = str_replace( '\"', "\"" , $value );

            // Create an array of json objects
            $value = explode(',', $value);

            // Let's transform into an array of inputed values
            // Create an array
            $value_array = array();

            // Check if is array and not empty
            if ( is_array($value) && 0 !== count($value) ) {

                foreach ($value as $value_inner) {
                    $value_array[] = json_decode( $value_inner );
                }

                // Convert object to array
                // Note: function (array) not working.
                // This is the trick: create a json of the values
                // and then transform back to an array
                $value_array = json_decode(json_encode($value_array), true);

                // Create an array only with the values of the child array
                $output = array();

                foreach($value_array as $value_array_inner) {
                    foreach ($value_array_inner as $key=>$val) {
                        $output[] = $val;
                    }
                }

            }

            return $output;

        }

    }
}