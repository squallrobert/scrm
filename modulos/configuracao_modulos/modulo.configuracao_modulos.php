<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:02
 */
switch ($app_comando){
    case "listar_configuracao_modulos":
        $template = "tpl.geral.configuracao_modulos.php";
        break;
        
    case "ajax_listar_configuracao_campos":
        $template = "tpl.lis.campos.configuracao_modulos.php";
        break;

    case "ajax_listar_configuracao_modulos":
        $template = "tpl.lis.modulos.configuracao_modulos.php";
        break;
        
    case "frm_adicionar_campo":
        $template = "tpl.frm.campos.configuracao_modulos.php";
        break;
        
    case "adicionar_campo":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objConfiguracaoModulos = new ConfiguracaoModulos($pdo);

            ($_REQUEST['padrao'] != "") ? $padrao = 1 : $padrao = 0;
            ($_REQUEST['obrigatorio'] != "") ? $obrigatorio = 1 : $obrigatorio = 0;

            $objConfiguracaoModulos->setNome($_REQUEST['nome']);
            $objConfiguracaoModulos->setRotulo($_REQUEST['rotulo']);
            $objConfiguracaoModulos->setValor($_REQUEST['valor']);
            $objConfiguracaoModulos->setNameId($_REQUEST['name_id']);
            $objConfiguracaoModulos->setPadrao($padrao);
            $objConfiguracaoModulos->setObrigatorio($obrigatorio);
            $objConfiguracaoModulos->setClasse($_REQUEST['classe']);
            $retorno = $objConfiguracaoModulos->Adicionar();

            $msg["codigo"] = 0;
            $msg["mensagem"] = TXT_ALERT_SUCESSO_ADICIONAR;
            $pdo->commit();
        }catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO . " ". $e->getMessage();
            $msg["debug"] = $e->getMessage();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.configuracao_modulos.php";
        break;

    case "frm_modificar_campo":
        $objConfiguracaoModulos = new ConfiguracaoModulos();
        $objConfiguracaoModulos->setId($_REQUEST['app_codigo']);
        $linha    = $objConfiguracaoModulos->Editar();
        $template = "tpl.frm.campos.configuracao_modulos.php";
        break;

    case "modificar_campo":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objConfiguracaoModulos = new ConfiguracaoModulos($pdo);
            ($_REQUEST['padrao'] != "") ? $padrao = 1 : $padrao = 0;
            ($_REQUEST['obrigatorio'] != "") ? $obrigatorio = 1 : $obrigatorio = 0;

            $objConfiguracaoModulos->setId($_REQUEST['id']);
            $objConfiguracaoModulos->setNome($_REQUEST['nome']);
            $objConfiguracaoModulos->setRotulo($_REQUEST['rotulo']);
            $objConfiguracaoModulos->setValor($_REQUEST['valor']);
            $objConfiguracaoModulos->setNameId($_REQUEST['name_id']);
            $objConfiguracaoModulos->setPadrao($padrao);
            $objConfiguracaoModulos->setObrigatorio($obrigatorio);
            $objConfiguracaoModulos->setClasse($_REQUEST['classe']);
            $retorno = $objConfiguracaoModulos->Modificar();

            $msg["codigo"] = 0;
            $msg["mensagem"] = "Sucesso ao modificar registro";
            $pdo->commit();
        }catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO . " ". $e->getMessage();
            $msg["debug"] = $e->getMessage();
            $pdo->rollBack();
        }
        echo json_encode($msg);

        $template = "ajax.configuracao_modulos.php";
        break;

    case "deletar_campo":
        $objConfiguracaoModulos = new ConfiguracaoModulos();
        if (count($_POST['registros']) > 0) {
            $retorno = $objConfiguracaoModulos->Remover($_POST['registros']);
            if ($retorno == 1) {
                $msg["codigo"]   = 0;
                $msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
            } else {
                $msg["codigo"]   = 1;
                $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            }

            echo json_encode($msg);
        } else {
            $msg["codigo"]   = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
        }
        $template = "ajax.configuracao_modulo.php";
        break;

    case "frm_atribuir_campo":
        $template = "tpl.frm.atribuir_campos.configuracao_modulos.php";
        break;

    case "listar_colunas_disponiveis":
        $template = "tpl.lis.colunas_disponivel.php";
        break;

    case "listar_colunas_viculadas":
        $template = "tpl.lis.colunas_vinculadas.php";
        break;

    case "atribuir_campo":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objConfiguracaoModulos = new ConfiguracaoModulos($pdo);
            $objConfiguracaoModulos->RemoverTodos($_REQUEST['modulo']);
            if(@count($_REQUEST['id_coluna']) > 0){
                foreach($_REQUEST['id_coluna'] as $coluna){
                    $objConfiguracaoModulos->setIdModulo($_REQUEST['modulo']);
                    $objConfiguracaoModulos->setIdConfiguracaoCampos($coluna);
                    $objConfiguracaoModulos->AdicionarConfModulo();
                }
            }
            $msg["codigo"] = 0;
            $msg["mensagem"] = TXT_ALERT_SUCESSO_MODIFICAR;
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            $msg["debug"] = $e->getMessage();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.configuracao_modulos.php";
        break;
        
    case "deletar_configuracao_relatorios_campos":
        $pdo = new Conexao();
        $pdo->beginTransaction();
        try {
            $objConfiguracaoModulos = new ConfiguracaoModulos($pdo);
            $objConfiguracaoModulos->RemoverConfig($_REQUEST['registros']);
            $msg["codigo"] = 0;
            $msg["mensagem"] = TXT_ALERT_SUCESSO_OPERACAO;
            $pdo->commit();
        } catch (Exception $e) {
            $msg["codigo"] = 1;
            $msg["mensagem"] = TXT_ALERT_ERRO_OPERACAO;
            $msg["debug"] = $e->getMessage();
            $pdo->rollBack();
        }
        echo json_encode($msg);
        $template = "ajax.configuracao_modulos.php";
        break;
}