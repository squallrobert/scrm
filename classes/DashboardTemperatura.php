<?php
/**
 * Created by PhpStorm.
 * User: Diego
 * Date: 19/12/2018
 * Time: 15:23
 */

class DashboardTemperatura
{

    public function ListarPaginacao($parametros)
    {

        $parametros['numeroInicioRegistro'] = $parametros['numeroInicioRegistro']?:0;
        $parametros['numeroRegistros']      = $parametros['numeroRegistros']?:200;
        $parametros['id_grupo']             = $parametros['id_grupo']?:$_SESSION['usuario']['id_grupo'];

        $pdo = new Conexao();
        $select = " SELECT    
                    veiculo.id as id_veiculo,
                    veiculo.rotulo,
                    veiculo.complemento_placa,
                    veiculo.observacao,
                    veiculo.embarcacao,
                    veiculo.marca,
                    veiculo.modelo,
                    veiculo.ano_modelo,
                    ultimas.latitude,
                    ultimas.longitude,
                    ultimas.data_hora,
                    ultimas.velocidade,
                    ultimas.odometro,
                    ultimas.horimetro,
                    ultimas.ignicao,
                    ultimas.panico,
                    ultimas.saidas,
                    ultimas.telemetria,
                    ultimas.tensao,
                    ultimas.entradas,
                    veiculo_interface.entrada1,
                    veiculo_interface.entrada2,
                    veiculo_interface.entrada3,
                    veiculo_interface.entrada4,
                    veiculo_interface.entrada5,
                    veiculo_interface.entrada6,
                    veiculo_interface.entrada7,
                    veiculo_interface.entrada8,
                    veiculo_interface.saida1,
                    veiculo_interface.saida2,
                    veiculo_interface.saida3,
                    veiculo_interface.saida4,
                    veiculo_interface.saida5,
                    veiculo_interface.saida6,
                    veiculo_interface.saida7,
                    veiculo_interface.saida8,
                    ultimas.id as id_posicao,
                    grupo.id as id_grupo,
                    grupo.nome as nome_grupo,
                    icone.arquivo AS icone_ligado,
                    icone2.arquivo AS icone_desligado,
                    ultimas.logradouro,
                    rastreador.numero_serie,
                    rastreador.id AS id_rastreador,
                    cliente.id as id_cliente,
                    IF(cliente.nome_fantasia != '',cliente.nome_fantasia ,cliente.nome) as nome_cliente,
                    fabricante.id as id_fabricante,
                    modelo_rastreador.id as id_modelo_rastreador,
                    modelo_rastreador.nome as nome_modelo_rastreador,
                    motorista.nome as nome_motorista,
                    veiculo_categoria.nome as categoria_veiculo,
                    sensor_1.nome AS 1_sensor_nome,
                    sensor_1.temperatura_minima AS 1_temp_min, 
                    sensor_1.temperatura_maxima AS 1_temp_max,
                    sensor_2.nome AS 2_sensor_nome,
                    sensor_2.temperatura_minima AS 2_temp_min, 
                    sensor_2.temperatura_maxima AS 2_temp_max,
                    sensor_3.nome AS 3_sensor_nome,
                    sensor_3.temperatura_minima AS 3_temp_min, 
                    sensor_3.temperatura_maxima AS 3_temp_max,
                    sensor_4.nome AS 4_sensor_nome,
                    sensor_4.temperatura_minima AS 4_temp_min, 
                    sensor_4.temperatura_maxima AS 4_temp_max

                    ";

        $from = " FROM veiculo
                    INNER JOIN ultimas ON (ultimas.id_veiculo = veiculo.id AND ultimas.id_rastreador = veiculo.id_rastreador)
                    INNER JOIN rastreador ON (rastreador.id = veiculo.id_rastreador)
                    INNER JOIN modelo_rastreador ON (modelo_rastreador.id = rastreador.id_modelo_rastreador)
                    INNER JOIN fabricante ON (fabricante.id = modelo_rastreador.id_fabricante)
                    INNER JOIN grupo ON (grupo.id = veiculo.id_grupo)
                    INNER JOIN veiculo_interface ON (veiculo.id = veiculo_interface.id_veiculo)
                    INNER JOIN veiculo_categoria ON (veiculo.id_veiculo_categoria = veiculo_categoria.id )
                    LEFT JOIN cliente ON (grupo.id = cliente.id_grupo)
                    LEFT JOIN motorista ON (motorista.id = veiculo.id_motorista)
                    INNER JOIN tipo_veiculo ON (tipo_veiculo.id = veiculo.id_tipo_veiculo)
                    INNER JOIN icone ON (icone.id = tipo_veiculo.id_icone)
                    INNER JOIN icone icone2 ON (icone2.id = tipo_veiculo.id_icone2)
                    LEFT JOIN veiculo_sensor_temperatura AS sensor_1 ON (veiculo.id = sensor_1.id_veiculo AND sensor_1.posicao = 1) 
                    LEFT JOIN veiculo_sensor_temperatura AS sensor_2 ON (veiculo.id = sensor_2.id_veiculo AND sensor_2.posicao = 2) 
                    LEFT JOIN veiculo_sensor_temperatura AS sensor_3 ON (veiculo.id = sensor_3.id_veiculo AND sensor_3.posicao = 3) 
                    LEFT JOIN veiculo_sensor_temperatura AS sensor_4 ON (veiculo.id = sensor_4.id_veiculo AND sensor_4.posicao = 4)
                    ";

        $where = " WHERE
                        (grupo.id = $parametros[id_grupo] OR grupo.arvore LIKE '%;$parametros[id_grupo];%' 
                        OR  veiculo.id in
                        (SELECT id_veiculo FROM grupo_veiculo INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
                        WHERE grupo.id = $parametros[id_grupo] OR grupo.arvore LIKE '%;$parametros[id_grupo];%'))
                    AND grupo.excluido IS NULL
                    AND veiculo.excluido IS NULL
                   ";

        $where .= " AND (ultimas.telemetria LIKE '%ST%' OR  ultimas.telemetria LIKE '%AS%' OR ultimas.telemetria LIKE '%TMP%')";

       /* $where .= " AND (SELECT COUNT(contrato.id) FROM contrato
                    INNER JOIN erp_contrato_acessorio ON (erp_contrato_acessorio.`id_contrato` = contrato.id)
                    WHERE contrato.`id_veiculo` = veiculo.id AND veiculo.excluido IS NULL AND contrato.`excluido`IS NULL
                    AND erp_contrato_acessorio.`id_produto` IN (47, 71) ) > 0
			";*/
        if($parametros["id_veiculo"] != ""){
            $where .= " AND veiculo.id = $parametros[id_veiculo]";

        }


        if($parametros["veiculo"] != ""){
            $where .= " AND (veiculo.rotulo LIKE '%$parametros[veiculo]%' OR grupo.nome LIKE '%$parametros[veiculo]%')";
        }

        $order = " ORDER BY ultimas.ignicao DESC ";
        $limit = " LIMIT  ?,?";
        $sql  = $select . $from . $where . $order . $limit;
        $stmt = $pdo->prepare($sql);


        $stmt->bindParam(1, $parametros['numeroInicioRegistro'], PDO::PARAM_INT);
        $stmt->bindParam(2, $parametros['numeroRegistros'], PDO::PARAM_INT);

        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = "SELECT count(*) as total" . $from . $where;
        $total          = $pdo->query($total);
        $totalRegistros = $total->fetch(PDO::FETCH_ASSOC)['total'];

        return [$resultado,$totalRegistros];
    }

