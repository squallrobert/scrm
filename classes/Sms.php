<?php
/**
 * Class Sms
 *
 * Classe responsável por enviar e controlar as mensagens enviadas por SMS
 *
 * @author Fernando em 04/02/15
 */
include_once(URL_FILE . 'classes/human_gateway_client_api/HumanClientMain.php');
include_once(URL_FILE . 'classes/human_gateway_client_api/service/HumanMultipleSend.php');
include_once(URL_FILE . 'classes/human_gateway_client_api/util/HumanConnectionHelper.php');
include_once(URL_FILE . 'classes/human_gateway_client_api/util/HumanHTTPHelper.php');
include_once(URL_FILE . 'classes/human_gateway_client_api/util/HumanResponse.php');
include_once(URL_FILE . "classes/Conexao.php");


class Sms
{
    private $conta;     // Dados de autenticação do serviço
    private $senha;     // Dados de autenticação do serviço
    private $idFranquia;// Franquia do usuário
    private $idUsuario; // Id do usuário

    //Números dos telefones que receberão a mensagem, Array ou String
    //Ex: 41 9999 9999, 4199999999, (41)9999-9999.
    private $destinatario;

    // Mensagem a ser enviada
    private $conteudo;

    //Usuário logado?
    private $autenticado;

    public function Sms($idUsuario = 0, $idFranquia = 0)
    {
        $this->idFranquia = $idFranquia;
        $this->idUsuario = $idUsuario;

        if ($idFranquia == "")
            $idFranquia = 0;

        $usuario = $this->AutenticarUsuario($idFranquia);

        if ($usuario)
            $this->autenticado = true;

        $this->conta = $usuario->conta;
        $this->senha = $usuario->senha;
    }

    /**
     * @return boolean
     */
    public function isAutenticado()
    {
        return $this->autenticado;
    }

    /**
     * @param boolean $autenticado
     */
    public function setAutenticado($autenticado)
    {
        $this->autenticado = $autenticado;
    }

    /**
     * @return mixed
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * @param mixed $destinatario
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;
    }

    /**
     * @return string
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * @param string $mensagem
     */
    public function setConteudo($mensagem)
    {
        $this->conteudo = $mensagem;
    }

    /**
     * Procura no banco as informações de login do usuário
     * @param $idFranquia
     * @return object
     */
    private function AutenticarUsuario($idFranquia)
    {
        $pdo = new Conexao();

        $sql = "SELECT conta, senha FROM sms_contas WHERE id_franquia = $idFranquia";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetch(PDO::FETCH_OBJ);

        return $rs;
    }

    /**
     * Envia mensagens, único método para envio único e múltiplo
     *
     * @return string
     */
    public function EnviarMensagens()
    {
        $parametro = "";        //corpo das mensagens
        $mensagem = Array();    //array para gravação no banco

        //Valida a mensagem e os números antes de tentar enviar a mensagem
        $validacao = $this->ValidarMensagem();

        if($validacao[1])
        {
            $id = $this->PesquisarIdValido();   //id válido para as mensagens
            $cont = $id;                        //variavel auxiliar

            if (is_array($this->destinatario))
            {
                //Monta o parametro do método de envio para cada telefone encontrado
                foreach($this->destinatario as $numero)
                {
                    $parametro .= "55" . $numero . ";" . $this->conteudo . ";" . $id . "\n";
                    $mensagem[$id]['id_franquia'] = $this->idFranquia;
                    $mensagem[$id]['id_msg'] = $id;
                    $mensagem[$id]['id_usuario'] = $this->idUsuario;
                    $mensagem[$id]['destinatario'] = $numero;
                    $mensagem[$id]['mensagem'] = $this->conteudo;
                    $mensagem[$id]['data_hora_enviado'] = date('Y-m-d H:i:s');
                    $id++;
                }
            }
            else
            {
                $parametro .= "55" . $this->destinatario . ";" . $this->conteudo . ";" . $id . "\n";
                $mensagem[$id]['id_franquia'] = $this->idFranquia;
                $mensagem[$id]['id_msg'] = $id;
                $mensagem[$id]['id_usuario'] = $this->idUsuario;
                $mensagem[$id]['destinatario'] = $this->destinatario;
                $mensagem[$id]['mensagem'] = $this->conteudo;
                $mensagem[$id]['data_hora_enviado'] = date('Y-m-d H:i:s');
            }

            // Autenticação na API
            $humanMultipleSend = new HumanMultipleSend($this->conta, $this->senha);

            // Envia as mensagens e recebe a resposta da API, callback do status final da mensagem ativo
            $response = $humanMultipleSend->sendMultipleList(HumanMultipleSend::TYPE_C, $parametro, 1);

            $respostaEnvio = "" ;
            $primeiro = true;   //Primeira linha da resposta é genérica

            foreach ($response as $resp) {
                if($primeiro)
                {
                    $respostaEnvio .=  $this->StatusMensagem($resp->getCode()) . "<br />";
                    $primeiro = false;
                }
                else
                {
                    $respostaEnvio .= "Mensagem ID: ".$cont." Status: ".$this->StatusMensagem($resp->getCode()) . "<br />";
                    $mensagem[$cont]['status_api'] = $resp->getCode();
                    $cont++;
                }
            }

            //Salva as mensagens no banco
            $this->SalvarMensagens($mensagem);
            return $respostaEnvio;
        }
        else
        {
            return $validacao[0];
        }

    }

