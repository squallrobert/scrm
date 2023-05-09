<?
include_once("modulos/cliente/template/js.lis.cliente.php");
$busca = $_REQUEST["busca"];
$pagina = $_REQUEST["pagina"];
$filtro = $_REQUEST["filtro"];
($_REQUEST["ordem"] == "desc")? $ordem = "asc": $ordem = "desc";
if($pagina == "") { $pagina = 0; }

$numeroRegistros = 50;
$numeroInicioRegistro = $pagina * $numeroRegistros;

$objCliente = new Cliente();
$listar = $objCliente->ListarPaginacao($_SESSION['usuario']['id_grupo'],$numeroRegistros,$numeroInicioRegistro,$busca,$filtro,$ordem);

//dados do formulário
$dados_form["name"] = "form";
$dados_form["id"] = "form";
$dados_form["onsubmit"] = "return false";
// dados da tabela
$dados_tabela["class"] = "table table-hover";
$dados_tabela["id"]    = "id_tabela_cliente";

$dados_coluna["dados_th"][] = ["configuracao" => "config_caixa_selecao", "nome" => "box", "class"=> "checkboxes","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "IMG","filtro"=> "id", "tipo"=> "$ordem","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "ID","filtro"=> "id", "tipo"=> "$ordem","width" => "40"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "NOME","filtro"=> "nome", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "NOME_FANTASIA","filtro"=> "nome_fantasia", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "CPF/CNPJ","filtro"=> "cpf_cnpj", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "INSCRICAO_ESTADUAL","filtro"=> "inscricao_estadual", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "DATA_NASCIMENTO","filtro"=> "data_nascimento", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "CELULAR","filtro"=> "endereco.celular", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "VENC.","filtro"=> "dia_vencimento", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "ID ASAAS","filtro"=> "dia_vencimento", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "RG","filtro"=> "rg", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "STATUS","filtro"=> "status", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "FOTO","filtro"=> "foto", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "<i class='fa fa-clock' data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" title=\"Data Hora Cadastro\" ></i>","filtro"=> "data_hora_cadastro", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "OBSERVACAO_DADOS","filtro"=> "observacao_dados", "tipo"=> "$ordem"];
//$dados_coluna["dados_th"][] = ["configuracao" => "", "nome" => "DATA_HORA_ATUALIZACAO","filtro"=> "data_hora_atualizacao", "tipo"=> "$ordem"];
$dados_coluna["dados_th"][] = ["configuracao" => "config_acoes", "nome" => "Alterar", "class"=> "","width" => "40"];

$x = 0;
if(@count($listar[0])> 0){
	foreach($listar[0] as $linha){
        if ($linha['foto'] == "" || !file_exists($linha['foto'])) {
            $letra_inicial = '<div class="symbol symbol-35px symbol-circle mb-5">
                        <span class="symbol-label  fw-semibold text-dark bg-warning">'.ucfirst(substr($linha['nome'],0,1)).'</span>
                    </div>';
            $foto        = $letra_inicial;

        } else {
            $thumbs              = new Thumbs();
            $thumbs->caminho     = "upload/fotos_clientes/";
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
        $bt_valores = '<a '.TAMANHO_BT_GRID.' class="btn btn-danger btn-sm btn-icon" href="#index_xml.php?app_modulo=cliente_valores&app_comando=frm_adicionar_cliente_valores&app_codigo='.$linha["id"].'"  data-placement="top"  data-original-title="Tabela de valores" data-bs-toggle="tooltip"><i class="fas fa-money-bill  " title="Tabela de valores"></i></a>';
        $bt_id_asaas = '<a '.TAMANHO_BT_GRID.' class="btn btn-info btn-sm btn-icon" href="javascript:;" onclick="AtualizarIdAsaas('.$linha["id"].')"  data-placement="top"  data-original-title="Pegar Id Asaas" data-bs-toggle="tooltip"><i class="fas fa-refresh  " title="Pegar Id Asaas"></i></a>';
        $bt_saldo = ($linha['id_forma_pagamento'] ==  16) ? '<a '.TAMANHO_BT_GRID.' class="btn btn-dark btn-sm " href="javascript:;" onclick="AtualizarSaldo('.$linha["id"].')"  data-placement="top"  data-original-title="Atualizar Saldo" data-bs-toggle="tooltip"><i class="fas fa-coins " title="Atualizar Saldo"></i>R$ '.number_format($linha['saldo'],'2',',','.').'</a>' : '';
        $status = ($linha['status'] == 1) ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Inativo</span>';
        $dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"class"=> "checkboxes","nome" => "box"];
        $dados_linha[$x]["dados_td"][] = ["valor" => $foto,"align"=> "center"];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => ($linha["nome"])?$linha["nome"]:$linha["nome_fantasia"]];
//		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["nome_fantasia"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["cpf_cnpj"]];
//		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["inscricao_estadual"]];
//		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_nascimento"], $_SESSION["usuario"]["id_fuso_horario"])];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["celular"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["dia_vencimento"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id_asaas"]];
//		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["rg"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => $status];
//		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["foto"]];
		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_cadastro"], $_SESSION["usuario"]["id_fuso_horario"])];
//		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["observacao_dados"]];
//		$dados_linha[$x]["dados_td"][] = ["valor" => Conexao::PrepararDataPHP($linha["data_hora_atualizacao"], $_SESSION["usuario"]["id_fuso_horario"])];
		$dados_linha[$x]["dados_td"][] = ["valor" => $linha["id"],"nome" => "Alterar","style" => "text-align:right","acoes" => [$bt_saldo,$bt_valores,$bt_id_asaas]];
		$x++;
	}
}
//Componente::FiltrarRelatorioConfiguracao($dados_coluna, $dados_linha, $_SESSION["configuracao_usuario"]["cliente"]);
$grid = new GerarGrid();
$grid->form = $dados_form;
$grid->tabela = $dados_tabela;
$grid->titulo = "";
$grid->funcao_atualizar = "AtualizarGridCliente";
$grid->funcao_modificar = "ModificarCliente";
$grid->valor_campo_busca = $busca;
$grid->filtro = $filtro;
$grid->pagina = $pagina;
$grid->numeroRegistros = $numeroRegistros;
$grid->numeroRegistroIncio = $pagina * $numeroRegistros;
$grid->ordem = $_REQUEST["ordem"];
$grid->totalRegistros = $listar[1];
$grid->linhas  = $dados_linha;
$grid->colunas = $dados_coluna;
$grid->Gerar();
