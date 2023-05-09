<?
class AcaoUsuarioTipo
{
	private $id;
	private $id_usuario_tipo;
	private $id_acao;
	private $conexao;

	public function setId($arg)
	{
		$this->id = $arg;
	}
 	
	public function getId()
	{
		return $this->id;
	}
 	
	public function setIdUsuarioTipo($arg)
	{
		$this->id_usuario_tipo = $arg;
	}
 	
	public function getIdUsuarioTipo()
	{
		return $this->id_usuario_tipo;
	}
 	
	public function setIdAcao($arg)
	{
		$this->id_acao = $arg;
	}
 	
	public function getIdAcao()
	{
		return $this->id_acao;
	}
 	
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

	public function Adicionar()
	{
		$pdo = $this->getConexao();
		$sql = '
		INSERT INTO acao_usuario_tipo SET id_usuario_tipo = ?';
		 $sql .= ",id_acao = ?";

		$stmt = $pdo->prepare($sql);
		 $stmt->bindParam(++$x,$this->getIdUsuarioTipo(),PDO::PARAM_INT);
		 $stmt->bindParam(++$x,$this->getIdAcao(),PDO::PARAM_INT);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}
    public function AdicionarTodosUsers($tipo)
    {
        $pdo = $this->getConexao();
        $sql = ' INSERT INTO map_usuario_acao (id_usuario,id_acao) 
                SELECT usuario.id,acao_usuario_tipo.id_acao FROM usuario,acao_usuario_tipo 
                WHERE acao_usuario_tipo.`id_usuario_tipo` = usuario.`id_usuario_tipo`  AND usuario.`id_usuario_tipo` = '.$tipo .' AND (`master`  IS NULL OR `master` = 0)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $pdo->lastInsertId() ;
    }
	public function Modificar()
	{
        $pdo = $this->getConexao();
		$sql = '
		UPDATE acao_usuario_tipo SET 
			id = ?';
		$sql .= ",id_usuario_tipo = ?";
		$sql .= ",id_acao = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getIdUsuarioTipo(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getIdAcao(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM acao_usuario_tipo WHERE id IN({$lista})";
		$sql = "UPDATE acao_usuario_tipo SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}
    public function RemoverAll($id)
    {
        $pdo = $this->getConexao();
        $sql = "DELETE FROM acao_usuario_tipo WHERE id_usuario_tipo = $id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }
    public function RemoverAllUsuario($id)
    {
        $pdo = $this->getConexao();
        $sql = "DELETE FROM map_usuario_acao WHERE id_usuario = $id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }

    public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();
		
		$joins = "
		INNER JOIN usuario_tipo ON (usuario_tipo.id = acao_usuario_tipo.id_usuario_tipo)
		";
		
		$where = "
			WHERE acao_usuario_tipo.id > 0
		";
		
		if($busca != "") $where .= " AND (nome LIKE :busca)";
		
		$sql = "
			SELECT COUNT(*) AS total
			FROM acao_usuario_tipo
			$joins
			$where
		";

		$stmt = $pdo->prepare($sql);

		if($busca != "") {
			$busca = "%".$busca."%";
			$stmt->bindParam(":busca",$busca,PDO::PARAM_STR);
		}

		$stmt->execute();
		$totalRegistros = $stmt->fetch(PDO::FETCH_OBJ)->total;

		$sql = "
			SELECT 
				acao_usuario_tipo.*,
			       usuario_tipo.nome as nome_tipo,
			       usuario_tipo.id as usuario_tipo_id
			FROM acao_usuario_tipo
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY acao_usuario_tipo.id DESC";
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
		return [$linhas,$totalRegistros];
	}

	public function Editar()
	{
		$pdo = $this->getConexao();
		$sql = "SELECT * FROM acao_usuario_tipo WHERE id_usuario_tipo = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getIdUsuarioTipo(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    public function ListarFuncionalidades($id_usuario_tipo)
    {
        $pdo = $this->getConexao();

            $sql = "SELECT
                        map_acao.id,
                        map_acao.nome nome_acao,
                        map_acao.modulo,
                        map_modulo.nome nome_modulo,
                        acao_usuario_tipo.id selecionado
                    FROM map_acao
                        INNER JOIN map_modulo ON (map_modulo.id = map_acao.modulo)
                        LEFT JOIN acao_usuario_tipo  ON (acao_usuario_tipo.id_acao = map_acao.id AND acao_usuario_tipo.id_usuario_tipo = $id_usuario_tipo)
                    WHERE map_modulo.excluido IS NULL
                    ORDER BY map_modulo.nome, map_acao.nome asc";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $listar = $stmt->fetchAll(PDO::FETCH_OBJ);


        foreach ($listar as $acao) {
            $vetorFinal[$acao->nome_modulo][] = Array("id_acao" => $acao->id, "nome_acao" => $acao->nome_acao, "selecionado" => $acao->selecionado);
        }

        return $vetorFinal;
    }
    public function AdicionarFuncionalidadesTipo($id_usuario,$i_usuario_tipo)
    {
        $pdo = $this->getConexao();

        $sql = "INSERT INTO map_usuario_acao (id_acao,id_usuario) SELECT id_acao,$id_usuario FROM acao_usuario_tipo WHERE id_usuario_tipo = $i_usuario_tipo";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }
}
