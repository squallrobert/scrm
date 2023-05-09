<?php

/**
 * Created by PhpStorm.
 * User: Fernando
 * Date: 20/05/2016
 * Time: 10:21
 */
abstract class Crud
{
	abstract public function Adicionar();
	abstract public function Editar();
	abstract public function Modificar();
	abstract public function Remover($lista);
	abstract public function ListarPaginacao($idGrupo, $limit, $offset, $busca = "", $filtro = "", $ordem = "");
}