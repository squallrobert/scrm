<?
class Menu
{
	private $id;
	private $nome;
	private $descricao;
	private $id_acao;
	private $id_pai;
	private $ordem;
	private $acao;
	private $index;
	private $target;
	private $excluido;
	private $icone;
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
 	
	public function setDescricao($arg)
	{
		$this->descricao = $arg;
	}
 	
	public function getDescricao()
	{
		return $this->descricao;
	}
 	
	public function setIdAcao($arg)
	{
        $this->id_acao = ($arg == '')? NULL : $arg;
	}
 	
	public function getIdAcao()
	{
		return $this->id_acao;
	}
 	
	public function setIdPai($arg)
	{
        $this->id_pai = ($arg == '')? NULL : $arg;
	}
 	
	public function getIdPai()
	{
		return $this->id_pai;
	}
 	
	public function setOrdem($arg)
	{
		$this->ordem = $arg;
	}
 	
	public function getOrdem()
	{
		return $this->ordem;
	}
 	
	public function setAcao($arg)
	{
		$this->acao = $arg;
	}
 	
	public function getAcao()
	{
		return $this->acao;
	}
 	
	public function setIndex($arg)
	{
		$this->index = $arg;
	}
 	
	public function getIndex()
	{
		return $this->index;
	}
 	
	public function setTarget($arg)
	{
		$this->target = $arg;
	}
 	
	public function getTarget()
	{
		return $this->target;
	}
 	
	public function setExcluido($arg)
	{
		$this->excluido = $arg;
	}
 	
	public function getExcluido()
	{
		return $this->excluido;
	}
 	
	public function setIcone($arg)
	{
		$this->icone = $arg;
	}
 	
