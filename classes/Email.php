<?
include_once(URL_FILE ."classes/Mail/Phpmailer.php");
include_once(URL_FILE ."classes/Mail/Smtp.php");

class Email extends PHPMailer
{

    public $remetenteNome  = EMAIL_NOME;
    public $remetenteEmail = EMAIL_REMETENTE;
    public $remetenteSenha = EMAIL_SENHA;
    public $hostEmail= EMAIL_HOST_SMTP;
    public $porta= EMAIL_PORTA_SMTP;
    public $Secure= EMAIL_SEGURANCA;


    public function EnviarEmail($assunto, $mensagem, $destinatarios, $nomeTemp = array(), $nomeAnexo = array(), $destinatariosOcultos = array(), $confirmaLeitura = false)
    {
        $this->IsSMTP();                            //send via SMTP
        $this->Host = $this->hostEmail;             //seu servidor SMTP
        $this->SMTPAuth = true;                     //'true' para autenticação
        $this->Username = $this->remetenteEmail;    //usuário de SMTP
        $this->Password = $this->remetenteSenha;    //senha de SMTP
//        $this->SMTPDebug = 2; //para debugar

        $this->SMTPOptions = array
        (
            'ssl' => array
            (
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
//        $this->SMTPSecure = "ssl";
        $this->AllowEmpty = true;
        $this->SMTPSecure = $this->Secure;
        $this->Port = $this->porta;
//        $this->SMTPDebug  = 2;
        $this->ContentType = 'text/html; charset=utf-8\r\n';

//        $this->SMTPSecure = 'tls';
//        $this->Port = 587;

        //coloque aqui o seu correio, para que a autenticação não barre a mensagem
        $this->From = $this->remetenteEmail;//"sistema@sheephouse.com.br";
        $this->FromName = $this->remetenteNome;//"Usebens Seguradora";
        //aqui você coloca o endereço de quem está enviando a mensagem pela sua página
        $this->WordWrap = 50; // Definição de quebra de linha
        $this->IsHTML = true; // envio como HTML se 'true'

        if($confirmaLeitura && $this->remetenteEmail != "fotografia@sheephouse.com.br")
            $this->ConfirmReadingTo = $this->remetenteEmail;
        if (count($destinatariosOcultos))
        {
            foreach ($destinatariosOcultos as $enderecos)
            {
                $this->AddBCC($enderecos);
            }
        }
        if(is_array($destinatarios))
        {
            foreach($destinatarios as $destinatario)
                $this->AddAddress($destinatario);
        }
        else
        {
            $this->AddAddress($destinatarios);
        }

        $qtdAnexos = count($nomeTemp);
        if($qtdAnexos > 0 )
        {
            for($i = 0;$i < $qtdAnexos; $i++ )
            {
                $this->AddAttachment($nomeTemp[$i],$nomeAnexo[$i]);
            }
        }

        $this->Subject = $assunto;
        $this->Body = $mensagem;
        $this->AltBody = "Para mensagens somente texto";

        if($this->Send())
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    public static function EmailValido($email)
    {
        if(!preg_match('/^[a-zA-Z0-9\-\_\.]+@[a-zA-Z0-9\-]+[a-zA-Z0-9]+\.[a-zA-Z]+(\.[a-zA-Z]+){0,3}$/', $email))
        {
            return false;
        }
        return true;
    }


}

?>
