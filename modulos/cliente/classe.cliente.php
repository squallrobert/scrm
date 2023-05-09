<?
class Cliente
{
	private $id;
	private $id_grupo;
	private $id_cliente_tipo_pessoa;
	private $nome;
	private $nome_fantasia;
	private $cpf_cnpj;
	private $inscricao_estadual;
	private $id_endereco;
	private $id_endereco_cobranca;
	private $data_nascimento;
	private $id_cliente_estado_civil;
	private $sexo;
	private $dia_vencimento;
	private $id_forma_pagamento;
	private $rg;
	private $status;
	private $foto;
	private $data_hora_cadastro;
	private $observacao_dados;
	private $excluido;
	private $id_usuario_atualizacao;
	private $data_hora_atualizacao;
	private $id_asaas;
	private $conexao;

	public function setId($arg)
	{
		$this->id = $arg;
	}
 	
	public function getId()
	{
		return $this->id;
	}
 	
	public function setIdGrupo($arg)
	{
		$this->id_grupo = $arg;
	}
 	
	public function getIdGrupo()
	{
		return $this->id_grupo;
	}
 	
	public function setIdClienteTipoPessoa($arg)
	{
		$this->id_cliente_tipo_pessoa = $arg;
	}
 	
	public function getIdClienteTipoPessoa()
	{
		return $this->id_cliente_tipo_pessoa;
	}
 	
	public function setNome($arg)
	{
		$this->nome = $arg;
	}
 	
	public function getNome()
	{
		return $this->nome;
	}
 	
	public function setNomeFantasia($arg)
	{
		$this->nome_fantasia = $arg;
	}
 	
	public function getNomeFantasia()
	{
		return $this->nome_fantasia;
	}
 	
	public function setCpfCnpj($arg)
	{
		$this->cpf_cnpj = $arg;
	}
 	
	public function getCpfCnpj()
	{
		return $this->cpf_cnpj;
	}
 	
	public function setInscricaoEstadual($arg)
	{
		$this->inscricao_estadual = $arg;
	}
 	
	public function getInscricaoEstadual()
	{
		return $this->inscricao_estadual;
	}
 	
	public function setIdEndereco($arg)
	{
		$this->id_endereco = $arg;
	}
 	
	public function getIdEndereco()
	{
		return $this->id_endereco;
	}
 	
	public function setIdEnderecoCobranca($arg)
	{
		$this->id_endereco_cobranca = $arg;
	}
 	
	public function getIdEnderecoCobranca()
	{
		return $this->id_endereco_cobranca;
	}
 	
	public function setDataNascimento($arg)
	{
		$this->data_nascimento = $arg;
	}
 	
	public function getDataNascimento()
	{
		return $this->data_nascimento;
	}
 	
	public function setIdClienteEstadoCivil($arg)
	{
		$this->id_cliente_estado_civil = $arg;
	}
 	
	public function getIdClienteEstadoCivil()
	{
		return $this->id_cliente_estado_civil;
	}
 	
	public function setSexo($arg)
	{
		$this->sexo = $arg;
	}
 	
	public function getSexo()
	{
		return $this->sexo;
	}
 	
	public function setDiaVencimento($arg)
	{
		$this->dia_vencimento = $arg;
	}
 	
	public function getDiaVencimento()
	{
		return $this->dia_vencimento;
	}
 	
	public function setIdFormaPagamento($arg)
	{
		$this->id_forma_pagamento = $arg;
	}
 	
	public function getIdFormaPagamento()
	{
		return $this->id_forma_pagamento;
	}
 	
	public function setRg($arg)
	{
		$this->rg = $arg;
	}
 	
	public function getRg()
	{
		return $this->rg;
	}
 	
	public function setStatus($arg)
	{
		$this->status = $arg;
	}
 	
	public function getStatus()
	{
		return $this->status;
	}
 	
	public function setFoto($arg)
	{
		$this->foto = $arg;
	}
 	
	public function getFoto()
	{
		return $this->foto;
	}
 	
	public function setDataHoraCadastro($arg)
	{
		$this->data_hora_cadastro = $arg;
	}
 	
	public function getDataHoraCadastro()
	{
		return $this->data_hora_cadastro;
	}
 	
	public function setObservacaoDados($arg)
	{
		$this->observacao_dados = $arg;
	}
 	
	public function getObservacaoDados()
	{
		return $this->observacao_dados;
	}
 	
	public function setExcluido($arg)
	{
		$this->excluido = $arg;
	}
 	
	public function getExcluido()
	{
		return $this->excluido;
	}
 	
	public function setIdUsuarioAtualizacao($arg)
	{
		$this->id_usuario_atualizacao = $arg;
	}
 	
	public function getIdUsuarioAtualizacao()
	{
		return $this->id_usuario_atualizacao;
	}

    /**
     * @return mixed
     */
    public function getIdAsaas()
    {
        return $this->id_asaas;
    }

    /**
     * @param mixed $id_asaas
     */
    public function setIdAsaas($id_asaas): void
    {
        $this->id_asaas = $id_asaas;
    }
 	
