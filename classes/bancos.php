<?php
/**
 * Objeto com funções utilitárias utilizadas em várias áreas do sistema.
 *
 */
abstract class Bancos{

    public static function listabancos($conexao){

        $pdo = $conexao;
        $sql = "SELECT id,nome FROM erp_banco WHERE excluido IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}