<?
class Modulo
{
	private $id;
	private $nome;
	private $dir;
	private $status;
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
 	
	public function setNome($arg)
	{
		$this->nome = $arg;
	}
 	
	public function getNome()
	{
		return $this->nome;
	}
 	
	public function setDir($arg)
	{
		$this->dir = $arg;
	}
 	
	public function getDir()
	{
		return $this->dir;
	}
 	
	public function setStatus($arg)
	{
		$this->status = $arg;
	}
 	
	public function getStatus()
	{
		return $this->status;
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
		INSERT INTO map_modulo SET 
			nome = ?,
			dir = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getDir(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}

	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE map_modulo SET 
			id = ?';
		$sql .= ",nome = ?";
		$sql .= ",dir = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getDir(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM modulo WHERE id IN({$lista})";
		$sql = "DELETE FROM map_modulo WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

    public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
    {
		$pdo = $this->getConexao();

		$joins = "

		";

		$where = "
			WHERE map_modulo.id > 0
		";

        if($busca != "") $where .= " AND (nome LIKE :busca)";

        $sql = "
			SELECT COUNT(*) AS total
			FROM map_modulo
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
				map_modulo.*
			FROM map_modulo
			$joins
			$where
		";

        if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY map_modulo.id DESC";
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
        $sql = "SELECT * FROM map_modulo WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
	}

    public function ListarCombo()
    {
        $pdo = $this->getConexao();
        $sql = "SELECT * FROM map_modulo ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Conexao::pr($rs);
        return $rs;
    }
}