    /**
     * Validação da mensagem
     * @return array(string, bool)
     */
    private function ValidarMensagem()
    {
        $msg = "";
        $valido = true;

        //Checagem se existe mais de um número como destino. Destrói qualquer caractere não numérico.
        if (is_array($this->destinatario))
        {
            foreach($this->destinatario as $numero)
            {
                $numero = preg_replace('/\D/', '', $numero);

                if ($numero != "")
                {
                    if (strlen($numero) > 13)
                    {
                        $msg .= "O número: " . $numero . " é longo demais. Informe apenas o código de área (sem o zero) e um número de até 11 dígitos. <br />";
                        $valido = false;
                    }
                }
                else
                {
                    $msg = "Existem números em branco, verifique e tente novamente. <br />";
                    $valido = false;
                    break;
                }
            }
        }
        else
        {
            $this->destinatario = preg_replace('/\D/', '', $this->destinatario);

            if ($this->destinatario != "")
            {
                if (strlen($this->destinatario) > 13)
                {
                    $msg .= "O número: " . $this->destinatario . " é longo demais. Informe apenas o código de área (sem o zero) e um número de até 11 dígitos. <br />";
                    $valido = false;
                }
            }
            else
            {
                $msg .= "O número não pode ser em branco, verifique e tente novamente. <br />";
                $valido = false;
            }
        }

        //Valida o tamanho da mensagem a ser enviada. Note que caracteres especiais só serão tratados no momento de salvar no banco
        $this->conteudo = trim($this->conteudo);
        if ($this->conteudo != "")
        {
            if(strlen($this->conteudo) > 150)
            {
                $msg .= "O conteúdo da mensagem é longo demais. O limite é de 150 caracteres.";
                $valido = false;
            }
        }
        else
        {
            $msg .= "O conteúdo da mensagem está em branco, verifique e tente novamente.";
            $valido = false;
        }


        return array($msg, $valido);
    }

