<?

class UsuarioConfiguracao
{
	private $id;
	private $id_usuario;
	private $id_sessao;
	private $id_relatorio;
	private $id_campo_modulo;
	private $configuracao;
	private $configuracaoResumo;
	private $nome_sessao;
	private $latitude;
	private $longitude;
	private $dir_modulo;
	private $valor_campo;

	public function getDirModulo()
	{
		return $this->dir_modulo;
	}

	public function setDirModulo($dir_modulo)
	{
		$this->dir_modulo = $dir_modulo;
	}

	public function getValorCampo()
	{
		return $this->valor_campo;
	}

	public function setValorCampo($valor_campo)
	{
		$this->valor_campo = $valor_campo;
	}

	public function getIdCampoModulo()
	{
		return $this->id_campo_modulo;
	}

	public function setIdCampoModulo($id_campo_modulo)
	{
		$this->id_campo_modulo = $id_campo_modulo;
	}

	public function getIdRelatorio()
	{
		return $this->id_relatorio;
	}

	public function setIdRelatorio($id_relatorio)
	{
		$this->id_relatorio = $id_relatorio;
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

	public function setId($arg)
	{
		$this->id = $arg;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setIdUsuario($arg)
	{
		$this->id_usuario = $arg;
	}

	public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	public function setIdSessao($arg)
	{
		$this->id_sessao = $arg;
	}

	public function getIdSessao()
	{
		return $this->id_sessao;
	}

	public function setConfiguracao($arg)
	{
		$this->configuracao = $arg;
	}

	public function getConfiguracao()
	{
		return $this->configuracao;
	}

	public function setConfiguracaoResumo($arg)
	{
		$this->configuracaoResumo = $arg;
	}

	public function getConfiguracaoResumo()
	{
		return $this->configuracaoResumo;
	}

	public function setNomeSessao($arg)
	{
		$this->nome_sessao = $arg;
	}

	public function getNomeSessao()
	{
		return $this->nome_sessao;
	}

	public function AdicionarUsuarioConfiguracaoAntigo()
	{

		$pdo = new Conexao();
		$sql = "INSERT INTO usuario_configuracao (id_usuario,id_sessao,configuracao)values(:id_usuario,:id_sessao,:configuracao)";
		$sql .= " ON DUPLICATE KEY UPDATE configuracao = :configuracao";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->bindParam(':id_sessao', $this->getIdSessao(), PDO::PARAM_INT);
		$stmt->bindParam(':configuracao', $this->getConfiguracao(), PDO::PARAM_STR);
		$rs = $stmt->execute();

		return $rs;
	}

	public function AdicionarUsuarioConfiguracao()
	{
		$pdo  = new Conexao();
		$null = NULL;

		//tabela do sistema novo
		$sql  = "INSERT INTO rp_configuracao_usuario SET
                     id_usuario = ?,
                     id_configuracao_modulo_campos = ?,
                     valor = ?
                 ON DUPLICATE KEY UPDATE
                     valor = ?";
		$stmt = $pdo->prepare($sql);
		$x    = 0;
		$stmt->bindParam(++$x, $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getIdCampoModulo(), PDO::PARAM_INT);
		if ($this->getValorCampo() != "") {
			$stmt->bindParam(++$x, $this->getValorCampo(), PDO::PARAM_STR);
			$stmt->bindParam(++$x, $this->getValorCampo(), PDO::PARAM_STR);
		} else {
			$stmt->bindParam(++$x, $null, PDO::PARAM_NULL);
			$stmt->bindParam(++$x, $null, PDO::PARAM_NULL);
		}

		return $stmt->execute();
	}

	/**
	 * Remove as configuracoes do modulo especificado
	 */
	public function LimparConfiguracoes()
	{
		$pdo = new Conexao();

		if ($this->getDirModulo() != "" && $this->getIdUsuario() != "") {
			$sql = "DELETE rp_configuracao_usuario FROM rp_configuracao_usuario
                    INNER JOIN rp_configuracao_relatorios_campos crc ON (crc.id = rp_configuracao_usuario.`id_configuracao_modulo_campos`)
                    INNER JOIN rp_modulo ON (rp_modulo.id = crc.`id_modulo`)
                    WHERE rp_modulo.dir = ?
                    AND rp_configuracao_usuario.id_usuario = ?";

			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(1, $this->getDirModulo(), PDO::PARAM_STR);
			$stmt->bindParam(2, $this->getIdUsuario(), PDO::PARAM_INT);

			return $stmt->execute();
		} else {
			return false;
		}
	}

	public function AdicionarConfiguracoesPessoais()
	{
		$pdo = new Conexao();
		$sql = "INSERT INTO configuracoes_pessoais (id_usuario,configuracao)values(:id_usuario,:configuracao)";
		$sql .= " ON DUPLICATE KEY UPDATE configuracao = :configuracao";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->bindParam(':configuracao', $this->getConfiguracao(), PDO::PARAM_STR);

		return $stmt->execute();
	}

	public function AdicionarUsuarioConfiguracaoResumo()
	{
		$pdo = new Conexao();
		$sql = "INSERT INTO usuario_configuracao (id_usuario,id_sessao,configuracao_resumo)values(:id_usuario,:id_sessao,:configuracao_resumo)";
		$sql .= " ON DUPLICATE KEY UPDATE configuracao_resumo = :configuracao_resumo";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->bindParam(':id_sessao', $this->getIdSessao(), PDO::PARAM_INT);
		$stmt->bindParam(':configuracao_resumo', $this->getConfiguracaoResumo(), PDO::PARAM_STR);
		$rs = $stmt->execute();

		return $rs;
	}

	public function ListarComandosFranquia()
	{
		$pdo  = new Conexao();
		$sql  = "
            SELECT 
        	comando.id,
        	comando.rotulo,
        	erp_modelo_comando_franqueado.id_comando
        FROM 
            erp_modelo_comando_franqueado
        INNER JOIN 
            comando ON (erp_modelo_comando_franqueado.id_comando = comando.id)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $rs;
	}

	public function ChecarClienteFranquia($arvore)
	{
		$pdo  = new Conexao();
		$sql  = "
                SELECT 
                grupo.id,
                cliente.id AS id_cliente,
                franqueado.id AS id_franqueado
                FROM grupo
                LEFT JOIN cliente ON (cliente.id_grupo = grupo.id)
                LEFT JOIN franqueado ON (franqueado.id_grupo = grupo.id AND franqueado.id NOT IN (52,51) AND franqueado.master != 1)
                WHERE grupo.id IN ($arvore)
                AND (
				   franqueado.id != ''
				   OR
				   cliente.id != ''
				);
        ";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $rs;
	}

	public function ListarComandos()
	{
		$pdo  = new Conexao();
		$sql  = "
            SELECT 
        	comando.id as id_comando,
        	comando.rotulo
        FROM 
            comando
        LEFT JOIN 
            erp_modelo_comando_franqueado ON (erp_modelo_comando_franqueado.id_comando = comando.id)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $rs;
	}

	public function RemoverComandosUsuario($id_usuario)
	{
		$pdo  = new Conexao();
		$sql  = " DELETE
                FROM usuario_comando
                WHERE id_usuario = $id_usuario";
		$stmt = $pdo->prepare($sql);
		$rs   = $stmt->execute();

		return $rs;
	}

	public function BuscarComandosUsuario()
	{
		$pdo  = new Conexao();
		$sql  = "
        SELECT 
        	usuario_comando.id_comando,
        	usuario_comando.id_usuario,
        	comando.id,
        	comando.rotulo,
        	erp_modelo_comando_franqueado.id_comando
        FROM 
            erp_modelo_comando_franqueado
        INNER JOIN 
            comando ON (erp_modelo_comando_franqueado.id_comando = comando.id)
        INNER JOIN 
            usuario_comando ON (erp_modelo_comando_franqueado.id_comando = usuario_comando.id_comando)
        WHERE
            usuario_comando.id_usuario = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->execute();
		$rs = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $rs;
	}

	public function AdicionarUsuarioComandos($comandos, $usuario)
	{
		$pdo = new Conexao();
		$sql = "INSERT INTO usuario_comando (id_usuario,id_comando) VALUES ";
		foreach ($comandos as $indice => $value) {
			$sql .= " ($usuario,$value),";
		}
		$sql  = substr($sql, 0, -1);
		$stmt = $pdo->prepare($sql);
		$rs   = $stmt->execute();

		return $rs;
	}

	public function ListarPaginacaoUsuarioConfiguracao($pagina, $numeroRegistros, $numeroInicioRegistro, $id_grupo, $busca = "", $filtro = "", $ordem = "")
	{
		$pdo = new Conexao();
		$sql = " SELECT count(*) as total";
		$sql .= " FROM usuario
    		      INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
    		      INNER JOIN fuso_horario ON (fuso_horario.id = usuario.id_fuso_horario)";
		$sql .= " WHERE (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%') AND usuario.excluido IS NULL ";
		if (trim($busca) != "") {
			$sql .= " AND (usuario.nome LIKE '%$busca%' OR usuario.usuario LIKE '%$busca%' OR usuario.email LIKE '%$busca%' OR grupo.nome LIKE '%$busca%') ";
		}
		if ($filtro != "") {
			$sql .= " ORDER BY $filtro $ordem";
		} else {
			$sql .= " ORDER BY usuario.id DESC";
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$totalRegistros = $stmt->fetch(PDO::FETCH_OBJ);

		$sql2 = "SELECT
		            usuario.id,
		            usuario.nome,
		            usuario.usuario,
		            grupo.nome as nome_grupo";

		$sql2 .= " FROM usuario
    		       INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
    		       INNER JOIN fuso_horario ON (fuso_horario.id = usuario.id_fuso_horario)";
		$sql2 .= " WHERE (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%') AND usuario.excluido IS NULL ";
		if (trim($busca) != "") {
			$sql2 .= " AND (usuario.nome LIKE '%$busca%' OR usuario.usuario LIKE '%$busca%' OR usuario.email LIKE '%$busca%' OR grupo.nome LIKE '%$busca%') ";
		}
		if ($filtro != "") {
			$sql2 .= " ORDER BY $filtro $ordem";
		} else {
			$sql .= " ORDER BY usuario.id DESC";
		}
		$sql2 .= " LIMIT  $numeroInicioRegistro,$numeroRegistros ";

		$stmt = $pdo->prepare($sql2);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_OBJ);

		return array($listar, $totalRegistros);
	}

	public function ListaUsuarioConfiguracao()
	{
		$pdo  = new Conexao();
		$sql  = "SELECT * FROM usuario_configuracao WHERE id_usuario = :id_usuario AND id_sessao = :id_sessao";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->bindParam(':id_sessao', $this->getIdSessao(), PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ);
	}

	public function ListarConfiguracoes()
	{
		$pdo  = new Conexao();
		$sql  = "SELECT
                    rp_modulo.dir AS modulo,
                    rp_configuracao_relatorios_campos.id AS id_campo,
                    rp_configuracao_campos.name_id,
                    rp_configuracao_usuario.valor
                FROM rp_configuracao_relatorios_campos
                    LEFT JOIN rp_configuracao_usuario ON (rp_configuracao_usuario.id_configuracao_modulo_campos = rp_configuracao_relatorios_campos.id)
                    INNER JOIN rp_configuracao_campos ON (rp_configuracao_campos.id = rp_configuracao_relatorios_campos.id_configuracao_campos)
                    INNER JOIN rp_modulo ON (rp_modulo.id = rp_configuracao_relatorios_campos.id_modulo)
                WHERE id_usuario = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function ListaConfiguracoesPessoais()
	{
		$pdo  = new Conexao();
		$sql  = "SELECT * FROM configuracoes_pessoais WHERE id_usuario = :id_usuario";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
		$stmt->execute();
		$rs = $stmt->fetch(PDO::FETCH_OBJ);

		return @unserialize($rs->configuracao);
	}

	public function LiberarSms($eventos)
	{
		$pdo = new Conexao();
		if (count($eventos > 0)) {
			$sql  = "UPDATE usuario_prioridade_evento_tipo SET sms_ativado = 0 WHERE id_usuario = ?";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(1, $this->getIdUsuario(), PDO::PARAM_INT);
			$rs = $stmt->execute();

			foreach ($eventos as $evento) {

				$sql  = "UPDATE usuario_prioridade_evento_tipo SET sms_ativado = 1 WHERE id_usuario = ? AND id_evento_tipo = ?";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(1, $this->getIdUsuario(), PDO::PARAM_INT);
				$stmt->bindParam(2, $evento, PDO::PARAM_INT);
				$rs = $stmt->execute();

				if ($rs == 0) {
					return false;
				}
			}
		}
		return true;
	}
}