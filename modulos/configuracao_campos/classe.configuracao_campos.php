<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 09:12
 */
class ConfiguracaoCampos{
    private $conexao;

    /**
     * @return mixed
     */
    public function getConexao()
    {
        return $this->conexao;
    }

    /**
     * @param mixed $conexao
     */
    public function setConexao($conexao)
    {
        $this->conexao = $conexao;
    }

    public function __construct($conexao = null)
    {
        if ($conexao) {
            $this->conexao = $conexao;
        } else {
            $this->conexao = new Conexao();
        }
    }
    
    public function ListarColunasDisponiveis($notIn,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
    {

        $pdo = $this->getConexao();
        $joins = '';

        $where = "
			WHERE configuracao_campos.id > 0
		";

        if($notIn != '') $where .=" AND id NOT IN($notIn)";

        if($busca != "") $where .= " AND (nome LIKE :busca)";

        $sql = "
			SELECT 
				configuracao_campos.*
			FROM configuracao_campos
			$joins
			$where
		";

        if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY configuracao_campos.id DESC";
        $sql .= " LIMIT :offset,:limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":offset",$numeroInicioRegistro,PDO::PARAM_INT);
        $stmt->bindParam(":limit",$numeroRegistros,PDO::PARAM_INT);

        if($busca != "") {
            $busca = "%".$busca."%";
            $stmt->bindParam(":busca",$busca,PDO::PARAM_STR);
        }

        $stmt->execute();
        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $linhas;
    }

    public function ListarColunasVinculadas($idModulo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
    {

        $pdo = $this->getConexao();
        $joins = '
        INNER JOIN map_configuracao_relatorios_campos ON (map_configuracao_relatorios_campos.id_configuracao_campos = configuracao_campos.id)
        INNER JOIN map_modulo ON(map_modulo.id = map_configuracao_relatorios_campos.id_modulo)
        ';

        $where = "
			WHERE map_configuracao_relatorios_campos.id_modulo = '$idModulo'
		";

        if($busca != "") $where .= " AND (nome LIKE :busca) ";

        $sql = "
			SELECT 
				configuracao_campos.*,
				map_configuracao_relatorios_campos.id AS id_relatorio
			FROM configuracao_campos
			$joins
			$where
		";

        $sql .=" ORDER BY configuracao_campos.nome ASC";
        $stmt = $pdo->prepare($sql);

        if($busca != "") {
            $busca = "%".$busca."%";
            $stmt->bindParam(":busca",$busca,PDO::PARAM_STR);
        }
        $stmt->execute();
        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $linhas;
    }
}