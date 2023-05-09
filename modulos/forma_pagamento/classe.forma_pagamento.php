<?
class FormaPagamento
{
	private $id;
	private $rotulo;
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
 	
	public function setRotulo($arg)
	{
		$this->rotulo = $arg;
	}
 	
	public function getRotulo()
	{
		return $this->rotulo;
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
		INSERT INTO forma_pagamento SET  rotulo = ? ';

		$stmt = $pdo->prepare($sql);
		 $stmt->bindParam(++$x,$this->getRotulo(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}
	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE forma_pagamento SET rotulo = ?';

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getRotulo(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM forma_pagamento WHERE id IN({$lista})";
		$sql = "UPDATE forma_pagamento SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();
		
		$joins = "
		
		";
		
		$where = "
			WHERE forma_pagamento.excluido IS NULL
		";
		
		if($busca != "") $where .= " AND (rotulo LIKE :busca)";
		
		$sql = "
			SELECT COUNT(*) AS total
			FROM forma_pagamento
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
				forma_pagamento.*
			FROM forma_pagamento
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY forma_pagamento.id DESC";
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
		$sql = "SELECT * FROM forma_pagamento WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}
    public function ComboFormaPagamento()
    {
        $pdo = $this->getConexao();
        $sql = "SELECT id,rotulo  FROM forma_pagamento WHERE excluido IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $linhas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $x = 0;
        foreach($linhas as $linha)
        {
            $vetor[$x]['id'] = $linha['id'];
            $vetor[$x]['rotulo'] = $linha['rotulo'];
            $x++;
        }
        return $vetor;
    }
    public function Listar($colToOrder="",$ordem = "") {

        $pdo = $this->getConexao();

        $where = " WHERE excluido is null ";

        $order = " ORDER BY rotulo ASC";

        //query que busca registroscom LIMIT E OFFSET
        $sql = "SELECT * FROM forma_pagamento $where $order ";
        // preparando para executar PDO
        $stmt = $pdo->prepare ( $sql );


        // executando PDO
        $stmt->execute ();
//        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // loopping do resultado parassando para um array de objetos
        while ( $linha = $stmt->fetch ( PDO::FETCH_OBJ ) ) {
            // criando instancia do Objeto
            $forma_pagamento = new FormaPagamento ($pdo);
            // setando valores no Objeto
            $forma_pagamento->setId ( $linha->id );
            //aplicar R1 da especificação de caso de uso
            $forma_pagamento->setRotulo ( $linha->rotulo );
            $forma_pagamento->setExcluido ( $linha->excluido );
            // adicionando objeto em array
            $vetor [] = $forma_pagamento;
        }

        return $vetor;
    }
}
