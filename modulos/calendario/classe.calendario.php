<?php
class Calendario
{
    private $conexao;

    public function setConexao($arg)
    {
        $this->conexao = $arg;
    }

    public function getConexao()
    {
        return $this->conexao;
    }

    public function __construct($conexao = "")
    {
        if ($conexao) {
            $this->conexao = $conexao;
        } else {
            $this->conexao = new Conexao();
        }
    }

    public function ListarEventos($idGrupo, $param = array())
    {
        //      DATE_ADD(CONCAT(servicos.data_prevista,' ',servicos.hora_prevista), INTERVAL 1 HOUR) as end,
        $pdo = $this->getConexao();
        $sql = "
        	SELECT 
				servicos.*,
        	     CONCAT(servicos.data_prevista,' ',servicos.hora_prevista) as end,
			     corretor.nome as nome_corretor,
			     fotografo.nome as nome_fotografo,
			     fotografo.foto ,
			     grupo.nome as nome_cliente ,
			     cliente.observacao_dados as obs_cliente ,
			     corretor.foto  as foto_corretor,
			     endereco.`id` as id_endereco,
                 endereco.`logradouro` as endereco,
                 endereco.`numero`,
                 endereco.`complemento`,
                 endereco.`bairro`,
                 endereco.`cidade`,
                 endereco.`estado`,
                 endereco.`cep`,
                 endereco.`referencia`,
			     servicos_status.nome as nome_status,
			     (SELECT SUM(valor) FROM servicos_itens WHERE servicos_itens.id_servico = servicos.id) as valor,
			     (SELECT SUM(valor_fornecedor) FROM servicos_itens WHERE servicos_itens.id_servico = servicos.id) as valor_fornecedor
			FROM servicos
            INNER JOIN servicos_status ON (servicos_status.id = servicos.status)
            INNER JOIN usuario as corretor ON (corretor.id = servicos.id_corretor)
            INNER JOIN grupo ON (grupo.id = corretor.id_grupo)
            INNER JOIN usuario as fotografo ON (fotografo.id = servicos.id_fotografo)
            INNER JOIN endereco  ON (endereco.id = servicos.id_endereco)
            LEFT JOIN cliente ON (grupo.id = cliente.id_grupo)
        ";

        $sql .= "
			WHERE servicos.excluido IS NULL
		";
        if (!empty($idGrupo)) $sql .= " AND (grupo.id = {$idGrupo} OR grupo.arvore LIKE '%;$idGrupo;%')";
        if (($param['status'])) $sql .= " AND servicos.status = {$param['status']}";
        if (($param['fotografo'])) $sql .= " AND fotografo.id = {$param['fotografo']}";
        if (($param['data_hora_inicio'])) $sql .= " AND servicos.data_prevista >='{$param['data_hora_inicio']}' AND servicos.data_prevista <= '{$param['data_hora_fim']}'";
        if (($param['cancelados'])) $sql .= " AND servicos.status != 4";
        if (($param['status_notin'])) $sql .= " AND servicos.status NOT IN({$param['status_notin']})";
        if (($param['busca'])) $sql .= " AND (endereco.logradouro LIKE '%{$param['busca']}%' OR endereco.bairro LIKE '%{$param['busca']}%' OR corretor.nome LIKE '%{$param['busca']}%' OR fotografo.nome LIKE '%{$param['busca']}%' OR cliente.nome LIKE '%{$param['busca']}%' OR cliente.nome_fantasia LIKE '%{$param['busca']}%')";

//        echo $sql ;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $linha = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($linha)) {
            foreach ($linha as $key => $item) {
                $endereco = $item['endereco'] . ' , ' . $item['numero'] . ' ' . $item['bairro'] . ' ' . $item['cidade'] . ' ' . $item['estado'];
                $result[$key]['id'] = $item['id'];
                $result[$key]['start'] = $item['data_prevista'] . " " . $item['hora_prevista'];
                $result[$key]['end'] = $item['end'] ;
                $result[$key]['title'] = $endereco ." - ". $item['nome_corretor'] ;
                $result[$key]['color'] = ServicosStatus::GetStatusColor($item['status']);
//                $result[$key]['display'] = "background";
//                $result[$key]['ClassName'] = "".ServicosStatus::GetStatus($item['status']);
                $result[$key]['overlap'] = "false";
//                $result[$key]['allDay'] = "true";
            }
        }
        return $result;
    }
}