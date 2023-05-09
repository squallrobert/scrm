<?php
include_once("modulos/usuario/template/js.lis.usuario.php");
//Conexao::pr($_SESSION);

$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc")? $ordem = "asc": $ordem = "desc";
if($pagina == "") { $pagina = 0; }

$numeroRegistros = ($_REQUEST["numero_registros"] == "") ? 50 :(int) $_REQUEST["numero_registros"];
$numeroInicioRegistro = $pagina * $numeroRegistros ;

$objUsuario = new Usuario();
$listar = $objUsuario->ListarPaginacao($_SESSION['usuario']['id_grupo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro,$ordem);

//dados do formulário
$dados_form["name"] = "form";
$dados_form["id"] = "form";
$dados_form["onsubmit"] = "return false";
// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela_usuario";

$dados_coluna["dados_th"][] = ["configuracao" => "config_caixa_selecao", "nome" => "box", "class"=> "checkboxes","width" => "30"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Foto',"filtro"=> "foto", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'ID',"filtro"=> "id", "tipo"=> "$ordem","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Nome',"filtro"=> "nome", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Grupo',"filtro"=> "grupo.nome", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'usuário',"filtro"=> "usuario", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Ativo',"filtro"=> "ativo", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => 'Ultimo Login',"filtro"=> "data_hora_ultimo_login", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "config_acoes", "nome" => "Alterar", "class"=> "","width" => "40"];

$x = 0;
if(@count($listar[0])> 0){
	foreach($listar[0] as $linha){
        ($linha['ativo']) ? $linha['ativo'] = RTL_SIM : $linha['ativo'] = RTL_NAO;

        $linha['data_hora_ultimo_login'] = ($linha['data_hora_ultimo_login']) ?  Conexao::PrepararDataPHP($linha['data_hora_ultimo_login'], $_SESSION['usuario']['timezone'],'d/m/y H:s') :"Não logou";

        if ($linha['foto'] == "" || !file_exists($linha['foto'])) {
            $letra_inicial = '<div class="symbol symbol-35px symbol-circle mb-5">
                        <span class="symbol-label  fw-semibold text-dark bg-warning">'.ucfirst(substr($linha['nome'],0,1)).'</span>
                    </div>';
            $foto        = $letra_inicial;

        } else {
            $thumbs              = new Thumbs();
            $thumbs->caminho     = "upload/fotos_usuario/";
            $thumbs->arquivo     = $linha['foto'];
            $thumbs->largura_max = 350;
            $thumbs->altura_max  = 350;
            $thumbs->Prepare();
            $dadosimg            = @getimagesize($linha['foto']);
            if ($dadosimg[0] > $thumbs->largura_max) {
                $width = "350px";
            } else {
                $width = $dadosimg[0];
            }

            if ($dadosimg[0] > $thumbs->altura_max) {
                $height = "350px";
            } else {
                $height = $dadosimg[1];
            }
            $foto = '<div class="symbol symbol-35px symbol-circle mb-5"> ';
            $foto .= "<img src=\"{$linha['foto']}\" data-toggle=\"popover\" data-title=\"{$linha['nome']}\" data-content=\"<div style='width:{$width}px'><img src='{$linha['foto']}' width='{$width}px' height='{$height}px'></div>\">";
            $foto .= '</div>';

        }

        $disabled_bt = ($_SESSION['usuario']['id_usuario_tipo'] == 5 && ($linha["id_usuario_tipo"] == 1 || $linha["id_usuario_tipo"] == "")) ? 'disabled' : '';

        $bt_remove = ($linha['permissao'] > 0) ? 'btn-warning' : 'btn-dark disabled';
        $bt_permissoes = ($_SESSION['PERMISSAO']['frm_permissoes_usuario']) ? '<a '.TAMANHO_BT_GRID.' class="btn  btn-success  '.$disabled_bt.'  btn-sm btn-icon waves-effect waves-themed" href="#index_xml.php?app_modulo=usuario&app_comando=frm_permissoes_usuario&app_codigo='.$linha['id'].'"  data-placement="top" title="" data-original-title="Permissões" data-toggle="tooltip"><i class="fas fa-check-circle" title="Permissões"></i></a>' : '';
        $bt_remove_permissoes = ($_SESSION['PERMISSAO']['resetar_permissao']) ? '<a '.TAMANHO_BT_GRID.' class="btn btn-sm  '.$bt_remove.'  '.$disabled_bt.'  btn-sm btn-icon waves-effect waves-themed" href="javascript:ResetarPermissao('.$linha['id'].');void(0)"  data-placement="top" title="" data-original-title="Voltar Permissões de Grupo" data-toggle="tooltip"><i class="fas fa-trash-alt" title="Voltar Permissões de Grupo"></i></a>': '';


        $bt_logar =  "<a ".TAMANHO_BT_GRID." class='btn btn-info ".$disabled_bt." btn-sm btn-icon waves-effect waves-themed text-white' onclick='Emular(\"{$linha['email']}\", \"{$linha['senha']}\")' data-placement=\"top\" title=\"\" data-original-title=\"Logar\" data-toggle=\"tooltip\"><i class=\"fas fa-sign-in\" title=\"Logar\"></i></a>";

        $id_alterar = ($_SESSION['usuario']['id_usuario_tipo'] == 5 && ($linha["id_usuario_tipo"] == 1 || $linha["id_usuario_tipo"] == "")) ? '' : $linha["id"];

        $dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"class"=> "checkboxes","nome" => "box"];
        $dados_linha[$x]["dados_td"][] = ["valor" => $foto,"align"=> "center"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["nome"]."<span class='text-muted' style='font-size: 10px'><br><strong>Grupo:</strong> ".$linha["nome_grupo"]."</span>"."<span class='text-muted' style='font-size: 10px'><br><strong>Tipo</strong>: ".$linha["nome_tipo"]."</span>","class"=> "uppercase"];
//		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["nome_grupo"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => str_replace("@","<br>@",$linha["usuario"]),"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["ativo"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["data_hora_ultimo_login"],"class"=> "uppercase"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $id_alterar,"nome" => "Alterar","style" => "text-align:right","acoes" => array($bt_logar, $bt_permissoes,$bt_remove_permissoes)];
		$x++;
	}
}
$grid = new GerarGrid();
$grid->form = $dados_form;
$grid->tabela = $dados_tabela;
$grid->titulo = "";
$grid->funcao_atualizar = "AtualizarGridUsuario";
$grid->funcao_modificar = "ModificarUsuario";
$grid->valor_campo_busca = $busca;
$grid->ph_campo_busca = "Busca por: Nome, grupo ou usuário";
$grid->filtro = $filtro;
$grid->pagina = $pagina;
$grid->numeroRegistros =  $numeroRegistros ;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem = $_REQUEST["ordem"];
$grid->totalRegistros = $listar[1];
$grid->linhas  = $dados_linha;
$grid->colunas = $dados_coluna;
$grid->Gerar();
