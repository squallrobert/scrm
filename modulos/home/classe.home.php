<?php


class Home
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
    public function ContadoresPizza($idGrupo)
    {
        $pdo = $this->getConexao();
        $sql = "
        SELECT COUNT(*) as data,ocorrencias_tipo.id,ocorrencias_tipo.nome as label
         FROM ocorrencias
        INNER JOIN grupo ON (ocorrencias.id_grupo = grupo.id)
        INNER JOIN ocorrencias_tipo ON (ocorrencias.id_tipo_ocorrencia = ocorrencias_tipo.id)
        WHERE ocorrencias.excluido IS NULL
        AND ocorrencias.despachada IS NULL
        AND (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
        GROUP BY ocorrencias_tipo.id,ocorrencias_tipo.nome
        LIMIT 5
		";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $x = 0;
//        $colors = array('color.success._500','color.primary._500','color.info._500','color.danger._500','color.fusion._500');
//        $colors = ["#FF0000","#FFFF00","#00FF00"];
        foreach($rs as $row)
        {
            $vetor[$x]['label'] = $row['label'];
            $vetor[$x]['data'] = $row['data'];
//            $vetor[$x]['color'] = $colors[$x];
            $x++;
        }
        return $vetor;
    }
    public function OccorenciasMes($idGrupo)
    {
        $data = date("Ymd", mktime(0, 0, 0,  date('m') - 6,  date('d'),  date('Y')));
        $pdo = $this->getConexao();
        $sql = "
        SELECT COUNT(*) as `value`,CONCAT(MONTH(ocorrencias.data_hora_cadastro),'-',YEAR (ocorrencias.data_hora_cadastro) )as label
         ,YEAR (ocorrencias.data_hora_cadastro) as ano,MONTH(ocorrencias.data_hora_cadastro) as mes
         FROM ocorrencias
        INNER JOIN grupo ON (ocorrencias.id_grupo = grupo.id)
        INNER JOIN ocorrencias_tipo ON (ocorrencias.id_tipo_ocorrencia = ocorrencias_tipo.id)
        WHERE ocorrencias.excluido IS NULL
        AND ocorrencias.despachada IS NULL
        AND ocorrencias.data_hora_cadastro >= $data
        AND (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
        GROUP BY MONTH(ocorrencias.data_hora_cadastro),YEAR (ocorrencias.data_hora_cadastro)
        ORDER BY ocorrencias.data_hora_cadastro ASC
		";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $vetor = array();
        foreach($rs as $row)
        {
            switch ($row['mes']){
                case"1":
                    $row['label'] = 'Janeiro - ' . $row['ano'];
                    break;
                case"2":
                    $row['label'] = 'Fevereiro - '. $row['ano'];
                    break;
                case"3":
                    $row['label'] = 'Março - '. $row['ano'];
                    break;
                case"4":
                    $row['label'] = 'Abril - '. $row['ano'];
                    break;
                case"5":
                    $row['label'] = 'Maio - '. $row['ano'];
                    break;
                case"6":
                    $row['label'] = 'Junho - '. $row['ano'];
                    break;
                case"7":
                    $row['label'] = 'Julho - '. $row['ano'];
                    break;
                case"8":
                    $row['label'] = 'Agosto - '. $row['ano'];
                    break;
                case"9":
                    $row['label'] = 'Setembro - '. $row['ano'];
                    break;
                case"10":
                    $row['label'] = 'Outubro - '. $row['ano'];
                    break;
                case"11":
                    $row['label'] = 'Novembro - '. $row['ano'];
                    break;
                case"12":
                    $row['label'] = 'Dezembro - '. $row['ano'];
                    break;
            }

            $vetor[] = $row;
        }


        return $vetor;
    }
    public function ContadoresBarraDia($idGrupo)
    {
        $data = date("Ymd", mktime(0, 0, 0,  date('m') - 12,  date('d'),  date('Y')));
        $pdo = $this->getConexao();
        $sql = "
        SELECT COUNT(*) as total, DATE(ocorrencias.data_hora_cadastro) as rotulo
         FROM ocorrencias
        INNER JOIN grupo ON (ocorrencias.id_grupo = grupo.id)
        INNER JOIN ocorrencias_tipo ON (ocorrencias.id_tipo_ocorrencia = ocorrencias_tipo.id)
        WHERE ocorrencias.excluido IS NULL
        AND ocorrencias.despachada IS NULL
        AND ocorrencias.data_hora_cadastro >= $data
        AND (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
        GROUP BY DATE(ocorrencias.data_hora_cadastro)
		";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);


        foreach($rs as $row)
        {
            $timestamp =str_pad(strtotime($row['rotulo']) ,13  , '0' , STR_PAD_RIGHT);

            $vetor[] = [$timestamp,(int) $row['total']];
        }
        return $vetor;
    }
    public function ContadorOcorrencias($idGrupo)
    {
        $pdo = $this->getConexao();
        $sql = "
        SELECT COUNT(*) as total,ocorrencias.id_ocorrencia_status FROM ocorrencias
        INNER JOIN grupo ON (ocorrencias.id_grupo = grupo.id)
        INNER JOIN ocorrencias_tipo ON (ocorrencias.id_tipo_ocorrencia = ocorrencias_tipo.id)
        WHERE ocorrencias.excluido IS NULL
        AND ocorrencias.despachada IS NULL
        AND (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
        GROUP BY ocorrencias.id_ocorrencia_status
		";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $vetor['Abertas'] = 0;
        $vetor['Despachadas'] = 0;
        $vetor['Atendimentos'] = 0;
        $vetor['Local'] = 0;
        $vetor['Finalizadas'] = 0;

        foreach($rs as $linha)
        {
            $nome = '';
            $total = ($linha['total'] == "") ? 0: $linha['total'];
            switch ($linha['id_ocorrencia_status'])
            {
                case 1:
                    $nome = 'Abertas';
                    break;
                case 2:
                    $nome = 'Despachadas';
                    break;
                case 3:
                    $nome = 'Atendimentos';
                    break;
                case 4:
                    $nome = 'Local';
                    break;
                case 5:
                    $nome = 'Finalizadas';
                    break;
            }

            $vetor[$nome] = $total;
        }
        return $vetor;
    }
    public function VerificarNaoLidos($idUsuario, $tipo)
    {
        $pdo = new Conexao();
        $sql = "SELECT COUNT(*) AS total FROM log_atualizacao_usuario WHERE id_usuario = $idUsuario AND tipo = '$tipo'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if($resultado['total'] > 0)
            return 'false';
        else
            return 'true';
    }
    public function getTotalComunicadosNaoLidos($idUsuario){
        $pdo = $this->getConexao();
        $sql= "SELECT
               COUNT(*) AS total
               FROM
               comunicados
               INNER JOIN comunicados_usuario ON (comunicados.id = comunicados_usuario.id_comunicado)
			   LEFT JOIN erp_usuario ON (comunicados_usuario.id_usuario = erp_usuario.id)
               WHERE comunicados_usuario.id_usuario = $idUsuario
               AND comunicados.data_cadastro > erp_usuario.data_hora_cadastro 
               AND comunicados_usuario.data_leitura IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_COLUMN);
        return $dados;
    }

    public function SalvarPanelsUsuario($params){
        $pdo = $this->getConexao();
        $sql = " INSERT INTO preferencia_panel_usuario SET
                    id_usuario = :id_usuario,
                    objeto = :objeto
                    ON DUPLICATE KEY 
                    UPDATE objeto = :objeto ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id_usuario", $params['id_usuario'], PDO::PARAM_STR);
        $stmt->bindParam(":objeto", $params['objeto'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function ListarPreferenciasPanel($idUsuario){
        $pdo = $this->getConexao();
        $sql= "SELECT
               objeto
               FROM
               preferencia_panel_usuario
               WHERE id_usuario = {$idUsuario}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_COLUMN);
        return $dados;
    }

    public function InformacoesPanel($id){
        $pdo = $this->getConexao();
        $sql= "SELECT
               id,
               nome, 
               color, 
               font_awesome,
               url
               FROM
               panels
               WHERE id = {$id}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados;
    }

}