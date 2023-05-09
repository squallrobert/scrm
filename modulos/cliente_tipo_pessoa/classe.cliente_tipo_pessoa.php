<?
class ClienteTipoPessoa
{
	private $id;
	private $rotulo;
	private $conexao;

	public function setId($arg)
	{
		$this->id = $arg;
	}
 	
	public function getId()
	{
		return $this->id;
	}
 	
	public function setRotulo($arg)
	{
		$this->rotulo = $arg;
	}
 	
	public function getRotulo()
	{
		return $this->rotulo;
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
		INSERT INTO cliente_tipo_pessoa SET 
			id = ?';
		 $sql .= ",rotulo = ?";

		$stmt = $pdo->prepare($sql);
		 $stmt->bindParam(++$x,$this->getRotulo(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}
	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE cliente_tipo_pessoa SET 
			id = ?';
		$sql .= ",rotulo = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getRotulo(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM cliente_tipo_pessoa WHERE id IN({$lista})";
		$sql = "UPDATE cliente_tipo_pessoa SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();
		
		$joins = "
		
		";
		
		$where = "
			WHERE cliente_tipo_pessoa.id > 0
		";
		
		if($busca != "") $where .= " AND (nome LIKE :busca)";
		
		$sql = "
			SELECT COUNT(*) AS total
			FROM cliente_tipo_pessoa
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
				cliente_tipo_pessoa.*
			FROM cliente_tipo_pessoa
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY cliente_tipo_pessoa.id DESC";
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
		$sql = "SELECT * FROM cliente_tipo_pessoa WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}
    public function ComboTipoPessoa()
    {
        $pdo = $this->getConexao();
        $sql = "SELECT id,rotulo FROM cliente_tipo_pessoa";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $linhas =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        $x = 0;
        foreach($linhas as $linha)
        {
            $vetor[$x]['id'] = $linha['id'];
            $vetor[$x]['rotulo'] = $linha['rotulo'];
            $x++;
        }
        return $vetor;
    }
}