    /**
     * Salva as mensagens enviadas no banco
     * @param $mensagens
     * @return bool
     */
    private function SalvarMensagens($mensagens)
    {
        //retur
        $pdo = new Conexao();

        foreach($mensagens as $mensagem)
        {
            //SALVA TODAS / descomentar if para salvar apenas enviadas
            //if ($mensagem['status_api'] == '000')
            //{
                //$sql = "INSERT INTO sms (`id_franquia`, `id_msg`, `id_usuario`, `destinatario`, `mensagem`, `data_hora_enviado`, `status_api`) VALUES (?,?,?,?,?,?,?)";
            $sql = "INSERT INTO sms 
                        (`id_franquia`
                        , `id_msg`
                        , `id_usuario`
                        , `destinatario`
                        , `mensagem`
                        , `data_hora_enviado`
                        , `status_api`) 
                        VALUES 
                        ('{$mensagem['id_franquia']}'
                        ,'{$mensagem['id_msg']}'
                        ,'{$mensagem['id_usuario']}'
                        ,'{$mensagem['destinatario']}'
                        ,'{$mensagem['mensagem']}'
                        ,'{$mensagem['data_hora_enviado']}'
                        ,'{$mensagem['status_api']}'
                        )";

                $stmt = $pdo->prepare($sql);
//                $stmt->bindParam(1,$mensagem['id_franquia'],PDO::PARAM_INT);
//                $stmt->bindParam(2,$mensagem['id_msg'],PDO::PARAM_INT);
//                $stmt->bindParam(3,$mensagem['id_usuario'],PDO::PARAM_INT);
//                $stmt->bindParam(4,$mensagem['destinatario'],PDO::PARAM_STR);
//                $stmt->bindParam(5,$mensagem['mensagem'],PDO::PARAM_STR);
//                $stmt->bindParam(6,$mensagem['data_hora_enviado'],PDO::PARAM_STR);
//                $stmt->bindParam(7,$mensagem['status_api'],PDO::PARAM_STR);
                $resposta = $stmt->execute();

                if($resposta === 1)
                {
                    return false;
                }
            //}
        }
        return true;
    }

    /** Retorna o próximo ID válido para ser usado nas mensagens
     * @return int
     */
    private function PesquisarIdValido()
    {
        $pdo = new Conexao();
        $sql = "SELECT id_msg from sms where id_franquia = $this->idFranquia ORDER BY id_msg DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);

        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_OBJ);

        $id_valido = $id->id_msg;

