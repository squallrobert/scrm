<?
include_once(URL_FILE . "classes/Conexao.php");
include_once(URL_FILE . "modulos/endereco/classe.endereco.php");
include_once(URL_FILE . "modulos/grupo/classe.grupo.php");

class Usuario
{
	private $id;
	private $id_grupo;
	private $id_usuario_tipo;
	private $id_endereco;
	private $nome;
	private $cpf_cnpj;
	private $usuario;
	private $senha;
	private $senha_confirmacao;
	private $email;
	private $rg;
	private $id_fuso_horario;
	private $ativo;
	private $data_hora_cadastro;
	private $data_hora_ultimo_login;
	private $excluido;
	private $usuario_logado;
	private $master;
	private $foto;
	private $validacao;
	private $timezone;
	private $conexao;

	public function getTimezone()
	{
		return $this->timezone;
	}

	public function setTimezone($timezone)
	{
		$this->timezone = $timezone;
	}

	public function getTutorial()
	{
		return $this->tutorial;
	}

	public function setTutorial($tutorial)
	{
		$this->tutorial = $tutorial;
	}

    /**
     * @return mixed
     */
    public function getIdUsuarioTipo()
    {
        return $this->id_usuario_tipo;
    }

    /**
     * @param mixed $id_usuario_tipo
     */
    public function setIdUsuarioTipo($id_usuario_tipo)
    {
        $this->id_usuario_tipo = $id_usuario_tipo;
    }

	public function getIdUsuarioPai()
	{
		return $this->id_usuario_pai;
	}

	public function setIdUsuarioPai($id_usuario_pai)
	{
		$this->id_usuario_pai = $id_usuario_pai;
	}

	public function getFoto()
	{
		return $this->foto;
	}

	public function setFoto($foto)
	{
		$this->foto = $foto;
	}

