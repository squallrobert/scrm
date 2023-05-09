<?
class GrupoAcao
{
	private $id_grupo;
	private $id_acao;
	private $customizada;
	private $conexao;

	public function setIdGrupo($arg)
	{
		$this->id_grupo = $arg;
	}
 	
	public function getIdGrupo()
	{
		return $this->id_grupo;
	}
 	
	public function setIdAcao($arg)
	{
		$this->id_acao = $arg;
	}
 	
	public function getIdAcao()
	{
		return $this->id_acao;
	}
 	
	public function setCustomizada($arg)
	{
		$this->customizada = $arg;
	}
 	
	public function getCustomizada()
	{
		return $this->customizada;
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
		INSERT INTO map_grupo_acao SET 
			id = ?';
		if ($this->getIdGrupo() != "") $sql .= ",id_grupo = ?";
		if ($this->getIdAcao() != "") $sql .= ",id_acao = ?";
		if ($this->getCustomizada() != "") $sql .= ",customizada = ?";

		$stmt = $pdo->prepare($sql);
		if ($this->getIdGrupo() != "") $stmt->bindParam(++$x,$this->getIdGrupo(),PDO::PARAM_INT);
		if ($this->getIdAcao() != "") $stmt->bindParam(++$x,$this->getIdAcao(),PDO::PARAM_INT);
		if ($this->getCustomizada() != "") $stmt->bindParam(++$x,$this->getCustomizada(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}
	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE map_grupo_acao SET 
			id = ?';
		if ($this->getIdGrupo() != "") $sql .= ",id_grupo = ?";
		if ($this->getIdAcao() != "") $sql .= ",id_acao = ?";
		if ($this->getCustomizada() != "") $sql .= ",customizada = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		if ($this->getIdGrupo() != "") $stmt->bindParam(++$x,$this->getIdGrupo(),PDO::PARAM_INT);
		if ($this->getIdAcao() != "") $stmt->bindParam(++$x,$this->getIdAcao(),PDO::PARAM_INT);
		if ($this->getCustomizada() != "") $stmt->bindParam(++$x,$this->getCustomizada(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM grupo_acao WHERE id IN({$lista})";
		$sql = "UPDATE map_grupo_acao SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();
		
		$joins = "
		
		";
		
		$where = "
			WHERE grupo_acao.id_grupo > 0
		";
		
		if($busca != "") $where .= " AND (nome LIKE :busca)";
		
		$sql = "
			SELECT COUNT(*) AS total
			FROM grupo_acao
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
				grupo_acao.*
			FROM grupo_acao
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY grupo_acao.id_grupo DESC";
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
		$sql = "SELECT * FROM map_grupo_acao WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}
}
