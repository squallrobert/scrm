<?
class Fatura extends Conexao
{
    private $idNotaFiscal;
    private $limiteVersaoDetalhada = 50;


    /*public function GetIdNotaFiscal()
    {
        return $this->idNotaFiscal;
    }*/

    public function SetIdNotaFiscal($arg)
    {
        $this->idNotaFiscal = $arg;
    }

    /**
     * Busca os dados da fatura de ocorrências
     */
    public function BuscarDadosFaturaOcorrencia($idOcorrencia)
    {
        // echo "----->" . $this->idNotaFiscal;
        $pdo = new Conexao();
        $sql = "SELECT
                    erp_usuario.nome AS cadpor, erp_usuario_cancelamento.nome AS cancelpor,
                    erp_nota_fiscal.id AS idNfe, erp_nota_fiscal.numero_fatura,
                    erp_contas_receber_ocorrencias.desconto_geral as desconto_geral_nf, 
                    erp_contas_receber_ocorrencias.desconto_detalhado as desconto_detalhado_nf, 
                    erp_nota_fiscal.faturas_origem,
                    erp_nota_fiscal.valor + erp_contas_receber.juros + erp_contas_receber.multa - COALESCE(erp_nota_fiscal.desconto_geral) - COALESCE(erp_nota_fiscal.desconto_detalhado) AS valor_nf,
                    (
                        select GROUP_CONCAT(numero_nfe SEPARATOR ', ')
                        FROM erp_nota_fiscal_anexos
                        WHERE id_erp_nota_fiscal = erp_nota_fiscal.id
                        AND excluido IS NULL
                    ) AS numero_nfe,

                    (
                        select GROUP_CONCAT(date_format(data_emissao_nfe, '%d/%m/%Y') SEPARATOR ', ')
                        FROM erp_nota_fiscal_anexos
                        WHERE id_erp_nota_fiscal = erp_nota_fiscal.id
                        AND excluido IS NULL
                    ) AS data_emissao_nfe,
                    date_format(erp_nota_fiscal.data_hora_cadastro,'%d/%m/%Y') AS data_hora_cadastro_nfe,
                    erp_contas_receber.id AS id_contas_receber,
                    erp_contas_receber.renegociado,
                    date_format(erp_contas_receber_ocorrencias.data_hora_cadastro,'%d/%m/%Y %H:%i') AS data_cadastro,
                    date_format(erp_contas_receber_ocorrencias.data_hora_cadastro,'%d/%m/%Y') AS datacadastro,
                    date_format(erp_contas_receber_ocorrencias.DATA_VENCIMENTO,'%d/%m/%Y') AS vencimento,
                    date_format(erp_contas_receber_ocorrencias.DATA_PAGAMENTO,'%d/%m/%Y') AS dta_pagamento,
                    date_format(erp_contas_receber_ocorrencias.data_hora_cancelamento,'%d/%m/%Y') AS dta_cancelamento,
                    0 AS valor_pago,
                    0 AS valor_com_desconto,
                    IF(cliente.nome_fantasia != '',cliente.nome_fantasia ,cliente.nome) AS cliente, cliente.cpf_cnpj AS cliente_documento, cliente.id AS id_cliente, cliente.id_endereco_cobranca,
                    erp_contas_receber_ocorrencias.*,
                    endereco_cobranca.email AS endcobranca_email, endereco_cobranca.logradouro AS endcobranca_logradouro, endereco_cobranca.numero AS endcobranca_numero,
                    endereco_cobranca.complemento AS endcobranca_complemento, endereco_cobranca.bairro AS endcobranca_bairro, endereco_cobranca.cep AS endcobranca_cep,
                    endereco_cobranca.uf AS endcobranca_estado, endereco_cobranca.cidade AS endcobranca_cidade, endereco_cobranca.ddd_telefone AS endcobranca_ddd,
                    endereco_cobranca.telefone AS endcobranca_telefone,
                    franqueado.nome AS franqueado, franqueado.cnpj AS franqueado_cnpj,
                    endereco_cliente.email AS cliente_email, endereco_cliente.logradouro AS cliente_logradouro, endereco_cliente.numero AS cliente_numero,
                    endereco_cliente.complemento AS cliente_complemento, endereco_cliente.bairro AS cliente_bairro, endereco_cliente.cep AS cliente_cep,
                    endereco_cliente.uf AS cliente_estado, endereco_cliente.cidade AS cliente_cidade, endereco_cliente.ddd_telefone AS cliente_ddd,
                    endereco_cliente.telefone AS cliente_telefone,
                    endereco_franqueado.logradouro AS franqueado_logradouro, endereco_franqueado.numero AS franqueado_numero,
                    endereco_franqueado.complemento AS franqueado_complemento, endereco_franqueado.bairro AS franqueado_bairro, endereco_franqueado.cep AS franqueado_cep,
                    endereco_franqueado.uf AS franqueado_estado, endereco_franqueado.cidade AS franqueado_cidade, endereco_franqueado.ddd_comercial AS franqueado_ddd,
                    endereco_franqueado.comercial AS franqueado_telefone,
                    (
                        SELECT DISTINCT erp_contas_receber_ocorrencias.id_forma_pagamento
                        FROM erp_contas_receber_ocorrencias
                        INNER JOIN forma_pagamento ON (forma_pagamento.id = erp_contas_receber_ocorrencias.id_forma_pagamento)
                        WHERE erp_contas_receber_ocorrencias.id = :id LIMIT 1
                    ) AS forma_pagamento,
                    date_format(min(erp_contas_receber.DATA_REFERENCIA),'%m/%Y') as mes_ano_referencia,";
        
        $sql .= "    contrato_servico.prazo_pagamento
                FROM erp_nota_fiscal
        		INNER JOIN erp_contas_receber ON (erp_nota_fiscal.id = erp_contas_receber.id_erp_nota_fiscal)
        		INNER JOIN erp_usuario ON (erp_usuario.id = erp_contas_receber.id_erp_usuario)
        		INNER JOIN cliente ON (cliente.id = erp_contas_receber.id_cliente)
        		INNER JOIN franqueado ON (franqueado.id = erp_contas_receber.id_franqueado)
        		INNER JOIN endereco AS endereco_cliente ON (endereco_cliente.id = cliente.id_endereco)
        		INNER JOIN endereco AS endereco_franqueado ON (endereco_franqueado.id = franqueado.id_endereco)
                INNER JOIN erp_nota_fiscal_item ON (erp_nota_fiscal_item.id_erp_nota_fiscal = erp_nota_fiscal.id)
                LEFT JOIN contrato_servico ON (erp_nota_fiscal_item.id_contrato = contrato_servico.id_contrato)
        		LEFT JOIN erp_usuario AS erp_usuario_cancelamento ON (erp_usuario_cancelamento.id = erp_contas_receber.id_erp_usuario_cancelamento)
                LEFT JOIN endereco AS endereco_cobranca ON (endereco_cobranca.id = cliente.id_endereco_cobranca)
                LEFT JOIN erp_contas_receber_ocorrencias ON (erp_contas_receber_ocorrencias.id_contas_receber = erp_contas_receber.id)
                WHERE erp_contas_receber_ocorrencias.id = :id";
        //echo $sql;erp_contas_receber
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $idOcorrencia, PDO::PARAM_INT);
        $stmt->execute();
        $linha = $stmt->fetch(PDO::FETCH_OBJ);
        if(is_null($linha->valor) || $linha->valor == "") $linha->valor = 0;
        if(is_null($linha->juros) || $linha->juros == "") $linha->juros = 0;
        if(is_null($linha->multa) || $linha->multa == "") $linha->multa = 0;
        if(is_null($linha->desconto) || $linha->desconto == "") $linha->desconto = 0;
        //calcula valor pago - R17
        $linha->valor_pago = $linha->valor + $linha->juros + $linha->multa - $linha->desconto;
        $linha->valor_com_desconto = $linha->valor - $linha->desconto_geral - $linha->desconto_detalhado;
        return $linha;
    }
    /**
     *  Busca os dados da fatura.
     *
     */
    public function BuscarDadosFatura()
    {
        // echo "----->" . $this->idNotaFiscal;

        if($this->idNotaFiscal > 0 === false)
            return false;

        $pdo = new Conexao();

        // Busca os IDs dos contratos da fatura
        // Busca os IDs dos contratos da fatura
        $stmt = $pdo->prepare("SET SESSION group_concat_max_len = 1000000;");
        $stmt->execute();

        $sql = "SELECT GROUP_CONCAT(DISTINCT id_contrato SEPARATOR ',') as ids FROM erp_nota_fiscal_item WHERE id_erp_nota_fiscal = ?";
//        echo str_replace("?", $this->idNotaFiscal, $sql); die;
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $this->idNotaFiscal, PDO::PARAM_INT);
        $stmt->execute();
        $idsContratos = $stmt->fetch(PDO::FETCH_OBJ);
        $idsContratos = $idsContratos->ids;


        /*SELECT date_format(min(DATA_REFERENCIA),'%m/%Y') as mesano
	        		FROM erp_nota_fiscal_item WHERE id_erp_nota_fiscal = :id_erp_nota_fiscal*/
        //query que conta numero de registros
        $sql = "SELECT
                    erp_usuario.nome AS cadpor, erp_usuario_cancelamento.nome AS cancelpor,
                    erp_nota_fiscal.id AS idNfe, erp_nota_fiscal.numero_fatura,
                    erp_nota_fiscal.desconto_geral as desconto_geral_nf, 
                    erp_nota_fiscal.desconto_detalhado as desconto_detalhado_nf, 
                    erp_nota_fiscal.faturas_origem,
                    erp_nota_fiscal.valor + erp_contas_receber.juros + erp_contas_receber.multa - COALESCE(erp_nota_fiscal.desconto_geral) - COALESCE(erp_nota_fiscal.desconto_detalhado) AS valor_nf,
                    (
                        select GROUP_CONCAT(numero_nfe SEPARATOR ', ')
                        FROM erp_nota_fiscal_anexos
                        WHERE id_erp_nota_fiscal = erp_nota_fiscal.id
                        AND excluido IS NULL
                    ) AS numero_nfe,

                    (
                        select GROUP_CONCAT(date_format(data_emissao_nfe, '%d/%m/%Y') SEPARATOR ', ')
                        FROM erp_nota_fiscal_anexos
                        WHERE id_erp_nota_fiscal = erp_nota_fiscal.id
                        AND excluido IS NULL
                    ) AS data_emissao_nfe,
                    date_format(erp_nota_fiscal.data_hora_cadastro,'%d/%m/%Y') AS data_hora_cadastro_nfe,
                    erp_contas_receber.id AS id_contas_receber,
                    erp_contas_receber.renegociado,
                    date_format(erp_contas_receber.data_hora_cadastro,'%d/%m/%Y %H:%i') AS data_cadastro,
                    date_format(erp_contas_receber.data_hora_cadastro,'%d/%m/%Y') AS datacadastro,
                    date_format(erp_contas_receber.DATA_VENCIMENTO,'%d/%m/%Y') AS vencimento,
                    date_format(erp_contas_receber.DATA_PAGAMENTO,'%d/%m/%Y') AS dta_pagamento,
                    date_format(erp_contas_receber.data_cancelamento,'%d/%m/%Y') AS dta_cancelamento,
                    0 AS valor_pago,
                    0 AS valor_com_desconto,
                    IF(cliente.nome_fantasia != '',cliente.nome_fantasia ,cliente.nome) AS cliente, cliente.cpf_cnpj AS cliente_documento, cliente.id AS id_cliente, cliente.id_endereco_cobranca,
                    erp_contas_receber.*,
                    endereco_cobranca.email AS endcobranca_email, endereco_cobranca.logradouro AS endcobranca_logradouro, endereco_cobranca.numero AS endcobranca_numero,
                    endereco_cobranca.complemento AS endcobranca_complemento, endereco_cobranca.bairro AS endcobranca_bairro, endereco_cobranca.cep AS endcobranca_cep,
                    endereco_cobranca.uf AS endcobranca_estado, endereco_cobranca.cidade AS endcobranca_cidade, endereco_cobranca.ddd_telefone AS endcobranca_ddd,
                    endereco_cobranca.telefone AS endcobranca_telefone,
                    franqueado.nome AS franqueado, franqueado.cnpj AS franqueado_cnpj,
                    endereco_cliente.email AS cliente_email, endereco_cliente.logradouro AS cliente_logradouro, endereco_cliente.numero AS cliente_numero,
                    endereco_cliente.complemento AS cliente_complemento, endereco_cliente.bairro AS cliente_bairro, endereco_cliente.cep AS cliente_cep,
                    endereco_cliente.uf AS cliente_estado, endereco_cliente.cidade AS cliente_cidade, endereco_cliente.ddd_telefone AS cliente_ddd,
                    endereco_cliente.telefone AS cliente_telefone,
                    endereco_franqueado.logradouro AS franqueado_logradouro, endereco_franqueado.numero AS franqueado_numero,
                    endereco_franqueado.complemento AS franqueado_complemento, endereco_franqueado.bairro AS franqueado_bairro, endereco_franqueado.cep AS franqueado_cep,
                    endereco_franqueado.uf AS franqueado_estado, endereco_franqueado.cidade AS franqueado_cidade, endereco_franqueado.ddd_comercial AS franqueado_ddd,
                    endereco_franqueado.comercial AS franqueado_telefone,
                    (
                        SELECT DISTINCT erp_contas_receber.id_forma_pagamento
                        FROM erp_contas_receber
                        INNER JOIN forma_pagamento ON (forma_pagamento.id = erp_contas_receber.id_forma_pagamento)
                        WHERE erp_contas_receber.id_erp_nota_fiscal = :id LIMIT 1
                    ) AS forma_pagamento,
                    date_format(min(erp_contas_receber.DATA_REFERENCIA),'%m/%Y') as mes_ano_referencia,";

        if($idsContratos)
        {
            $sql .= "(SELECT fatura_carne FROM contrato_habilitacao WHERE id_contrato IN ($idsContratos) ORDER BY fatura_carne DESC LIMIT 1) as carne_habilitacao,
                             (SELECT fatura_carne FROM contrato_servico WHERE id_contrato IN ($idsContratos) ORDER BY fatura_carne DESC LIMIT 1) as carne_servico, ";
        }
        $sql .= "    contrato_servico.prazo_pagamento
                FROM erp_nota_fiscal
        		INNER JOIN erp_contas_receber ON (erp_nota_fiscal.id = erp_contas_receber.id_erp_nota_fiscal)
        		INNER JOIN erp_usuario ON (erp_usuario.id = erp_contas_receber.id_erp_usuario)
        		INNER JOIN cliente ON (cliente.id = erp_contas_receber.id_cliente)
        		INNER JOIN franqueado ON (franqueado.id = erp_contas_receber.id_franqueado)
        		INNER JOIN endereco AS endereco_cliente ON (endereco_cliente.id = cliente.id_endereco)
        		INNER JOIN endereco AS endereco_franqueado ON (endereco_franqueado.id = franqueado.id_endereco)
                INNER JOIN erp_nota_fiscal_item ON (erp_nota_fiscal_item.id_erp_nota_fiscal = erp_nota_fiscal.id)
                LEFT JOIN contrato_servico ON (erp_nota_fiscal_item.id_contrato = contrato_servico.id_contrato)
        		LEFT JOIN erp_usuario AS erp_usuario_cancelamento ON (erp_usuario_cancelamento.id = erp_contas_receber.id_erp_usuario_cancelamento)
                LEFT JOIN endereco AS endereco_cobranca ON (endereco_cobranca.id = cliente.id_endereco_cobranca)
        		WHERE erp_nota_fiscal.id = :id";
        //echo $sql;erp_contas_receber
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $this->idNotaFiscal, PDO::PARAM_INT);
        //echo $sql;
        $stmt->execute();
        $linha = $stmt->fetch(PDO::FETCH_OBJ);
        if(is_null($linha->valor) || $linha->valor == "") $linha->valor = 0;
        if(is_null($linha->juros) || $linha->juros == "") $linha->juros = 0;
        if(is_null($linha->multa) || $linha->multa == "") $linha->multa = 0;
        if(is_null($linha->desconto) || $linha->desconto == "") $linha->desconto = 0;

        //calcula valor pago - R17
        $linha->valor_pago = $linha->valor + $linha->juros + $linha->multa - $linha->desconto;
        $linha->valor_com_desconto = $linha->valor - $linha->desconto_geral - $linha->desconto_detalhado;
        return $linha;
    }

    public function BuscarItensFatura()
    {
        if($this->idNotaFiscal > 0 === false)
            return false;

        $pdo = new Conexao();

        $sql = "SELECT 
                IFNULL(veiculo.rotulo,'Sem placa') as veiculo,
                erp_item_faturamento.nome AS item_faturamento,
        		erp_nota_fiscal_item.valor,
                erp_nota_fiscal.faturas_origem,
                erp_nota_fiscal_item.desconto,
                erp_nota_fiscal_item.valor - erp_nota_fiscal_item.desconto AS valor_com_desconto,
                erp_nota_fiscal_item.id_erp_item_faturamento AS id_item_faturamento,
                DATE_FORMAT(erp_nota_fiscal_item.data_referencia,'%m/%Y') AS faturamento_habilitacao_atual,
                erp_nota_fiscal.valor AS total, DATE_FORMAT(erp_nota_fiscal_item.data_referencia,'%m/%Y' ) AS dt_referencia,
                contrato_servico.valor_mensal AS valor_mensal_servico,
                contrato.id as id_contrato,
                contrato.data_ativacao,
                contrato.dia_vencimento,
                numero_contrato,
                contrato_habilitacao.parcelas,
                contrato_habilitacao.parcelas_faturadas,
                erp_contrato_acessorio.parcelas AS parcelas_acessorios,
                erp_contrato_acessorio.parcelas_faturadas AS parcelas_faturadas_acessorios,
                (
                    SELECT MIN(DATE_FORMAT(erp_nota_fiscal_item.data_referencia,'%m/%Y'))
                	FROM erp_nota_fiscal_item
                	WHERE id_erp_nota_fiscal = :id_nota_fiscal AND id_contrato = contrato.id
                	ORDER BY id LIMIT 1
                ) AS faturamento_habilitacao_primeiro,
                IF(
                    erp_nota_fiscal_item.id_erp_item_faturamento = 2,
                    (SELECT
                        COUNT(erp_nota_fiscal_item_sub.id)
                    FROM
                        erp_nota_fiscal_item AS erp_nota_fiscal_item_sub
                        INNER JOIN erp_nota_fiscal ON (erp_nota_fiscal_item_sub.id_erp_nota_fiscal = erp_nota_fiscal.id)
                        INNER JOIN erp_contas_receber ON (erp_nota_fiscal.id = erp_contas_receber.id_erp_nota_fiscal)
                    WHERE  id_contrato = contrato.id AND
                        erp_nota_fiscal_item_sub.id_erp_item_faturamento = 2
                            AND erp_contas_receber.data_cancelamento IS NULL
                            AND erp_nota_fiscal_item_sub.id_contrato = erp_nota_fiscal_item.id_contrato
                            AND ((erp_nota_fiscal_item_sub.id < erp_nota_fiscal_item.id
                            AND erp_nota_fiscal_item_sub.data_referencia = erp_nota_fiscal_item.data_referencia)
                            OR erp_nota_fiscal_item_sub.data_referencia < erp_nota_fiscal_item.data_referencia)) + 1,
                0) AS numero_habilitacao_faturado,
  
                 contrato_habilitacao.`parcelas_faturadas` AS quantidade_habilitacao_faturada,
 
                IF( contrato_servico.`parcelas_faturadas` <= 1  , 1, 0) AS numero_servico_faturado
                 
        		FROM erp_nota_fiscal_item
        		INNER JOIN erp_nota_fiscal ON (erp_nota_fiscal.id = erp_nota_fiscal_item.id_erp_nota_fiscal)
        		LEFT JOIN contrato ON (contrato.id = erp_nota_fiscal_item.id_contrato)
        		LEFT JOIN veiculo ON (veiculo.id = contrato.id_veiculo)
                LEFT JOIN contrato_servico ON (contrato_servico.id_contrato = contrato.id)
                LEFT JOIN erp_contrato_acessorio ON (erp_nota_fiscal_item.id_erp_contrato_acessorio = erp_contrato_acessorio.id)
        		INNER JOIN erp_item_faturamento ON (erp_item_faturamento.id = erp_nota_fiscal_item.id_erp_item_faturamento)
                LEFT JOIN contrato_habilitacao ON (contrato_habilitacao.id_contrato = contrato.id)
        		WHERE erp_nota_fiscal.id = :id_nota_fiscal
        		ORDER BY veiculo.rotulo ASC";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id_nota_fiscal", $this->idNotaFiscal, PDO::PARAM_INT);
        $stmt->execute();

        //       Conexao::pd($stmt->fetchAll(PDO::FETCH_OBJ));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function BuscarItensFaturaOcorrencia($idOcorrencia)
    {
        $pdo = new Conexao();

        $sql = "SELECT 
                IFNULL(veiculo.rotulo,'Sem placa') as veiculo,
                erp_nota_fiscal_item.descricao AS item_faturamento,
        		erp_nota_fiscal_item.valor,
                erp_nota_fiscal.faturas_origem,
                erp_nota_fiscal_item.desconto,
                erp_nota_fiscal_item.valor - erp_nota_fiscal_item.desconto AS valor_com_desconto,
                erp_nota_fiscal_item.id_erp_item_faturamento AS id_item_faturamento,
                DATE_FORMAT(erp_nota_fiscal_item.data_referencia,'%m/%Y') AS faturamento_habilitacao_atual,
                erp_nota_fiscal.valor AS total, DATE_FORMAT(erp_nota_fiscal_item.data_referencia,'%m/%Y' ) AS dt_referencia,
                contrato_servico.valor_mensal AS valor_mensal_servico,
                contrato.id as id_contrato,
                contrato.data_ativacao,
                contrato.dia_vencimento,
                numero_contrato,
                contrato_habilitacao.parcelas,
                contrato_habilitacao.parcelas_faturadas,
                erp_contrato_acessorio.parcelas AS parcelas_acessorios,
                erp_contrato_acessorio.parcelas_faturadas AS parcelas_faturadas_acessorios,
                IF(
                    erp_nota_fiscal_item.id_erp_item_faturamento = 2,
                    (SELECT
                        COUNT(erp_nota_fiscal_item_sub.id)
                    FROM
                        erp_nota_fiscal_item AS erp_nota_fiscal_item_sub
                        INNER JOIN erp_nota_fiscal ON (erp_nota_fiscal_item_sub.id_erp_nota_fiscal = erp_nota_fiscal.id)
                        INNER JOIN erp_contas_receber ON (erp_nota_fiscal.id = erp_contas_receber.id_erp_nota_fiscal)
                    WHERE  id_contrato = contrato.id AND
                        erp_nota_fiscal_item_sub.id_erp_item_faturamento = 2
                            AND erp_contas_receber.data_cancelamento IS NULL
                            AND erp_nota_fiscal_item_sub.id_contrato = erp_nota_fiscal_item.id_contrato
                            AND ((erp_nota_fiscal_item_sub.id < erp_nota_fiscal_item.id
                            AND erp_nota_fiscal_item_sub.data_referencia = erp_nota_fiscal_item.data_referencia)
                            OR erp_nota_fiscal_item_sub.data_referencia < erp_nota_fiscal_item.data_referencia)) + 1,
                0) AS numero_habilitacao_faturado,
  
                 contrato_habilitacao.`parcelas_faturadas` AS quantidade_habilitacao_faturada,
 
                IF( contrato_servico.`parcelas_faturadas` <= 1  , 1, 0) AS numero_servico_faturado
                 
        		FROM erp_nota_fiscal_item
        		INNER JOIN erp_nota_fiscal ON (erp_nota_fiscal.id = erp_nota_fiscal_item.id_erp_nota_fiscal)
        		LEFT JOIN contrato ON (contrato.id = erp_nota_fiscal_item.id_contrato)
        		LEFT JOIN veiculo ON (veiculo.id = contrato.id_veiculo)
                LEFT JOIN contrato_servico ON (contrato_servico.id_contrato = contrato.id)
                LEFT JOIN erp_contrato_acessorio ON (erp_nota_fiscal_item.id_erp_contrato_acessorio = erp_contrato_acessorio.id)
        		LEFT JOIN erp_item_faturamento ON (erp_item_faturamento.id = erp_nota_fiscal_item.id_erp_item_faturamento)
                LEFT JOIN contrato_habilitacao ON (contrato_habilitacao.id_contrato = contrato.id)
                INNER JOIN erp_contas_receber ON (erp_contas_receber.id_erp_nota_fiscal = erp_nota_fiscal.id)
                LEFT JOIN erp_contas_receber_ocorrencias ON (erp_contas_receber_ocorrencias.id_contas_receber = erp_contas_receber.id)
        		WHERE erp_contas_receber_ocorrencias.id = :id
        		ORDER BY veiculo.rotulo ASC";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(":id", $idOcorrencia, PDO::PARAM_INT);
        $stmt->execute();

        //       Conexao::pd($stmt->fetchAll(PDO::FETCH_OBJ));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function AgruparItensFatura($itensFatura)
    {
        /*$sql = "SELECT veiculo.rotulo AS veiculo,
        erp_item_faturamento.nome AS item_faturamento,
        COUNT(erp_item_faturamento.nome) AS qtde_item,
		SUM(erp_nota_fiscal_item.valor) AS valor,
        SUM(erp_nota_fiscal_item.desconto) AS desconto,
        SUM(erp_nota_fiscal_item.valor)-SUM(erp_nota_fiscal_item.desconto) AS valor_com_desconto,
		erp_nota_fiscal.valor AS total,
        COUNT(CASE when erp_nota_fiscal_item.desconto > 0 THEN erp_item_faturamento.nome END) AS qtde_item_desconto

		FROM erp_nota_fiscal_item

		INNER JOIN erp_nota_fiscal ON (erp_nota_fiscal.id = erp_nota_fiscal_item.id_erp_nota_fiscal)
		INNER JOIN erp_contas_receber ON (erp_contas_receber.id_erp_nota_fiscal = erp_nota_fiscal.id)
		INNER JOIN contrato ON (contrato.id = erp_nota_fiscal_item.id_contrato)
		INNER JOIN veiculo ON (veiculo.id = contrato.id_veiculo)
		INNER JOIN erp_item_faturamento ON (erp_item_faturamento.id = erp_nota_fiscal_item.id_erp_item_faturamento)
		WHERE erp_contas_receber.id=:id
		GROUP BY erp_item_faturamento.id
		";*/

        $itensAgrupados = array();
        foreach($itensFatura as $item)
        {
            if(!is_array($itensAgrupados[$item->item_faturamento]))
            {
                $itensAgrupados[$item->item_faturamento] = array();
                $itensAgrupados[$item->item_faturamento]['quantidade'] = 0;
                $itensAgrupados[$item->item_faturamento]['valor'] = 0;
                $itensAgrupados[$item->item_faturamento]['desconto'] = 0;
            }

            $itensAgrupados[$item->item_faturamento]['quantidade']++;
            $itensAgrupados[$item->item_faturamento]['valor'] += $item->valor;
            $itensAgrupados[$item->item_faturamento]['desconto'] += $item->desconto;
        }

        return $itensAgrupados;
    }

    public function GerarFatura($imprimirFatura = true, $dadosPreFatura = null, $qtdBoletos = 1, $parcial = "", $idOcorrencia)
    {
        if($this->idNotaFiscal > 0 === false && $dadosPreFatura == null && empty($idOcorrencia))
        return false;

        if($dadosPreFatura)
            $dadosFatura = $dadosPreFatura->dadosFatura;
        else if(!empty($idOcorrencia))
            $dadosFatura = $this->BuscarDadosFaturaOcorrencia($idOcorrencia);
        else
            $dadosFatura = $this->BuscarDadosFatura();
        //echo '<pre>'; print_r($dadosFatura); echo '</pre>';die();


        $mesAnoReferencia = $dadosFatura->mes_ano_referencia;
        /*$linha = $erp_impressao_fatura->GetDadosEdicao($id_nota_fiscal);
        $ErpNotaFiscalItem = new ErpNotaFiscalItem();
        $mesAnoReferencia = $ErpNotaFiscalItem->GetMesAnoReferencia($linha->idnfe);*/

        if($dadosFatura->franqueado_ddd != null && $dadosFatura->franqueado_ddd != "")
            $foneFranqueado = '('.$dadosFatura->franqueado_ddd.') ';

        if($dadosFatura->franqueado_telefone != null && $dadosFatura->franqueado_telefone != "")
        {
            $telefoneBd = preg_replace("/[^0-9]/",'', $dadosFatura->franqueado_telefone);
            $foneFranqueado .= substr($telefoneBd, 0, strlen($telefoneBd)-4) . "-" . substr($telefoneBd, -4);
        }

        //Monta endereço de cobrança
        $enderecoCobranca = "";//Apresentar campos LOGRADOURO+’, ’ +NUMERO+’ ‘+COMPLEMENTO+’-‘+BAIRRO da tabela ENDERECO
        $cidadeCobranca = "";
        $estadoCobranca = "";
        $cepCobranca = "";

        // Define qual endereço do cliente será exibido no endereço de cobrança
        if($dadosFatura->id_endereco_cobranca > 0 === false)
        {
            $tmpLogradouro = trim($dadosFatura->cliente_logradouro);
            $tmpNumero = trim($dadosFatura->cliente_numero);
            $tmpComplemento = trim($dadosFatura->cliente_complemento);
            $tmpBairro = trim($dadosFatura->cliente_bairro);
            $tmpCidade = trim($dadosFatura->cliente_cidade);
            $tmpUF = trim($dadosFatura->cliente_estado);
            $tmpCEP = trim($dadosFatura->cliente_cep);
        }
        else
        {
            $tmpLogradouro = trim($dadosFatura->endcobranca_logradouro);
            $tmpNumero = trim($dadosFatura->endcobranca_numero);
            $tmpComplemento = trim($dadosFatura->endcobranca_complemento);
            $tmpBairro = trim($dadosFatura->cliente_bairro);
            $tmpCidade = trim($dadosFatura->endcobranca_cidade);
            $tmpUF = trim($dadosFatura->endcobranca_estado);
            $tmpCEP = trim($dadosFatura->endcobranca_cep);
        }
        $cidadeCobranca = $tmpCidade;
        $estadoCobranca = $tmpUF;
        $cepCobranca = $tmpCEP;
        $enderecoCobranca  = trim($tmpLogradouro).", ".trim($tmpNumero)." ".trim($tmpComplemento);
        if($tmpBairro != '')
            $enderecoCobranca .= " - ".$tmpBairro;


        /*$totalItens = $erp_contas_receber->GetTotalItensNotas($linha->id_contas_receber);
        $limiteVersaoDetalhada = 24;

        $itensNota = array();
        if($totalItens >= $limiteVersaoDetalhada)
        {
            $itensNota  = $erp_contas_receber->GetItensNotasToImpressaoSintetica($linha->id_contas_receber);
            $versaoAnalitica = false;
        }
        else
        {
            $itensNota  = $erp_contas_receber->GetItensNotasToImpressaoAnalitica($linha->id_contas_receber);
            $versaoAnalitica = true;
        }

        $itensNotaRel  = $erp_contas_receber->GetItensNotasToImpressaoAnalitica($linha->id_contas_receber);

        /*
         * A LINHA ABAIXO ESTAVA NA PRIMEIRA LINHA DA VARIAVEL $html.
         */
        //<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        $html = '
        
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
            <title>'.ROTULO_IMPRESSAO_BOLETO.'</title>
            <style type="text/css">
                body {
                	font-family: Verdana, Arial, Helvetica, sans-serif;
                	font-size: 12px;
                }
                table {
                    border-spacing:0;
                    border-collapse:collapse;
                }
                .box
                {
                    width:816px;
                }
                #tabela_mae
                {
                    border-color: #000;
                }
                .fatura_bloco1, .fatura_bloco2, .fatura_bloco3, .fatura_bloco4
                {
                    border: 1px solid #000;
                }
                .fatura_bloco5
                {
                    border-top: 1px solid #000;
                }
                .fatura_bloco1 td
                {
                    border: 0;
                    padding: 2px;
                }
                .fatura_bloco2 td
                {
                    border: 1px solid #000;
                    font-weight: bold;
                    padding: 2px;
                    text-align: center;
                }
                .fatura_bloco3 td
                {
                    border: 0;
                    padding: 2px;
                	text-transform: uppercase;
                }
                .fatura_bloco4 td
                {
                    border: 1px solid #000;
                    padding: 2px;
                }
                .fatura_bloco5 td
                {
                    border-left: 1px solid #000;
                    border-right: 1px solid #000;
                    padding: 2px;
                }
                .fatura_bloco5 th
                {
                    border-left: 1px solid #000;
                    border-right: 1px solid #000;
                    border-bottom: 1px solid #000;
                    text-align: center;
                    padding: 2px;
                }
                /*.subtabelas
                {
                    border-spacing: 0;
                    border-collapse: collapse;
                    border-style: solid;
                    border-color: #000;
                }*/
                label
                {
                	margin-right: 10px;
                	text-transform: uppercase;
                }
                .rotulo_upper
                {
                    text-transform: uppercase;
                }
                .sem_formatacao, .sem_formatacao td
                {
                    padding: 0;
                    margin: 0;
                    border: 1px solid #FF0000;
                }

            </style>
        </head>
        <body>';
        // Se for carnê não exibe a fatura
        $comFatura = false;
        //if($dadosFatura->carne_habilitacao == 0 && $dadosFatura->carne_servico == 0)
//        if($dadosFatura->faturas_origem != '' )
//            $imprimirFatura = false;
        //else
        //    $imprimirFatura = true;
        //echo "-->".$imprimirFatura;die();
        if($imprimirFatura)
        {
            $comFatura = true;
            $html .= '
            <div class="box">
                <div class="table_container">
    				<table class="fatura_bloco1" width="100%" cellpadding="0" cellspacing="0" border="0">
    					<tr>
    						<td align="center" rowspan="6" width="180"><img src="img/logo_preta.png" align="absmiddle"></td>
                            <td colspan="2" class="sem_formatacao" height="10"></td>
                        </tr>
                        <tr>
                            <td colspan="2">'.$dadosFatura->franqueado.'</td>
    					</tr>
    					<tr>
    						<td colspan="2">'.$dadosFatura->franqueado_logradouro.", ".$dadosFatura->franqueado_numero." - ".$dadosFatura->franqueado_bairro.'</td>
    					</tr>
    					<tr>
    						<td width="330">'.trim($dadosFatura->franqueado_cidade)."/". trim($dadosFatura->franqueado_estado)." - ".ROTULO_CEP." ". trim($dadosFatura->franqueado_cep).'</td>
    						<td align="center" width="200"><b>'.ROTULO_CNPJ.': '.$dadosFatura->franqueado_cnpj.'</b></td>
    					</tr>
    					<tr>
    						<td>'.ROTULO_TELEFONE.': <b>'.$foneFranqueado.'</b></td>
    						<td align="center">www.linkmonitoramento.com.br</td>
    					</tr>
                        <tr><td colspan="2" class="sem_formatacao" height="10"></td></tr>
    				</table>
                </div>
                <div class="table_container">
    				<table class="fatura_bloco2" cellpadding="0" cellspacing="0" width="100%" border="0">
    					<tr>
    						<td class="rotulo_upper" width="138">'.ROTULO_NUMERO_DA_FATURA.'</td>';

            if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
                $html .= '
                            <td class="rotulo_upper" width="138">'.ROTULO_MES_REFERENCIA_UPPER.'</td>';

            $html .= '
                            <td class="rotulo_upper" width="138">'.ROTULO_DATA_EMISSAO_UPPER.'</td>
    						<td class="rotulo_upper" width="138">'.ROTULO_VALOR_UPPER.'</td>';

            if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
                $html .= '
                            <td class="rotulo_upper" width="138">'.ROTULO_VENCIMENTO_UPPER.'</td>';

            if($dadosFatura->numero_nfe != '')
                $html .= '
                            <td class="rotulo_upper" width="138">Numero NFE</td>';

            $html .= '
                        </tr>
    					<tr>
    						<td>'.$dadosFatura->numero_fatura.'</td>';

            if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
                $html .= '
                            <td>'.$mesAnoReferencia.'</td>';

            $html .= '
                            <td>'.$dadosFatura->data_hora_cadastro_nfe.'</td>
    						<td>R$ '.Utils::MaskFloat($dadosFatura->valor_nf).'</td>';

            if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
                $html .= '
                            <td>'.$dadosFatura->vencimento.'</td>';

            if($dadosFatura->numero_nfe != '')
                $html .= '
                            <td>'.$dadosFatura->numero_nfe.'</td>';
            if($dadosFatura->prazo_pagamento == 3)
                $prazo = "TRIMESTRAL";
            else  if($dadosFatura->prazo_pagamento == 6)
                $prazo = "SEMESTRAL";
            else  if($dadosFatura->prazo_pagamento == 12)
                $prazo = "ANUAL";
            else
                $prazo = "MENSAL";
            $html .= '

                        </tr>
                        <tr>
                            <td style="text-align:left" colspan="5">PERIODO DE COBRANÇA: '.$prazo.'</td>
                        </tr>
    				</table>
                </div>
        		<!-- fim quadro 2 -->

        		<!-- Quadro 3 -->
        		<div class="table_container">
    				<table class="fatura_bloco3" cellpadding="0" cellspacing="0" width="100%" border="0">
    					<tr>
    						<td colspan="3">'.ROTULO_CLIENTE.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->cliente.'</b>
    						</td>
    					</tr>
    					<tr>
    						<td colspan="3">'.ROTULO_ENDERECO.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->cliente_logradouro.", ".$dadosFatura->cliente_numero." ".$dadosFatura->cliente_complemento." - ".$dadosFatura->cliente_bairro.'</b></td>
    					</tr>
    					<tr>
    						<td style="width: 35%;">'.ROTULO_CIDADE.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->cliente_cidade.'</b></td>
    						<td style="min-width: 100px;">'.ROTULO_ESTADO.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->cliente_estado.'</b></td>
    						<td style="min-width: 110px;">'.ROTULO_CEP.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->cliente_cep.'</b></td>
    					</tr>
    					<tr>
    						<td colspan="3">'.ROTULO_ENDERECO_COBRANCA.':&nbsp;&nbsp;&nbsp;<b>'.$enderecoCobranca.'</b></td>
    					</tr>
    					<tr>
    						<td>'.ROTULO_CIDADE_DE_COBRANCA.':&nbsp;&nbsp;&nbsp;<b>'.$cidadeCobranca.'</b></td>
    						<td>'.ROTULO_ESTADO.':&nbsp;&nbsp;&nbsp;<b>'.$estadoCobranca.'</b></td>
    						<td>'.ROTULO_CEP.':&nbsp;&nbsp;&nbsp;<b>'.$cepCobranca.'</b></td>
    					</tr>
    					<tr>
    						<td colspan="3">'.ROTULO_CPF_CNPJ.':&nbsp;&nbsp;&nbsp;<b>'.$dadosFatura->cliente_documento.'</b></td>
    					</tr>
    				</table>
    			</div>
        		<!-- Fim Quadro 3 -->

        		<!-- quadro 4 -->
        		<div class="table_container">
    				<table class="fatura_bloco4" cellpadding="0" cellspacing="0" width="100%" border="0">
    					<tr>
    						<th class="rotulo_upper" style="width: 70px;">'.ROTULO_VALOR_EXTENSO.'</th>
    						<td>';

            $aValor = explode(".", $dadosFatura->valor_nf);
            $numDec = 2;
            if(isset($aValor[1]) && $aValor[1] != "")
            {
                $numDec = strlen($aValor[1]);
                if($numDec < 2)
                    $numDec = 2;
            }
            $multiplicador = pow(10,$numDec);
            //echo "numDec=$numDec - multiplicador=$multiplicador<br>";
            $vlrInteiro = $dadosFatura->valor_nf * $multiplicador;
            //echo "vlrInteiro=$vlrInteiro - numDec=$numDec<br>";
            $html .= strtoupper(GExtenso::moeda($vlrInteiro, $numDec));

            $html .= '</td>
    					</tr>
    				</table>
    			</div>
        		<!-- fim quadro 4 -->';

            // Itens da fatura
            if($dadosPreFatura)
                $itensFatura = $dadosPreFatura->itensFatura;
            else if(!empty($idOcorrencia))
                $itensFatura = $this->BuscarItensFaturaOcorrencia($idOcorrencia);
            else
                $itensFatura = $this->BuscarItensFatura();


            if(count($itensFatura) > $this->limiteVersaoDetalhada)
            {

                $itensFaturaAgrupados = $this->AgruparItensFatura($itensFatura);
                $html .= $this->GerarItensAgrupados($dadosFatura, $itensFaturaAgrupados);

            }
            else
            {
                $html .= $this->GerarItens($dadosFatura, $itensFatura);
            }

            // Totais
            $html .= '
                        <tr>
    						<td colspan="4" style="border-left: 0;">&nbsp;</td>
    						<td class="rotulo_upper" style="border-bottom: 1px solid #000;" align="right">'.ROTULO_JUROS.'</td>
    						<td colspan="2" style="border-bottom: 1px solid #000;">
                                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                                    <tr>
                                        <td width="15" style="padding: 0px; border: 0;">R$ </td>
                                        <td align="right" style="padding: 0px; border: 0;">'.Utils::MaskFloat($dadosFatura->juros + $dadosFatura->multa).'</td>
                                    </tr>
                                </table>
    						</td>
    					</tr>
                        <!-- total descontos -->
    					<tr>
    						<td colspan="4" style="border-left: 0;">&nbsp;</td>
    						<td class="rotulo_upper" style="border-bottom: 1px solid #000;" align="right">'.ROTULO_TOTAL_DESCONTOS.'</td>
    						<td colspan="2" style="border-bottom: 1px solid #000;">
                                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                                    <tr>
                                        <td width="15" style="padding: 0px; border: 0;">R$ </td>
                                        <td align="right" style="padding: 0px; border: 0;">'.Utils::MaskFloat($dadosFatura->desconto_geral_nf + $dadosFatura->desconto_detalhado_nf).'</td>
                                    </tr>
                                </table>
    						</td>
    					</tr>
    					<!-- fim total descontos -->
    					<!-- total -->
    					<tr>
    						<td colspan="4" style="border-left: 0;">&nbsp;</td>
    						<td class="rotulo_upper" style="border-bottom: 1px solid #000;" align="right">'.ROTULO_TOTAL.'</td>
    						<td colspan="2" style="border-bottom: 1px solid #000;">
                                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                                    <tr>
                                        <td width="15" style="padding: 0px; border: 0;">R$ </td>
                                        <td align="right" style="padding: 0px; border: 0;">'.Utils::MaskFloat($dadosFatura->valor_nf).'</td>
                                    </tr>
                                </table>
    						</td>
    					</tr>
    					<!-- fim total -->
    		        </table>
                </div>
            </div>';
        }
        if($dadosPreFatura == null)
        {
            //echo "teste"; die();
            if($dadosFatura->valor_com_desconto > 0)
            {
                //echo "--->" . $dadosFatura->valor_com_desconto; die();
                // Boletos
                $boleto = new Boleto();
                $boleto->SetIdNotaFiscal($this->idNotaFiscal);
                $html .= $boleto->GerarBoletos(null, null, $comFatura, $qtdBoletos,$parcial, $idOcorrencia);
            }
        }

        if($imprimirFatura)
        {
            if(count($itensFatura) > $this->limiteVersaoDetalhada)
            {
                $html .= $this->GerarItensDetalhados($dadosFatura, $itensFatura);
            }
        }

        $html .= '
        </body>
        </html>';
        return $html;
    }

    public function CompletarLinhaPreFatura()
    {
        $html .= '
            <td height="20px" style="white-space:nowrap; font-size: 10px;"></td>
            <td style="font-size: 10px;"></td>
            <td style="font-size: 10px;"></td>
        ';

        return $html;
    }

    /**
     *  Gera todos os Item de Faturamento (< 50 itens)
     */
    public function GerarItens($dadosFatura, $itensFatura)
    {
        //Conexao::pd($itensFatura);
        $html .= '
            <div class="table_container">
    			<table class="fatura_bloco5" cellpadding="0" cellspacing="0" width="100%" height="220px" border="0">
    				<tr>
    					<th class="rotulo_upper" height="12px" width="70px">'.ROTULO_PLACA.'</th>
    					<th class="rotulo_upper" width="210px" >'.ROTULO_ITEM.'</th>
    					<th class="rotulo_upper" width="70" >'.ROTULO_VALOR.'</th>

    					<th class="rotulo_upper" width="70px">'.ROTULO_PLACA.'</th>
    					<th class="rotulo_upper" width="210px" >'.ROTULO_ITEM.'</th>
    					<th class="rotulo_upper" width="70">'.ROTULO_VALOR.'</th>
    				</tr>';

        $tamanhoRestante = 160;

        if(count($itensFatura) > 0)
        {
            $html .= '<tr>';
            // Itens da fatura
            for($j = 0; $j < count($itensFatura); $j++)
            {
                static $i;

                /*HABILITAÇÃO*/
                if($itensFatura[$j]->id_item_faturamento == 2)
                {
                    if($itensFatura[$j]->fatura_carne == 1)
                    {
                        $resto = $itensFatura[$j]->parcelas - ($itensFatura[$j]->parcelas_faturadas - 1);
                        for ($y = 1; $y <= $resto; $y++)
                        {
                            if ($y > 1)
                                $itensFatura[$j]->parcelas_faturadas = $itensFatura[$j]->parcelas_faturadas + 1;
                            $html .= '
                                            <td height="20px" style="white-space:nowrap; font-size: 10px;">' . $itensFatura[$j]->veiculo . '</td>
                                            <td style="font-size: 10px;">' . $this->FormatarItemFatura($dadosFatura, $itensFatura[$j]) . '</td>
                                            <td style="font-size: 10px;">' . Utils::MaskFloat($itensFatura[$j]->valor / $resto). '</td>
                                    ';
                            if ((++$i % 2) == 0)
                            {
                                $html .= '</tr><tr>';
                                $tamanhoRestante -= 20; //diminui a altura da linha
                            }
                            else
                            {
                                if($y == $resto && count($itensFatura)  == $j + 1)
                                    $html .= $this->CompletarLinhaPreFatura();
                            }
                        }
                    }
                    else
                    {

                        $html .= '
                            <td height="20px" style="white-space:nowrap; font-size: 10px;">'.$itensFatura[$j]->veiculo.'</td>
                            <td style="font-size: 10px;">'.$this->FormatarItemFatura($dadosFatura, $itensFatura[$j]).'</td>
                            <td style="font-size: 10px;">'.Utils::MaskFloat($itensFatura[$j]->valor).'</td>';
                        if ((++$i % 2) == 0)
                        {
                            $html .= '</tr><tr>';
                            $tamanhoRestante -= 20; //diminui a altura da linha
                        }
                        else
                        {
                            if(count($itensFatura)  == $j + 1)
                                $html .= $this->CompletarLinhaPreFatura();
                        }

                    }
                } /*SERVIÇO*/
                else if($itensFatura[$j]->id_item_faturamento == 1)
                {
                    $parcelas  = $itensFatura[$j]->parcelas;
                    $parcelas_faturadas = $itensFatura[$j]->parcelas_faturadas -1;
                    if($itensFatura[$j]->fatura_carne == 1)
                    {
                        if ($parcelas_faturadas > $parcelas)
                            $resto = $parcelas - ($parcelas_faturadas - (floor($parcelas_faturadas / $parcelas)) * $parcelas);
                        else
                            $resto = $itensFatura[$j]->parcelas - ($itensFatura[$j]->parcelas_faturadas - 1);
                        for($y = 1; $y <= $resto; $y++ )
                        {
                            if($y == 1 && $itensFatura[$j]->valor_pro_rata)
                            {
                                $valor = Utils::MaskFloat($itensFatura[$j]->valor_pro_rata);
                                $item = $this->FormatarItemFatura($dadosFatura, $itensFatura[$j]);
                                $resto = $resto + 1;
                            }
                            else
                            {
                                $valor = Utils::MaskFloat($itensFatura[$j]->valor_mensal_servico);
                                $item =  $itensFatura[$j]->item_faturamento;
                            }

                            $html .= '
                                            <td height="20px" style="white-space:nowrap; font-size: 10px;">'.$itensFatura[$j]->veiculo.'</td>
                                            <td style="font-size: 10px;">'.$item.'</td>
                                            <td style="font-size: 10px;">'.$valor.'</td>
                                            ';
                            if((++$i % 2) == 0)
                            {
                                $html .= '</tr><tr>';
                                $tamanhoRestante -= 20; //diminui a altura da linha
                            }
                            else
                            {
                                if($y == $resto && count($itensFatura)  == $j + 1)
                                    $html .= $this->CompletarLinhaPreFatura();
                            }
                        }

                    }
                    else
                    {

                        $html .= '
                                <td height="20px" style="white-space:nowrap; font-size: 10px;">'.$itensFatura[$j]->veiculo.'</td>
                                <td style="font-size: 10px;">'.$this->FormatarItemFatura($dadosFatura, $itensFatura[$j]).'</td>
                                <td style="font-size: 10px;">'.Utils::MaskFloat($itensFatura[$j]->valor).'</td>';

                        if ((++$i % 2) == 0)
                        {
                            $html .= '</tr><tr>';
                            $tamanhoRestante -= 20; //diminui a altura da linha
                        }
                        else
                        {
                            if(count($itensFatura)  == $j + 1)
                                $html .= $this->CompletarLinhaPreFatura();
                        }

                    }
                }
                else //serviços extras ordem de serviço
                {
                    $html .= '
                            <td height="20px" style="white-space:nowrap; font-size: 10px;">'.$itensFatura[$j]->veiculo.'</td>
                            <td style="font-size: 10px;">'.$this->FormatarItemFatura($dadosFatura, $itensFatura[$j]).'</td>
                            <td style="font-size: 10px;">'.Utils::MaskFloat($itensFatura[$j]->valor).'</td>';

                    if ((++$i % 2) == 0)
                    {
                        $html .= '</tr><tr>';
                        $tamanhoRestante -= 20; //diminui a altura da linha
                    }
                    else
                    {
                        if(count($itensFatura)  == $j + 1)
                            $html .= $this->CompletarLinhaPreFatura();
                    }
                }

            }
            // Descontos
            if($dadosFatura->desconto_detalhado_nf > 0)
            {
                for($j = 0; $j < count($itensFatura); $j++)
                {
                    if($itensFatura[$j]->desconto > 0)
                    {
                        if ((++$i % 2) == 0)
                        {
                            $html .= '</tr><tr>';
                            $tamanhoRestante -= 20; //diminui a altura da linha
                        }

                        $html .= '
                                    <td height="20px" style="white-space:nowrap; font-size: 10px;">'.$itensFatura[$j]->veiculo.'</td>
                                    <td style="font-size: 10px;">'.$this->FormatarItemFatura($dadosFatura, $itensFatura[$j], true).'</td>
                                    <td style="font-size: 10px;">-'.Utils::MaskFloat($itensFatura[$j]->desconto).'</td>';
                    }

                    if(count($itensFatura)  == $j + 1)
                        $html .= $this->CompletarLinhaPreFatura();


                }
            }

//        if((++$i % 2) == 0)
//        {
//            $html .= '</tr><tr>';
//            $tamanhoRestante -= 20;//diminui a altura da linha
//            //$i = 0;
//        }
//        if($dadosFatura->desconto_geral_nf > 0)
//        {
//            $html .= '
//                                    <td height="20px" style="white-space:nowrap; font-size: 10px;"></td>
//                                    <td style="font-size: 10px;">DESCONTO GERAL</td>
//                                    <td style="font-size: 10px;">-'.Utils::MaskFloat($dadosFatura->desconto_geral_nf).'</td>';
//            //$i++;
//        }

            // Completa a ultima linha se necessário
            //        for(; $i < 2; $i++)
            //        {
            //            $html .= '
            //                            <td>&nbsp;</td>
            //                            <td>&nbsp;</td>
            //                            <td>&nbsp;</td>';
            //        }

            //if((++$i % 2) != 0)
            //$html .= $this->CompletarLinhaPreFatura();
            $html .= '</tr>';
        }

        //Conexao::pd($itensFatura);
        $html .= '
                        <tr>
                            <th height="'.$tamanhoRestante.'">&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>

                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>';

        //echo $html;die();

        return $html;
    }

    /**
     *  Gera os dados AGRUPADOS por Item de Faturamento (>= 24 itens)
     */
    public function GerarItensAgrupados($dadosFatura, $itensFatura)
    {
        $html .= '
		<div class="table_container">
			<table class="fatura_bloco5" cellpadding="0" cellspacing="0" width="100%" height="220px" border="0">
				<tr>
					<th class="rotulo_upper" height="12px">'.ROTULO_QTDE.'</th>
					<th class="rotulo_upper">'.ROTULO_ITEM.'</th>
					<th class="rotulo_upper">'.ROTULO_VALOR.'</th>

					<th class="rotulo_upper">'.ROTULO_QTDE.'</th>
					<th class="rotulo_upper">'.ROTULO_ITEM.'</th>
					<th class="rotulo_upper">'.ROTULO_VALOR.'</th>
				</tr>';

        //$total = 0;
        $tamanhoRestante = 160;
        if(count($itensFatura) > 0)
        {
            //$total = $itensFatura[0]->total;
            $i = 0;
            $html .= '
                    <tr>';

            // Itens da fatura
            foreach($itensFatura as $indice => $itens)
            {
                if($i == 2)
                {
                    $html .= '
                            </tr>
                            <tr>';
                    $tamanhoRestante -= 20; //diminui a altura da linha
                    $i = 0;
                }

                $html .= '
                        <td height="12px">'.$itens['quantidade'].'</td>
                        <td>'.$indice.'</td>
                        <td>'.Utils::MaskFloat($itens['valor']).'</td>';
                $i++;
            }
            // Descontos
            if($dadosFatura->desconto_nf > 0)
            {
                foreach($itensFatura as $indice => $itens)
                {
                    if($itens['desconto'] > 0)
                    {
                        if($i == 2)
                        {
                            $html .= '</tr><tr>';
                            $tamanhoRestante -= 20; //diminui a altura da linha
                            $i = 0;
                        }

                        $html .= '
                                <td height="12px">&nbsp;</td>
                                <td>'.$indice.'</td>
                                <td>-'.Utils::MaskFloat($itens['desconto']).'</td>';
                        $i++;
                    }
                }
            }

            // Completa a ultima linha se necessário
            for(; $i < 2; $i++)
            {
                $html .= '
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>';
            }
            $html .= '
                    </tr>';
        }

        $html .= '
                <tr>
					<th height="'.$tamanhoRestante.'">&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>

					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>';

        return $html;
    }

    public function GerarItensDetalhados($dadosFatura, $itensFatura)
    {
        $html = '<style>
            <!--
            .itens_bloco1
            {
                border: 1px solid #000;
            }
            .itens_bloco1 td
            {
                border: 0;
                padding: 2px;
            }
            .itens_bloco2 td
            {
                border: 1px solid #000;
                font-weight: bold;
                padding: 2px;
                text-align: center;
            }
            .itens_bloco3 td
            {
                border-left: 1px solid #000;
                border-right: 1px solid #000;
                padding: 2px;
            }
            -->
            </style>
            <div class="box">
				<table class="itens_bloco1" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" align="left" rowspan="4" width="160"><img src="img/logo_preta.png" align="absmiddle"></td>
                        <td colspan="2">'.$dadosFatura->franqueado.'</td>
					</tr>
					<tr>
						<td colspan="2">'.$dadosFatura->franqueado_logradouro.", ".$dadosFatura->franqueado_numero." - ".$dadosFatura->franqueado_bairro.'</td>
					</tr>
					<tr>
						<td width="330">'.trim($dadosFatura->franqueado_cidade)."/". trim($dadosFatura->franqueado_estado)." - ".ROTULO_CEP." ". trim($dadosFatura->franqueado_cep).'</td>
						<td align="center" width="200"><b>'.ROTULO_CNPJ.': '.$dadosFatura->franqueado_cnpj.'</b></td>
					</tr>
					<tr>
						<td>'.ROTULO_TELEFONE.': <b>'.$foneFranqueado.'</b></td>
						<td align="center">www.linkmonitoramento.com.br</td>
					</tr>
				</table>
            </div>
            <div class="box">
				<table class="itens_bloco2" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="rotulo_upper" width="138">'.ROTULO_NUMERO_DA_FATURA.'</td>';

        if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
            $html .= '
                        <td class="rotulo_upper" width="138">'.ROTULO_MES_REFERENCIA_UPPER.'</td>';

        $html .= '
                        <td class="rotulo_upper" width="138">'.ROTULO_DATA_EMISSAO_UPPER.'</td>
						<td class="rotulo_upper" width="138">'.ROTULO_VALOR_UPPER.'</td>';

        if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
            $html .= '
                        <td class="rotulo_upper" width="138">'.ROTULO_VENCIMENTO_UPPER.'</td>';

        $html .= '
                    </tr>
					<tr>
						<td>'.$dadosFatura->numero_fatura.'</td>';

        if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
            $html .= '
                        <td>'.$mesAnoReferencia.'</td>';

        $html .= '
                        <td>'.$dadosFatura->data_hora_cadastro_nfe.'</td>
						<td>R$ '.Utils::MaskFloat($dadosFatura->valor_nf).'</td>';

        if($carne_habilitacao == 0 && $carne_servico == 0 && $carne_acessorio == 0)
            $html .= '
                        <td>'.$dadosFatura->vencimento.'</td>';

        $html .= '
                    </tr>
				</table>
            </div>
    		<!-- fim quadro 2 -->

    		<!-- Quadro 3 – Listagem dos itens-->
            <div class="box">
				<table class="itens_bloco3" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
						<th style="border: 1px solid #000; border-top: 0;">'.ROTULO_DATA_REFERENCIA.'</th>
                        <th style="border: 1px solid #000; border-top: 0;">'.ROTULO_CONTRATO.'</th>
                        <th style="border: 1px solid #000; border-top: 0;">'.ROTULO_PLACA.'</th>
						<th style="border: 1px solid #000; border-top: 0;">'.ROTULO_ITEM.'</th>
                        <th style="border: 1px solid #000; border-top: 0;">'.ROTULO_VALOR.'</th>
                    </tr>
            		<tr>';
        //Conexao::pd($itensFatura);
        if(count($itensFatura) > 0)
        {

            $idContratoAtual = null;
            for ($i = 0; $i < count($itensFatura); $i++)
            {

                $html .= '
                                <tr>
                                    <td>'.$itensFatura[$i]->dt_referencia.'</td>
                                    <td>'.$itensFatura[$i]->numero_contrato.'</td>
                                    <td>'.$itensFatura[$i]->veiculo.'</td>
                                    <td>'.$this->FormatarItemFatura($dadosFatura, $itensFatura[$i]/*, $j*/).'</td>
                                    <td align="right">'.Utils::MaskFloat($itensFatura[$i]->valor_com_desconto).'</td>
                                </tr>';
            }
        }

        $html .= '
                        <tr>
    						<td></td>
    						<td></td>
    						<td></td>
    						<td></td>
    						<td></td>
    					</tr>

                        <!-- total -->
    					<tr>
                            <td colspan="3" style="border-left: 0; border-top:1px solid #000"></td>
    						<td class="rotulo_upper" align="left" style="border-bottom: 1px solid #000; border-top:1px solid #000">'.ROTULO_TOTAL.'</td>
                            <td  align="right" style="border-bottom: 1px solid #000; border-top:1px solid #000">R$ '.Utils::MaskFloat($dadosFatura->valor_nf).'</td>
    					</tr>
    					<!-- fim total -->

            		</tr>
                </table>
            </div>
    		<!-- Fim Quadro 3 – Listagem dos itens -->';

        return $html;
    }

    /**
     *  Formata os itens de faturamento para exibir Pró rata e Quantidade de parcelas da habilitação
     *
     *  @return Item de faturamento formatado
     */
    public function FormatarItemFatura($dadosFatura, $itemFatura, $itemDesconto = false)
    {
        //echo '<pre>'; print_r($dadosFatura); echo '</pre>';
        //echo '<pre>'; print_r($itemFatura); echo '</pre>';

        if($itemDesconto == true)
        {
            return $itemFatura->item_faturamento . ' ('.$itemFatura->dt_referencia.')';
        }

        if($itemFatura->id_item_faturamento == 1 )
        {
            //echo "if($itemFatura->id_item_faturamento == 1)<br>";
            return $this->FormatarProRata($dadosFatura, $itemFatura);
            //return $this->FormatarServicoMensal($dadosFatura, $itemFatura);
        }
        elseif($itemFatura->id_item_faturamento == 2)
        {
            return $this->FormatarHabilitacaoMensalidades($dadosFatura, $itemFatura);
        }
        else
            return ($itemFatura->parcelas_acessorios ? $itemFatura->item_faturamento . "(" . $itemFatura->parcelas_faturadas_acessorios  . "/" . $itemFatura->parcelas_acessorios . ")" : $itemFatura->item_faturamento);
    }

    /**
     *  Efetua os cálculos para verificar se existiu Pró Rata no serviço
     *
     *  @return Se existir retorna o nome do item concatenado com o intervalo de dias utilizados
     *          Senão retorna apenas o item de faturamento
     */
    public function FormatarProRata($dadosFatura, $itemFatura)
    {
//    echo "<pre>";print_r($itemFatura);"</pre>";
        if(strpos($itemFatura->data_ativacao, '-') === false)
        {
            $itemFatura->data_ativacao = $this->PrepararDataBD($itemFatura->data_ativacao, null, 'Y-m-d');
        }
        //echo $itemFatura->valor ."===".number_format($itemFatura->valor_mensal_servico * 12,2) . "<br>";
        // Se não for o primeiro Serviço faturado do contrato retorna apenas o item de faturamento
        if($itemFatura->numero_servico_faturado != 1 || $itemFatura->valor == $itemFatura->valor_mensal_servico || ($itemFatura->valor == number_format($itemFatura->valor_mensal_servico * 12,2))  )
            //return $this->FormatarServicoMensal($dadosFatura, $itemFatura);
            return $itemFatura->item_faturamento;
        //echo '<pre>'; print_r($dadosFatura); echo '</pre>';
        //echo '<pre>'; print_r($itemFatura); echo '</pre>';

        list($anoAtivacao, $mesAtivacao, $diaAtivacao) = explode('-', $itemFatura->data_ativacao);
        list($anoVencimento, $mesVencimento, $diaVencimentoConta) = explode('-', $dadosFatura->data_vencimento);
        list($mesReferencia, $anoReferencia) = explode('/', $itemFatura->dt_referencia);
        //echo "VENC: ANO $anoVencimento MES $mesVencimento DIA $diaVencimento<BR>";
        //echo "MESREF: $mesReferencia # ANOREF: $anoReferencia<BR>";
        $diaVencimento =  $itemFatura->dia_vencimento;
        if($diaAtivacao - 1 == $itemFatura->dia_vencimento)
            return $itemFatura->item_faturamento;

        // Calcula os dias utilizados
        $ativacaoTime = strtotime($itemFatura->data_ativacao);

        /*if($diaAtivacao > $diaVencimento)
        {
            //$mesAtivacao++;
            //echo "$itemFatura->veiculo #### if($diaAtivacao > $diaVencimento)<br>";
            //$vencimentoTime = strtotime(date("Y-m-d", mktime(0,0,0, $mesAtivacao + 1, $diaVencimento, $anoAtivacao)));
            $vencimentoTime = strtotime(date("Y-m-d", mktime(0,0,0, $mesReferencia, $diaVencimento, $anoReferencia)));
            $diasUtilizados = floor(($vencimentoTime - $ativacaoTime) / (60*60*24)) + 1;
            //echo "DIAS UTILIZADOS: $diasUtilizados<BR>";

            // Calcula os dias entre o "utlimo faturamento"
            //$vencimentoAnteriorTime = strtotime(date('Y-m-d', mktime(0,0,0, $mesAtivacao, $diaVencimento, $anoAtivacao)));
            $vencimentoAnteriorTime = strtotime(date('Y-m-d', mktime(0,0,0, $mesReferencia - 1, $diaVencimento, $anoReferencia)));
            $diasMes = floor(($vencimentoTime - $vencimentoAnteriorTime) / (60*60*24));
            //$mesAtivacao++;
            //echo "VENC: $diaVencimento/$mesVencimento/$anoVencimento<BR>";
            //$mesAtivacao = $mesVencimento - 1;
            //echo "MES ATV: $mesAtivacao<BR>";
        }
        else
        {
            //echo "$itemFatura->veiculo #### ELSE: $diaAtivacao < $diaVencimento<BR>";
            //$vencimentoTime = strtotime($this->PrepararDataBD($dadosFatura->vencimento, null, 'Y-m-d'));
            $vencimentoTime = strtotime(date("Y-m-d", mktime(0,0,0, $mesReferencia, $diaVencimento, $anoReferencia)));
            $diasUtilizados = floor(($vencimentoTime - $ativacaoTime) / (60*60*24)) + 1;

            // Calcula os dias entre o "utlimo faturamento"
            //$vencimentoAnteriorTime = strtotime(date('Y-m-d', mktime(0,0,0, $mesVencimento - 1, $diaVencimento, $anoVencimento)));
            $vencimentoAnteriorTime = strtotime(date('Y-m-d', mktime(0,0,0, $mesReferencia - 1, $diaVencimento, $anoReferencia)));
            $diasMes = floor(($vencimentoTime - $vencimentoAnteriorTime) / (60*60*24));
            //echo "VENC: ANO $anoVencimento MES $mesVencimento DIA $diaVencimento<BR>";
            //$mesAtivacao = $mesVencimento - 1;
            //echo "MES ATV: $mesAtivacao<BR>";
        }*/
        // TESTE 23/04/14

        if($itemFatura->fatura_carne == 1)
        {
            //$vencimentoTime = strtotime(date("Y-m-d", mktime(0, 0, 0, $mesAtivacao + 1, $diaVencimento, $anoAtivacao)));
            //$vencimentoAnteriorTime = strtotime(date('Y-m-d', mktime(0,0,0, $mesAtivacao - 1, $diaVencimento, $anoAtivacao)));
        }
        else
        {
            $vencimentoTime = strtotime(date("Y-m-d", mktime(0, 0, 0, $mesReferencia, $diaVencimento, $anoReferencia)));
            $vencimentoAnteriorTime = strtotime(date('Y-m-d', mktime(0,0,0, $mesReferencia - 1, $diaVencimento, $anoReferencia)));
        }
        // echo $diaVencimento . "<br>";
        // echo date("Y-m-d", mktime(0, 0, 0, $mesReferencia, $diaVencimento, $anoReferencia))."<br>";

        $diasUtilizados = floor(($vencimentoTime - $ativacaoTime) / (60*60*24)) + 1;

        //echo "$itemFatura->veiculo # $diasUtilizados<br>";
        // Calcula os dias entre o "utlimo faturamento"
        $diasMes = floor(($vencimentoTime - $vencimentoAnteriorTime) / (60*60*24));

        if($diasUtilizados == $diasMes)
            return $itemFatura->item_faturamento;

        //echo "$itemFatura->veiculo # $diasUtilizados<br>";

        if($diasUtilizados > $diasMes && $itemFatura->id_contrato != 52413)
        {
            // Calcula os dias de pro rata
            $diasUtilizados = $diasUtilizados - $diasMes;
        }
        //echo "$itemFatura->veiculo # $diasUtilizados<br>";

        do
        {
            $diasUtilizados--;
        } while(date('d', mktime(0,0,0, $mesAtivacao, $diaAtivacao + $diasUtilizados, $anoAtivacao)) > $itemFatura->dia_vencimento);// $diaVencimento);
        //echo "HUE: $diaAtivacao + $diasUtilizados<br>";
        $fimProRata = date('d/m/Y', mktime(0,0,0, $mesAtivacao, $diaAtivacao + $diasUtilizados, $anoAtivacao));
        /**
         *
         *  HARDCODE INSERIDO DIA 29/09/14 DEVIDO AO CHAMADO 4778
         *
         */
        if($dadosFatura->idnfe == 10037)
            $fimProRata = '06/10/2014';
        //$fimProRataComp = date('m/Y', mktime(0,0,0, $mesAtivacao, $diaAtivacao + $diasUtilizados - 1, $anoAtivacao));
        /**
         *
         *  HARDCODE INSERIDO DIA 01/12/14 DEVIDO AO CHAMADO 5084
         *
         */
        if($dadosFatura->idnfe == 15710)
            $fimProRata = '01/12/2014';
        /**
         *
         *  HARDCODE INSERIDO DIA 16/12/2014 DEVIDO AO CHAMADO 5176
         *
         */
        if($dadosFatura->idnfe == 17902)
            $fimProRata = '05/01/2015';
        /**
         *
         *  HARDCODE INSERIDO DIA 17/12/2014 DEVIDO AO CHAMADO 5183
         *
         */
        if($dadosFatura->idnfe == 17931 || $dadosFatura->idnfe == 17920 || $dadosFatura->idnfe == 17928)
            $fimProRata = '05/01/2015';
        //if($itemFatura->dt_referencia != $fimProRataComp)
        //  return $itemFatura->item_faturamento;
        $ProRata = $this->PrepararDataPHP($itemFatura->data_ativacao, null, 'd/m').' a '.$fimProRata;

//    return $itemFatura->item_faturamento.' (pro rata:'.number_format($itemFatura->valor_pro_rata,2,',','.').' ref. a '.$ProRata.')';
        return $itemFatura->item_faturamento.' (pro rata ref. a '.$ProRata.')';
    }

    public function FormatarHabilitacaoMensalidades($dadosFatura, $itemFatura)
    {
        if($itemFatura->parcelas > 1 === false)
        {
            return $itemFatura->item_faturamento;
        }
        else
        {
            // CALCULO ESTRANHO. REASULTADO  1-0+1 = 2 .. SENDO QUE É A PRIMEIRA HABILITAÇÃO
            /*
             * O calculo, valida o seu numero de faturamento.
            Exemplo, parcela x, seu numero de faturamento é o 2
            No proximo mês sera o 3

            Se eu voltar e imprimir a fatura, ela continuara sendo o 2 e não a 3.
             *
             * */
//        $parcela = ($itemFatura->parcelas_faturadas - $itemFatura->quantidade_habilitacao_faturada) + $itemFatura->numero_habilitacao_faturado;
            $parcela =  ($itemFatura->numero_habilitacao_faturado ? $itemFatura->numero_habilitacao_faturado : $itemFatura->parcelas_faturadas );

            return $itemFatura->item_faturamento.' ('.$parcela.'/'.$itemFatura->parcelas.')';
        }
    }

//    public function FormatarServicoMensal($dadosFatura, $itemFatura)
//    {
//        if($itemFatura->parcelas > 1 === false)
//        {
//            return $itemFatura->item_faturamento;
//        }
//        else
//        {
//            $parcela = ($itemFatura->parcelas_faturadas - $itemFatura->quantidade_servico_faturado) + $itemFatura->numero_servico_faturado;
//
//            return $itemFatura->item_faturamento.' ('.$parcela.'/'.$itemFatura->parcelas.')';
//        }
//    }
}