	public function getIcone()
	{
		return $this->icone;
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
		INSERT INTO map_menu SET 
			nome = ?
			,descricao = ?';
		$sql .= ',id_acao = ?';
        $sql .= ',id_pai = ? ';
        $sql .= ',ordem = ?';
        $sql .= ',acao = ?';
        $sql .= ',`index` = ?
                ,target = ?
                ,icone = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getDescricao(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getIdAcao(),PDO::PARAM_INT);
        $stmt->bindParam(++$x,$this->getIdPai(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getOrdem(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getAcao(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getIndex(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getTarget(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getIcone(),PDO::PARAM_STR);
		$stmt->execute();
		return $pdo->lastInsertId() ;
	}

	public function Modificar()
	{
		$pdo = $this->getConexao();
		$sql = '
		UPDATE map_menu SET nome = ?';
		$sql .= ",descricao = ?";
        $sql .= ",id_acao = ?";
        $sql .= ",id_pai = ?";
		if ($this->getOrdem() != "") $sql .= ",ordem = ?";
		$sql .= ",acao = ?";
		$sql .= ",`index` = ?";
		$sql .= ",target = ?";
		$sql .= ",icone = ?";

		$sql .= ' WHERE id = ?';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(++$x,$this->getNome(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getDescricao(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getIdAcao(),PDO::PARAM_INT);
        $stmt->bindParam(++$x,$this->getIdPai(),PDO::PARAM_INT);
		if ($this->getOrdem() != "") $stmt->bindParam(++$x,$this->getOrdem(),PDO::PARAM_INT);
		$stmt->bindParam(++$x,$this->getAcao(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getIndex(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getTarget(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getIcone(),PDO::PARAM_STR);
		$stmt->bindParam(++$x,$this->getId(),PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function Remover($lista)
	{
		$pdo = $this->getConexao();
		$lista = implode(",",$lista);
		$sql = "DELETE FROM map_menu WHERE id IN({$lista})";
//		$sql = "UPDATE menu SET excluido = NOW() WHERE id IN({$lista})";
		$stmt = $pdo->prepare($sql);
		return $stmt->execute();
	}

	public function ListarPaginacao($idGrupo,$numeroRegistros,$numeroInicioRegistro,$busca = "",$filtro = "",$ordem = "")
	{
		$pdo = $this->getConexao();
		
		$joins = "
		LEFT JOIN map_menu AS menu_pai ON (menu_pai.id = map_menu.id_pai)
		";
		
		$where = "
			WHERE map_menu.id > 0
		";
		
		if($busca != "") $where .= " AND (map_menu.nome LIKE :busca)";
		
		$sql = "
			SELECT COUNT(*) AS total
			FROM map_menu
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
				map_menu.*,
				CONCAT( 'ID:',menu_pai.id,' - ',menu_pai.nome )as nome_pai
			FROM map_menu
			$joins
			$where
		";

		if($filtro != "") $sql .=" ORDER BY $filtro $ordem"; else $sql .=" ORDER BY map_menu.id DESC";
		$sql .= " LIMIT $numeroInicioRegistro,$numeroRegistros";
		$stmt = $pdo->prepare($sql);
//		$stmt->bindParam(":offset",$numeroInicioRegistro,PDO::PARAM_INT);
//		$stmt->bindParam(":limit",$numeroRegistros,PDO::PARAM_INT);

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
		$sql = "SELECT * FROM map_menu WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(1,$this->getId(),PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetch();
	}


    public function GerarSelectPai()
    {
        $pdo = new Conexao();
        $sql = "
                SELECT
                  map_menu.id,
                  IF(
                    menu_pai.id != '',
                    CONCAT(menu_pai.nome, ' -> ', map_menu.nome)
                    ,    map_menu.nome
                  ) AS nome
                FROM
                  map_menu
                  LEFT JOIN map_menu AS menu_pai ON (menu_pai.id = map_menu.id_pai)
                WHERE map_menu.excluido IS NULL
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($listar as &$variavel)
        {
            if (defined($variavel->nome))
                $variavel->nome = constant($variavel->nome);
        }
        return $listar;
    }

    public function GerarSelectIdAcao()
    {
        $pdo = new Conexao();
        $sql = " SELECT
                map_acao.id,
                map_acao.nome
                FROM map_acao  ORDER BY nome ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $listar = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $listar;
    }

    public function ComboNome()
    {
        $pdo = new Conexao();
        $sql = "SELECT id,IF(menu_pai.nome = '',map_menu.nome,CONCAT(menu_pai.nome,'=>',map_menu.nome)) as nome FROM map_menu LEFT JOIN map_menu as menu_pai ON (menu_pai.id = map_menu.id_pai) WHERE excluido IS NULL ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ComboAcao()
    {
        $pdo = new Conexao();
        $sql = "SELECT id,nome FROM map_acao ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function ListarItensMenu($idGrupo)
    {
        $pdo  = new Conexao();
        $sql = "SELECT
          map_menu.*,
			map_modulo.dir
        FROM
          map_menu
        LEFT JOIN map_acao ON (map_menu.id_acao = map_acao.id)
        LEFT JOIN map_modulo ON (map_modulo.id = map_acao.modulo)
        WHERE
          map_menu.excluido IS NULL
          AND
            (map_menu.id_acao  IS NULL OR map_menu.id_acao = 0 OR map_menu.id_acao IN ( SELECT id_acao FROM map_grupo_acao WHERE id_grupo = {$idGrupo}))
        ORDER BY ordem,map_menu.nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        Conexao::pr($lista);
        return $lista;
    }
    public function ListarItensMenuUsuario($id_usuario)
    {
        $pdo  = new Conexao();
        $sql = "SELECT
          map_menu.*,
			map_modulo.dir
        FROM
          map_menu
        LEFT JOIN map_acao ON (map_menu.id_acao = map_acao.id)
        LEFT JOIN map_modulo ON (map_modulo.id = map_acao.modulo)
        WHERE
          map_menu.excluido IS NULL
          AND
            (map_menu.id_acao  IS NULL OR map_menu.id_acao = 0 OR map_menu.id_acao IN ( SELECT id_acao FROM map_usuario_acao WHERE id_usuario = {$id_usuario}))
        ORDER BY ordem ,map_menu.nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Conexao::pr($lista);
        return $lista;
    }

    public function MontarArvore( &$registros, $idPai,$nivel = 0 )
    {
        $retorno = null;
        // Percorre todos os registros
        foreach( $registros as $registro )
        {
            // Se o pai do registro for o noAtual
            if( intval($registro['id_pai']) == intval($idPai) )
            {
                $retorno[$registro['id']] = $registro;
                $retorno[$registro['id']]['nivel'] = $nivel + 1;
                $retorno[$registro['id']]['filhos'] = $this->MontarArvore( $registros, $registro['id'],$nivel + 1);

                if( !$retorno[$registro['id']]['filhos'] && $retorno[$registro['id']]['acao'] == "" )
                    unset( $retorno[$registro['id']] );
            }
        }
        return $retorno;
    }
    public function GerarMenu($id_grupo)
    {
        $itensMenu = $this->ListarItensMenu($id_grupo);
        $arvore = $this->MontarArvore($itensMenu, null);
        //print_r($arvore);
        $html = "       <div";
        $html .= "    class=\"menu menu-column menu-rounded menu-sub-indention px-3\"";
        $html .= "    id=\"#kt_app_sidebar_menu\"";
        $html .= "    data-kt-menu=\"true\"";
        $html .= "    data-kt-menu-expand=\"false\"";
        $html .= ">";
        $html .= $this->MenuMultiNiveis($arvore);
        $html .= "</div>";
        return $html;
    }
    public function GerarMenuUsuario($id_usuario)
    {
        $itensMenu = $this->ListarItensMenuUsuario($id_usuario);
        $arvore = $this->MontarArvore($itensMenu, null);
        $html = "       <div";
        $html .= "    class=\"menu menu-column menu-rounded menu-sub-indention px-3\"";
        $html .= "    id=\"#kt_app_sidebar_menu\"";
        $html .= "    data-kt-menu=\"true\"";
        $html .= "    data-kt-menu-expand=\"false\"";
        $html .= ">";
        $html .= $this->MenuMultiNiveis($arvore);
        $html .= "</div>";
        return $html;
    }
    public function MenuMultiNiveis( $arvore, &$html = "")
    {
        if(is_array($arvore) && @count($arvore) > 0)
        {

            foreach($arvore as $itemMenu)
            {

                $nome 		= $itemMenu['nome'];
                $target 	= ($itemMenu['target'] =="_blank")?'_blank':'';
                $pai        = $itemMenu['id_pai'];
                $index = ($itemMenu['index'] == 'index.php')?'index_xml.php':'index_xml.php';
                if( @count($itemMenu['filhos']) == 0)
                {
                    if($itemMenu['target'] =="_blank")
                    {
                        $onclick = " onclick='javascript:window.open(\"".$index."?app_modulo={$itemMenu['dir']}&app_comando={$itemMenu['acao']}\");'";
                        $href='href="#"';
                        $ajax = '';
                    }
                    else
                    {
                        $ajax = 'data-toggle="ajax"';
//                        $ajax = '';
                        $href = " href='".$index."?app_modulo={$itemMenu['dir']}&app_comando={$itemMenu['acao']}' " ;
//                        print_r($href);die;
                        $onclick = '';
                    }


                }

                else
                    $href = "";

//                $icone = "<i class=\"icon-Car-Wheel\"></i>";
                $icone = "<i class='".$itemMenu['icone']."'></i>";
                // Verifica se item � um menu pai
                if( @count($itemMenu['filhos']) )
                {
                    $html .= "    <!--begin:Menu item-->";
                    $html .= "    <div  data-kt-menu-trigger=\"click\"  class=\"menu-item here  menu-accordion\" >";
                    $html .= "<!--begin:Menu link-->";
                    $html .= "<span class=\"menu-link\" >";
                    $html .= "    <span  class=\"menu-icon\" >";
                    $html .= "    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->";
                    $html .= "<span class=\"svg-icon svg-icon-2\">";
                    $html .= $icone;
                    $html .= "</span>";
                    $html .= "<!--end::Svg Icon-->";
                    $html .= "    </span>";
                    $html .= "    <span  class=\"menu-title\" >".$nome."</span>";
                    $html .= "    <span  class=\"menu-arrow\" ></span>";
                    $html .= "</span>";
                    $html .= "<!--end:Menu link-->";
                    $html .= "<!--begin:Menu sub-->";
                    $html .= "<div  class=\"menu-sub menu-sub-accordion\" >";
                    $html .= "    <!--begin:Menu item-->";
                    $html .= "    <div  class=\"menu-item\" >";
                    $this->MenuMultiNiveis($itemMenu['filhos'], $html);
                    $html .= "        </div>";
                    $html .= "   </div>";
                    $html .= "</div>";
                }
                else
                {
                    if($itemMenu['nivel'] ==  1)
                    {

                        $html .= "<!--begin:Menu item-->";
                        $html .= "<div   class=\"menu-item here \" >";
                        $html .= "<!--begin:Menu link-->";
                        $html .= "<span class=\"menu-link\" >";
                        $html .= "    <span  class=\"menu-icon\" >";
                        $html .= "<span class=\"svg-icon svg-icon-2\">";
                        $html .= $icone;
                        $html .= "</span>";
                        $html .= "<!--end::Svg Icon-->";
                        $html .= "    </span>";
                        $html .= "<a  $target $href $onclick title=\"$nome\" $ajax>"."     <span  class=\"menu-title\" >".$nome."</span>";
                        $html .= "</a></span>";
                        $html .= "<!--end:Menu link-->";
                        $html .= "</div>";
                    }
                    else
                    {
                        $html .= "<!--begin:Menu link-->";
                        $html .= "<a class=\"menu-link \" $ajax  $target $href $onclick title=\"$nome\">";
                        $html .= "    <span  class=\"menu-icon\" >";
                        $html .= "<span class=\"svg-icon svg-icon-2\">";
                        $html .= $icone;
                        $html .= "</span>";
                        $html .= "<!--end::Svg Icon-->";
                        $html .= "    </span>";
                        $html .= "    <span  class=\"menu-title\" >$nome</span>";
                        $html .= "</a>";
                        $html .= "<!--end:Menu link-->";
                    }
                }
            }
        }
        return $html;
    }
}