    public function ListarEventos($parametros)
    {
        $parametros['numeroInicioRegistro'] = $parametros['numeroInicioRegistro'] ?: 0;
        $parametros['numeroRegistros'] = $parametros['numeroRegistros'] ?: 200;
        $parametros['id_grupo'] = $parametros['id_grupo'] ?: $_SESSION['usuario']['id_grupo'];

        if($parametros['id_veiculo'] != ""){
            $veiculo = " AND veiculo.id= $parametros[id_veiculo] ";
        }else{
            $veiculo = "";
        }

        if($parametros['tempo'] != ""){
            $tempo_evento = " AND evento.data_hora >= DATE_SUB(UTC_TIMESTAMP(),INTERVAL $parametros[tempo] DAY) ";
            $tempo_abastecimento = " AND abastecimento.data_hora >= DATE_SUB(UTC_TIMESTAMP(),INTERVAL $parametros[tempo] DAY) ";
        }else{
            $tempo_evento = "";
            $tempo_abastecimento = "";
        }

        $pdo = new Conexao();

        $sql = "
        (SELECT      
            evento.id_veiculo,
            evento.id_rastreador,
            evento.data_hora,
            veiculo.rotulo,
            veiculo.marca,
            veiculo.modelo,
            veiculo.ano_modelo,
            evento_tipo.rotulo AS tipo_evento,
            NULL AS litros,
            NULL AS preco_litro,
            NULL AS preco_total,
            NULL AS local_abastecimento,
            NULL AS tipo_combustivel
            FROM evento 
            INNER JOIN veiculo ON (evento.id_veiculo = veiculo.id)
            INNER JOIN rastreador ON (rastreador.id = veiculo.id_rastreador)
            INNER JOIN grupo ON (grupo.id = veiculo.id_grupo)
            INNER JOIN evento_tipo ON (evento.id_evento_tipo = evento_tipo.id)
            WHERE
            (grupo.id = $parametros[id_grupo] OR grupo.arvore LIKE '%;$parametros[id_grupo];%' 
            OR  veiculo.id IN
            (SELECT id_veiculo FROM grupo_veiculo INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
            WHERE grupo.id = $parametros[id_grupo] OR grupo.arvore LIKE '%;$parametros[id_grupo];%'))
            AND grupo.excluido IS NULL
            AND veiculo.excluido IS NULL
            $tempo_evento
            $veiculo
            )      
            UNION   
            (SELECT 
			abastecimento.id_veiculo,
			veiculo.id_rastreador,
			abastecimento.data_hora, 
			veiculo.rotulo,
			veiculo.marca,
			veiculo.modelo,
			veiculo.ano_modelo,
			'Abastecimento' AS tipo_evento,
			abastecimento.litros,
			abastecimento.preco_litro,
			FORMAT(abastecimento.litros*abastecimento.preco_litro,2) AS preco_total,
			abastecimento.local,
			tipo_combustivel.tipo_combustivel
			FROM abastecimento 
			INNER JOIN veiculo ON (abastecimento.id_veiculo = veiculo.id)
			INNER JOIN rastreador ON (rastreador.id = veiculo.id_rastreador)
			INNER JOIN grupo ON (grupo.id = veiculo.id_grupo)
			INNER JOIN tipo_combustivel ON (tipo_combustivel.id = abastecimento.id_combustivel)
			 WHERE
                        (grupo.id = $parametros[id_grupo] OR grupo.arvore LIKE '%;$parametros[id_grupo];%' 
                        OR  veiculo.id IN
                        (SELECT id_veiculo FROM grupo_veiculo INNER JOIN grupo ON (grupo.id = grupo_veiculo.id_grupo)
                        WHERE grupo.id = $parametros[id_grupo] OR grupo.arvore LIKE '%;$parametros[id_grupo];%'))
			AND grupo.excluido IS NULL
			AND veiculo.excluido IS NULL
			AND abastecimento.excluido IS NULL
			$tempo_abastecimento
			$veiculo
			)
			ORDER BY data_hora DESC
			LIMIT  ?,?;
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(1, $parametros['numeroInicioRegistro'], PDO::PARAM_INT);
        $stmt->bindParam(2, $parametros['numeroRegistros'], PDO::PARAM_INT);

        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }


    public function GerarSelectAutoCompleteVeiculoMobile($idGrupo, $targetAutocomplete = "nome_veiculo", $resultadoAutocomplete = "id_veiculo", $resultadoGrupo = "#id_grupo", $targetGrupo = "#nome_grupo")
    {


            $veiculo = new Veiculo();
            $result  = $veiculo->ListarVeiculosSelect($idGrupo);

            $html .= $this->GerarSelectPDOMobile("$resultadoAutocomplete", "$resultadoAutocomplete", "", $result, Array(""), array("", ROTULO_SELECIONE), array("id", "nome_veiculo"), false, "form-control");



        return $html;
    }

    public function GerarSelectPDOMobile($nome = "selecione", $id = "selecione", $style = "", $registros = array(), $selecionados = array(), $primeiro = array(), $campos = array('id', "nome"), $multiple = false, $class = "form-control", $outros = "")
    {
        $registros = json_decode(json_encode($registros), false);
        $campo = '';
        $campo .= "<select name='" . $nome . "' id='" . $id . "'  class='" . $class . "' $outros style='" . $style . "'";

        $campo .= " class ='" . $class . "'>\n";
        //se existir um item a ser adicionado como primeiro.
        if (count($primeiro) > 0) {
            //$campo .= "<option value='$primeiro[0]'>$primeiro[1]</option>\n";
        }

        if (count($registros) > 0) {
            foreach ($registros as $row) {
                $campo .= "<option value='" . $row->$campos[0] . "'";
                if (array_search($row->$campos[0], $selecionados) !== false) {
                    $campo .= "selected = 'selected'";
                }
                $campo .= ">";
                $campo .= $row->$campos[1] . "</option>\n";
            }
        }
        $campo .= "</select>\n";

        return $campo;
    }

    public function Adicionar()
    {
        $pdo = new Conexao();
        $sql = '
        INSERT INTO abastecimento SET
            id_veiculo = ?,
            data_hora_cadastro = UTC_TIMESTAMP(),
            data_hora = ?,
            id_combustivel = ?,
            km_veiculo = ?,
            horimetro_veiculo = ?,
            litros = ?,
            preco_litro = ?,
            `local` = ?,
            nota_fiscal = ?,
            id_manutencao_centro_custos = ?,
            matricula = ?,
            observacao = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(++$x,$this->getIdVeiculo(),PDO::PARAM_INT);
        $stmt->bindParam(++$x,$this->getDataHora(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getIdCombustivel(),PDO::PARAM_INT);
        $stmt->bindParam(++$x,$this->getKmVeiculo(),PDO::PARAM_INT);
        $stmt->bindParam(++$x, $this->getHorimetroVeiculo(), PDO::PARAM_INT);
        $stmt->bindParam(++$x,$this->getLitros(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getPrecoLitro(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getLocal(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getNotaFiscal(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getIdManutencaoCentroCustos(),PDO::PARAM_INT);
        $stmt->bindParam(++$x,$this->getMatricula(),PDO::PARAM_STR);
        $stmt->bindParam(++$x,$this->getObservacao(),PDO::PARAM_STR);
        return $stmt->execute();
    }
}

