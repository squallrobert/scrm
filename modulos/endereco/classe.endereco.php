<?
class Endereco
{
	private $id;
	private $logradouro;
	private $numero;
	private $complemento;
	private $bairro;
	private $cidade;
	private $estado;
	private $cep;
	private $referencia;
	private $observacao;
	private $telefone;
	private $comercial;
	private $celular;
	private $email;
	private $email_mkt;
	private $email_mkt2;
	private $latitude;
	private $longitude;
	private $data_hora_cadastro;
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
 	
	public function setLogradouro($arg)
	{
		$this->logradouro = $arg;
	}
 	
	public function getLogradouro()
	{
		return $this->logradouro;
	}
 	
	public function setNumero($arg)
	{
		$this->numero = $arg;
	}
 	
	public function getNumero()
	{
		return $this->numero;
	}
 	
	public function setComplemento($arg)
	{
		$this->complemento = $arg;
	}
 	
	public function getComplemento()
	{
		return $this->complemento;
	}
 	
	public function setBairro($arg)
	{
		$this->bairro = $arg;
	}
 	
	public function getBairro()
	{
		return $this->bairro;
	}
 	
	public function setCidade($arg)
	{
		$this->cidade = $arg;
	}
 	
	public function getCidade()
	{
		return $this->cidade;
	}
 	
	public function setEstado($arg)
	{
		$this->estado = $arg;
	}
 	
	public function getEstado()
	{
		return $this->estado;
	}
 	
	public function setCep($arg)
	{
		$this->cep = $arg;
	}
 	
	public function getCep()
	{
		return $this->cep;
	}
 	
	public function setReferencia($arg)
	{
		$this->referencia = $arg;
	}
 	
	public function getReferencia()
	{
		return $this->referencia;
	}
 	
	public function setObservacao($arg)
	{
		$this->observacao = $arg;
	}
 	
	public function getObservacao()
	{
		return $this->observacao;
	}
 	
	public function setTelefone($arg)
	{
		$this->telefone = $arg;
	}
 	
	public function getTelefone()
	{
		return $this->telefone;
	}
 	
	public function setComercial($arg)
	{
		$this->comercial = $arg;
	}
 	
	public function getComercial()
	{
		return $this->comercial;
	}
 	
	public function setCelular($arg)
	{
		$this->celular = $arg;
	}
 	
	public function getCelular()
	{
		return $this->celular;
	}
 	
	public function setEmail($arg)
	{
		$this->email = $arg;
	}
 	
	public function getEmail()
	{
		return $this->email;
	}
 	
	public function setEmailMkt($arg)
	{
		$this->email_mkt = $arg;
	}
 	
	public function getEmailMkt()
	{
		return $this->email_mkt;
	}
 	
	public function setEmailMkt2($arg)
	{
		$this->email_mkt2 = $arg;
	}
 	
	public function getEmailMkt2()
	{
		return $this->email_mkt2;
	}
 	
	public function setLatitude($arg)
	{
		$this->latitude = $arg;
	}
 	
	public function getLatitude()
	{
		return $this->latitude;
	}
 	
	public function setLongitude($arg)
	{
		$this->longitude = $arg;
	}
 	
	public function getLongitude()
	{
		return $this->longitude;
	}
 	
	public function setDataHoraCadastro($arg)
	{
		$this->data_hora_cadastro = $arg;
	}
 	
	public function getDataHoraCadastro()
	{
		return $this->data_hora_cadastro;
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
		INSERT INTO endereco SET data_hora_cadastro = NOW(), logradouro = ? ';
		 $sql .= ",numero = ?";
		 $sql .= ",complemento = ?";
		 $sql .= ",bairro = ?";
		 $sql .= ",cidade = ?";
		 $sql .= ",estado = ?";
		 $sql .= ",cep = ?";
		 $sql .= ",referencia = ?";
		 $sql .= ",observacao = ?";
		 $sql .= ",telefone = ?";
		 $sql .= ",comercial = ?";
		 $sql .= ",celular = ?";
		 $sql .= ",email = ?";
		 $sql .= ",email_mkt = ?";
		 $sql .= ",email_mkt2 = ?";
		 $sql .= ",latitude = ?";
		 $sql .= ",longitude = ?";

		$stmt = $pdo->prepare($sql);
		 $stmt->bindParam(++$x,$this->getLogradouro(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getNumero(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getComplemento(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getBairro(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getCidade(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getEstado(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getCep(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getReferencia(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getObservacao(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getTelefone(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getComercial(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getCelular(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getEmail(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getEmailMkt(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getEmailMkt2(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getLatitude(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getLongitude(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}
	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE endereco SET logradouro = ? ';
		$sql .= ",numero = ?";
		$sql .= ",complemento = ?";
		$sql .= ",bairro = ?";
		$sql .= ",cidade = ?";
		$sql .= ",estado = ?";
		$sql .= ",cep = ?";
		$sql .= ",referencia = ?";
		$sql .= ",observacao = ?";
		$sql .= ",telefone = ?";
		$sql .= ",comercial = ?";
		$sql .= ",celular = ?";
		$sql .= ",email = ?";
		$sql .= ",email_mkt = ?";
		$sql .= ",email_mkt2 = ?";
		$sql .= ",latitude = ?";
		$sql .= ",longitude = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getLogradouro(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getNumero(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getComplemento(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getBairro(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getCidade(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getEstado(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getCep(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getReferencia(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getObservacao(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getTelefone(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getComercial(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getCelular(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getEmail(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getEmailMkt(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getEmailMkt2(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getLatitude(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getLongitude(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM endereco WHERE id IN({$lista})";
		$sql = "UPDATE endereco SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();
		
		$joins = "
		
		";
		
		$where = "
			WHERE endereco.id > 0
		";
		
		if($busca != "") $where .= " AND (nome LIKE :busca)";
		
		$sql = "
			SELECT COUNT(*) AS total
			FROM endereco
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
				endereco.*
			FROM endereco
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY endereco.id DESC";
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
		$sql = "SELECT * FROM endereco WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}
}
