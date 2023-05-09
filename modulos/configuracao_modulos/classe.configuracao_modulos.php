<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:02
 */

class ConfiguracaoModulos{
    private $id;
    private $nome;
    private $rotulo;
    private $valor;
    private $padrao      = 1;        //campo habilitado por padrão
    private $obrigatorio = 0;   //campo opcional por padrão
    private $nameId;
    private $classe;
    private $conexao;
    private $id_configuracao_campos;
    private $id_modulo;

    public function setIdModulo($arg)
    {
        $this->id_modulo = $arg;
    }

    public function getIdModulo()
    {
        return $this->id_modulo;
    }

    public function setIdConfiguracaoCampos($arg)
    {
        $this->id_configuracao_campos = $arg;
    }

    public function getIdConfiguracaoCampos()
    {
        return $this->id_configuracao_campos;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getPadrao()
    {
        return $this->padrao;
    }

    public function setPadrao($padrao)
    {
        $this->padrao = $padrao;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getNameId()
    {
        return $this->nameId;
    }

    public function setNameId($nameId)
    {
        $this->nameId = $nameId;
    }

    public function getRotulo()
    {
        return $this->rotulo;
    }

    public function setRotulo($rotulo)
    {
        $this->rotulo = $rotulo;
    }

    public function getObrigatorio()
    {
        return $this->obrigatorio;
    }

    public function getClasse()
    {
        return $this->classe;
    }

    public function setClasse($classe)
    {
        $this->classe = $classe;
    }

    public function setObrigatorio($obrigatorio)
    {
        $this->obrigatorio = $obrigatorio;
    }
    public function getConexao()
    {
        return $this->conexao;
    }
    public function setConexao($conexao)
    {
        $this->conexao = $conexao;
    }
    public function __construct($conexao = null)
    {
        if ($conexao) {
            $this->conexao = $conexao;
        } else {
            $this->conexao = new Conexao();
        }
    }
    
    public function ListarPaginacaoCampos($numeroRegistros, $numeroInicioRegistro, $filtro = "", $ordem = "", $busca = "")
    {
        $pdo = $this->conexao;
        //Count
        $sql = "SELECT COUNT(*) AS total FROM configuracao_campos";
        if ($busca != "") {
            $sql .= " WHERE (nome LIKE '%$busca%' OR name_id LIKE '%$busca%')";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC);

        //Listagem
        $sql = "SELECT * FROM configuracao_campos";
        if ($busca != "") {
            $sql .= " WHERE (nome LIKE '%$busca%' OR name_id LIKE '%$busca%')";
        }
        if ($filtro != "") {
            $sql .= " ORDER BY $filtro $ordem";
        } else {
            $sql .= " ORDER BY id DESC";
        }
        $sql .= " LIMIT $numeroInicioRegistro, $numeroRegistros";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Array($lista, $totalRegistros);
    }

    public function ListarPaginacaoModulos($numeroRegistros, $numeroInicioRegistro, $filtro = "", $ordem = "", $busca = "")
    {
        $pdo = new Conexao();
        //Count
        $sql = "SELECT COUNT(*) AS total FROM map_modulo WHERE excluido IS NULL ";
        if ($busca != "") {
            $sql .= " AND nome LIKE '%$busca%'";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC);

        //Listagem
        $sql = "SELECT * FROM map_modulo WHERE excluido IS NULL";
        if ($busca != "") {
            $sql .= " AND nome LIKE '%$busca%'";
        }
        if ($filtro != "") {
            $sql .= " ORDER BY $filtro $ordem";
        } else {
            $sql .= " ORDER BY map_modulo.id DESC";
        }
        $sql .= " LIMIT $numeroInicioRegistro, $numeroRegistros";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return Array($lista, $totalRegistros);
    }

    public function Editar()
    {
        $pdo  = new Conexao();
        $sql  = "SELECT * FROM configuracao_campos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function Modificar()
    {
        $pdo = $this->conexao;
        $sql = "UPDATE configuracao_campos SET
                nome = ?,
                padrao = ?,
                name_id = ?,
                rotulo = ?,
                obrigatorio = ?,
                valor = ?,
                classe = ?";
        $sql .= " WHERE id = ?";

        $stmt = $pdo->prepare($sql);
        $x    = 0;
        $null = NULL;
        $stmt->bindParam(++$x, $this->getNome(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getPadrao(), PDO::PARAM_INT);
        $stmt->bindParam(++$x, $this->getNameId(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getRotulo(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getObrigatorio(), PDO::PARAM_INT);
        if ($this->getValor() != "") {
            $stmt->bindParam(++$x, $this->getValor(), PDO::PARAM_STR);
        } else {
            $stmt->bindParam(++$x, $null, PDO::PARAM_NULL);
        }
        if ($this->getClasse() != "") {
            $stmt->bindParam(++$x, $this->getClasse(), PDO::PARAM_STR);
        } else {
            $stmt->bindParam(++$x, $null, PDO::PARAM_NULL);
        }
        $stmt->bindParam(++$x, $this->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function Adicionar()
    {
        $pdo = new Conexao();
        $sql = "INSERT INTO configuracao_campos SET
                nome = ?,
                padrao = ?,
                name_id = ?,
                rotulo = ?,
                obrigatorio = ?,
                valor = ?,
                classe = ?";

        $stmt = $pdo->prepare($sql);
        $x    = 0;
        $null = NULL;
        $stmt->bindParam(++$x, $this->getNome(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getPadrao(), PDO::PARAM_INT);
        $stmt->bindParam(++$x, $this->getNameId(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getRotulo(), PDO::PARAM_STR);
        $stmt->bindParam(++$x, $this->getObrigatorio(), PDO::PARAM_INT);
        if ($this->getValor() != "") {
            $stmt->bindParam(++$x, $this->getValor(), PDO::PARAM_STR);
        } else {
            $stmt->bindParam(++$x, $null, PDO::PARAM_NULL);
        }
        if ($this->getClasse() != "") {
            $stmt->bindParam(++$x, $this->getClasse(), PDO::PARAM_STR);
        } else {
            $stmt->bindParam(++$x, $null, PDO::PARAM_NULL);
        }

        return $stmt->execute();
    }
    
    public function Remover($lista)
    {
        $lista = implode(",", $lista);
        $pdo  = new Conexao();
        $sql  = "DELETE FROM configuracao_campos WHERE id IN ($lista)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }

    public function RemoverTodos($id_modulo)
    {
        if($id_modulo == '') return;
        $pdo = $this->getConexao();
        $sql = "DELETE FROM map_configuracao_relatorios_campos WHERE id_modulo = $id_modulo";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }

    public function AdicionarConfModulo()
    {
        $pdo = $this->getConexao();
        $sql = '
		INSERT INTO map_configuracao_relatorios_campos SET id_modulo = ? ';
        if ($this->getIdConfiguracaoCampos() != "") $sql .= ",id_configuracao_campos = ?";

        $stmt = $pdo->prepare($sql);
        if ($this->getIdModulo() != "") $stmt->bindParam(++$x,$this->getIdModulo(),PDO::PARAM_INT);
        if ($this->getIdConfiguracaoCampos() != "") $stmt->bindParam(++$x,$this->getIdConfiguracaoCampos(),PDO::PARAM_INT);
        $stmt->execute();
        return $pdo->lastInsertId() ;
    }

    public function GerarTemplateConfiguracao($dirModulo)
    {
        $pdo  = new Conexao();
        $sql  = "SELECT
                    configuracao_campos.nome AS texto,
                    configuracao_campos.name_id,
                    configuracao_campos.valor AS value_texto,
                    map_configuracao_relatorios_campos.id AS value,
                    CASE WHEN configuracao_campos.obrigatorio = 1 THEN 'true' END AS obrigatorio,
                    CASE WHEN configuracao_campos.padrao = 1 THEN 'checked' END AS configuracao_padrao
                FROM map_configuracao_relatorios_campos
                    INNER JOIN configuracao_campos ON (configuracao_campos.id = map_configuracao_relatorios_campos.id_configuracao_campos)
                    INNER JOIN map_modulo ON (map_modulo.id = map_configuracao_relatorios_campos.id_modulo)
                WHERE map_modulo.dir = '$dirModulo' ORDER BY configuracao_campos.nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function RemoverConfig($id)
    {
        $pdo = $this->getConexao();
        $sql = "DELETE FROM map_configuracao_relatorios_campos WHERE id = $id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }
}