	public function setId($arg)
	{
		$this->id = $arg;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId_grupo($arg)
	{
		$this->id_grupo = $arg;
	}

	public function getId_grupo()
	{
		return $this->id_grupo;
	}

	public function setNome($arg)
	{
		$this->nome = $arg;
	}

	public function getNome()
	{
		return $this->nome;
	}

	public function setCpf_cnpj($arg)
	{
		$this->cpf_cnpj = $arg;
	}

	public function getCpf_cnpj()
	{
		return $this->cpf_cnpj;
	}

	public function setUsuario($arg)
	{
		$this->usuario = $arg;
	}

	public function getUsuario()
	{
		return $this->usuario;
	}

	public function setSenha($arg)
	{
		$this->senha = $arg;
	}

	public function getSenha()
	{
		return $this->senha;
	}

	public function setSenha_confirmacao($arg)
	{
		$this->senha_confirmacao = $arg;
	}

	public function getSenha_confirmacao()
	{
		return $this->senha_confirmacao;
	}

	public function setEmail($arg)
	{
		$this->email = $arg;
	}

	public function getEmail()
	{
		return $this->email;
	}



	public function setSenha_provisoria($arg)
	{
		$this->senha_provisoria = $arg;
	}

	public function getSenha_provisoria()
	{
		return $this->senha_provisoria;
	}

	public function setId_endereco($arg)
	{
		$this->id_endereco = $arg;
	}

	public function getId_endereco()
	{
		return $this->id_endereco;
	}

	public function setId_fuso_horario($arg)
	{
		$this->id_fuso_horario = $arg;
	}

	public function getId_fuso_horario()
	{
		return $this->id_fuso_horario;
	}

	public function setAtivo($arg)
	{
		$this->ativo = $arg;
	}

	public function getAtivo()
	{
		return $this->ativo;
	}

	public function setMaster($arg)
	{
		$this->master = $arg;
	}

	public function getMaster()
	{
		return $this->master;
	}


	public function setData_hora_expirado($arg)
	{
		$this->data_hora_expirado = $arg;
	}

	public function getData_hora_expirado()
	{
		return $this->data_hora_expirado;
	}

	public function setDataHoraUltimoLogin($arg)
	{
		$this->data_hora_ultimo_login = $arg;
	}

	public function getDataHoraUltimoLogin()
	{
		return $this->data_hora_ultimo_login;
	}

	public function setExcluido($arg)
	{
		$this->excluido = $arg;
	}

	public function getExcluido()
	{
		return $this->excluido;
	}


	public function setValidacao($arg)
	{
		$this->validacao = $arg;
	}

	public function getValidacao()
	{
		return $this->validacao;
	}

    public function getConexao()
    {
        return $this->conexao;
    }

    public function setConexao($conexao)
    {
        $this->conexao = $conexao;
    }

    /**
     * @return mixed
     */
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * @param mixed $rg
     */
    public function setRg($rg)
    {
        $this->rg = $rg;
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
		$sql = " INSERT INTO usuario SET
                id_grupo = ?,
                id_usuario_tipo = ?,
                nome = ?,
                usuario = ?,
                senha = ?,
                id_fuso_horario = ?,
                timezone = ?,
                rg = ?,
                master = ? ,
                ativo = ? ";

		if (trim($this->getData_hora_expirado()) != "") { $sql .= " ,`data_hora_expirado` = ?";}
		if (trim($this->getId_endereco()) != "") { $sql .= " ,`id_endereco` = ?"; }
		if (trim($this->getEmail()) != "") { $sql .= " ,`email` = ?"; }
		if (trim($this->getFoto()) != "") {	$sql .= " ,`foto` = ?";	}
		if (trim($this->getValidacao() != "")) { $sql .= " ,`validacao` = ?";	}

		$sql .= ",`data_hora_cadastro` = utc_timestamp()
		   ";

		$stmt = $pdo->prepare($sql);

		$x = 0;
		$stmt->bindParam(++$x, $this->getId_grupo(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getIdUsuarioTipo(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getNome(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $this->getEmail(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $this->getSenha(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $this->getId_fuso_horario(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getTimezone(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getRg(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getMaster(), PDO::PARAM_INT);
        $stmt->bindParam(++$x, $this->getAtivo(), PDO::PARAM_INT);

		if (trim($this->getData_hora_expirado()) != "") {
			$stmt->bindParam(++$x, $this->getData_hora_expirado(), PDO::PARAM_STR);
		}
		if (trim($this->getId_endereco()) != "") {
			$stmt->bindParam(++$x, $this->getId_endereco(), PDO::PARAM_INT);
		}
		if (trim($this->getEmail()) != "") {
			$stmt->bindParam(++$x, $this->getEmail(), PDO::PARAM_STR);
		}
		if (trim($this->getFoto()) != "") {
			$stmt->bindParam(++$x, $this->getFoto(), PDO::PARAM_STR);
		}
		if (trim($this->getValidacao() != "")) {
			$stmt->bindParam(++$x, $this->getValidacao(), PDO::PARAM_STR);
		}

		$stmt->execute();
		return $pdo->lastInsertId();
	}

	public function AdicionarMapas()
	{
        $pdo = $this->getConexao();

		if (!is_numeric($this->master)) $this->master = 0;
		if (!is_numeric($this->senha_provisoria)) $this->senha_provisoria = 0;
//		if (!is_numeric($this->ativo)) $this->ativo = 0;

		$sql = " INSERT INTO usuario SET
                id_grupo = ?,
                id_usuario_tipo = ?,
                nome = ?,
                usuario = ?,
                senha = ?,
                pergunta_senha = 'p',
                resposta_senha = 'r',
                senha_provisoria = ?,
                id_fuso_horario = ?,
                timezone = ?,
                rg = ?,
                ativo = ? ";

		if (trim($this->getData_hora_expirado()) != "") {
			$sql .= " ,`data_hora_expirado` = ?";
		}
		if (trim($this->getId_endereco()) != "") {
			$sql .= " ,`id_endereco` = ?";
		}
		if (trim($this->getCpf_cnpj()) != "") {
			$sql .= " ,`cpf_cnpj` = ?";
		}
		if (trim($this->getEmail()) != "") {
			$sql .= " ,`email` = ?";
		}
		if (is_numeric($this->getMaster())) {
			$sql .= " ,`master` = ?";
		}
		if (trim($this->getFoto()) != "") {
			$sql .= " ,`foto` = ?";
		}
		if (trim($this->getIdUsuarioPai() != "")) {
			$sql .= " ,`id_usuario_pai` = ?";
		}
		if (trim($this->getValidacao() != "")) {
			$sql .= " ,`validacao` = ?";
		}
		if (trim($this->getTutorial() != "")) {
			$sql .= " ,`tutorial` = ?";
		}

		$sql .= ",`data_hora_cadastro` = utc_timestamp()
		         ON DUPLICATE KEY UPDATE
                excluido = null ";

		$stmt = $pdo->prepare($sql);

		$x = 0;
		$stmt->bindParam(++$x, $this->getId_grupo(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getIdUsuarioTipo(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getNome(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $this->getEmail(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $this->getSenha(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $this->getSenha_provisoria(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getId_fuso_horario(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getTimezone(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getRg(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getAtivo(), PDO::PARAM_INT);

		if (trim($this->getData_hora_expirado()) != "") {
			$stmt->bindParam(++$x, $this->getData_hora_expirado(), PDO::PARAM_STR);
		}
		if (trim($this->getId_endereco()) != "") {
			$stmt->bindParam(++$x, $this->getId_endereco(), PDO::PARAM_INT);
		}
		if (trim($this->getCpf_cnpj()) != "") {
			$stmt->bindParam(++$x, $this->getCpf_cnpj(), PDO::PARAM_STR);
		}
		if (trim($this->getEmail()) != "") {
			$stmt->bindParam(++$x, $this->getEmail(), PDO::PARAM_STR);
		}
		if (is_numeric($this->getMaster())) {
			$stmt->bindParam(++$x, $this->getMaster(), PDO::PARAM_INT);
		}
		if (trim($this->getFoto()) != "") {
			$stmt->bindParam(++$x, $this->getFoto(), PDO::PARAM_STR);
		}
		if (trim($this->getIdUsuarioPai() != "")) {
			$stmt->bindParam(++$x, $this->getIdUsuarioPai(), PDO::PARAM_STR);
		}
		if (trim($this->getValidacao() != "")) {
			$stmt->bindParam(++$x, $this->getValidacao(), PDO::PARAM_STR);
		}
		if (trim($this->getTutorial() != "")) {
			$stmt->bindParam(++$x, $this->getTutorial(), PDO::PARAM_STR);
		}

		$stmt->execute();
		return $pdo->lastInsertId();
	}

	public function Modificar()
	{
        $pdo = $this->getConexao();
        if (!is_numeric($this->master) || $this->master == "") {
            $this->master = 0;
        }

        if (!is_numeric($this->senha_provisoria) || $this->senha_provisoria == "") {
            $this->senha_provisoria = 0;
        }

        if (!is_numeric($this->ativo) || $this->ativo == "") {
            $this->ativo = 0;
        }
//             Conexao::pr($this);

		$sql = "UPDATE usuario SET
                `nome` = ?,
                `id_fuso_horario` = ?,
                `ativo` = ?,
                `master` = ?,
                `rg` = ?
                ";

		if ($this->getEmail() != "") {
			$sql .= " ,`email` = ?";
		}
		if ($this->getUsuario() != "") {
			$sql .= " ,`usuario` = ?";
		}
		if ($this->getTimezone() != "") {
			$sql .= " ,`timezone` = ?";
		}
		if (trim($this->getSenha()) != "") {
			$sql .= " ,`senha` = ?";
		}

        if (is_numeric($this->getId_grupo())) {
            $sql .= " ,`id_grupo` = ?";
        }
        if (is_numeric($this->getIdUsuarioTipo())) {
            $sql .= " ,`id_usuario_tipo` = ?";
        }

        if (is_numeric($this->getId_endereco())) {
            $sql .= " ,`id_endereco` = ?";
        }
		$sql .= " WHERE id = ? ";
		$stmt = $pdo->prepare($sql);

		//obrigatórios
		$x = 0;
		$stmt->bindParam(++$x, $this->getNome(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $this->getId_fuso_horario(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getAtivo(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getMaster(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getRg(), PDO::PARAM_INT);

		//opcionais
		if ($this->getEmail() != "") {
			$stmt->bindParam(++$x, $this->getEmail(), PDO::PARAM_STR);
		}
        if ($this->getUsuario() != "") {
            $stmt->bindParam(++$x, $this->getUsuario(), PDO::PARAM_STR);
        }
		if ($this->getTimezone() != "") {
			$stmt->bindParam(++$x, $this->getTimezone(), PDO::PARAM_STR);
		}
		if ($this->getSenha() != "") {
			$stmt->bindParam(++$x, $this->getSenha(), PDO::PARAM_STR);
		}
        if (is_numeric($this->getId_grupo())) {
			$stmt->bindParam(++$x, $this->getId_grupo(), PDO::PARAM_INT);
		}
        if (is_numeric($this->getIdUsuarioTipo())) {
			$stmt->bindParam(++$x, $this->getIdUsuarioTipo(), PDO::PARAM_INT);
		}
        if (is_numeric($this->getId_endereco())) {
			$stmt->bindParam(++$x, $this->getId_endereco(), PDO::PARAM_INT);
		}
		$stmt->bindParam(++$x, $this->getId(), PDO::PARAM_INT);

		return $stmt->execute();
	}
	public function ModificarSimples(){

		$pdo = $this->getConexao();

		$sql = "UPDATE usuario SET
					nome = ?
		";

		if ($this->getEmail() != "") {
			$sql .= " ,`email` = ?";
		}
		if ($this->getUsuario() != "") {
			$sql .= " ,`usuario` = ?";
		}
		if ($this->getTimezone() != "") {
			$sql .= " ,`timezone` = ?";
		}
		if (trim($this->getSenha()) != "") {
			$sql .= " ,`senha` = ?  ";
		}

		$sql .= " WHERE id = ? ";
		$stmt = $pdo->prepare($sql);

		$x = 0;
		$stmt->bindParam(++$x, $this->getNome(), PDO::PARAM_STR);

		if ($this->getEmail() != "") {
			$stmt->bindParam(++$x, $this->getEmail(), PDO::PARAM_STR);
		}
        if ($this->getUsuario() != "") {
            $stmt->bindParam(++$x, $this->getUsuario(), PDO::PARAM_STR);
        }
		if ($this->getTimezone() != "") {
			$stmt->bindParam(++$x, $this->getTimezone(), PDO::PARAM_STR);
		}
		if ($this->getSenha() != "") {
			$stmt->bindParam(++$x, $this->getSenha(), PDO::PARAM_STR);
		}
		$stmt->bindParam(++$x, $this->getId(), PDO::PARAM_INT);

		return $stmt->execute();

	}
	public function ModificarFoto()
	{
        $pdo = $this->getConexao();
		$sql = "UPDATE usuario SET foto = ? WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getFoto(), PDO::PARAM_STR);
		$stmt->bindParam(2, $this->getId(), PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function ModificarTimezone()
	{
        $pdo = $this->getConexao();
		$sql = "UPDATE usuairo SET timezone = ? WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getTimezone(), PDO::PARAM_STR);
		$stmt->bindParam(2, $this->getId(), PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
        $pdo = $this->getConexao();
		$lista   = implode(",", $lista);
		$sql     = "UPDATE usuario SET excluido = utc_timestamp() WHERE id IN ({$lista})";
		$stmt    = $pdo->prepare($sql);
		$deletar = $stmt->execute();

		return $deletar;
	}
    public function Inativar($id)
    {
        $pdo = $this->getConexao();
        $sql     = "UPDATE usuario SET excluido = utc_timestamp(),ativo = 0 WHERE id = $id";
        $stmt    = $pdo->prepare($sql);
        $deletar = $stmt->execute();

        return $deletar;
    }

	public function ListarPaginacao($id_grupo,  $numeroRegistros, $numeroInicioRegistro, $busca = "", $filtro = "", $ordem = "")
	{
        $pdo = $this->getConexao();
		$sql = "SELECT COUNT(*) AS total
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                    LEFT JOIN usuario_tipo ON (usuario.id_usuario_tipo = usuario_tipo.id)
                WHERE usuario.excluido IS NULL
                AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";


		if ($busca != "") {
			$sql .= "
                AND (usuario.nome LIKE '%$busca%'
                OR usuario.usuario LIKE '%$busca%'
                OR usuario.email LIKE '%$busca%'
                OR grupo.nome LIKE '%$busca%')";
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC);

		$sql = "SELECT
                   usuario.id,
                   usuario.id_grupo,
                   usuario.nome,
                   usuario.cpf_cnpj,
                   usuario.usuario,
                   usuario.senha,
                   usuario.email,
                   usuario.id_endereco,
                   usuario.timezone,
                   usuario.ativo,
                   usuario.data_hora_cadastro,
                   usuario.data_hora_ultimo_login,
                   usuario.excluido,
                   usuario_tipo.nome as nome_tipo,
                   usuario.id_usuario_tipo,
                   usuario.foto,
                   (SELECT COUNT(*) FROM map_usuario_acao WHERE id_usuario = usuario.id) as permissao,
                   grupo.nome as nome_grupo
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                    LEFT JOIN franqueado ON (franqueado.id_grupo = grupo.id)
                    LEFT JOIN usuario_tipo ON (usuario.id_usuario_tipo = usuario_tipo.id)
                WHERE usuario.excluido IS NULL
                AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";

		if ($busca != "") {
			$sql .= "
                AND (usuario.nome LIKE '%$busca%'
                OR usuario.usuario LIKE '%$busca%'
                OR usuario.email LIKE '%$busca%'
                OR grupo.nome LIKE '%$busca%')";
		}

		if ($filtro != "") {
			$sql .= " ORDER BY usuario.$filtro $ordem";
		} else {
			$sql .= " ORDER BY usuario.id DESC";
		}

		$sql .= " LIMIT ?, ?";

		$stmt = $pdo->prepare($sql);
		$x    = 0;
		$stmt->bindParam(++$x, $numeroInicioRegistro, PDO::PARAM_INT);
		$stmt->bindParam(++$x, $numeroRegistros, PDO::PARAM_INT);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array($listar, $totalRegistros['total']);
	}
    public function ListarFornecedor($id_grupo,  $numeroRegistros, $numeroInicioRegistro, $busca = "", $filtro = "", $ordem = "")
    {
        $pdo = $this->getConexao();
        $sql = "SELECT COUNT(*) AS total
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                    INNER JOIN usuario_tipo ON (usuario.id_usuario_tipo = usuario_tipo.id)
                WHERE usuario.excluido IS NULL AND usuario.id_usuario_tipo IN(2,3)
                AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";


        if ($busca != "") {
            $sql .= "
                AND (usuario.nome LIKE '%$busca%'
                OR usuario.usuario LIKE '%$busca%'
                OR usuario.email LIKE '%$busca%'
                OR grupo.nome LIKE '%$busca%')";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT
                   usuario.id,
                   usuario.id_grupo,
                   usuario.nome,
                   usuario.cpf_cnpj,
                   usuario.usuario,
                   usuario.senha,
                   usuario.email,
                   usuario.id_endereco,
                   usuario.timezone,
                   usuario.ativo,
                   usuario.data_hora_cadastro,
                   usuario.data_hora_ultimo_login,
                   usuario.excluido,
                   usuario.foto,
                   (SELECT COUNT(*) FROM areas_fornecedores WHERE id_fornecedor = usuario.id) as quantidade_areas,
                   usuario_tipo.nome as tipo_usuario,
                   (SELECT COUNT(*) FROM map_usuario_acao WHERE id_usuario = usuario.id) as permissao,
                   grupo.nome as nome_grupo
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                    INNER JOIN usuario_tipo ON (usuario.id_usuario_tipo = usuario_tipo.id)
                    LEFT JOIN franqueado ON (franqueado.id_grupo = grupo.id)
                WHERE usuario.excluido IS NULL AND usuario.id_usuario_tipo IN(2,3)
                AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";

        if ($busca != "") {
            $sql .= "
                AND (usuario.nome LIKE '%$busca%'
                OR usuario.usuario LIKE '%$busca%'
                OR usuario.email LIKE '%$busca%'
                OR grupo.nome LIKE '%$busca%')";
        }

        if ($filtro != "") {
            $sql .= " ORDER BY usuario.$filtro $ordem";
        } else {
            $sql .= " ORDER BY usuario.id DESC";
        }

        $sql .= " LIMIT ?, ?";

        $stmt = $pdo->prepare($sql);
        $x    = 0;
        $stmt->bindParam(++$x, $numeroInicioRegistro, PDO::PARAM_INT);
        $stmt->bindParam(++$x, $numeroRegistros, PDO::PARAM_INT);
        $stmt->execute();
        $listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array($listar, $totalRegistros['total']);
    }
    public function ListarCorretores($id_grupo,  $numeroRegistros, $numeroInicioRegistro, $busca = "", $filtro = "", $ordem = "")
    {
        $pdo = $this->getConexao();
        $sql = "SELECT COUNT(*) AS total
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                    INNER JOIN usuario_tipo ON (usuario.id_usuario_tipo = usuario_tipo.id)
                WHERE usuario.excluido IS NULL AND usuario.id_usuario_tipo IN(4)
                AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";


        if ($busca != "") {
            $sql .= "
                AND (usuario.nome LIKE '%$busca%'
                OR usuario.usuario LIKE '%$busca%'
                OR usuario.email LIKE '%$busca%'
                OR grupo.nome LIKE '%$busca%')";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT
                   usuario.id,
                   usuario.id_grupo,
                   usuario.nome,
                   usuario.cpf_cnpj,
                   usuario.usuario,
                   usuario.senha,
                   usuario.email,
                   usuario.id_endereco,
                   usuario.timezone,
                   usuario.ativo,
                   usuario.data_hora_cadastro,
                   usuario.data_hora_ultimo_login,
                   usuario.excluido,
                   usuario.foto,
                   usuario_tipo.nome as tipo_usuario,
                   (SELECT COUNT(*) FROM map_usuario_acao WHERE id_usuario = usuario.id) as permissao,
                   grupo.nome as nome_grupo
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                    INNER JOIN usuario_tipo ON (usuario.id_usuario_tipo = usuario_tipo.id)
                    LEFT JOIN franqueado ON (franqueado.id_grupo = grupo.id)
                WHERE usuario.excluido IS NULL AND usuario.id_usuario_tipo IN(4)
                AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";

        if ($busca != "") {
            $sql .= "
                AND (usuario.nome LIKE '%$busca%'
                OR usuario.usuario LIKE '%$busca%'
                OR usuario.email LIKE '%$busca%'
                OR grupo.nome LIKE '%$busca%')";
        }

        if ($filtro != "") {
            $sql .= " ORDER BY usuario.$filtro $ordem";
        } else {
            $sql .= " ORDER BY usuario.id DESC";
        }

        $sql .= " LIMIT ?, ?";

        $stmt = $pdo->prepare($sql);
        $x    = 0;
        $stmt->bindParam(++$x, $numeroInicioRegistro, PDO::PARAM_INT);
        $stmt->bindParam(++$x, $numeroRegistros, PDO::PARAM_INT);
        $stmt->execute();
        $listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array($listar, $totalRegistros['total']);
    }
	public function ListarPaginacaoGrupo($id_grupo, $pagina, $numeroRegistros, $numeroInicioRegistro, $busca = "")
	{
		$pdo = $this->getConexao();
		$count = "
            SELECT COUNT(*) as total";

		$select = "
            SELECT
                usuario.id,
                usuario.id_grupo,
                usuario.nome,
                usuario.cpf_cnpj,
    		    usuario.usuario,
                usuario.senha,
                usuario.email,
    		    usuario.pergunta_senha,
                usuario.resposta_senha,
                usuario.senha_provisoria,
    		    usuario.id_endereco,
                usuario.ativo,
                usuario.data_hora_cadastro,
    		    usuario.data_hora_expirado,
                usuario.data_hora_ultimo_login,
                usuario.excluido,
                usuario.foto,
                usuario.master,
    		    grupo.nome as nome_grupo,
                franqueado.nome as nome_franqueado,
                franqueado.codigo,
                IF(cliente.nome_fantasia != '',cliente.nome_fantasia ,cliente.nome) as nome_cliente
        ";
		$from   = "
            FROM
                usuario
    		    INNER JOIN
                    grupo ON (usuario.id_grupo = grupo.id)
                LEFT JOIN
                    franqueado ON (grupo.id = franqueado.id_grupo)
                LEFT JOIN
                    cliente ON (grupo.id =  cliente.id_grupo)
        ";
		$where  = "
            WHERE
                usuario.excluido IS NULL
            AND
                (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')
        ";
		if ($busca != "") {
			$where .= "
                AND
                    (
                        usuario.nome LIKE '%$busca%'
                        OR
                            usuario.usuario LIKE '%$busca%'
                        OR
                            usuario.email LIKE '%$busca%'
                        OR
                            grupo.nome LIKE '%$busca%'
                    )
            ";
		}

		$sql  = $count . $from . $where;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs             = $stmt->fetch(PDO::FETCH_OBJ);
		$totalRegistros = $rs->total;
		$sql            = $select . $from . $where;
		if ($numeroRegistros) {
			$sql .= "LIMIT
                    $numeroInicioRegistro, $numeroRegistros
            ";
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array($rs, $totalRegistros);
	}

	public function Editar()
	{
        $pdo = $this->getConexao();
		$sql = "SELECT
                    usuario.id AS id_usuario,
                    usuario.email AS email_usuario,
                    usuario.*,
                    grupo.nome AS nome_grupo,
                    grupo.arvore AS arvore,
                    grupo.id AS id_grupo,
                    endereco.id AS id_endereco,
                    endereco.*,
                    franqueado.id AS id_franqueado
                FROM usuario
                INNER JOIN grupo ON (grupo.id = usuario.id_grupo)
                LEFT JOIN franqueado ON (franqueado.id_grupo = grupo.id)
                LEFT JOIN endereco ON (endereco.id = usuario.id_endereco)
                WHERE usuario.id = ?";

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		$stmt->execute();
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);

		return $linha;
	}
    public function LocalizarCliente()
    {
        $pdo = $this->getConexao();
        $sql = "SELECT
                    usuario.id AS id_usuario,
                    cliente.id as id_cliente,
                    endereco.cidade as cidade_cliente
                FROM usuario
                INNER JOIN cliente ON (cliente.id_grupo = usuario.id_grupo)
                INNER JOIN endereco ON (cliente.id_endereco = endereco.id)
                WHERE usuario.id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        return $linha;
    }


	public function ListarCliente($lista = "")
	{
        $pdo = $this->getConexao();
		$sql = "SELECT usuario.* , (SELECT COUNT(*) FROM cliente WHERE cliente.id_grupo = usuario.id_grupo) AS confere_usuario
                FROM usuario
                    INNER JOIN cliente ON (usuario.id_grupo = cliente.id_grupo)
                WHERE cliente.id = {$this->id} AND usuario.excluido IS NULL AND id_usuario_pai IS NULL";
		if ($lista != "") {
			$sql .= " AND usuario.id NOT IN ($lista)";
		}

		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
    public function ListarCombo($id)
    {
        $pdo = $this->getConexao();
        $sql = "SELECT id,CONCAT(nome,' ',email) as nome FROM usuario WHERE id = $id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function ListarUsuarioCliente()
	{
        $pdo = $this->getConexao();
		$sql  = "SELECT * FROM usuario WHERE usuario.id = {$this->id} AND usuario.excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function updateUsuario($lista_id)
	{
        $pdo = $this->getConexao();
		$sql = "UPDATE usuario SET
                excluido = utc_timestamp()
                WHERE usuario.id_grupo = {$this->id_grupo}";
		if ($lista_id != "") {
			$sql .= " AND usuario.id NOT IN ($lista_id)";
		}
		$stmt           = $pdo->prepare($sql);
		$move_excluidos = $stmt->execute();

		return $move_excluidos;
	}

	public function Logar()
	{

        $pdo = $this->getConexao();
		$sql  = "SELECT
                    usuario.id,
                    usuario.id_grupo,
                    usuario.master,
                    grupo.nome nome_grupo,
                    grupo.arvore,
                    grupo.id_categoria AS categoria_grupo,
                    usuario.nome,
                    usuario.senha,
                    usuario.usuario as uLogin,
                    usuario.usuario as nome_usuario,
                    usuario.ativo,
                    usuario.email,
                    usuario.timezone,
                    usuario.id_fuso_horario,
                    usuario.id_usuario_tipo,
                    usuario.senha_provisoria,
                    usuario.master,
                    usuario.foto,
                    usuario.data_hora_ultimo_login,
                    franqueado.codigo,
                    franqueado.acesso_bloqueado,
                    franqueado.id as id_franqueado,
                    franqueado.nome as nome_franqueado,
                    franqueado.tipo_aprovacao,
                    cliente.id_grupo as id_grupo_cliente,
                    cliente.id as id_cliente,
                    cliente.id_forma_pagamento,
                    cliente.status as acesso_bloqueado,
                    cliente.saldo,
                    endereco.celular,
                    usuario_tipo.nome as nome_tipo
                FROM usuario
                    INNER JOIN grupo ON (grupo.id = usuario.id_grupo AND grupo.excluido IS NULL)
                    LEFT JOIN franqueado ON (grupo.id = franqueado.id_grupo)
                    LEFT JOIN cliente ON (grupo.id = cliente.id_grupo)
                    LEFT JOIN endereco ON (endereco.id = usuario.id_endereco)
                    LEFT JOIN usuario_tipo ON (usuario_tipo.id = usuario.id_usuario_tipo)
                WHERE usuario.usuario = '{$this->email}'
                    AND usuario.senha = '{$this->senha}'
                    AND usuario.excluido IS NULL";
//		if ($this->getId_grupo() != "") {
//			$sql .= " AND usuario.id_grupo = " . $this->getId_grupo();
//		} else {
//			$sql .= " AND id_usuario_pai IS NULL";
//		}
        //echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Inativa todos os usuarios ativos e expirados
	// Se for informado um ID do usuario, retorna se ele esta ativo ou nao
	public function AtualizarUsuarioExpirado($idFusoHorario, $idUsuario = NULL)
	{
        return true;
		$data_hora_expirado = FusoHorario::ObterHoraAtualServidor(NULL, $idFusoHorario);

        $pdo = $this->getConexao();
		$sql  = "
            UPDATE
                usuario
            SET
                ativo = 0
            WHERE
                data_hora_expirado <= '$data_hora_expirado'
            AND
                ativo = 1
        ";
		$stmt = $pdo->prepare($sql);
		$rs   = $stmt->execute();

		if ($idUsuario) {
			$sql = "
                SELECT
                    ativo
                FROM
                    usuario
                WHERE
                    id = $idUsuario
            ";

			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$rs = $stmt->fetchAll(PDO::FETCH_OBJ);
			if (count($rs[0])) {
				return ($rs[0]->ativo != 0) ? true : false;
			}
		}

		return true;
	}

	public function VerificarCadastrado($validarUsuario)
	{
        if ($this->senha != "") {
            if (trim($this->senha) != "" && (strlen($this->senha) < 8)) {
                return Array("codigo" => 1, "mensagem" => 'Senha deve ter no mínimo 8 caracteres e no máximo 32');
            }

            if ($this->senha != $this->senha_confirmacao) {
                return Array("codigo" => 1, "mensagem" => 'Confirmação de senha diferente da senha');
            }

            $this->senha = md5($this->senha);
        }

        if ($validarUsuario){
            $pdo = $this->getConexao();

            if ($this->email != "" && (strlen($this->email) < 3)) {
                return Array("codigo" => 1, "mensagem" => 'Usuário deve ter no mínimo 3 caracteres');
            }

            if ($this->email != "" && preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2,3}/", $this->email) === false) {
                return Array("codigo" => 1, "mensagem" => 'Caracteres inválido para o usuário');
            }

           // if ($this->email != "" && !filter_var($this->email, FILTER_VALIDATE_EMAIL)){
               // return Array("codigo" => 1, "mensagem" => TXT_USUARIO_INVALIDO);
           // }

            $sql  = "
            SELECT *
            FROM usuario
            WHERE usuario.usuario = '{$this->usuario}'
            {USUARIO_CADASTRADO}
            AND usuario.excluido IS NULL
            ORDER BY usuario.nome
            LIMIT 1
        ";

            $sql = str_replace("{USUARIO_CADASTRADO}", "AND usuario.id != '{$this->id}' ", $sql);


            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            if ($rs->usuario != "") {
                return Array("codigo" => 1, "mensagem" => 'Já existe um usuário com este nome');
            }
        }
        return Array("codigo" => 0, "mensagem" => 'Não existe usuário cadastrado com este e-mail ou nome de usuário');
	}

    public function VerificarCadastradoMapas($email)
    {
        $pdo = $this->getConexao();
        $sql  = "
            SELECT *
            FROM usuario
            WHERE usuario.usuario = '{$email}'
            AND usuario.excluido IS NULL
            ORDER BY usuario.nome
            LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetch(PDO::FETCH_OBJ);
        if ($rs->usuario != "") {
            return true;
        }
        return false;
    }

    function geraSaltAleatorio($tamanho = 22) {
        return substr(sha1(mt_rand()), 0, $tamanho);
    }

	public function UsuariosRelatorioGrupo($id_grupo)
	{
        $pdo = $this->getConexao();
		$sql  = "
            SELECT
                usuario.id,
                usuario.id_grupo,
                usuario.nome,
                usuario.usuario,
                usuario.email
            FROM
                usuario
                INNER JOIN
                    grupo ON (usuario.id_grupo = grupo.id)
            WHERE
                (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')
            AND
                usuario.excluido IS NULL AND id_usuario_pai IS NULL

        ";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_ASSOC);


		$vetor = array();
		$x     = 0;
		if (count($listar) > 0) {
			foreach ($listar as $linha) {
				$vetor[$linha['id_grupo']][$x]['id']       = $linha['id'];
				$vetor[$linha['id_grupo']][$x]['id_grupo'] = $linha['id'];
				$vetor[$linha['id_grupo']][$x]['nome']     = $linha['nome'];
				$vetor[$linha['id_grupo']][$x]['usuario']  = $linha['usuario'];
				$vetor[$linha['id_grupo']][$x++]['email']  = $linha['email'];
			}
		}

		return $vetor;
	}

	public function BuscarPeguntaSecreta($usuario = NULL, $resposta = NULL)
	{
		if ($usuario == "" && $resposta == "") {
			return;
		}

        $pdo = $this->getConexao();
		$sql = "
            SELECT
                id,
                email,
                usuario,
                pergunta_senha,
                nome
            FROM
                usuario
            WHERE
                 id_usuario_pai IS NULL 
            ";
		if ($usuario) {
			$sql .= " AND usuario = '{$usuario}'";
		}
		if ($resposta) {
			$sql .= " AND resposta_senha = '{$resposta}' AND id = {$this->getId()}";
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function LocalizarDadosUsuario($email)
    {
        if (empty($email)) {
            return;
        }
        $pdo = $this->getConexao();
        $sql = "
            SELECT
                id,
                email,
                usuario,
                nome
            FROM
                usuario
            WHERE
                usuario.excluido IS NULL AND usuario = '{$email}' 
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	public function gerarSenha()
	{

		$letras  = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
		$numeros = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");

		$p  = array_rand($letras, 1);
		$s  = array_rand($numeros, 1);
		$t  = array_rand($letras, 1);
		$q  = array_rand($numeros, 1);
		$qi = array_rand($letras, 1);
		$se = array_rand($numeros, 1);

		$senha = $letras[$p] . $numeros[$s] . $letras[$t] . $numeros[$q] . $letras[$qi] . $numeros[$se]. $letras[$t] . $numeros[$q] ;

		return $senha;
	}
	function randPass($length, $strength=8) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength >= 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength >= 2) {
			$vowels .= "AEUY";
		}
		if ($strength >= 4) {
			$consonants .= '23456789';
		}
		if ($strength >= 8) {
			$consonants .= '@#$%';
		}

		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}

	public function MudarSenha()
	{

        $pdo = $this->getConexao();
		$sql  = 'UPDATE usuario SET `senha` = ?,`senha_provisoria` = 0   WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->senha, PDO::PARAM_STR);
		$stmt->bindParam(2, $this->id, PDO::PARAM_INT);
		$rs = $stmt->execute();

		return $rs;
	}

	public function AtualizarUltimoLogin()
	{
		if ($this->id > 0 === false) {
			return false;
		}

        $pdo = $this->getConexao();
		$sql  = 'UPDATE usuario SET `data_hora_ultimo_login` = utc_timestamp() WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->id, PDO::PARAM_INT);
		$rs = $stmt->execute();

		return $rs;
	}

	public function EnviarEmail($dados)
	{
		$html =  "<html>";
		$html .=  "<head>";
		$html .=  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		$html .=  "<title>Sheephouse</title>";
		$html .=  "</head>";
		$html .=  "<body>";
		$html .=  "<table width=\"560\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:-8px;\">";
		$html .=  "  <tr>";
		$html .=  "    <td height=\"150\" valign=\"top\"><img src=\"http://app.sheephouse.com.br/app/news/assets/images/topo.png\" width=\"560\" height=\"117\" /></td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td><p></p>";
		$html .=  "      <p style=\"color:#555555;font:700 24px 'Arial',sans-serif;\">Olá {$dados['nome']}</p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">Você solicitou uma recuperação de senha.</p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">O sistema Sheephouse gerou uma nova senha para você:</p>";
		$html .=  "      <p style=\"color:#555555;font:20px 'Arial',sans-serif;line-height:26px;\">Nova Senha: <span style=\"color:#ed1c24;text-decoration: none;\">{$dados['senha']}</span></p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">use esta senha para acessar o sistema.</p>";
		$html .=  "    </td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"220\" valign=\"middle\"><a href=\"http://app.sheephouse.com.br\" target=\"_blank\"><img style=\"display:block;border:none;\" src=\"http://app.sheephouse.com.br/app/news/assets/images/btn-acessar-sistema.png\" width=\"240\" height=\"67\" /></a></td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"80\" style=\"color:#555555;font:12px 'Arial',sans-serif;border-top:1px dotted #9c9c9c;\">Copyright 2015. Sheephouse. Todos os direitos reservados.</td>";
		$html .=  "  </tr>";
		$html .=  "</table>";
		$html .=  "</body>";
		$html .=  "</html>";


		$dados_vetor['email']['Pessoal'] = $dados['email'];
		$enviar = new Email();
		$enviar->EnviarEmail("Alteração de senha Sheephouse",$html,$dados_vetor['email']);
	}

	public function AtribuirPrioridadeEventoTipo($prioridades)
	{
		$objUsuarioPrioridadeEventoTipo = new UsuarioPrioridadeEventoTipo();
		$objUsuarioPrioridadeEventoTipo->setIdUsuario($this->id);
		if (count($prioridades)) {
			$eventosPrioridadeSirene = array(1, 59, 70, 72, 104);
			if (count($prioridades['email']) == 0) {
				$prioridades['email'] = array();
			}
			if (count($prioridades['sirene']) == 0) {
				$prioridades['sirene'] = array();
			}
			if (count($prioridades['sms']) == 0) {
				$prioridades['sms'] = array();
			}

			$emails  = $prioridades['email'];
			$sirenes = $prioridades['sirene'];
			$sms     = $prioridades['sms'];

			// Remove do array $prioridades os elementos dos outros formulários
			unset($prioridades['email']);
			unset($prioridades['sirene']);
			unset($prioridades['sms']);
			unset($prioridades['seleciona_monitorar']);
			unset($prioridades['seleciona_posicao']);
			unset($prioridades['seleciona_sms']);
			unset($prioridades['latitude']);
			unset($prioridades['longitude']);
			unset($prioridades['logradouro']);
			unset($prioridades['logradouro_hidden']);
			unset($prioridades['seleciona_comandos']);
			unset($prioridades['seleciona_rel_por_email']);

			$auxUsuario = new Usuario();
			$auxUsuario->setId($this->id);
			$auxUsuario = $auxUsuario->Editar();

			//$resultado = $objUsuarioPrioridadeEventoTipo->Remover();
			foreach ($prioridades as $prioridade => $valor) {
				$id_evento = str_replace('id_evento_tipo_', '', $prioridade);

				// Verifica se a linha do evento que está sendo trabalho no foreach está dentro do array de sirenes, se verdadeiro adicione.
				if (gettype(array_search($id_evento, $sirenes)) == 'integer') {
					$objUsuarioPrioridadeEventoTipo->setSirene(1);
				} else {
					$objUsuarioPrioridadeEventoTipo->setSirene(0);
				}

				// Verifica se o usuario é uma franquia, se sim os eventos obrigatorios serão aplicados, se não, não existirá e o usuário poderá customizar.
				if ($auxUsuario->id_franqueado != "" && $auxUsuario->master != 1) {
					if (in_array($id_evento, $eventosPrioridadeSirene)) {
						$objUsuarioPrioridadeEventoTipo->setSirene(1);
					}
				}

				// Verifica se a linha do evento que está sendo trabalho no foreach está dentro do array de emails, se verdadeiro adicione.
				if (gettype(array_search($id_evento, $emails)) == 'integer') {
					$objUsuarioPrioridadeEventoTipo->setEmail(1);
				} else {
					$objUsuarioPrioridadeEventoTipo->setEmail(0);
				}

				// Verifica se a linha do evento que está sendo trabalho no foreach está dentro do array de sms, se verdadeiro adicione.
				if (gettype(array_search($id_evento, $sms)) == 'integer') {
					$objUsuarioPrioridadeEventoTipo->setSms(1);
				} else {
					$objUsuarioPrioridadeEventoTipo->setSms(0);
				}

				$objUsuarioPrioridadeEventoTipo->setIdEventoTipo($id_evento);
				$objUsuarioPrioridadeEventoTipo->setIdPrioridade($valor);
				$resultado = $objUsuarioPrioridadeEventoTipo->Adicionar();

				if ($resultado <= 0) {
					return false;
				}
			}
		}

		return true;
	}

	public function gerarLogSessao($fusoHorario, $ignorarSessaoAberta = false)
	{
        $pdo = $this->getConexao();

		$sql  = "SELECT * FROM log_sessao WHERE data_hora_fim IS NULL AND id_usuario = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		$stmt->execute();
		$sessaoAberta = $stmt->fetch(PDO::FETCH_OBJ);

		if ($sessaoAberta > 0 && !$ignorarSessaoAberta) {
			//retorna a diferença de tempo desde o último login com a sessão em aberto
			return floor((time() - strtotime($sessaoAberta->data_hora_inicio)) / (60));
		} else {

			$sql = "INSERT INTO log_sessao SET `id_usuario` = ?, `data_hora_inicio` = utc_timestamp()";

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);

			return $stmt->execute();
		}
	}

	public function finalizarSessaoPendente($fusoHorario, $dataHora, $obs = "")
	{
        $pdo = $this->getConexao();

		$dataHoraFim = Conexao::PrepararDataBD($dataHora, $fusoHorario);

		$sql  = "SELECT * FROM log_sessao WHERE data_hora_fim IS NULL AND id_usuario = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		$stmt->execute();
		$sessaoAberta = $stmt->fetch(PDO::FETCH_OBJ);

		$sql = "UPDATE log_sessao
                SET `data_hora_fim` = ?, `obs` = ?
                WHERE id = ?";

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $dataHoraFim, PDO::PARAM_STR);
		$stmt->bindParam(2, $obs, PDO::PARAM_STR);
		$stmt->bindParam(3, $sessaoAberta->id, PDO::PARAM_INT);
		$stmt->execute();

		return $this->gerarLogSessao($fusoHorario);
	}

	public function finalizarSessao()
	{
        $pdo = $this->getConexao();

		$sql  = "SELECT * FROM log_sessao WHERE data_hora_fim IS NULL AND id_usuario = ? ORDER BY id DESC LIMIT 1";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		$stmt->execute();
		$sessaoAberta = $stmt->fetch(PDO::FETCH_OBJ);

		$sql = "UPDATE log_sessao
                SET `data_hora_fim` = UTC_TIMESTAMP(), `obs` = ?
                WHERE id = ?";

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $obs, PDO::PARAM_STR);
		$stmt->bindParam(2, $sessaoAberta->id, PDO::PARAM_INT);

		return $stmt->execute();
	}

	public function checarOperador()
	{
        $pdo = $this->getConexao();

		$sql = "SELECT COUNT(*) AS total
                FROM usuario
                INNER JOIN grupo on (grupo.id = usuario.id_grupo)
                INNER JOIN cliente on (FIND_IN_SET(cliente.id_grupo, (select SUBSTRING(SUBSTRING(REPLACE(grupo.arvore,';',','), 2), 1, LENGTH(grupo.arvore)-2) FROM grupo WHERE id = usuario.id_grupo)) OR cliente.id_grupo = usuario.id_grupo)
                WHERE usuario = '{$this->getUsuario()}'";

		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ);
	}

	public function RemoverFoto()
	{
        $pdo = $this->getConexao();
		$sql  = "SELECT * FROM usuario WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$x    = 0;
		$stmt->bindParam(++$x, $this->getId(), PDO::PARAM_INT);
		$resultado  = $stmt->execute();
		$usuarioAux = $stmt->fetch(PDO::FETCH_OBJ);

		if ($usuarioAux->foto != "") {
			@unlink($usuarioAux->foto);
			$sql  = "UPDATE usuario SET foto = NULL WHERE id = ?";
			$stmt = $pdo->prepare($sql);
			$x    = 0;
			$stmt->bindParam(++$x, $this->getId(), PDO::PARAM_INT);
			$resultado = $stmt->execute();
		}


		return $resultado;
	}

	public function ListarUsuarioMultiselect($id_grupo, $busca = "")
	{
        $pdo = $this->getConexao();
		$sql = "SELECT
                CONCAT(usuario.nome, ' - ', usuario.usuario) as value,
                usuario.id,
                usuario.nome,
                usuario.nome as rotulo,
                CONCAT(usuario.nome, ' - ', usuario.usuario) as label
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                WHERE usuario.excluido IS NULL

                AND (grupo.id = :grupo OR grupo.arvore LIKE :arvore) AND id_usuario_pai IS NULL";

		if ($busca != "") {
			$busca = "%$busca%";
			$sql .= " AND (usuario.nome LIKE :busca OR usuario.usuario LIKE :busca OR usuario.email LIKE :busca OR grupo.nome LIKE :busca)";
		}

		$sql .= " ORDER BY usuario.nome asc LIMIT 100";
		$stmt = $pdo->prepare($sql);
		$arvore = "%;$id_grupo;%";
		$stmt->bindParam(":grupo", $id_grupo, PDO::PARAM_INT);
		$stmt->bindParam(":arvore", $arvore, PDO::PARAM_INT);
		if ($busca != "") {
			$stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
		}
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * Função que verifica no banco se o usuário já viu o tutorial.
	 * Marca como visto caso o usuário não tenha visto o tutorial.
	 */
	public function ChecarTutorial($idUsuario)
	{
        $pdo = $this->getConexao();
		$sql  = "SELECT tutorial FROM usuario WHERE id = $idUsuario";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_OBJ);
	}

    public function VerificarGrupo($idGrupo, $idSelecionado)
    {
        $pdo = $this->getConexao();
        $sql  = "SELECT id FROM grupo WHERE (id = {$idGrupo} OR grupo.arvore LIKE '%{$idGrupo}%') AND grupo.id = {$idSelecionado}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

	public function AlterarTutorial($novo, $idUsuario)
	{
        $pdo = $this->getConexao();
		$sql  = "UPDATE usuario SET tutorial = $novo WHERE id = $idUsuario";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ChecarEmail($parametros)
	{
        $pdo = $this->getConexao();
		$sql  = "SELECT email FROM usuario WHERE usuario = '{$parametros[novo_email]}' AND id != '{$parametros[id]}' AND excluido IS NULL AND id_usuario_pai IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$retorno = $stmt->fetch(PDO::FETCH_OBJ);

		if ($retorno->email == '') {
			$sql  = "UPDATE usuario SET usuario = '{$parametros[novo_email]}',email = '{$parametros[novo_email]}',
					usuario_antigo = '{$parametros[usuario]}',codigo_validador = '{$parametros[codigo_validador]}',
					validacao = 0, celular_validador = '{$parametros[celular]}' WHERE id = '{$parametros[id]}'";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$this->EmailValidador($parametros);

		}
		return $retorno;
	}
	public function NovoValidador($parametros)
	{
        $pdo = $this->getConexao();
			$sql  = "UPDATE usuario SET codigo_validador = '{$parametros[codigo_validador]}',validacao = 0 WHERE id = '{$parametros[id]}'";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$this->EmailValidador($parametros);
		return true;
	}
	public function ChecarValidor($parametros)
	{
        $pdo = $this->getConexao();
		$sql  = "SELECT codigo_validador FROM usuario WHERE codigo_validador = '{$parametros[codigo_validador]}' AND id = '{$parametros[id]}' AND excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$retorno = $stmt->fetch(PDO::FETCH_OBJ);

		if ($retorno->codigo_validador != '')
		{
			$sql  = "UPDATE usuario SET validacao = 1 WHERE id = '{$parametros[id]}'";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$this->EmailNovoLogin($parametros);
		}
		return $retorno;
	}
	public function EmailValidador($parametros)
	{
		$html =  "<html>";
		$html .=  "<head>";
		$html .=  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		$html .=  "<title>Sheephouse</title>";
		$html .=  "</head>";
		$html .=  "<body>";
		$html .=  "<table width=\"560\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:-8px;\">";
		$html .=  "  <tr>";
		$html .=  "    <td height=\"150\" valign=\"top\"><img src=\"http://app.sheephouse.com.br/app/news/assets/images/topo.png\" width=\"560\" height=\"117\" /></td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td>";
		$html .=  "      <p style=\"color:#555555;font:700 24px 'Arial',sans-serif;\"> Olá {$parametros['nome']}</p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">Você está a apenas um passo para começar a acessar o sistema da Sheephouse.</p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">Confirme sua inscrição inserindo o código abaixo no campo do sistema.</p>";
		$html .=  "      <p style=\"color:#555555;font:20px 'Arial',sans-serif;line-height:26px;\">Código Validador: <span style=\"color:#ed1c24;\">{$parametros['codigo_validador']}</span></p>";
		$html .=  "    </td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"220\" valign=\"middle\"><a href=\"http://app.sheephouse.com.br/app\" target=\"_blank\"><img style=\"display:block;border:none;\" src=\"http://app.sheephouse.com.br/app/news/assets/images/btn-acessar-sistema.png\" width=\"240\" height=\"67\" /></a></td>";
		$html .=  "  </tr>";

        $html .=  "  <tr>";
        $html .=  "    <td align=\"center\">Esta é uma mensagem automática. Por favor, não responda este e-mail</td>";
        $html .=  "  </tr>";

		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"80\" style=\"color:#555555;font:12px 'Arial',sans-serif;border-top:1px dotted #9c9c9c;\">Copyright 2010. Sheephouse. Todos os direitos reservados.</td>";
		$html .=  "  </tr>";
		$html .=  "</table>";
		$html .=  "</body>";
		$html .=  "</html>";

		$dados['email']['Pessoal'] = $parametros['novo_email'];
		$enviar = new Email();
//		$enviar->remetenteEmail = "validador@eforza.com.br";
//		$enviar->remetenteSenha = "validacao#2018";
		$enviar->EnviarEmail("Confirmação de E-mail Sheephouse",$html,$dados['email']);

	}
	public function EmailNovoLogin($parametros)
	{
		$html =  "<html>";
		$html .=  "<head>";
		$html .=  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		$html .=  "<title>Sheephouse</title>";
		$html .=  "</head>";
		$html .=  "<body>";
		$html .=  "<table width=\"560\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:-8px;\">";
		$html .=  "  <tr>";
		$html .=  "    <td height=\"150\" valign=\"top\"><img src=\"http://app.sheephouse.com.br/app/news/assets/images/topo.png\" width=\"560\" height=\"117\" /></td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td>";
		$html .=  "      <p style=\"color:#555555;font:700 24px 'Arial',sans-serif;\">Olá {$parametros['nome']}</p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">Para acessar o sistema da Sheephouse você sempre utilizará o seu email como login.</p>";
		$html .=  "      <p style=\"color:#555555;font:20px 'Arial',sans-serif;line-height:26px;\">Login: <span style=\"color:#ed1c24;text-decoration: none;\">{$parametros['novo_email']}</span></p>";
		$html .=  "      <p style=\"color:#555555;font:20px 'Arial',sans-serif;line-height:26px;\">Senha: <span style=\"color:#ed1c24;text-decoration: none;\">{$parametros['senha']}</span></p>";
		$html .=  "    </td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"220\" valign=\"middle\"><a href=\"http://app.sheephouse.com.br/app\" target=\"_blank\"><img style=\"display:block;border:none;\" src=\"http://app.sheephouse.com.br/app/news/assets/images/btn-acessar-sistema.png\" width=\"240\" height=\"67\" /></a></td>";
		$html .=  "  </tr>";
        $html .=  "  <tr>";
        $html .=  "    <td align=\"center\">Esta é uma mensagem automática. Por favor, não responda este e-mail</td>";
        $html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"80\" style=\"color:#555555;font:12px 'Arial',sans-serif;border-top:1px dotted #9c9c9c;\">Copyright 2021. Sheephouse. Todos os direitos reservados.</td>";
		$html .=  "  </tr>";
		$html .=  "</table>";
		$html .=  "</body>";
		$html .=  "</html>";

		$dados['email']['Pessoal'] = $parametros['novo_email'];
		$enviar = new Email();
//        $enviar->remetenteEmail = "validador@eforza.com.br";
//        $enviar->remetenteSenha = "validacao#2018";
		$enviar->EnviarEmail("Seu novo login Link Monitoramento",$html,$dados['email']);

	}

	public function AlteracaoEmail($parametros)
	{
		$html =  "<html>";
		$html .=  "<head>";
		$html .=  "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
		$html .=  "<title>Sheephouse</title>";
		$html .=  "</head>";
		$html .=  "<body>";
		$html .=  "<table width=\"560\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin-top:-8px;\">";
		$html .=  "  <tr>";
		$html .=  "    <td height=\"150\" valign=\"top\"><img src=\"http://app.sheephouse.com.br/app/news/assets/images/topo.png\" width=\"560\" height=\"117\" /></td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td>";
		$html .=  "      <p style=\"color:#555555;font:700 24px 'Arial',sans-serif;\">Olá {$parametros['nome']}</p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">Você alterou seu e-mail de acesso ao sistema Sheephouse.</p>";
		$html .=  "      <p style=\"color:#555555;font:16px 'Arial',sans-serif;line-height:26px;\">Lembre-se, sempre que for acessar o sistema, o e-mail de acesso agora é:</p>";
		$html .=  "      <p style=\"color:#555555;font:20px 'Arial',sans-serif;line-height:26px;\">Login: <span style=\"color:#ed1c24;text-decoration: none;\">{$parametros['novo_email']}</span></p>";
		$html .=  "    </td>";
		$html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"220\" valign=\"middle\"><a href=\"http://app.sheephouse.com.br/app\" target=\"_blank\"><img style=\"display:block;border:none;\" src=\"http://app.sheephouse.com.br/app/news/assets/images/btn-acessar-sistema.png\" width=\"240\" height=\"67\" /></a></td>";
		$html .=  "  </tr>";
        $html .=  "  <tr>";
        $html .=  "    <td align=\"center\">Esta é uma mensagem automática. Por favor, não responda este e-mail</td>";
        $html .=  "  </tr>";
		$html .=  "  <tr>";
		$html .=  "    <td align=\"center\" height=\"80\" style=\"color:#555555;font:12px 'Arial',sans-serif;border-top:1px dotted #9c9c9c;\">Copyright 2010. Sheephouse. Todos os direitos reservados.</td>";
		$html .=  "  </tr>";
		$html .=  "</table>";
		$html .=  "</body>";
		$html .=  "</html>";


		$dados['email']['Pessoal'] = $parametros['novo_email'];
		$enviar = new Email();
//        $enviar->remetenteEmail = "validador@eforza.com.br";
//        $enviar->remetenteSenha = "validacao#2018";
		$enviar->EnviarEmail("Seu novo login Sheephouse",$html,$dados['email']);

	}
	public function GerarBiSistema($parametros)
	{
        $pdo = $this->getConexao();
		$sql  = "INSERT INTO sistema_bi (usuario,sistema,data_hora,id_usuario,id_grupo) VALUES ('{$parametros['usuario']}','{$parametros['sistema']}',NOW(),'{$parametros['id_usuario']}','{$parametros['id_grupo']}')";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		return true;
	}

	public function BuscarGruposVinculados($idUsuario, $grupoLogado, $apenasFilhos = false)
	{
        $pdo = $this->getConexao();
		$sql = "SELECT
					grupo.id,
					grupo.nome as label,
					grupo.nome as rotulo,
					grupo.nome as value,
					usuario.senha,
					usuario.foto,
					usuario.id AS id_usuario
				FROM grupo
					INNER JOIN usuario ON (usuario.id_grupo = grupo.id)
				WHERE usuario.excluido IS NULL
				AND grupo.excluido IS NULL
				AND grupo.id != ?";


			$sql .= " AND usuario.id = ?";

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x, $idUsuario, PDO::PARAM_INT);
		if (!$apenasFilhos) $stmt->bindParam(++$x, $idUsuario, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function RemoverClones($idUsuario, $grupos = "")
	{
        $pdo = $this->getConexao();
		$sql = "UPDATE usuario SET excluido = UTC_TIMESTAMP() WHERE id_usuario_pai = ? AND excluido IS NULL";

		if ($grupos != "") {
			$sql .= " AND id_grupo NOT IN ($grupos)";
		}

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x, $idUsuario, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function BuscaUsuarioGrupo($idGrupo)
	{
        $pdo = $this->getConexao();
		$sql = "
			SELECT
				usuario.nome AS label,
				usuario.id AS VALUE
			FROM usuario
			WHERE id_grupo = :grupo";

		$sql .= " LIMIT 200";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(":grupo", $idGrupo, PDO::PARAM_INT);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $listar;
	}

	public function BuscarUsuarios($busca)
	{
        $pdo = $this->getConexao();
        $id_grupo = $_SESSION['usuario']['id_grupo'];
		$sql = "
			SELECT
				usuario.*,
			       CONCAT(usuario.nome, ' - ', usuario.email) AS text
			FROM usuario
				INNER JOIN grupo ON (grupo.id = usuario.id_grupo)
				LEFT JOIN franqueado as frq2 ON ((FIND_IN_SET(frq2.id_grupo,(SUBSTRING(SUBSTRING(REPLACE(grupo.arvore,';',','), 2), 1, LENGTH(grupo.arvore)-2))) > 0 AND frq2.master != 1) OR frq2.id_grupo = grupo.id)
			WHERE usuario.excluido IS NULL AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";
		if ($busca){
		    $sql .= " AND usuario.nome LIKE '%$busca%'";
        }
		$sql .= " ORDER BY nome DESC";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($usuarios AS &$usuario) {
			if (!file_exists($usuario['foto'])) {
				$usuario['foto'] = "img/avatar.png";
			}
		}

		return $usuarios;
	}
    public function listarUsuariosFotografos($busca){
        $pdo = new Conexao();
        $sql = " SELECT
                     usuario.*,
                     CONCAT(usuario.nome, ' - ', usuario.email) AS text
                 FROM usuario
                 INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                 WHERE usuario.excluido IS NULL AND  usuario.id_usuario_tipo = 2";

        if($busca != ''){
            $sql .= " AND (usuario.nome LIKE '%$busca%' OR usuario.email  LIKE '%$busca%' OR usuario.id  LIKE '%$busca%') ";
        }

        $sql .= ' LIMIT 50';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tess = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tess;
    }

    public function listarUsuariosCorretores($idGrupo, $busca){
        $pdo = new Conexao();
        $sql = " SELECT
                     usuario.*,
                     CONCAT(usuario.nome, ' - ', usuario.email) AS text
                 FROM usuario
                 INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                 WHERE (usuario.id_grupo = {$idGrupo} OR grupo.arvore LIKE '%;{$idGrupo};%' ) AND usuario.excluido IS NULL AND  usuario.id_usuario_tipo = 4";

        if($busca != ''){
            $sql .= " AND (usuario.nome LIKE '%$busca%' OR usuario.email  LIKE '%$busca%' OR usuario.id  LIKE '%$busca%') ";
        }

        $sql .= ' LIMIT 50';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tess = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tess;
    }
    public function listarUsuariosCombo($idGrupo, $id_area,$tipo = 2){
        $pdo = new Conexao();
        $sql = " SELECT
                     usuario.id,
                     CONCAT(usuario.nome, ' - ', usuario.email) AS nome
                 FROM usuario
                 INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                 WHERE (usuario.id_grupo = {$idGrupo} OR grupo.arvore LIKE '%;{$idGrupo};%' ) 
                   AND usuario.excluido IS NULL 
                   AND  usuario.id_usuario_tipo = $tipo
                   AND usuario.id IN (SELECT id_fornecedor FROM areas_fornecedores WHERE id_area = $id_area)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tess = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tess;
    }
    public function ListarFotografos($id){
        $pdo = new Conexao();
        $sql = " SELECT
                     usuario.id,
                     CONCAT(usuario.nome, ' - ', usuario.email) AS nome
                 FROM usuario
                   WHERE usuario.excluido IS NULL 
                   AND  usuario.id_usuario_tipo = 2
                   AND  usuario.id = $id
              ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tess = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tess;
    }
    public function ListarUsuarioSelecionado($id){
        $pdo = new Conexao();
        $sql = " SELECT
                     usuario.id,
                     CONCAT(usuario.nome, ' - ', usuario.email) AS nome
                 FROM usuario
                   WHERE usuario.excluido IS NULL 
                   AND  usuario.id = $id
              ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tess = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tess;
    }

    public function ListarFotografosMultiple($ids){
        $pdo = new Conexao();
        $sql = " SELECT
                     usuario.id,
                     CONCAT(usuario.nome, ' - ', usuario.email) AS nome
                 FROM usuario
                   WHERE usuario.excluido IS NULL 
                   AND  usuario.id_usuario_tipo = 2
                   AND  usuario.id IN(".implode(",",$ids).")
              ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $tess = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $tess;
    }
	public function MudarIdioma($idioma)
	{
        $pdo = $this->getConexao();
		$sql = "UPDATE usuario SET idioma = :idioma WHERE id = :id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(":idioma", $idioma, PDO::PARAM_STR);
		$stmt->bindParam(":id", $this->getId(), PDO::PARAM_INT);
		$stmt->execute();
	}

	public function BuscarDicasDiarias($idUsuario)
	{
        $pdo = $this->getConexao();
		$sql = "
			SELECT
				*
			FROM dica_diaria 
			WHERE excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$dicas = $stmt->fetchAll();

		$sql = "
			SELECT COUNT(*) AS total 
			FROM dica_diaria_usuario 
			WHERE data_hora > DATE_FORMAT(UTC_TIMESTAMP(), '%Y-%m-%d 00:00:00')
			AND id_usuario = :id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(":id", $idUsuario, PDO::PARAM_INT);
		$stmt->execute();

		//Usuário já viu a dica hoje
		if ($stmt->fetch()['total'] == 1) {
			$ret = ["mostrar" => 0, "dicas" => $dicas];
		} else {
			$ret = ["mostrar" => 1, "dicas" => $dicas];
			$sql = "
			INSERT INTO dica_diaria_usuario SET
			id_usuario = :id,
			data_hora = UTC_TIMESTAMP()
			ON DUPLICATE KEY UPDATE
			data_hora = UTC_TIMESTAMP()";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":id", $idUsuario, PDO::PARAM_INT);
			$stmt->execute();
		}

		return $ret;
	}

    public function LogarServicosRest(){
        $pdo = $this->getConexao();

        $sql = " 
                SELECT 
                    usuario.id,
                    usuario.nome,
                    usuario.id_grupo,
                    usuario.timezone
                FROM usuario
                WHERE usuario.usuario = '{$this->usuario}'
                AND usuario.senha = '{$this->senha}'
                AND usuario.excluido IS NULL
                AND usuario.ativo = 1
                AND id_usuario_pai IS NULL
                LIMIT 1 ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function TratarIniciais($nome)
    {
        list($primeiro,$segundo) = explode(" ",$nome);
        return strtoupper(substr($primeiro,0,1) . substr($segundo,0,1));
    }
    public function VetorPermissaoUsuario()
    {
        $pdo  = $this->getConexao();
        $sql  = "
            SELECT
                map_acao.acao,
                 map_usuario_acao.id_acao,
                 map_usuario_acao.id_usuario
            FROM
                 map_usuario_acao
                INNER JOIN map_acao ON ( map_usuario_acao.id_acao = map_acao.id)
                AND  map_usuario_acao.id_usuario = {$this->id}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs                = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $listar            = new stdClass();
        $listar->resultado = $rs;

        return $listar->resultado;
    }
    public function ChecarPermissao($id_usuario,$acao)
    {
        $pdo  = $this->getConexao();
        $sql  = "
            SELECT
                 map_usuario_acao.id_usuario,
                 map_usuario_acao.id_acao,
                map_acao.acao
            FROM
                 map_usuario_acao
                INNER JOIN map_acao ON ( map_usuario_acao.id_acao = map_acao.id)
            WHERE
                 map_usuario_acao.id_usuario = $id_usuario
                AND map_acao.acao LIKE '%{$acao}%'
            ORDER BY
                LENGTH(map_acao.acao)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs                = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $listar            = new stdClass();
        $listar->resultado = $rs;

        return $listar->resultado;
    }

    function FormatarEmailCodigoValidador($parametros){
	    $html = "
                <style>
                
                    body {
                        padding: 0;
                        margin: 0;
                        background: #f8fafa;
                        /*padding-top:20px;*/
                        padding-bottom: 40px;
                        width: 100%;
                        /*text-align: center;*/
                    }
                
                    h1, h2, h3, h4 {
                        font-family: \"Poppins\", sans-serif;
                        letter-spacing: 0.05em;
                        line-height: 120%;
                        color: #666;
                    }
                
                    p {
                        font-family: \"Poppins\", sans-serif;
                        font-size: 14px;
                        color: #666;
                    }
                </style>
       
                <table width=\"600px\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" style=\"margin:auto;padding-top: 30px\">
                    <tr>
                        <td style=\"width: 400px; height: 100px; padding: 0; margin: 0; background-color: #00468C; border-radius: 4px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; text-align: center; background-repeat: no-repeat\">
                            <a href=\"javascript:void(0)\" class=\"page-logo-link\">
                                <img src=\"".BASE_URL."assets/logo_full_branca.png\" alt=\"".TITULO_GERAL."\" aria-roledescription=\"logo\">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"background-color:#F5F5F5; padding-left: 20px; padding-bottom: 10px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; padding-right: 20px\" ; align=\"justify\">
                            <p> Olá, <strong style=\"color: #DD2C00\">{$parametros['nome']}</strong>!</p>
                            <p>Recebemos uma solicitação para redefinir sua senha do ERP.
                                <br/>
                   
                            <p>Use o link abaixo para iniciar a alteração:</p>
                            <div style=\"border-collapse:collapse; border-radius:6px; text-align:center; display:block; border:none; background:#00468C; \">
                                <a href=\"{$parametros['url']}\" target=\"_blank\" style=\"color:#3b5998; padding:6px 20px 10px 20px; text-decoration:none; display:block\" data-linkindex=\"1\">
                                    <center style=\"font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif; color:#FFFFFF; font-weight:500; font-size:17px;\"> Alterar senha </center>
                                </a>
                            </div>
                            <br/>
                            <small style=\"color:#666;\"> Caso não tenha sido você, desconsidere este e-mail.</small>
                            <br/>
                        </td>
                    </tr>
                    <table>
                    ";
        return $html;
    }

    function FormatarEmailDuasEtapas($parametros){
        $html = "<!DOCTYPE html>
                <html lang=\"en\">
                <head>
                    <meta charset=\"UTF-8\">
                    <title>Title</title>
                </head>
                <style>
                
                    body {
                        padding: 0;
                        margin: 0;
                        background: #f8fafa;
                        /*padding-top:20px;*/
                        padding-bottom: 40px;
                        width: 100%;
                        /*text-align: center;*/
                    }
                
                    h1, h2, h3, h4 {
                        font-family: \"Poppins\", sans-serif;
                        letter-spacing: 0.05em;
                        line-height: 120%;
                        color: #666;
                    }
                
                    p {
                        font-family: \"Poppins\", sans-serif;
                        font-size: 14px;
                        color: #666;
                    }
                </style>
                <body>
                <table width=\"600px\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" style=\"margin:auto;padding-top: 30px\">
                    <tr>
                        <td style=\"width: 400px; height: 100px; padding: 0; margin: 0; background-color: #DD2C00; border-radius: 4px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; text-align: center; background-repeat: no-repeat\">
                            <a href=\"javascript:void(0)\" class=\"page-logo-link\">
                                <img src=\"http://linkmonitoramento.com.br/link_report/img/logo.png\" alt=\"SmartAdmin WebApp\"
                                     aria-roledescription=\"logo\">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"background-color:#EEEEEE; padding-left: 20px; padding-bottom: 10px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; padding-right: 20px\" ; align=\"justify\">
                            <p> Olá, <strong style=\"color: #DD2C00\">{$parametros['nome']}</strong>!</p>
                            <p>Insira o código a seguir para a verificação:</p>
                            <h4><strong>{$parametros['codigo_validador']}</strong></h4>
                            <small style=\"color:#666;\"> Caso não tenha sido você, entre em contato com a nossa equipe.</small>
                            <br/>
                        </td>
                    </tr>
                    <table>
                </body>
                </html>";
        return $html;
    }

    function FormatarEmailMapas($parametros){
        $html = "<!DOCTYPE html>
                <html lang=\"en\">
                <head>
                    <meta charset=\"UTF-8\">
                    <title>Title</title>
                </head>
                <style>
                
                    body {
                        padding: 0;
                        margin: 0;
                        background: #f8fafa;
                        /*padding-top:20px;*/
                        padding-bottom: 40px;
                        width: 100%;
                        /*text-align: center;*/
                    }
                
                    h1, h2, h3, h4 {
                        font-family: \"Poppins\", sans-serif;
                        letter-spacing: 0.05em;
                        line-height: 120%;
                        color: #666;
                    }
                
                    p {
                        font-family: \"Poppins\", sans-serif;
                        font-size: 14px;
                        color: #666;
                    }
                </style>
                <body>
                <table width=\"600px\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" style=\"margin:auto;padding-top: 30px\">
                    <tr>
                        <td style=\"width: 400px; height: 100px; padding: 0; margin: 0; background-color: #DD2C00; border-radius: 4px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; text-align: center; background-repeat: no-repeat\">
                            <a href=\"javascript:void(0)\" class=\"page-logo-link\">
                                <img src=\"http://linkmonitoramento.com.br/link_report/img/logo.png\" alt=\"SmartAdmin WebApp\"
                                     aria-roledescription=\"logo\">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style=\"background-color:#EEEEEE; padding-left: 20px; padding-bottom: 10px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; padding-right: 20px\" ; align=\"justify\">
                            <p> Olá, <strong style=\"color: #DD2C00\">{$parametros['nome']}</strong>!</p>
                            <p> Gostariamos de informar que seu cadastro foi realizado com sucesso no mapas.</p>
                            <p> Seu usuário e senha provisória é {$parametros['email']}, {$parametros['senha']}, respectivamente.</p>
                            <p>Para acessar o mapas, clique no link abaixo.</p>
                            <div style=\"border-collapse:collapse; border-radius:6px; text-align:center; display:block; border:none; background:#DD2C00; \">
                                <a href=\"http://linkmonitoramento.com.br/app\" target=\"_blank\" style=\"color:#3b5998; padding:6px 20px 10px 20px; text-decoration:none; display:block\" data-linkindex=\"1\">
                                    <center style=\"font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif; color:#FFFFFF; font-weight:500; font-size:17px;\"> Acessar sistema </center>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <table>
                </body>
                </html>";
        return $html;
    }

    function UpdateParamsSenha($params){
        $pdo = $this->getConexao();
        $sql  = "UPDATE usuario SET codigo_validador = {$params['codigo_validador']}, hash_validador = '{$params['hash']}' WHERE id = {$params['id']}";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }
    function UpdateSenha($params){
        $pdo = $this->getConexao();
        $sql  = "UPDATE usuario SET codigo_validador = null, hash_validador = null, senha = '{$params['senha']}' WHERE hash_validador = '{$params['hash']}' AND codigo_validador = '{$params['codigo']}'";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }

    function VerificarCodigoValidadorHash($params){
	    $pdo = $this->getConexao();
	    $sql = "SELECT codigo_validador, hash_validador FROM usuario WHERE id = {$params['id']}";
	    $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function VerificarHash($codigo,$hash){
        $pdo = $this->getConexao();
        $sql = "SELECT codigo_validador, hash_validador,id FROM usuario WHERE hash_validador = '$hash' AND codigo_validador = '$codigo' ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function UpdateParamsCodigoValidador($params){
        $pdo = $this->getConexao();
        $sql  = "UPDATE usuario SET hash_duas_etapas = '{$params['hash_duas_etapas']}' WHERE id = {$params['id']}";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }

    function VerificarHashDuasEtapas($params){
        $pdo = $this->getConexao();
        $sql = "SELECT id FROM usuario WHERE id = {$params['id']} AND hash_duas_etapas = '{$params['hash_duas_etapas']}'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function listarErpUsuarioLaunch()
    {
        $pdo = new Conexao();
        //query que busca registroscom LIMIT E OFFSET
        $sql = "(SELECT erp.usuario.usuario, erp.usuario.senha, erp.usuario_launch.sistema
                    FROM erp.usuario_launch
                    INNER JOIN erp.usuario ON (erp.usuario_launch.id_usuario = erp.usuario.id)
                    WHERE  
                    erp.usuario.excluido IS NULL 
                    AND erp.usuario_launch.excluido IS NULL 
                    AND (erp.usuario.data_hora_expirado > NOW() OR erp.usuario.data_hora_expirado IS NULL) 
                    AND erp.usuario.ativo = 1
                    AND erp.usuario_launch.sistema = 1
                    AND erp.usuario_launch.id_usuario_vinculo = {$this->getId()}  
                ) 
                UNION
                (SELECT monitoramento.usuario.usuario, monitoramento.usuario.senha, monitoramento.usuario_launch.sistema
                    FROM monitoramento.usuario_launch
                    INNER JOIN monitoramento.usuario ON (monitoramento.usuario_launch.id_usuario = monitoramento.usuario.id)
                    WHERE  
                    monitoramento.usuario.excluido IS NULL 
                    AND monitoramento.usuario_launch.excluido IS NULL 
                    AND (monitoramento.usuario.data_hora_expirado > NOW() OR monitoramento.usuario.data_hora_expirado IS NULL) 
                    AND monitoramento.usuario.ativo = 1
                    AND monitoramento.usuario_launch.sistema IS NULL
                    AND monitoramento.usuario_launch.id_usuario_vinculo = {$this->getId()} 
                    AND monitoramento.usuario_launch.id_usuario != {$this->getId()}
                )
              ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
