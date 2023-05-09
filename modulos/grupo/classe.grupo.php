<?

class Grupo
{
	protected $id;
	private   $id_grupo_pai;
	private   $id_modelo;
	private   $id_veiculo;
	private   $id_evento_tipo;
	private   $nome;
	private   $arvore;
	private   $data_hora_cadastro;
	private   $excluido;
	private   $funcionalidade_acessada;
	private   $usuario_logado;
	private   $id_categoria;

	/**
	 * @return mixed
	 */
	public function getIdCategoria()
	{
		return $this->id_categoria;
	}

	/**
	 * @param mixed $id_categoria
	 */
	public function setIdCategoria($id_categoria)
	{
		$this->id_categoria = $id_categoria;
	}


	public function setId($arg)
	{
		$this->id = $arg;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setIdEventoTipo($arg)
	{
		$this->id_evento_tipo = addslashes($arg);
	}

	public function getIdEventoTipo()
	{
		return $this->id_evento_tipo;
	}

	public function setIdVeiculo($arg)
	{
		$this->id_veiculo = addslashes($arg);
	}

	public function getIdVeiculo()
	{
		return $this->id_veiculo;
	}

	public function setId_grupo_pai($arg)
	{
		$this->id_grupo_pai = $arg;
	}

	public function getId_grupo_pai()
	{
		return $this->id_grupo_pai;
	}

	public function setNome($arg)
	{
		$this->nome = $arg;
	}

	public function getNome()
	{
		return $this->nome;
	}

	public function setId_modelo($arg)
	{
		$this->id_modelo = $arg;
	}

	public function getId_modelo()
	{
		return $this->id_modelo;
	}

	public function setArvore($arg)
	{
		$this->arvore = $arg;
	}

	public function getArvore()
	{
		return $this->arvore;
	}

	public function setData_hora_cadastro($arg)
	{
		$this->data_hora_cadastro = $arg;
	}

	public function getData_hora_cadastro()
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

	public function setFuncionalidadeAcesso($arg)
	{
		$this->funcionalidade_acessada = $arg;
	}

	public function getFuncionalidadeAcesso()
	{
		return $this->funcionalidade_acessada;
	}

	public function setUsuarioLogado($arg)
	{
		$this->usuario_logado = $arg;
	}

	public function getUsuarioLogado()
	{
		return $this->usuario_logado;
	}

	/**
	 * @return Conexao
	 */
	public function getConexao()
	{
		return $this->conexao;
	}

	/**
	 * @param Conexao $conexao
	 */
	public function setConexao($conexao)
	{
		$this->conexao = $conexao;
	}

	public function __construct($conexao = "")
	{
		if ($conexao) {
			$this->conexao = $conexao;
		} else {
			$this->conexao = new Conexao();
		}
	}
	public function MontarArvoreUpdate($id)
    {
        $pdo  = $this->getConexao();
        $sql  = "SELECT grupo.arvore FROM grupo where id = {$id}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetch(PDO::FETCH_OBJ);

        ($rs->arvore == "") ? $arvore = ';' . $id . ';' : $arvore = $rs->arvore . $id. ';';

        return $arvore;
    }
	
	public function Adicionar()
	{
		$pdo  = $this->getConexao();
		$sql  = "SELECT grupo.arvore FROM grupo where id = {$this->id_grupo_pai}";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs = $stmt->fetch(PDO::FETCH_OBJ);

		($rs->arvore == "") ? $arvore = ';' . $this->id_grupo_pai . ';' : $arvore = $rs->arvore . $this->id_grupo_pai . ';';

		$sql  = 'INSERT INTO grupo (id_grupo_pai, nome, arvore, data_hora_cadastro) VALUES (?,?,?,UTC_TIMESTAMP())';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x, $this->getId_grupo_pai(), PDO::PARAM_INT);
		$stmt->bindParam(++$x, $this->getNome(), PDO::PARAM_STR);
		$stmt->bindParam(++$x, $arvore, PDO::PARAM_STR);
//		$stmt->bindParam(++$x, $this->getIdCategoria(), PDO::PARAM_STR);
		$stmt->execute();

		return $pdo->lastInsertId();
	}

    public function AdicionarGrupoFranqueado()
    {
//        if( $cadastrado = $this->VerificarCadastrado() )
//            return $cadastrado;

        $pdo = $this->getConexao();
        $sql = 'INSERT INTO grupo (`id_grupo_pai`,`nome`,`arvore`,`data_hora_cadastro`) VALUES (?,?,?,UTC_TIMESTAMP)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1,$this->id_grupo_pai,PDO::PARAM_INT);
        $stmt->bindParam(2,$this->nome,PDO::PARAM_STR);
        $stmt->bindParam(4,$this->arvore,PDO::PARAM_STR);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

	public function Modificar()
	{
		$pdo  = $this->getConexao();
		$sql  = 'UPDATE grupo SET `nome` = ? WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->nome, PDO::PARAM_STR);
		$stmt->bindParam(2, $this->id, PDO::PARAM_INT);

		return $stmt->execute();
	}

	public function LimparAcoes()
	{
		$pdo  = $this->getConexao();
		$sql  = 'DELETE FROM map_grupo_acao WHERE id_grupo = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_STR);
		return $stmt->execute();

	}
    public function LimparAcoesArvore($acoes)
    {
        if(count($acoes) == 0) return false;

        $acoes = implode(',',$acoes);
        $pdo  = $this->getConexao();
        $sql  = 'DELETE map_grupo_acao.* FROM map_grupo_acao INNER JOIN grupo ON (map_grupo_acao.id_grupo = grupo.id) WHERE id_acao NOT IN ('.$acoes.') AND (id_grupo = ?  OR grupo.`arvore` LIKE \'%;'.$this->getId().';%\')';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $this->getId(), PDO::PARAM_STR);
        return $stmt->execute();

    }

	public function AdicionarGrupoAcao($id_grupo, $permissao, $customizada = 0)
	{
		$pdo = $this->getConexao();

		$sql  = "INSERT INTO map_grupo_acao SET id_grupo = ?, id_acao = ?, customizada = $customizada";
		$stmt = $pdo->prepare($sql);

		$stmt->bindParam(1, $id_grupo, PDO::PARAM_INT);
		$stmt->bindParam(2, $permissao, PDO::PARAM_INT);

		return $stmt->execute();
	}
    public function AdicionarPermissaoCliente($id_grupo)
    {
        $pdo = $this->getConexao();

        $sql  = "INSERT INTO map_grupo_acao (id_grupo, id_acao ,customizada ) SELECT $id_grupo,id_acao,0 FROM cliente_permissao ";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }

    public function AdicionarGrupoAcaoDuplicate($id_grupo, $permissao, $customizada = 0)
    {
        $pdo = $this->getConexao();

        $sql  = "INSERT INTO map_grupo_acao (id_grupo , id_acao, customizada) VALUES (:id_grupo,:id_acao,:customizada) ON DUPLICATE KEY UPDATE id_grupo = :id_grupo";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt->bindParam(':id_acao', $permissao, PDO::PARAM_INT);
        $stmt->bindParam(':customizada', $customizada, PDO::PARAM_INT);

        return $stmt->execute();
    }
	public function Remover($lista)
	{
		$lista = implode(",", $lista);
		$pdo  = $this->getConexao();
		$sql  = "UPDATE grupo SET `excluido` = now()  WHERE id IN ($lista)";
		$stmt = $pdo->prepare($sql);
        $rs = $stmt->execute();
		return $rs;
	}
    public function AtualizarPai($id_novo_pai)
    {
        $pdo  = $this->getConexao();
        $arvore = $this->MontarArvoreUpdate($id_novo_pai);
        $sql  = "UPDATE grupo SET `id_grupo_pai` = ?, arvore = ?  WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $id_novo_pai, PDO::PARAM_INT);
        $stmt->bindParam(2, $arvore, PDO::PARAM_INT);
        $stmt->bindParam(3, $this->getId(), PDO::PARAM_INT);
        $rs = $stmt->execute();

        return $rs;
    }

