<?php
include_once(URL_FILE . "classes/ConexaoLog.php");
/**
 * @author Squall Robert
 * @copyright 2013
 */

class LogAcesso
{
    public $pagina;
    public $ip ;
    public $usuario;
    public $usuario_nome;
    public $dados;
    public $agrupamento;
    public $data_hora;
    public $pdo;
    public $aplicativo;
    public $contador;
    public $modulos_excecao = ['listar_contador','listar_ultimas','contador_diario','verificar_saldo','home','ajax_listar_log_acesso_usuarios','listar_log_acesso_usuarios','visualizar_log'];
    public $id_fuso_horario = "America/Sao_Paulo";

    public function __construct($aplicativo = 1)
    {
        $this->pagina = $_SERVER['PHP_SELF'] . $_SERVER['QUERY_STRING'];
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->usuario = $_SESSION['usuario']['id'];
        $this->usuario_nome = $_SESSION['usuario']['nome'];
        $this->dados = json_encode($_REQUEST);
        $this->contador = count($_REQUEST);
        $this->data_hora = ConexaoLog::PrepararDataBD(date('d/m/Y H:i:s'),$this->id_fuso_horario);
        $this->aplicativo = $aplicativo;
        $this->agrupamento = $this->pagina . "\n" . $this->ip . "\n" . $this->usuario . "\n" .$this->usuario_nome . "\n" . $this->dados . "\n" . $this->data_hora. "\n\n";
    }
    public function Gravar($acao = "")
    {
        if(array_search($acao,$this->modulos_excecao) === false)
            if($this->usuario_nome != '')
                 if($this->contador > 1)
                     $this->GravarLogBanco();
    }
    public function GravarLogTxt($caminho)
    {
        $ponteiro = fopen ($caminho, "a");
        fwrite($ponteiro, $this->agrupamento);
        fclose ($ponteiro);
    }
    public function GravarLogBanco()
    {
        $this->pdo = new ConexaoLog();
        $sql    = 'INSERT INTO log_acesso_usuarios (`id_usuario`,`nome_usuario`,`pagina`,`ip`,`dados`,`data_hora`,`aplicativo`) VALUES (?,?,?,?,?,?,?)';
        $stmt   = $this->pdo->prepare($sql);
        $stmt->bindParam(1,$this->usuario,PDO::PARAM_INT);
        $stmt->bindParam(2, substr($this->usuario_nome, 0, 45),PDO::PARAM_STR);
        $stmt->bindParam(3,$this->pagina,PDO::PARAM_STR);
        $stmt->bindParam(4,$this->ip,PDO::PARAM_STR);
        $stmt->bindParam(5,$this->dados,PDO::PARAM_STR);
        $stmt->bindParam(6,$this->data_hora,PDO::PARAM_STR);
        $stmt->bindParam(7,$this->aplicativo,PDO::PARAM_INT);
        $rs = $stmt->execute();
        return $rs ;
    }

}

?>