        return ($id_valido + 1);
    }


    /**
     * Verifica o status de uma mensagem específica ou lista de mensagens
     * @parameter $idMensagens (id ou array de ids)
     * @return string
     */
    public function VerificarStatusMensagem($idMensagens)
    {
        //POG para aceitar inteiros e arrays
        //Destroi qualquer caracter não numérico do ID
        $arrayIds = array();

        if (!is_array($idMensagens))
        {
            $idMensagens = preg_replace('/\D/', '', $idMensagens);
            $arrayIds[0] = $idMensagens;
        }
        else
        {
            for ($x = 0; $x < count($idMensagens); $x++)
            {
                $arrayIds[$x] = preg_replace('/\D/', '', $idMensagens[$x]);
            }
        }

        //Autenticação do usuário
        $humanMultipleSend = new HumanMultipleSend($this->conta, $this->senha);

        //Recebe a resposta de cada ID pesquisado
        $resposta = $humanMultipleSend->queryMultipleStatus($arrayIds);

        $respostaEnvio = "";
        $aux = 0;

        //Monta a string de resposta para cada ID pesquisado
        foreach ($resposta as $resp) {
            $respostaEnvio .= "Mensagem ID: ".$arrayIds[$aux]." Status: ".$this->StatusMensagem($resp->getCode()) . "<br />";
            $aux++;
        }

        return $respostaEnvio;
    }

    /**
     * Lista as mensagens enviadas pela franquia pesquisada, pesquisa todas as mensagens como padrão
     * @param $idFranquia
     * @return array(object)
     */
    public function ListarMensagens($idFranquia = null)
    {
        $pdo = new Conexao();
        $sql = "SELECT
                  usuario.nome,
                  sms.*
                FROM sms
                INNER JOIN usuario ON (usuario.id = sms.id_usuario)";

        if ($idFranquia > 0)
        $sql .= " WHERE sms.id_franquia = $idFranquia";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $rs;
    }

    /**
     * Atualiza os dados de callback recebidos pelo script
     * @param $idMsg
     * @param $idStatus
     */
    public function AtualizarCallback($idMsg, $idStatus)
    {
        $pdo = new Conexao();

        $data = date('Y-m-d H:i:s');

        $sql = "UPDATE sms
                SET data_hora_status = ?,
                status_callback = ?
                WHERE id_msg = $idMsg
                AND id_franquia = $this->idFranquia";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1,$data, PDO::PARAM_STR);
        $stmt->bindParam(2,$idStatus, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Trata a resposta da API
     *
     * @param $id_status
     * @return string
     */
    private function StatusMensagem($id_status)
    {

        switch($id_status)
        {
            case '000':
                $msg = "Mensagem enviada com sucesso!";
                break;

            case '002':
                $msg = "Mensagem cancelada com sucesso!";
                break;

            case '010':
                $msg = "Mensagem com conteúdo vazio.";
                break;

            case '011':
                $msg = "Corpo da mensagem inválido.";
                break;

            case '012':
                $msg = "Excesso de caracteres na mensagem.";
                break;

            case '013':
                $msg = "Destinatário incorreto.";
                break;

            case '014':
                $msg =  "Destinatário vazio.";
                break;

            case '015':
                $msg =  "Data de agendamento inválida.";
                break;

            case '016':
                $msg =  "ID muito grande.";
                break;

            case '017':
                $msg =  "URL de envio incorreta.";
                break;

            case '018':
                $msg =  "Remetente inválido.";
                break;

            case '021':
                $msg =  "Campo ID é obrigatório.";
                break;

            case '080':
                $msg =  "Mensagem com o mesmo ID já foi enviada.";
                break;

            case '100':
                $msg =  "Mensagem em fila para envio.";
                break;

            case '110':
                $msg =  "Mensagem enviada à operadora.";
                break;

            case '120':
                $msg =  "Confirmação da mensagem indisponível.";
                break;

            case '130':
                $msg =  "Mensagem entregue ao destinatário.";
                break;

            case '131':
                $msg =  "Mensagem bloqueada.";
                break;

            case '132':
                $msg =  "Mensagem bloqueada por filtro de spam.";
                break;

            case '133':
                $msg =  "Mensagem já cancelada.";
                break;

            case '134':
                $msg =  "Conteúdo da mensagem em análise.";
                break;

            case '135':
                $msg =  "Mensagem bloqueada por conteúdo proibido.";
                break;

            case '136':
                $msg =  "'Aggregate' inválido ou inativo.";
                break;

            case '140':
                $msg =  "Mensagem expirada.";
                break;

            case '141':
                $msg =  "Envio de mensagem internacional proibido.";
                break;

            case '145':
                $msg =  "Número de destino inativo.";
                break;

            case '150':
                $msg =  "Mensagem expirou na operadora.";
                break;

            case '160':
                $msg =  "Erro de rede da operadora.";
                break;

            case '161':
                $msg =  "Mensagem rejeitada pela operadora.";
                break;

            case '162':
                $msg =  "Mensagem cancelada ou bloqueada pela operadora.";
                break;

            case '170':
                $msg =  "Caracteres proibidos na mensagem.";
                break;

            case '171':
                $msg =  "Caracteres proibidos no número de destino.";
                break;

            case '172':
                $msg =  "Falta de parâmetros.";
                break;

            case '180':
                $msg =  "ID da mensagem não encontrado.";
                break;

            case '190':
                $msg =  "Erro desconhecido. (190)";
                break;

            case '200':
                $msg =  "Mensagens enviadas!";
                break;

            case '210':
                $msg =  "Mensagens na fila para envio, mas limite da conta foi atingido. Entre em contato com o suporte.";
                break;

            case '240':
                $msg =  "Arquivo vazio ou não enviado.";
                break;

            case '241':
                $msg =  "Arquivo muito grande.";
                break;

            case '242':
                $msg =  "Erro de leitura no arquivo.";
                break;

            case '300':
                $msg =  "Mensagens recebidas foram encontradas.";
                break;

            case '301':
                $msg =  "Nenhuma mensagem recebida foi encontrada.";
                break;

            case '400':
                $msg =  "Entidade salva.";
                break;

            case '900':
                $msg =  "Erro de autenticação, verifique seu usuário e senha.";
                break;

            case '901':
                $msg =  "Tipo de conta não suporta essa operação.";
                break;

            case '990':
                $msg =  "Limite da conta foi atingido. Entre em contato com o suporte.";
                break;

            case '998':
                $msg =  "Operação errada foi requerida.";
                break;

            case '999':
                $msg =  "Erro desconhecido. (999)";
                break;

            default:
                $msg = "Um erro desconhecido foi encontrado. Contate o suporte. (sem código)";
                break;
        }

        return $msg;
    }

}