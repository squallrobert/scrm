<?
class Uf
{
	private $id;
	private $id_regiao_uf;
	private $nome;
	private $sigla;
	private $excluido;
	private $conexao;

	public function setId($arg)
	{
		$this->id = $arg;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setIdRegiaoUf($arg)
	{
		$this->id_regiao_uf = $arg;
	}

	public function getIdRegiaoUf()
	{
		return $this->id_regiao_uf;
	}

	public function setNome($arg)
	{
		$this->nome = $arg;
	}

	public function getNome()
	{
		return $this->nome;
	}

	public function setSigla($arg)
	{
		$this->sigla = $arg;
	}

	public function getSigla()
	{
		return $this->sigla;
	}

	public function setExcluido($arg)
	{
		$this->excluido = $arg;
	}

	public function getExcluido()
	{
		return $this->excluido;
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
		INSERT INTO estados SET id_regiao_uf = ?';
		if ($this->getNome() != "") $sql .= ",nome = ?";
		if ($this->getSigla() != "") $sql .= ",sigla = ?";
		if ($this->getSigla() != "") $sql .= ",uf = ?";

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getIdRegiaoUf(),PDO::PARAM_INT);
		if ($this->getNome() != "") $stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		if ($this->getSigla() != "") $stmt->bindParam(++$x,$this->getSigla(),PDO::PARAM_STR);
		if ($this->getSigla() != "") $stmt->bindParam(++$x,$this->getSigla(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}
	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE estados SET id_regiao_uf = ?';
		if ($this->getNome() != "") $sql .= ",nome = ?";
		if ($this->getSigla() != "") $sql .= ",sigla = ?";
		if ($this->getSigla() != "") $sql .= ",uf = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getIdRegiaoUf(),PDO::PARAM_INT);
		if ($this->getNome() != "") $stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		if ($this->getSigla() != "") $stmt->bindParam(++$x,$this->getSigla(),PDO::PARAM_STR);
		if ($this->getSigla() != "") $stmt->bindParam(++$x,$this->getSigla(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM uf WHERE id IN({$lista})";
		$sql = "UPDATE estados SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();

		$joins = "
		LEFT JOIN regiao_uf ON (regiao_uf.id = estados.id_regiao_uf)
		";

		$where = "
			WHERE estados.excluido IS NULL
		";

		if($busca != "") $where .= " AND (estados.nome LIKE :busca or estados.sigla LIKE :busca)";

		$sql = "
			SELECT COUNT(*) AS total
			FROM estados
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
				estados.*,
				regiao_uf.nome as nome_regiao
			FROM estados
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY estados.id DESC";
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
		$sql = "SELECT * FROM estados WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}
    public function ComboUf()
    {
        $pdo = $this->getConexao();
        $sql = "SELECT id,nome,uf, uf AS data_value FROM estados WHERE excluido IS NULL ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ListarTodosUf($busca)
    {
        $pdo = $this->getConexao();
        $sql = "SELECT estados.*, estados.nome AS text FROM estados WHERE excluido IS NULL";

        if(!empty($busca))
            $sql .= " AND estados.nome LIKE '%$busca%'";

        $sql .= " ORDER BY nome ASC LIMIT 50";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}