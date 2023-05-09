<?php
$usuario1 = new Usuario();
$usuario1->setId( $_GET['app_codigo'] ?: $_SESSION['usuario']['id']);
$auxUsuario = $usuario1->Editar();

$GruposExcecao = array(1,157,6464);

$usuario_conf = new UsuarioConfiguracao();
$usuario_conf->setIdUsuario($_GET['app_codigo'] ?: $usuario = $_SESSION['usuario']['id']);
$comandos = $usuario_conf->BuscarComandosUsuario();

$arvore = $auxUsuario->arvore;
$arvore = str_replace(';',',',$arvore);
$arvore = substr($arvore,0,-1);
$arvore = substr($arvore,1);

if(($_SESSION['id_franquia'] == "" && $_SESSION['usuario']['cliente'] == '' && $_SESSION['usuario']['id_franqueado_cliente'] == "") || array_search($_SESSION['usuario']['id_grupo'],$GruposExcecao) !== false)
    $acessoOperador = 0;//Desenvolvedor e Franqueadora

elseif ($_SESSION['id_franquia'] != "")
    $acessoOperador = 1;//Franquia

elseif ($_SESSION["id_cliente"] != "")
    $acessoOperador = 2;//Cliente

else
    $acessoOperador = 3; //ele é filho de um grupo de cliete ou franquia cliado pelo mapas

if(in_array($auxUsuario->id_grupo, $GruposExcecao))
    $acessoUsuario = 0;
elseif ($auxUsuario->id_franqueado != "")
    $acessoUsuario = 1;
elseif ($auxUsuario->id_cliente != "")
    $acessoUsuario = 2;
else
    $acessoUsuario = 3;

if($acessoOperador == 1 && $acessoOperador < $acessoUsuario){
    /*deixar essa visibilidade exclusiva para franquia*/
// Somente o cliente tem vis�o diferenciada, os outros vem os comandos do cliente de forma igual.
//    if ($acessoOperador == 3)
//        $col = $usuario_conf->BuscarComandosUsuario();
//    else
        $col = $usuario_conf->ListarComandosFranquia();
}else{
    echo
    '<script type="text/javascript">
            $(document).ready(function()
            {
                $("#aba_comando").parent().remove();
            });
            </script>';
}


if(count($comandos) == 0)
{
	$marcados = array();
}
else
	$marcados = $comandos;

$x = 0;
foreach($marcados as $linha)
{
	$comandos[$x] = $linha->id_comando;
	$x++;
}

$dados_form             = Array();
$dados_form["name"]     = "form";
$dados_form["id"]       = "form";
$dados_form["onsubmit"] = "return false";

$dados_tabela          = Array();
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "TabelaPrioridade";

$dados_coluna               = Array();
$dados_coluna["dados_th"][] = array("nome" => "#");
$dados_coluna["dados_th"][] = array("nome" => RTL_EVENTO);
$dados_linha = Array();

$x = 0;
if (count($col) > 0)
	foreach ($col as $linha) {

		if(array_search($linha->id_comando,$comandos) !== false) {
			$checked = "checked";
		} else {
			$checked = '';
		}
		$checkbox = "<input class='js-switch' type=\"checkbox\" name=\"seleciona_comandos[]\" value=\"{$linha->id_comando}\" $checked/>";
		$dados_linha[$x]["dados_td"][] = array("valor" => $checkbox, "class" => "uppercase");
		$dados_linha[$x]["dados_td"][] = array("valor" => (defined($linha->rotulo))?constant($linha->rotulo):$linha->rotulo, "class" => "uppercase");


		$x++;
	}

$grid                      = new GerarGrid();
$grid->form                = $dados_form;
$grid->tabela              = $dados_tabela;
$grid->titulo              = "";
$grid->funcao_atualizar    = "AtualizarGridEventoRelatorio";
$grid->funcao_modificar    = "";
$grid->valor_campo_busca   = $busca;
$grid->nome_campo_busca    = "_busca";
$grid->filtro              = $filtro;
$grid->pagina              = $pagina;
$grid->numeroRegistros     = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem               = $_REQUEST["ordem"];
$grid->totalRegistros      = 0;
$grid->colunas             = $dados_coluna;
$grid->linhas              = $dados_linha;
$grid->permitir_adicionar  = false;
$grid->permitir_excluir    = false;
$grid->permitir_busca      = false;
$grid->permitir_paginacao  = false;
$grid->permitir_form       = false;
$grid->permitir_outros     = false;
$grid->Gerar();