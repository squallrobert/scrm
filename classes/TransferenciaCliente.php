<?php
/**
 * @author Marcelo
 * @copyright 2016
 *
 */

class TransferenciaCliente
{ 
    private $idNewFranchise;
    private $idNewGroup;
    private $newTree;
    private $vehicles;

    public function TransferenciaCliente($idNewFranchise, $idNewGroup, $newTree, $vehicles)
    {
        $this->idNewFranchise = $idNewFranchise;
        $this->idNewGroup = $idNewGroup;
        $this->newTree = $newTree;
        $this->vehicles = $vehicles;
    }

    public function update($debug = false)
    {
        $objConnect = new Conexao();

        foreach($this->vehicles as $vehicle)
        {
            // Update the client's group
            $sql0 = "UPDATE grupo SET
                    id_grupo_pai = $this->idNewGroup,
                    arvore = '$this->newTree'
            WHERE id = (SELECT id_grupo FROM veiculo WHERE id = {$vehicle})";
            if(!$debug) {
                $stmt = $objConnect->prepare($sql0);
                $stmt->execute();
            }

            // Update the contract
            $sql1 = "UPDATE contrato SET
                    id_franqueado =  $this->idNewFranchise
            WHERE id_veiculo = {$vehicle}";
            if(!$debug) {
                $stmt = $objConnect->prepare($sql1);
                $stmt->execute();
            }

            // Update the tracker
            $sql2 = "UPDATE rastreador
                    SET id_grupo = $this->idNewGroup
                    WHERE id = (SELECT id_rastreador FROM veiculo WHERE id = {$vehicle})";
            if(!$debug) {
                $stmt = $objConnect->prepare($sql2);
                $stmt->execute();
            }

            // Update the sim card
            $sql3 = "UPDATE chip
                    SET id_grupo = $this->idNewGroup
                    WHERE id = (SELECT id_chip FROM rastreador WHERE id = (SELECT id_rastreador FROM veiculo WHERE id = {$vehicle}))";
            if(!$debug) {
                $stmt = $objConnect->prepare($sql3);
                $stmt->execute();
            }
        }
    }
}