	public function ListarPaginacao($id_grupo, $numeroRegistros, $numeroInicioRegistro, $busca = "", $filtro = "", $ordem = "", $categoria = "")
	{
		$pdo    = $this->getConexao();
		$select = "
            SELECT
                grupo.id AS id,
                grupo.id_grupo_pai,
                grupo.nome,
                grupo.data_hora_cadastro,
                grupo_pai.nome as nome_grupo_pai";
		$count  = "
            SELECT count(*) as total";
		$from   = "
            FROM grupo
			    LEFT JOIN franqueado  ON (franqueado.id_grupo = grupo.id)
			    LEFT JOIN grupo as grupo_pai ON (grupo_pai.id = grupo.id_grupo_pai)
";
		$where  = "
            WHERE  grupo.arvore LIKE '%;$id_grupo;%'
                AND grupo.excluido IS NULL";

		if ($busca != "") {
			$where .= "
                AND (grupo.nome LIKE '%$busca%' OR grupo.id = '$busca' OR grupo_pai.nome LIKE '%$busca%')";
		}


		$sql_count = $count . $from . $where;

		if ($filtro != "") {
			$sql = $select . $from . $where . " ORDER BY grupo.$filtro $ordem ";
		} else {
			$sql = $select . $from . $where . " ORDER BY grupo.id DESC  ";
		}

		if (is_numeric($numeroRegistros) & is_numeric($numeroInicioRegistro)) {
			$sql .= " LIMIT $numeroInicioRegistro,$numeroRegistros ";
		}
		$stmt = $pdo->prepare($sql_count);
		$stmt->execute();
		$totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC);
//        echo $sql;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array($listar, $totalRegistros);
	}

	public function ListarJsonGrupo($id_grupo, $busca = "", $omitir = "")
	{
		$pdo = $this->getConexao();
		$sql = "
            SELECT
                grupo.id,
                grupo.nome as label,
                grupo.nome as text,
                grupo.nome as rotulo,
                grupo.nome as nome,
                grupo.id as value
            FROM
                grupo
                LEFT JOIN grupo as grupo_pai ON (grupo_pai.id = grupo.id_grupo_pai)
            WHERE
                (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')
                AND grupo.excluido IS NULL
                AND grupo.nome != ''";

		if (trim($busca) != "") {
			$sql .= " AND grupo.nome LIKE '%" . trim($busca) . "%'";
		}
		if ($omitir) {
			$sql .= " AND grupo.id != $omitir";
		}

		$sql .= " LIMIT 200";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$lista = $stmt->fetchAll(PDO::FETCH_OBJ);

		if (count($lista) <= 0) {
			$lista[] = ["id" => '', "label" => ROTULO_NENHUM_REGISTRO_ENCONTRADO, "rotulo" => ROTULO_NENHUM_REGISTRO_ENCONTRADO, "value" => ""];
		}

		return $lista;
	}
    public function ListarJsonGrupoFranquias($id_grupo = '', $busca = "", $omitir = "", $pai_franquias = false)
    {
        $pdo = $this->getConexao();
        $sql = "
            SELECT
                grupo.id,
                CONCAT(grupo.nome,' - ',franqueado.codigo) as label,
                CONCAT(grupo.nome,' - ',franqueado.codigo) as text,
                CONCAT(grupo.nome,' - ',franqueado.codigo) as rotulo,
                CONCAT(grupo.nome,' - ',franqueado.codigo) as value
            FROM
                grupo
                 INNER JOIN franqueado ON (franqueado.id_grupo = grupo.id)
                LEFT JOIN grupo as grupo_pai ON (grupo_pai.id = grupo.id_grupo_pai)
            WHERE grupo.excluido IS NULL
                AND grupo.nome != ''";

        if($id_grupo != '')
        {
            $sql .= " AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')";
        }
		if($pai_franquias){
			$sql .= " AND grupo.id_grupo_pai = 157 ";
		}
        if (trim($busca) != "") {
            $sql .= " AND (grupo.nome LIKE '%" . trim($busca) . "%' OR franqueado.codigo LIKE '%" . trim($busca) . "%')";
        }
        if ($omitir) {
            $sql .= " AND grupo.id != $omitir";
        }

        $sql .= " LIMIT 200";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lista = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (count($lista) <= 0) {
            $lista[] = ["id" => '', "label" => ROTULO_NENHUM_REGISTRO_ENCONTRADO, "rotulo" => ROTULO_NENHUM_REGISTRO_ENCONTRADO, "value" => ""];
        }

        return $lista;
    }

	public function ListarJsonGrupoMultiSelect($id_grupo, $busca = "", $omitir = "")
	{
		$pdo = $this->getConexao();
		$sql = "
            SELECT
                grupo.id,
                grupo.nome as label,
                grupo.nome as text,
                grupo.nome as rotulo,
                grupo.id as value
            FROM
                grupo
                LEFT JOIN grupo as grupo_pai ON (grupo_pai.id = grupo.id_grupo_pai)
                INNER JOIN modelo ON (modelo.id = grupo.id_modelo)
            WHERE
                (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')
                AND grupo.excluido IS NULL
                AND grupo.nome != ''";

		if (trim($busca) != "") {
			$sql .= " AND grupo.nome LIKE '%" . trim($busca) . "%'";
		}
		if ($omitir) {
			$sql .= " AND grupo.id != $omitir";
		}

		$sql .= " LIMIT 200";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$lista = $stmt->fetchAll(PDO::FETCH_OBJ);

		if (count($lista) <= 0) {
			$lista[] = ["id" => '', "label" => ROTULO_NENHUM_REGISTRO_ENCONTRADO, "rotulo" => ROTULO_NENHUM_REGISTRO_ENCONTRADO, "value" => ""];
		}

		return $lista;
	}


	public function Editar()
	{
		$pdo  = $this->getConexao();
		$sql  = "
            SELECT
                grupo.id AS id_grupo,
                grupo.nome AS nome_grupo,
                grupo_pai.nome AS nome_grupo_pai,
                grupo_pai.id AS id_grupo_pai,
                grupo.arvore,
                IF(franqueado.id_grupo IS NULL,cliente.id_grupo,franqueado.id_grupo) as id_grupo_matriz,
                grupo.id_categoria
            FROM
                grupo
                LEFT JOIN grupo as grupo_pai ON (grupo_pai.id = grupo.id_grupo_pai)
                LEFT JOIN franqueado  ON (franqueado.id_grupo = grupo.id)
                LEFT JOIN cliente ON (cliente.id_grupo = grupo.id)
            WHERE
                grupo.id = $this->id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
    public function GruposAcoes()
    {
        $pdo = $this->getConexao();
        $sql = "
         SELECT
          grupo.id,
          grupo.nome,
          grupo.id_grupo_pai
         FROM
          grupo
        LEFT JOIN franqueado ON (grupo.id = franqueado.id_grupo)
        WHERE grupo.excluido IS NULL AND franqueado.id IS NOT NULL OR grupo.id IN (1,157)
            ";

        $sql .= " ORDER BY grupo.nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $vetor = $this->MontarArvore($rs,'');

        return $vetor;
    }
    public function GruposAcoesTree()
    {
        $pdo = $this->getConexao();
        $sql = "
         SELECT
          grupo.id,
          grupo.nome,
          grupo.id_grupo_pai
         FROM
          grupo
        LEFT JOIN franqueado ON (grupo.id = franqueado.id_grupo)
        WHERE grupo.excluido IS NULL AND franqueado.id IS NOT NULL OR grupo.id IN (1,157, 6463)
            ";

        $sql .= " ORDER BY grupo.id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $vetor = $this->MontarArvore($rs,'');
        return $vetor;
    }
    public function MontarArvore( &$registros, $idPai,$nivel = 0 )
    {
        $retorno = null;
        // Percorre todos os registros
        foreach( $registros as $registro )
        {
            // Se o pai do registro for o noAtual
            if( (int)($registro['id_grupo_pai']) == (int)($idPai))
            {
                $retorno[$registro['id']] = $registro;
                $retorno[$registro['id']]['nivel'] = $nivel + 1;
                $retorno[$registro['id']]['filhos'] = $this->MontarArvore( $registros, $registro['id'],$nivel + 1);

            }

        }
        return $retorno;
    }
    public function MontarMenuArvore($grupos,&$html = '')
    {

        foreach ($grupos as  $grup) {
            $selecionado = "";
            if ($grup['selecionado'] > 0) {
                $selecionado = 'data-checkstate="checked"';
            }
            if( @count($grup['filhos']))
            {
                $html .= "<li $selecionado id='{$grup['id']} ' class='jstree-open'>{$grup['nome']}";
                $html .= '<ul>';
                $this->MontarMenuArvore($grup['filhos'],$html);
                $html .= '</ul>';
                $html .= "</li>";
            }
            else {
                $html .= '<li ' . $selecionado . ' id=' . $grup['id'] . ' class="tree">' . $grup['nome'] . '</li>';
            }

        }

        return $html;
    }

	public function ComboGrupo()
	{
		$pdo = $this->getConexao();
		$sql = "
            SELECT id, nome
            FROM grupo
            WHERE status != 1";

		if ($_SESSION['SESSION_PAINEL_GRUPO'] != 1) {
			$sql .= " AND ID != 1";
		}

		$sql .= " ORDER BY nome ASC";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs               = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$lista            = new stdClass();
		$lista->resultado = $rs;

		if (count($lista->resultado) > 0) {
			$html = "";
			foreach ($lista->resultado as $linha) {
				$html .= "<option value=\"$linha[id]\" ";
				if ($linha['id'] == $this->id) {
					$html .= " selected ";
				}
				$html .= ">$linha[nome]</option>\n";
			}
		} else {
			$html = "<option value=\"0\">Nenhum grupo selecionado</option>";
		}

		return $html;
	}

	public function ChecarPermissao($id_grupo,$acao)
	{
		$pdo  = $this->getConexao();
		$sql  = "
            SELECT
                map_grupo_acao.id_grupo,
                map_grupo_acao.id_acao,
                map_acao.acao
            FROM
                map_grupo_acao
                INNER JOIN map_acao ON (map_grupo_acao.id_acao = map_acao.id)
            WHERE
                map_grupo_acao.id_grupo = $id_grupo
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

	public function ChecarPermissaoERP($acao)
	{
		$pdo  = $this->getConexao();
		$sql  = "
            SELECT
                usuario_acao.id_erp_usuario,
                usuario_acao.id_erp_acao,
                erp_acao.acao
            FROM
                usuario_acao
                INNER JOIN erp_acao ON (usuario_acao.id_erp_acao = erp_acao.id)
            WHERE
                usuario_acao.id_erp_usuario = {$_SESSION['usuario']['id']}
                AND erp_acao.acao LIKE '%{$acao}%'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$listar            = new stdClass();
		$listar->resultado = $rs;

		return $listar->resultado;
	}

	public function VetorPermissao()
	{
		$pdo  = $this->getConexao();
		$sql  = "
            SELECT
                map_acao.acao,
                map_grupo_acao.id_acao,
                map_grupo_acao.id_grupo
            FROM
                map_grupo_acao
                INNER JOIN map_acao ON (map_grupo_acao.id_acao = map_acao.id)
                AND map_grupo_acao.id_grupo = {$this->id}";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$listar            = new stdClass();
		$listar->resultado = $rs;

		return $listar->resultado;
	}


	public function ListarConfiguracoes($idGrupo)
	{

		if (!is_numeric($idGrupo)) {
			return false;
		}

		$pdo = $this->getConexao();
		// Recupera a logomarca, titulo da janela, o idioma e o tipo de mapa
		$sql  = "
            SELECT
                grupo.id_modelo,
                modelo.arquivo_logomarca,
                modelo.titulo_1,
                modelo.titulo_2,
                modelo.id_idioma,
                modelo.id_tipo_mapa,
                idioma.sigla as sigla_idioma
            FROM
                grupo
                INNER JOIN modelo ON (modelo.id = grupo.id_modelo)
                LEFT JOIN idioma ON (idioma.id = modelo.id_idioma)
            WHERE
                grupo.id = $idGrupo";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;

		return $resultado->resultado[0];
	}

	public function ListarVeiculos($id_grupo, $limit = 1000)
	{
		$pdo = $this->getConexao();

		$sql  = "SELECT
                    grupo.nome                AS nome_grupo,
                    motorista.nome            AS nome_motorista,
                    veiculo.id,
                    veiculo.rotulo,
                    veiculo.complemento_placa,
                    veiculo.origem,
                    veiculo.destino,
                    ultimas.ignicao,
                    ultimas.data_hora,
                    ultimas.velocidade,
                    ultimas.odometro,
                    ultimas.logradouro,
                    ultimas.telemetria,
                    ultimas.entradas,
                    ultimas.saidas,
                    ultimas.latitude,
                    ultimas.longitude,
                    ultimas.horimetro,
                    veiculo_interface.*,
                    icone.arquivo as icone,
                    icone2.arquivo as icone2
                FROM grupo
                    INNER JOIN veiculo ON (veiculo.id_grupo = grupo.id)
                    INNER JOIN ultimas ON (ultimas.id_veiculo = veiculo.id AND ultimas.id_rastreador = veiculo.id_rastreador)
                    INNER JOIN tipo_veiculo ON (tipo_veiculo.id = veiculo.id_tipo_veiculo)
                    LEFT JOIN motorista ON (motorista.id = veiculo.id_motorista)
                    INNER JOIN veiculo_interface ON (veiculo_interface.id_veiculo = veiculo.id)
                    INNER JOIN icone ON (icone.id = tipo_veiculo.id_icone)
                    INNER JOIN icone AS icone2 ON (icone2.id = tipo_veiculo.id_icone2)
                WHERE (	grupo.id = $id_grupo
                		OR grupo.arvore LIKE '%;$id_grupo;%'
                		OR veiculo.id IN (	SELECT id_veiculo
                							FROM grupo_veiculo
                							INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
                							WHERE ( grupo.id = $id_grupo
											OR grupo.arvore LIKE '%;$id_grupo;%')  AND grupo.excluido IS NULL ))
                   
				AND veiculo.excluido IS NULL
                GROUP BY veiculo.id
                ORDER BY ultimas.data_hora DESC
                LIMIT $limit";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function ListarRastreadores($id_grupo, $pagina, $numeroRegistros, $numeroInicioRegistro, $busca = "")
	{
		$pdo    = $this->getConexao();
		$count  = " SELECT COUNT(*) as total";
		$select = "
            SELECT
                rastreador.id,
                rastreador.numero_serie,
                rastreador.id_grupo,
                veiculo.rotulo,
                grupo_rastreador.id_rastreador as selecionado,
                grupo.nome as nome_grupo";
		$from   = "
            FROM
                rastreador
                INNER JOIN grupo ON (grupo.id = rastreador.id_grupo)
                LEFT JOIN grupo_rastreador ON (grupo_rastreador.id_rastreador = rastreador.id AND grupo_rastreador.id_grupo = {$this->id})
                LEFT JOIN veiculo ON (veiculo.id_rastreador = rastreador.id)";
		$where  = "
            WHERE
            (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%')
            OR rastreador.id IN (
                                SELECT id_rastreador
                                FROM grupo_rastreador
                                INNER JOIN grupo ON (grupo.id = grupo_rastreador.id_grupo)
                                WHERE id_grupo IN (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%'))
            OR rastreador.id IN (
                                SELECT id_rastreador, id_grupo
                                FROM erp_rastreador_emprestimo
                                WHERE id_grupo = $id_grupo
                                AND data_devolucao IS NULL)
            AND rastreador.excluido IS NULL
            ORDER BY rastreador.numero_serie";

		$sql_count = $count . $from . $where;
		$sql       = $select . $from . $where;
		$stmt      = $pdo->prepare($sql_count);
		$stmt->execute();
		$rs                        = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$totalRegistros            = new stdClass();
		$totalRegistros->resultado = $rs;
		if (is_numeric($numeroInicioRegistro) && is_numeric($numeroRegistros)) {
			$sql .= " LIMIT $numeroInicioRegistro,$numeroRegistros";
		}

		$sql  = $select . $from . $where;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$listar            = new stdClass();
		$listar->resultado = $rs;

		return array($listar, $totalRegistros);
	}

	public function ListarPontosInteresse($id_grupo, $pagina, $numeroRegistros, $numeroInicioRegistro, $busca = "")
	{
		$filtroId       = "AND ponto_interesse_grupo.id_grupo = {$this->id}";
		$ponto          = new PontoInteresse();
		$campos         = "count(*) as total";
		$from           = "ponto_interesse
			INNER JOIN grupo ON (grupo.id = ponto_interesse.id_grupo)
			LEFT JOIN ponto_interesse_grupo ON (ponto_interesse_grupo.id_ponto_interesse = ponto_interesse.id $filtroId)
			LEFT JOIN ponto_interesse_categoria ON (ponto_interesse_categoria.id = ponto_interesse.id_ponto_interesse_categoria)
			LEFT JOIN ponto_interesse_subcategoria ON (ponto_interesse_subcategoria.id = ponto_interesse.id_ponto_interesse_subcategoria)";
		$totalRegistros = $ponto->ListarPermitidos($id_grupo, $campos, $from);

		$campos  = "ponto_interesse.id, ponto_interesse.nome, ponto_interesse.id_grupo, ponto_interesse_categoria.nome nome_ponto_interesse_categoria, ponto_interesse_subcategoria.nome nome_ponto_interesse_subcategoria, ponto_interesse_grupo.id_ponto_interesse selecionado, grupo.nome nome_grupo";
		$options = " ORDER BY ponto_interesse.nome ASC LIMIT $numeroInicioRegistro,$numeroRegistros";
		$listar  = $ponto->ListarPermitidos($id_grupo, $campos, $from, "", $options);

		return array($listar, $totalRegistros);
	}

	public function ListarFuncionalidades($id_grupo, $id_grupo_pai)
	{
		$pdo = $this->getConexao();
		if ($id_grupo == "") {

			$sql = "SELECT
                        map_acao.id,
                        map_acao.nome nome_acao,
                        map_acao.modulo,
                        map_modulo.nome nome_modulo,
                        map_grupo_acao.id_grupo
                    FROM map_acao
                        INNER JOIN map_modulo ON (map_modulo.id = map_acao.modulo)
                        INNER JOIN map_grupo_acao ON (map_grupo_acao.id_acao = map_acao.id AND map_grupo_acao.id_grupo = $id_grupo_pai)
                    WHERE map_modulo.excluido IS NULL
                    ORDER BY map_modulo.nome, map_acao.nome asc ";
		} else {
			$sql = "SELECT
                        map_acao.id,
                        map_acao.nome nome_acao,
                        map_acao.modulo,
                        map_modulo.nome nome_modulo,
                        map_grupo_acao.id_grupo,
                        grupo_selecionado.id_grupo selecionado
                    FROM map_acao
                        INNER JOIN map_modulo ON (map_modulo.id = map_acao.modulo)
                        INNER JOIN map_grupo_acao ON (map_grupo_acao.id_acao = map_acao.id AND map_grupo_acao.id_grupo = $id_grupo_pai)
                        LEFT JOIN map_grupo_acao grupo_selecionado ON (grupo_selecionado.id_acao = map_grupo_acao.id_acao AND grupo_selecionado.id_grupo = $id_grupo)
                    WHERE map_modulo.excluido IS NULL
                    ORDER BY map_modulo.nome, map_acao.nome asc";
		}
//echo'<pre>'; print_r($sql); echo'<pre>';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_OBJ);


		foreach ($listar as $acao) {
			$vetorFinal[$acao->nome_modulo][] = Array("id_acao" => $acao->id, "nome_acao" => $acao->nome_acao, "selecionado" => $acao->selecionado);
		}

		return $vetorFinal;
	}
    public function ListarFuncionalidadesUsuario($id_usuario, $id_grupo)
    {
        $pdo = $this->getConexao();
        if ($id_usuario == "") {

            $sql = "SELECT
                        map_acao.id,
                        map_acao.nome nome_acao,
                        map_acao.modulo,
                        map_modulo.nome nome_modulo,
                        map_grupo_acao.id_grupo
                    FROM map_acao
                        INNER JOIN map_modulo ON (map_modulo.id = map_acao.modulo)
                        INNER JOIN map_grupo_acao ON (map_grupo_acao.id_acao = map_acao.id AND map_grupo_acao.id_grupo = $id_grupo)
                    WHERE map_modulo.excluido IS NULL
                    ORDER BY map_modulo.nome, map_acao.nome asc ";
        } else {
            $sql = "SELECT
                        map_acao.id,
                        map_acao.nome nome_acao,
                        map_acao.modulo,
                        map_modulo.nome nome_modulo,
                        map_grupo_acao.id_grupo,
                        usuario_selecionado.id_usuario selecionado
                    FROM map_acao
                        INNER JOIN map_modulo ON (map_modulo.id = map_acao.modulo)
                        INNER JOIN map_grupo_acao ON (map_grupo_acao.id_acao = map_acao.id AND map_grupo_acao.id_grupo = $id_grupo)
                         LEFT JOIN map_usuario_acao usuario_selecionado ON (usuario_selecionado.id_acao = map_grupo_acao.id_acao AND usuario_selecionado.id_usuario = $id_usuario)
                    WHERE map_modulo.excluido IS NULL
                    ORDER BY map_modulo.nome, map_acao.nome asc";
        }
//echo'<pre>'; print_r($sql); echo'<pre>';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $listar = $stmt->fetchAll(PDO::FETCH_OBJ);


        foreach ($listar as $acao) {
            $vetorFinal[$acao->nome_modulo][] = Array("id_acao" => $acao->id, "nome_acao" => $acao->nome_acao, "selecionado" => $acao->selecionado);
        }

        return $vetorFinal;
    }

	//TODO retirar $_SESSION da classe
	public function ListarModelos($id_grupo)
	{
		$pdo       = $this->getConexao();
		$sql       = "
            SELECT
                modelo.id,
                modelo.nome
            FROM
                modelo
                INNER JOIN grupo ON (grupo.id = modelo.id_grupo)
            WHERE
                (grupo.id = $id_grupo OR grupo.arvore LIKE '%;" . $id_grupo . ";%')
                AND modelo.excluido IS NULL
                OR modelo.id = {$_SESSION['usuario']['configuracoes']['id_modelo']}
            ORDER BY modelo.nome";
		$objModelo = new Modelo();
		$objModelo->setId($_SESSION['usuario']['configuracoes']['id_modelo']);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;
		$retorno              = $resultado->resultado;

		$ids = array();

		if (count($resultado->resultado)) {
			foreach ($resultado->resultado as $registro) {
				array_push($ids, $registro['id']);
			}
		}

		$sql = "
            SELECT
                modelo_grupo.id_modelo id,
                modelo.nome
            FROM
                modelo_grupo INNER JOIN modelo ON (modelo.id = modelo_grupo.id_modelo)
            WHERE
                modelo_grupo.id_grupo = '$id_grupo'
                AND modelo_grupo.id_modelo NOT IN (" . implode(",", $ids) . ")";

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;

		return array_merge($retorno, $resultado->resultado);
	}

	public function AtualizarVinculoVeiculos($veiculos)
	{
		if (!($this->id > 0)) {
			return false;
		}

		$objGrupoVeiculo = new GrupoVeiculo($this->getConexao());
		$resultado       = $objGrupoVeiculo->Desvincular(" id_grupo = $this->id");
		if (!$resultado || !count($veiculos)) {
			return $resultado;
		}

		$objGrupoVeiculo->setId_grupo($this->id);

		foreach ($veiculos as $veiculo) {
			$objGrupoVeiculo->setId_veiculo($veiculo);
			$resultado = $objGrupoVeiculo->Adicionar();

			if (!$resultado) {
				return $resultado;
			}
		}
		// Remover vinculo veiculos com grupos filhos
		// e que nao tenham vinculo com o grupo atual
		$gruposFilhos = $this->Editar();
		$arvore       = $gruposFilhos->arvore . $this->id . ";";

		if ($gruposFilhos != "") {
			$veiculos = implode(",", $veiculos);

			$resultado = $objGrupoVeiculo->Desvincular(" id_grupo IN (SELECT id FROM grupo WHERE arvore LIKE '%" . $arvore . "%')
		    AND id_veiculo NOT IN ($veiculos)
		    AND id_veiculo NOT IN (SELECT id FROM veiculo WHERE id_grupo = {$this->id})");
		}

		return $resultado;
	}

	public function AtualizarVinculoRastreadores($rastreadores)
	{
		if (!($this->id > 0)) {
			return false;
		}

		$objGrupoRastreador = new GrupoRastreador();
		$resultado          = $objGrupoRastreador->DesvincularRastreador("id_grupo = {$this->id}");

		if (!$resultado || !count($rastreadores)) {
			return $resultado;
		}

		$objGrupoRastreador->setId_grupo($this->id);

		foreach ($rastreadores as $rastreador) {
			$objGrupoRastreador->setId_rastreador($rastreador);
			$resultado = $objGrupoRastreador->Adicionar();

			if (!$resultado) {
				return $resultado;
			}
		}

		// Remover vinculo rastredores com grupos filhos
		// e que nao tenham vinculo com o grupo atual
		$gruposFilhos = $this->BuscarFilhos($this->id);
		$gruposFilhos = implode(",", $gruposFilhos);
		$gruposFilhos = str_replace("{$this->id},", "", $gruposFilhos);

		if ($gruposFilhos != "") {
			$rastreadores = implode(",", $rastreadores);
			$resultado    = $objGrupoRastreador->DesvincularRastreador("id_grupo IN ($gruposFilhos) AND id_rastreador NOT IN ($rastreadores)");
		}

		return $resultado;
	}

	public function VerificarCadastrado()
	{
		$pdo = $this->getConexao();

		$objGrupo = new Grupo();
		$objGrupo->setId($this->id_grupo_pai);
		//$idFranquia = $objGrupo->ChecarFranquia()[0]['id_franquia'];

		$sql = " SELECT * FROM grupo WHERE grupo.nome = '{$this->nome}' AND grupo.excluido IS NULL AND grupo.arvore LIKE '%;1;%' ";
		if ($this->id > 0) {
			$sql .= " AND grupo.id != '{$this->id}'";
		}
		$sql .= " LIMIT 1";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (count($rs)) {
			return true;
		} else {
			return false;
		}
	}

	public function ListaRastreadoresVeiculosCombo($id_grupo, $id_grupo_raiz, $ocultar = 1, $selecionados = array(), $busca = "")
	{
		if (count($selecionados) > 0) {
			$selecionados = implode(',', $selecionados);
		}
		if ($id_grupo == "") {
			return false;
		}

		$vetorGrupos = array(1, 157);

		$pdo = $this->getConexao();
		$sql = "SELECT
                    veiculo.id,
                    rastreador.id as id_rastreador,
                    IF(rastreador.numero_serie != '',IF(complemento_placa != '',CONCAT(veiculo.rotulo,' - ', complemento_placa ,' ( ',rastreador.numero_serie,' )' ),CONCAT(veiculo.rotulo,' - ',rastreador.numero_serie)),IF(complemento_placa != '',CONCAT(veiculo.rotulo,' - ', complemento_placa),veiculo.rotulo)) as rotulo,
                    IF((SELECT COUNT(*) as total FROM  grupo_veiculo as gv WHERE gv.id_veiculo = veiculo.id AND gv.id_grupo = $id_grupo) > 0,'checked','') as selecionado
                    FROM
                        veiculo";

		if (in_array($id_grupo_raiz, $vetorGrupos)) {
			$sql .= " LEFT JOIN contrato ON (veiculo.id = contrato.id_veiculo)";
		} else {
			$sql .= " INNER JOIN contrato ON (veiculo.id = contrato.id_veiculo)";
		}

		$sql .= " LEFT JOIN
                    rastreador ON (veiculo.id_rastreador = rastreador.id)
                        LEFT JOIN
                    grupo ON (veiculo.id_grupo = grupo.id)";

		$sql .= " WHERE ";
		$sql .= " veiculo.excluido IS NULL ";
		if (count($selecionados) > 0) {
			$sql .= "
                    AND veiculo.id NOT IN ($selecionados)";
		}
		if ($ocultar == 1) {
			$sql .= " AND veiculo.id NOT IN (SELECT veiculo.id FROM veiculo INNER JOIN grupo ON (grupo.id = veiculo.id_grupo) WHERE grupo.arvore LIKE '%;$id_grupo;%') ";
			$sql .= " AND veiculo.id NOT IN (SELECT id_veiculo FROM grupo_veiculo WHERE id_grupo = $id_grupo)";
			$sql .= " AND (grupo.id = $id_grupo_raiz OR grupo.arvore LIKE '%;$id_grupo_raiz;%' OR veiculo.id IN (SELECT id_veiculo FROM grupo_veiculo INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
                         WHERE grupo.id = $id_grupo_raiz  OR grupo.arvore LIKE '%;$id_grupo_raiz;%'))";
			$sql .= " AND veiculo.id_grupo != $id_grupo";
		} else {
			$sql .= "AND (grupo.id = $id_grupo_raiz OR grupo.arvore LIKE '%;$id_grupo_raiz;%' OR veiculo.id IN (SELECT id_veiculo FROM grupo_veiculo INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
                         WHERE grupo.id = $id_grupo_raiz  OR grupo.arvore LIKE '%;$id_grupo_raiz;%'))";
		}

		if ($busca != "") {
			$sql .= " AND (veiculo.rotulo LIKE '%$busca%'
		                OR grupo.nome LIKE '%$busca%'
                        OR veiculo.complemento_placa LIKE '%$busca%'
			            OR rastreador.numero_serie LIKE '%$busca%')";
		}

		$sql .= " LIMIT 100";
//		echo'<pre>'; print_r($sql); echo'<pre>';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $listar;
	}

	public function ListaRastreadoresVeiculosVinculados($id_grupo)
	{

		if ($id_grupo == "") {
			return false;
		}

		$pdo = $this->getConexao();
		$sql = "SELECT
                        veiculo.id,
                        rastreador.id as id_rastreador,veiculo.complemento_placa,
                        IF(rastreador.numero_serie != '',IF(complemento_placa != '',CONCAT(veiculo.rotulo,' - ', complemento_placa ,' ( ',rastreador.numero_serie,' )' ),CONCAT(veiculo.rotulo,' - ',rastreador.numero_serie)),IF(complemento_placa != '',CONCAT(veiculo.rotulo,' - ', complemento_placa),veiculo.rotulo)) as rotulo,
                        IF((SELECT COUNT(*) as total FROM  grupo_veiculo as gv WHERE gv.id_veiculo = veiculo.id AND gv.id_grupo = $id_grupo) > 0,'checked','') as selecionado
                        FROM
                            veiculo
                                LEFT JOIN
                            contrato ON (veiculo.id = contrato.id_veiculo)
                                LEFT JOIN
                            rastreador ON (veiculo.id_rastreador = rastreador.id)
                                LEFT JOIN
                            grupo ON (veiculo.id_grupo = grupo.id)";

		$sql .= " WHERE ";
		$sql .= " veiculo.excluido IS NULL";

		$sql .= " AND (grupo.id = $id_grupo
                OR grupo.arvore LIKE '%;$id_grupo;%'
                OR veiculo.id IN (SELECT
                    id_veiculo
                FROM
                    grupo_veiculo
                        INNER JOIN
                    grupo ON (grupo.id = grupo_veiculo.id_grupo)
                WHERE
                    grupo.id = $id_grupo
                        OR grupo.arvore LIKE '%;$id_grupo;%'))

                ";

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach($listar AS $veiculo) {
			if ($veiculo['selecionado']) {
				$aF[] = $veiculo;
			}
		}

		return $aF;
	}

	public function ListarGruposPontoInteresse($id_grupo)
	{

		$pdo = $this->getConexao();
		$sql = "SELECT
                        veiculo.id,
                        rastreador.id as id_rastreador,
                        IF(rastreador.numero_serie != '',CONCAT(veiculo.rotulo,' - ',rastreador.numero_serie),veiculo.rotulo) as rotulo,
                        IF((SELECT COUNT(*) as total FROM  grupo_veiculo as gv WHERE gv.id_veiculo = veiculo.id AND gv.id_grupo = $id_grupo) > 0,'checked','') as selecionado
                        FROM
                            veiculo
                                INNER JOIN
                            contrato ON (veiculo.id = contrato.id_veiculo)
                                LEFT JOIN
                            rastreador ON (veiculo.id_rastreador = rastreador.id)
                                LEFT JOIN
                            grupo ON (veiculo.id_grupo = grupo.id)";

		$sql .= " WHERE ";
		$sql .= " veiculo.excluido IS NULL";

		$sql .= " AND (grupo.id = $id_grupo
                OR grupo.arvore LIKE '%;$id_grupo;%'
                OR veiculo.id IN (SELECT
                    id_veiculo
                FROM
                    grupo_veiculo
                        INNER JOIN
                    grupo ON (grupo.id = grupo_veiculo.id_grupo)
                WHERE
                    grupo.id = $id_grupo
                        OR grupo.arvore LIKE '%;$id_grupo;%'))

                ";
		$stmt = $pdo->prepare($sql);
		//echo $sql;
		$stmt->execute();
		//echo $sql;
		$listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

		//echo "<pre>"; print_r($listar); echo "</pre>";
		return $listar;
	}

	/*
	 * Função utilizada para retornar um array específico para o plugin Treed.
	 * Consultas e sub-funções adaptadas para o mesmo.
	 * */
	public function MontarArvoreGrupo($idGrupo, $idGrupoFranquia)
	{
		//É impossível montar a árvore para o grupo Desenvolvedores por questões de performance.
		//Busca um grupo menor para montar a árvore para testes
		if ($idGrupo == 1 || $idGrupo == 157) {
			$idGrupo         = 159;
			$idGrupoFranquia = 159;
		}

		//Gera a árvore de grupos de acordo com o especificado para o plugin Treed
		function GerarArvoreTreed($groups, $id = NULL)
		{
			$tree = array();
			foreach ($groups as $index => $group) {
				if ($group->id_grupo_pai == $id) {
					unset($groups[$index]);
					$tree[] = Array("name" => $group->nome, "id" => $group->id, "children" => GerarArvoreTreed($groups, $group->id));
				}
			}

			return $tree;
		}

		//Limpa ramificações vazias
		function LimparArvoreTreed(&$a)
		{
			foreach ($a AS &$k) {
				if (count($k['children']) <= 0) {
					unset($k['children']);
				} else {
					LimparArvoreTreed($k['children']);
				}
			}
		}

		$pdo = $this->getConexao();
		if ($idGrupo == $idGrupoFranquia) {
			/*
			 * o campo id é relevante apenas para o primeiro grupo.
			 * Campo id não pode ser vazio nem duplicado
			 */
			$sql = "
            (
                SELECT
                    nome,
                    '$idGrupo' AS id,
                    '0' AS id_grupo_pai
                FROM grupo
                    WHERE id = $idGrupo
            ) UNION ALL (
                SELECT
                    CONCAT('" . RTL_PESSOAS_FISICAS . ": ', COUNT(CASE WHEN id_cliente_tipo_pessoa = 1 THEN cliente.id END)),
                    '2' AS id,
                    '$idGrupo' AS id_grupo_pai
                FROM cliente
                    INNER JOIN grupo ON (grupo.id = cliente.id_grupo)
                WHERE id_grupo = $idGrupo
                OR arvore LIKE '%;$idGrupo;%'
                AND cliente.excluido IS NULL
                AND grupo.excluido IS NULL
            ) UNION ALL (
                SELECT
                    CONCAT('" . RTL_PESSOAS_JURIDICAS . ": ', COUNT(CASE WHEN id_cliente_tipo_pessoa = 2 THEN cliente.id END)),
                    '3' AS id,
                    '$idGrupo' AS id_grupo_pai
                FROM cliente
                    INNER JOIN grupo ON (grupo.id = cliente.id_grupo)
                WHERE id_grupo = $idGrupo OR arvore LIKE '%;$idGrupo;%'
                AND cliente.excluido IS NULL
                AND grupo.excluido IS NULL
            ) UNION ALL (
                SELECT
                    CONCAT('" . RTL_GRUPOS_FILHOS . ": ', COUNT(CASE WHEN (arvore LIKE '%;$idGrupo;%' OR grupo.id = $idGrupo) THEN id END)),
                    '4' AS id,
                    '$idGrupo' AS id_grupo_pai
                FROM grupo
                    WHERE grupo.excluido IS NULL
            ) UNION ALL (
                SELECT
                    CONCAT('" . RTL_VEICULOS . ": ', COUNT(CASE WHEN (arvore LIKE '%;$idGrupo;%' OR veiculo.id_grupo = $idGrupo) THEN veiculo.id END)),
                    '5' AS id,
                    '$idGrupo' AS id_grupo_pai
                FROM veiculo
                    INNER JOIN grupo ON (veiculo.id_grupo = grupo.id)
                WHERE veiculo.excluido IS NULL
                AND grupo.excluido IS NULL
            ) UNION ALL (
                SELECT
                    CONCAT('" . RTL_USUARIOS . ": ', COUNT(CASE WHEN (arvore LIKE '%;$idGrupo;%' OR usuario.id_grupo = $idGrupo) THEN usuario.id END)),
                    '6' AS id,
                    '$idGrupo' AS id_grupo_pai
                FROM usuario
                    INNER JOIN grupo ON (usuario.id_grupo = grupo.id)
                WHERE usuario.excluido IS NULL
                AND grupo.excluido IS NULL

            )
            ";
		} else {
			/* Consulta comentada gera a árvore reversa direta do grupo pesquisado até Desenvolvedores
			 *
			 * $sql = "
			(
				SELECT
					grupo2.id,
					grupo2.id_grupo_pai,
					grupo2.nome
				FROM grupo
					INNER JOIN grupo AS grupo2 ON ((FIND_IN_SET(grupo2.id,(SUBSTRING(SUBSTRING(REPLACE(grupo.arvore,';',','), 2), 1, LENGTH(grupo.arvore)-2))) > 0))
				WHERE grupo.id = $idGrupo #4062 - id_grupo com 6 grupos na árvore
			) UNION ALL (
				SELECT
					grupo.id,
					grupo.id_grupo_pai,
					grupo.nome
				FROM grupo
				WHERE grupo.id = $idGrupo #4062 - id_grupo com 6 grupos na árvore
			)";*/

			$sql = "SELECT * FROM grupo WHERE id = $idGrupo OR arvore LIKE '%;$idGrupo;%' AND grupo.excluido IS NULL";
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$grupos = $stmt->fetchAll(PDO::FETCH_OBJ);

		//Recupera o nome do grupo pesquisado para aplicar à raíz da árvore
		foreach ($grupos AS $grupo) {
			if ($grupo->id = $idGrupo) {
				$nome = $grupo->nome;
				break;
			}
		}

		$tree = GerarArvoreTreed($grupos, $idGrupo);
		LimparArvoreTreed($tree);

		$t['id']       = $idGrupo;
		$t['name']     = $nome;
		$t['children'] = $tree;

		return $t;
	}

	/* MÉTODO DA CLASSE GRUPO_NOVO

	public function ListarGruposPontoInteresse($id_grupo,$busca = "",$id_ponto_interesse)
	{
		//echo "-->" . $id_grupo;
		$pdo = $this->getConexao();
		$sql = "SELECT id,nome as rotulo FROM grupo WHERE (grupo.id = '$id_grupo' OR arvore LIKE '%;$id_grupo;%') AND grupo.excluido IS NULL AND grupo.id NOT IN (SELECT id_grupo FROM ponto_interesse_grupo WHERE id_ponto_interesse = $id_ponto_interesse) ";
		if(trim($busca)!= "")
			$sql .= " AND nome LIKE '%$busca%'";

		$sql .= " LIMIT 100";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		//echo $sql;
		$listar = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $listar;
	}*/

	public function ListaRastreadoresVeiculosCombo_old($id_grupo, $busca = "", $veiculos = "", $rastreadores = "", $id_grupo_raiz = NULL)
	{
		$pdo = $this->getConexao();
		$this->setId($id_grupo);
		$resultado = $this->Editar();

		$select = "
            SELECT
                veiculo.id,
                rastreador.id as id_rastreador,
                rastreador.numero_serie,
                veiculo.rotulo,
                IF((SELECT COUNT(*) as total FROM grupo_veiculo as gv WHERE gv.id_veiculo = veiculo.id AND gv.id_grupo = {$this->id} ) > 0,'checked','') as selecionado";
		$from   = "
            FROM
                veiculo
                INNER JOIN contrato ON (veiculo.id = contrato.id_veiculo)
                LEFT JOIN rastreador ON (veiculo.id_rastreador = rastreador.id)
                LEFT JOIN grupo ON (veiculo.id_grupo = grupo.id)
                LEFT JOIN grupo_veiculo ON (veiculo.id = grupo_veiculo.id_veiculo)";
		$where  = "
            WHERE
                veiculo.excluido IS NULL
                AND veiculo.id NOT IN (
                                        SELECT veiculo.id FROM veiculo
                                        INNER JOIN grupo ON (grupo.id = veiculo.id_grupo)
                                        WHERE grupo.arvore LIKE '%;$id_grupo;%')";

		if ($busca != "") {
			$where .= " AND veiculo.rotulo LIKE '%$busca%'";
		}
		if ($veiculos != "") {
			$where .= " AND veiculo.id NOT IN($veiculos)";
		}

		$where .= " AND (grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%'
		                    OR veiculo.id IN (
		                                        SELECT id_veiculo FROM grupo_veiculo
		                                        INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
		                                        WHERE grupo.id = $id_grupo OR grupo.arvore LIKE '%;$id_grupo;%'))";

		$sql  = $select . $from . $where;
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;

		return $resultado;
	}

	public function AtribuirPrioridadeEventoTipo($prioridades)
	{
		$objGrupoPrioridadeEventoTipo = new GrupoPrioridadeEventoTipo();
		$objGrupoPrioridadeEventoTipo->setIdGrupo($this->id);

		if (count($prioridades)) {
			$resultado = $objGrupoPrioridadeEventoTipo->Remover();

			foreach ($prioridades as $prioridade) {
				$objGrupoPrioridadeEventoTipo->setIdEventoTipo($prioridade['id_evento_tipo']);
				$objGrupoPrioridadeEventoTipo->setIdPrioridade($prioridade['id_prioridade']);
				$resultado = $objGrupoPrioridadeEventoTipo->Adicionar();

				if ($resultado->codigo) {
					break;
				}
			}
		}

		return $resultado;
	}

	public function ChecarFranquia($caminho_logar = "", $arvore = NULL)
	{
		$pdo    = $this->getConexao();
		$arvore = substr(str_replace(';', ',', $arvore), 0, -1);
		$arvore = $this->id . $arvore;

		$sql = "
            SELECT
                franqueado.id as id_representante,
                franqueado.id_grupo as grupo_representante,
                franqueado.nome as nome_representante,
                IF(endereco.ddd_telefone != '',CONCAT(endereco.ddd_telefone,'-',endereco.telefone),endereco.telefone) as telefone_representante,
                endereco.ip,
                endereco.ip_secundario,franqueado.codigo
            FROM
                grupo
                INNER JOIN franqueado ON (franqueado.id_grupo = grupo.id)
                INNER JOIN endereco ON (franqueado.id_endereco = endereco.id)
            WHERE
                grupo.id IN ($arvore)";
		if ($this->id != 6464) {
			$sql .= " AND grupo.id != 6464";
		}
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function ChecarCliente()
	{
		$pdo = $this->getConexao();
		$sql = "
            SELECT
                cliente.id as id_cliente,
                cliente.id_grupo as grupo_cliente,
                IF(cliente.nome_fantasia != '',cliente.nome_fantasia ,cliente.nome) as nome_cliente,
                CONCAT(endereco.ddd_telefone,'-',endereco.telefone) as telefone_cliente
            FROM
                grupo
                INNER JOIN cliente ON (cliente.id_grupo = grupo.id)
                INNER JOIN endereco ON (cliente.id_endereco = endereco.id)
            WHERE
                grupo.id = $this->id OR grupo.arvore LIKE '%;$this->id;%'";

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;

		return $resultado;
	}

	public function BuscarGrupo($id_grupo)
	{
		$pdo  = $this->getConexao();
		$sql  = " SELECT grupo.* from grupo where grupo.id = $id_grupo";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;

		return $resultado;
	}

	/**
	 *  Marcelo Bruzetti - 21/01/2013
	 *
	 *  Verifica se o grupo passado é de uma franquia ou de um cliente
	 *  Retorno:
	 *      1 = matriz
	 *      2 = franquia
	 *      3 = cliente
	 *      0 = erro
	 */
	public function NivelGrupoBD()
	{
		if ($this->id > 0 === false) {
			return 0;
		}

		$pdo = $this->getConexao();
		$sql = "
            SELECT id
            FROM cliente INNER JOIN grupo ON (grupo.id = cliente.id_grupo)
            WHERE grupo.id = $this->id OR grupo.arvore LIKE '%;$this->id;%'";

		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;
		if ($resultado->resultado[0]['id'] > 0) {
			return 3;
		}

		$sql  = "
            SELECT id
            FROM franqueado INNER JOIN grupo ON (grupo.id = franqueado.id_grupo)
            WHERE grupo.id = $this->id OR grupo.arvore LIKE '%;$this->id;%'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$rs                   = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado            = new stdClass();
		$resultado->resultado = $rs;
		if ($resultado->resultado[0]['id'] > 0) {
			return 2;
		}

		return 1;
	}

	public function PopularArvore()
	{
		$pdo  = $this->getConexao();
		$sql  = 'UPDATE grupo SET `arvore` = ?  WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->arvore, PDO::PARAM_STR);
		$stmt->bindParam(2, $this->id, PDO::PARAM_INT);
		$modificar = $stmt->execute();

		return $modificar;
	}

	public function GerarVeiculosProprietario($idGrupo)
	{
		$pdo  = $this->getConexao();
		$sql  = "SELECT id, rotulo FROM veiculo WHERE id_grupo = $idGrupo AND excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function GerarVeiculosVinculado($idGrupo)
	{
		$pdo  = $this->getConexao();
		$sql  = "SELECT
                    veiculo.id,
                    veiculo.rotulo
                FROM grupo_veiculo
                    INNER JOIN veiculo ON (veiculo.id = grupo_veiculo.id_veiculo)
                WHERE grupo_veiculo.id_grupo = $idGrupo AND excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function GerarUsuarios($idGrupo)
	{
		$pdo  = $this->getConexao();
		$sql  = "SELECT nome, usuario FROM usuario WHERE id_grupo = $idGrupo";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function GerarFilhos($idGrupo)
	{
		$pdo  = $this->getConexao();
		$sql  = "SELECT
                    @id_grupo:=grupo.id AS id,
                    grupo.nome,
                    (SELECT COUNT(id) FROM grupo WHERE id_grupo_pai = @id_grupo) AS qtd_filhos
                FROM grupo
                WHERE grupo.excluido IS NULL
                AND grupo.id_grupo_pai = $idGrupo";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function GerarArvoreVeiculos($idGrupo)
	{
		$pdo  = $this->getConexao();
		$sql  = "SELECT
                    grupo.id AS id_grupo,
                    grupo.nome AS nome_grupo,
                    g2.nome AS nome_grupo_pai
                FROM grupo
                    INNER JOIN grupo g2 ON (g2.id = grupo.id_grupo_pai)
                WHERE (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
                AND grupo.excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$sql  = "SELECT
                    IF (veiculo.complemento_placa != '', CONCAT(veiculo.rotulo, ' - ', veiculo.complemento_placa), veiculo.rotulo) AS rotulo,
                    veiculo.id,
                    veiculo.id_grupo
                FROM veiculo
                    INNER JOIN grupo ON (grupo.id = veiculo.id_grupo)
                WHERE (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
                AND grupo.excluido IS NULL
                AND veiculo.excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$veiculosProprietario = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$sql  = "SELECT
                    IF (veiculo.complemento_placa != '', CONCAT(veiculo.rotulo, ' - ', veiculo.complemento_placa), veiculo.rotulo) AS rotulo,
                    veiculo.id,
                    grupo_veiculo.id_grupo
                FROM grupo_veiculo
                    INNER JOIN veiculo ON (veiculo.id = grupo_veiculo.id_veiculo)
                    INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
                WHERE (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
                AND veiculo.excluido IS NULL
                AND grupo.excluido IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$veiculosVinculados = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$sql  = "SELECT
                    usuario.nome,
                    usuario.usuario,
                    usuario.id_grupo
                FROM usuario
                    INNER JOIN grupo ON (grupo.id = usuario.id_grupo)
                WHERE (grupo.id = $idGrupo OR grupo.arvore LIKE '%;$idGrupo;%')
                AND grupo.excluido IS NULL
                AND usuario.excluido IS NULL
                AND usuario.id_usuario_pai IS NULL";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$arrayFinal = [];
		foreach ($grupos AS $grupo) {
			$arrayFinal[$grupo['id_grupo']]['id']             = $grupo['id_grupo'];
			$arrayFinal[$grupo['id_grupo']]['nome_grupo']     = $grupo['nome_grupo'];
			$arrayFinal[$grupo['id_grupo']]['nome_grupo_pai'] = $grupo['nome_grupo_pai'];

			foreach ($veiculosProprietario AS &$veiculoP) {
				if ($grupo['id_grupo'] == $veiculoP['id_grupo']) {
					$arrayFinal[$grupo['id_grupo']]['veiculos_proprietario'][] = $veiculoP;
					unset($veiculoP);
				}
			}

			foreach ($veiculosVinculados AS &$veiculoV) {
				if ($grupo['id_grupo'] == $veiculoV['id_grupo']) {
					$arrayFinal[$veiculoV['id_grupo']]['veiculos_vinculados'][] = $veiculoV;
					unset($veiculoV);
				}
			}

			foreach ($usuarios AS &$usuario) {
				if ($grupo['id_grupo'] == $usuario['id_grupo']) {
					$arrayFinal[$grupo['id_grupo']]['usuarios'][] = $usuario;
					unset($usuario);
				}
			}
		}

		return $arrayFinal;
	}

	/*
	 * MÉTODOS DE GRUPO_EVENTO
	 * */

	public function AdicionarGrupoEvento()
	{

		$pdo  = $this->getConexao();
		$sql  = 'INSERT INTO grupo_evento (id_grupo,id_evento_tipo,id_veiculo) VALUES (?,?,?)';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		$stmt->bindParam(2, $this->getIdEventoTipo(), PDO::PARAM_INT);
		$stmt->bindParam(3, $this->getIdVeiculo(), PDO::PARAM_INT);
		$rs = $stmt->execute();

		return $rs;
	}

	public function ListarEventoGrupoEvento()
	{
		$pdo  = $this->getConexao();
		$sql  = 'SELECT evento_tipo.id,evento_tipo.rotulo FROM grupo_evento
               INNER JOIN evento_tipo ON (grupo_evento.id_evento_tipo = evento_tipo.id)
               WHERE grupo_evento.id_grupo = ? GROUP BY evento_tipo.rotulo';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $listar;
	}

	public function ListarVeiculoGrupoEvento()
	{
		$pdo  = $this->getConexao();
		$sql  = 'SELECT veiculo.id,veiculo.rotulo FROM grupo_evento
               INNER JOIN veiculo ON (grupo_evento.id_veiculo = veiculo.id)
               WHERE grupo_evento.id_grupo = ? GROUP BY veiculo.rotulo';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		$stmt->execute();
		$listar = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $listar;
	}

	public function RemoverGrupoEvento()
	{
		$pdo  = $this->getConexao();
		$sql  = 'DELETE FROM grupo_evento WHERE grupo_evento.id_grupo = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
		//echo $sql;
		$rs = $stmt->execute();

		return $rs;
	}

	public function GerarPermissao($parametros)
	{
		if ($parametros['id_grupo'] == "") {
			return;
		}
		$pdo  = $this->getConexao();
		$sql  = "INSERT INTO map_grupo_acao (id_grupo,id_acao)  
			  	 SELECT {$parametros['id_grupo']},id FROM map_acao 
			  	 WHERE map_acao.modulo IN(SELECT id FROM map_modulo WHERE id IN(SELECT id_app_modulos FROM app_modelo_categoria WHERE id_app_categoria = {$parametros['categoria']}))";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		return true;
	}
	/*
	 * FIM MÉTODOS DE GRUPO_EVENTO
	 * */

	public function AdicionarGrupoAcaoAppModulo($idGrupo, $arrayIdModulo)
	{
		$pdo  = $this->getConexao();

		(is_array($arrayIdModulo))
			? $lista = implode(',', $arrayIdModulo)
			: $lista = $arrayIdModulo;

		$sql  = "
		INSERT INTO map_grupo_acao
		SELECT $idGrupo, map_acao.id, '0'
		FROM map_acao
		INNER JOIN map_modulo ON (map_modulo.id = map_modulo.`id`)
		WHERE map_modulo.id IN ($lista)
		ON DUPLICATE KEY UPDATE
		id_grupo = $idGrupo
		";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
	}

	//Funcao que checa se o usuario está vinculando um veículo que nao faz parte da arvore do grupo.
	public function ChecarVeiculosGrupo($arrayVeiculos)
    {
        $pdo  = $this->getConexao();
        $veiculos = implode(',', $arrayVeiculos);

        $sql = " SELECT rotulo FROM veiculo 
                INNER JOIN grupo ON (veiculo.id_grupo=grupo.id) 
                WHERE veiculo.id IN ($veiculos) 
                AND grupo.id NOT IN (SELECT REPLACE(SUBSTRING(arvore,2,LENGTH(arvore)-2),';',',') FROM grupo WHERE id = ?) ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function ListarCombo($id)
    {
        if($id == "") return;
        $pdo = $this->getConexao();
        $sql = "SELECT id,nome FROM grupo WHERE id = $id  ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

//        Conexao::pr($rs);
        return $rs;
    }
    public function ListarComboFranquias($id)
    {
        if($id == "") return;
        $pdo = $this->getConexao();
        $sql = "SELECT grupo.id,grupo.nome FROM grupo INNER JOIN franqueado ON (franqueado.id_grupo = grupo.id) WHERE grupo.id = $id ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

//        Conexao::pr($rs);
        return $rs;
    }
    public function PegarAcoes($id)
    {
        if($id == "") return;
        $pdo = $this->getConexao();
        $sql = "SELECT id_acao,id_grupo FROM map_grupo_acao WHERE id_grupo = $id ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

//        Conexao::pr($rs);
        return $rs;
    }
    public function BuscarFilhos($id_grupo)
    {
        $pdo = $this->getConexao();
        $sql = "SELECT id,nome FROM grupo  WHERE arvore LIKE '%;$id_grupo;%' ORDER BY id_grupo_pai ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->MontarFilhos( $rs, $id_grupo );
    }
    private function MontarFilhos( &$registros, $idPai )
    {
        $retorno = array($idPai);

        foreach( $registros as $registro )
        {
            // Se o pai do registro for o noAtual
            if( (int)$registro['id_grupo_pai'] === (int)$idPai )
                $retorno = array_merge( $retorno, $this->MontarFilhos( $registros, $registro['id'] ) );
        }

        return $retorno;
    }

    public function verificaGrupoFranquia($idFranquia)
    {
        $pdo = $this->getConexao();
        $sql = "SELECT id_grupo  FROM franqueado WHERE id = {$idFranquia}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }


    public function ListarFuncionalidadesMapas($id_grupo, $id_grupo_pai)
    {
        $pdo = $this->getConexao();
        if ($id_grupo == "") {

            $sql = "SELECT
                        app_acao.id,
                        app_acao.nome nome_acao,
                        app_acao.modulo,
                        app_funcionalidade.nome nome_modulo,
                        app_grupo_acao.id_grupo
                    FROM app_acao
                        INNER JOIN app_funcionalidade ON (app_funcionalidade.id = app_acao.modulo)
                        INNER JOIN app_grupo_acao ON (app_grupo_acao.id_app_acao = app_acao.id AND app_grupo_acao.id_grupo = $id_grupo_pai)
                    WHERE app_funcionalidade.excluido IS NULL
                    ORDER BY app_funcionalidade.nome, app_acao.nome asc ";
        } else {
            $sql = "SELECT
                        app_acao.id,
                        app_acao.nome nome_acao,
                        app_acao.modulo,
                        app_funcionalidade.nome nome_modulo,
                        app_grupo_acao.id_grupo,
                        grupo_selecionado.id_grupo selecionado
                    FROM app_acao
                        INNER JOIN app_funcionalidade ON (app_funcionalidade.id = app_acao.modulo)
                        INNER JOIN app_grupo_acao ON (app_grupo_acao.id_app_acao = app_acao.id AND app_grupo_acao.id_grupo = $id_grupo_pai)
                        LEFT JOIN app_grupo_acao grupo_selecionado ON (grupo_selecionado.id_app_acao = app_grupo_acao.id_app_acao AND grupo_selecionado.id_grupo = $id_grupo)
                    WHERE app_funcionalidade.excluido IS NULL
                    ORDER BY app_funcionalidade.nome, app_acao.nome asc";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $listar = $stmt->fetchAll(PDO::FETCH_OBJ);


        foreach ($listar as $acao) {
            $vetorFinal[$acao->nome_modulo][] = Array("id_acao" => $acao->id, "nome_acao" => $acao->nome_acao, "selecionado" => $acao->selecionado);
        }

        return $vetorFinal;
    }

    public function comboIdGrupo()
        {
            $pdo = $this->getConexao();
            $sql = "SELECT id,nome AS nome 
                      FROM grupo
                      WHERE grupo.id = ?
                      AND grupo.excluido IS NULL";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
//    public function Editar()
//    {
//
//        $pdo = $this->getConexao();
//        $sql = "
//            SELECT
//                grupo.*,
//                grupo_pai.nome as nome_grupo_pai,
//                modelo.nome as nome_modelo
//            FROM
//                grupo
//                LEFT JOIN grupo as grupo_pai ON (grupo_pai.id = grupo.id_grupo_pai)
//                INNER JOIN modelo ON (modelo.id = grupo.id_modelo)
//            WHERE
//                grupo.id = $this->id";
//        $stmt = $pdo->prepare($sql);
//        $stmt->execute();
//        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        $listar = new stdClass();
//        $listar->resultado = $rs;
//        return $listar->resultado[0];
//    }
}
