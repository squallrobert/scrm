<?
class Acao
{
	private $id;
	private $nome;
	private $acao;
	private $modulo;
	private $conexao;

	public function setId($arg)
	{
		$this->id = $arg;
	}
 	
	public function getId()
	{
		return $this->id;
	}
 	
	public function setNome($arg)
	{
		$this->nome = $arg;
	}
 	
	public function getNome()
	{
		return $this->nome;
	}
 	
	public function setAcao($arg)
	{
		$this->acao = $arg;
	}
 	
	public function getAcao()
	{
		return $this->acao;
	}
 	
	public function setModulo($arg)
	{
		$this->modulo = $arg;
	}
 	
	public function getModulo()
	{
		return $this->modulo;
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
		INSERT INTO map_acao SET 
			nome = ?,
			acao = ?,
			modulo = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getAcao(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getModulo(),PDO::PARAM_INT);
		$stmt->execute();

		$ultimo_id = $pdo->lastInsertId();

		if ($ultimo_id > 0)
		{
			$sql  = "INSERT INTO map_grupo_acao SET id_grupo = 1, id_acao = ?, customizada = 0";
			$stmt = $pdo->prepare($sql);
			$x    = 0;
			$stmt->bindParam(++$x, $ultimo_id, PDO::PARAM_INT);
			$stmt->execute();
		}

		return $ultimo_id;
	}

	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE map_acao SET nome = ?';
		$sql .= ",acao = ?";
		$sql .= ",modulo = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getAcao(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getModulo(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM acao WHERE id IN({$lista})";
		$sql = "DELETE FROM map_acao WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

    public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
    {
		$pdo = $this->getConexao();

		$joins = "

		";

		$where = "
			WHERE map_acao.id > 0
		";

        if($busca != "") $where .= " AND (acao LIKE :busca)";

        $sql = "
			SELECT COUNT(*) AS total
			FROM map_acao LEFT JOIN map_modulo ON(map_modulo.id = map_acao.modulo)
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
				map_acao.*,map_modulo.nome as nome_modulo
			FROM map_acao LEFT JOIN map_modulo ON(map_modulo.id = map_acao.modulo)
			$joins
			$where
		";

        if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY map_acao.id DESC";
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
        $sql = "SELECT * FROM map_acao WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
	}
}