	public function setDataHoraAtualizacao($arg)
	{
		$this->data_hora_atualizacao = $arg;
	}
 	
	public function getDataHoraAtualizacao()
	{
		return $this->data_hora_atualizacao;
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
		INSERT INTO cliente SET data_hora_cadastro = NOW(), id_grupo = ?';
		 $sql .= ",id_cliente_tipo_pessoa = ?";
		 $sql .= ",nome = ?";
		 $sql .= ",nome_fantasia = ?";
		 $sql .= ",cpf_cnpj = ?";
		 $sql .= ",inscricao_estadual = ?";
		 $sql .= ",id_endereco = ?";
		 $sql .= ",id_endereco_cobranca = ?";
		 $sql .= ",data_nascimento = ?";
		 $sql .= ",id_cliente_estado_civil = ?";
		 $sql .= ",sexo = ?";
		 $sql .= ",dia_vencimento = ?";
		 $sql .= ",id_forma_pagamento = ?";
		 $sql .= ",rg = ?";
		 $sql .= ",status = ?";
		 $sql .= ",foto = ?";
		 $sql .= ",observacao_dados = ?";
		 $sql .= ",id_usuario_atualizacao = ?";
		 $sql .= ",id_asaas = ?";
		 $sql .= ",data_hora_atualizacao = NOW()";

		$stmt = $pdo->prepare($sql);
		 $stmt->bindParam(++$x,$this->getIdGrupo(),PDO::PARAM_INT);
		 $stmt->bindParam(++$x,$this->getIdClienteTipoPessoa(),PDO::PARAM_INT);
		 $stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getNomeFantasia(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getCpfCnpj(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getInscricaoEstadual(),PDO::PARAM_STR);
         $stmt->bindParam(++$x,$this->getIdEndereco(),PDO::PARAM_INT);
         $stmt->bindParam(++$x,$this->getIdEnderecoCobranca(),PDO::PARAM_INT);
		 $stmt->bindParam(++$x,$this->getDataNascimento(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getIdClienteEstadoCivil(),PDO::PARAM_INT);
		 $stmt->bindParam(++$x,$this->getSexo(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getDiaVencimento(),PDO::PARAM_INT);
		 $stmt->bindParam(++$x,$this->getIdFormaPagamento(),PDO::PARAM_INT);
		 $stmt->bindParam(++$x,$this->getRg(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getStatus(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getFoto(),PDO::PARAM_STR);
		 $stmt->bindParam(++$x,$this->getObservacaoDados(),PDO::PARAM_STR);
         $stmt->bindParam(++$x,$this->getIdUsuarioAtualizacao(),PDO::PARAM_INT);
         $stmt->bindParam(++$x,$this->getIdAsaas(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}
	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = " UPDATE cliente SET id_cliente_tipo_pessoa = ?";
		$sql .= ",nome = ?";
		$sql .= ",nome_fantasia = ?";
		$sql .= ",cpf_cnpj = ?";
		$sql .= ",inscricao_estadual = ?";
		$sql .= ",data_nascimento = ?";
		$sql .= ",id_cliente_estado_civil = ?";
		$sql .= ",sexo = ?";
		$sql .= ",dia_vencimento = ?";
		$sql .= ",id_forma_pagamento = ?";
		$sql .= ",rg = ?";
		$sql .= ",status = ?";
		if($this->getFoto() != "") $sql .= ",foto = ?";
		$sql .= ",observacao_dados = ?";
		$sql .= ",id_usuario_atualizacao = ?";
		$sql .= ",data_hora_atualizacao = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getIdClienteTipoPessoa(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getNomeFantasia(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getCpfCnpj(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getInscricaoEstadual(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getDataNascimento(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getIdClienteEstadoCivil(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getSexo(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getDiaVencimento(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getIdFormaPagamento(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getRg(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getStatus(),PDO::PARAM_STR);
        if($this->getFoto() != "") $stmt->bindParam(++$x,$this->getFoto(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getObservacaoDados(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getIdUsuarioAtualizacao(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getDataHoraAtualizacao(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
    public function ModificarAsaas($id_asaas)
    {
        $pdo = $this->getConexao();
        $sql = " UPDATE cliente SET id_asaas = ?";

        $sql .= ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(++$x,$id_asaas,PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
        return $stmt->execute();
    }
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		//$sql = "DELETE FROM cliente WHERE id IN({$lista})";
		$sql = "UPDATE cliente SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();
		
		$joins = "
		INNER JOIN endereco ON (endereco.id = cliente.id_endereco)
		INNER JOIN endereco as endereco2 ON (endereco2.id = cliente.id_endereco_cobranca)
		INNER JOIN grupo ON (grupo.id = cliente.id_grupo)
		";
		
		$where = "
			WHERE cliente.excluido IS NULL
		";
        if (!empty($idGrupo))
            $where .= " AND (grupo.id = {$idGrupo} OR grupo.arvore LIKE '%;$idGrupo;%')";

		if($busca != "") $where .= " AND (cliente.nome LIKE :busca OR cliente.nome_fantasia LIKE :busca OR cliente.nome_fantasia LIKE :busca OR cliente.rg LIKE :busca OR cliente.cpf_cnpj LIKE :busca)";
		
		$sql = "
			SELECT COUNT(*) AS total
			FROM cliente
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
			  cliente.*,
              endereco.`logradouro`,
              endereco.`numero`,
              endereco.`complemento`,
              endereco.`bairro`,
              endereco.`cidade`,
              endereco.`estado`,
              endereco.`cep`,
              endereco.`referencia`,
              endereco.`observacao`,
              endereco.`telefone`,
              endereco.`comercial`,
              endereco.`celular`,
              endereco.`email`,
              endereco.`email_mkt`,
              endereco.`email_mkt2`
			FROM cliente
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY cliente.id DESC";
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
    public function ListarClientes()
    {
        $pdo = $this->getConexao();
        $sql = "SELECT 
              cliente.*,
              endereco.`logradouro`,
              endereco.`numero`,
              endereco.`complemento`,
              endereco.`bairro`,
              endereco.`cidade`,
              endereco.`estado`,
              endereco.`cep`,
              endereco.`referencia`,
              endereco.`observacao`,
              endereco.`telefone`,
              endereco.`comercial`,
              endereco.`celular`,
              endereco.`email`,
              endereco.`email_mkt`,
              endereco.`email_mkt2`,
			  endereco2.`logradouro` as c_logradouro,
              endereco2.`numero` as c_numero,
              endereco2.`complemento` as c_complemento,
              endereco2.`bairro` as c_bairro,
              endereco2.`cidade` as c_cidade,
              endereco2.`estado` as c_estado,
              endereco2.`cep` as c_cep,
              endereco2.`referencia` as c_referencia,
              endereco2.`observacao` as c_observacao,
              endereco2.`telefone` as c_telefone,
              endereco2.`comercial` as c_comercial,
              endereco2.`celular` as c_celular,
              endereco2.`email` as c_email,
              endereco2.`email_mkt` as c_email_mkt,
              endereco2.`email_mkt2` as c_email_mkt2
              FROM cliente
              INNER JOIN endereco ON (endereco.id = cliente.id_endereco)
              INNER JOIN endereco as endereco2 ON (endereco2.id = cliente.id_endereco_cobranca)
              WHERE cliente.excluido IS NULL ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function Editar()
    {
        $pdo = $this->getConexao();
        $sql = "SELECT 
              cliente.*,
              endereco.`logradouro`,
              endereco.`numero`,
              endereco.`complemento`,
              endereco.`bairro`,
              endereco.`cidade`,
              endereco.`estado`,
              endereco.`cep`,
              endereco.`referencia`,
              endereco.`observacao`,
              endereco.`telefone`,
              endereco.`comercial`,
              endereco.`celular`,
              endereco.`email`,
              endereco.`email_mkt`,
              endereco.`email_mkt2`,
			  endereco2.`logradouro` as c_logradouro,
              endereco2.`numero` as c_numero,
              endereco2.`complemento` as c_complemento,
              endereco2.`bairro` as c_bairro,
              endereco2.`cidade` as c_cidade,
              endereco2.`estado` as c_estado,
              endereco2.`cep` as c_cep,
              endereco2.`referencia` as c_referencia,
              endereco2.`observacao` as c_observacao,
              endereco2.`telefone` as c_telefone,
              endereco2.`comercial` as c_comercial,
              endereco2.`celular` as c_celular,
              endereco2.`email` as c_email,
              endereco2.`email_mkt` as c_email_mkt,
              endereco2.`email_mkt2` as c_email_mkt2
              FROM cliente
              INNER JOIN endereco ON (endereco.id = cliente.id_endereco)
              INNER JOIN endereco as endereco2 ON (endereco2.id = cliente.id_endereco_cobranca)
              WHERE cliente.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

	public function VerificarSaldo()
	{
		$pdo = $this->getConexao();
		$sql = "SELECT 
              cliente.saldo
              FROM cliente
              WHERE cliente.id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}
    public function AtualizarSaldo($valor)
    {
        $pdo = $this->getConexao();
        $sql = "UPDATE cliente SET saldo = (saldo + '$valor') WHERE cliente.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function DiminuirSaldo($valor)
    {
        $pdo = $this->getConexao();
        $sql = "UPDATE cliente SET saldo = (saldo - '$valor') WHERE cliente.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function BuscarClientes($idGrupo, $busca){
        $pdo = new Conexao();
        $sql = " SELECT
                     cliente.*,
                     cliente.nome  AS text
                 FROM cliente
                 INNER JOIN grupo ON (cliente.id_grupo = grupo.id)
                 WHERE (cliente.id_grupo = {$idGrupo} OR grupo.arvore LIKE '%;{$idGrupo};%' ) AND cliente.excluido IS NULL ";

        if($busca != ''){
            $sql .= " AND (cliente.nome LIKE '%$busca%' OR cliente.nome_fantasia  LIKE '%$busca%' OR cliente.id  LIKE '%$busca%') ";
        }

        $sql .= ' LIMIT 50';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
}
