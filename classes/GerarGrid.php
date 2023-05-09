<?php
/**
 * @author    Squall Robert
 * @copyright 2015
 */

@include('idioma/' . DIR_IDIOMA . '/classes/GerarGrid.loc.php');

class GerarGrid
{

    /**
     * Exemplo de Array para Colunas
     * $dados_coluna['dados_th'][]=array("nome" => "Id","filtro"=> "id","tipo"=> "$ordem");
     *
     */
    public $classThead = "table-dark ";
    public $travarThead = "wrapper"; // passar vaziu para não trava o thead
    public $travarTheadTable = "tablegrid"; // passar vaziu para não trava o thead
    public $travarTheadAltura = "height: 1200px;"; // passar vaziu para não trava o thead
    public $colunas;// Array com as colunas
    public $linhas; // Array com as Linhas
    public $linhas_rodape; // Array com as Linhas
    public $botoes; // Array com os Botões
    public $form; // Array com dados da tabela
    public $tabela; // Array com dados da tabela
    public $titulo = 'Listagem de Dados'; //  Nome da Listagem
    public $descricao; //  RTL_DESCRICAO Nome da Listagem
    public $nome_campo_busca = 'busca'; // Nome do imput do campo busca
    public $ph_campo_busca = RTL_O_QUE_PROCURA; // Place Holder do campo busca
    public $valor_campo_busca; //valor do campo busca
    public $retornar_html = false;

    public $filtro; // campo que será filtrado
    public $ordem;// Ordem do campo filtrado ex: ASC ou DESC
    public $busca_ativa; // Define se busca estará Ativa
    public $pagina; // pagina atual para a paginação
    public $numeroRegistroIncio;
    public $numeroRegistros;
    public $campo_numero_registros = 'numero_registros';
    public $totalRegistros;

    public $paginacao_abreviada = false; // Define se a paginação vai ser ebreviada nos nomes

    public $msg_campo_busca;//Exibe texto de ajuda abaixo do campo busca.

    public $rotulo_botao_adicionar = RTL_ADICIONAR;
    public $rotulo_botao_excluir = RTL_EXCLUIR;

    public $multiColunas;
    public $botao_outros;
    public $botao_imprimir_pagina = false;
    public $funcao_atualizar = "AtualizarGrid"; //funcao de javascript para atualizar o grid

    public $id_botao_adicionar = "AdicionarRegistro";
    public $id_botao_config = "ConfigurarListagem";
    public $funcao_modificar = "ModificarRegistro";
    public $funcao_excluir = 'ExcluirRegistro';
    public $id_botao_excluir = "ExcluirRegistro";
    public $div_form_bt = '';
    public $filtros_form = [];

    public $permitir_adicionar = true; // permite mostrar o botão adicionar
    public $permitir_config = false; // permite mostrar o botão config
    public $permitir_excluir = true; // permite mostrar o botão excluir
    public $permitir_outros = true; // permite mostrar o botão outros
    public $permitir_busca = true; // permite mostrar busca
    public $tamanho_campo_busca = "col-md-4";
    public $permitir_paginacao = true; // permite paginacao
    public $permitir_paginacao_top = true; // permite paginacao
    public $permitir_select_registros = true; // permite paginacao
    public $permitir_form = true; // permite a criação de um form na tabela

    public $id_checkbox_master = 'master'; // nome do checkbox master que seleciona todos para excluir
    public $nome_lista_checkbox = 'lista';// nome checkbox excluir
    public $divOverflow = false;// nome checkbox excluir

    // Variaveis Para gerar o BOX
    public $minimo_tamanho_box = '280px'; // define o tamanho minimo para o box
    public $maximo_tamanho_box = '350px'; // define o tamanho maximo para o box
    public $key_coluna_nome = 2; // define a chave do array de registr que que contem o nome
    public $key_coluna_id = 0; // define a chave do array de registro que  contem o ID
    public $key_coluna_complemento_titulo; // define a chave do array de registro que  contem o complemento do título
    public $class_color_box = 'danger'; //define classe da cor do BOX
    public $key_coluna_imagem = false;
    public $permitir_bt_tipo = false;
    public $limit_box_linha = 4;
    public $classTable = false;
    public $permitir_msg_nenhum_registro = true;
    public $campos_hidden = Array();
    //public $nome_checkbox = "lista";
    public function __construct()
    {
        $this->numeroRegistros =$_REQUEST['numero_registros'];
    }

    public function Gerar($tb = 2, $tamanho = 12)
    {
        if($this->totalRegistros > 10)
        {

            $this->travarTheadAltura =  "height: 1000px;";
        }
        else
            $this->travarTheadAltura = '';


        $html = '<script>' . "\n";
        $html .= '$(document).ready(function(){' . "\n";
//        $html .= '      var tamanho_div_dados = $(window).height();' . "\n";
//        $html .= '      $("#th_congelado").css("height", (tamanho_div_dados - 300));' . "\n";
//        $html .= '      $(window).resize(function(){' . "\n";
//        $html .= '          var tamanho_div_dados = $(".panel").height();' . "\n";
//        $html .= '          $("#th_congelado").css("height", (tamanho_div_dados ));' . "\n";
//        $html .= '          console.log(tamanho_div_dados);' . "\n";
//        $html .= '      });' . "\n";
//        $html .= '      StyleForm(".input-group", "has-length", "has-disabled");' . "\n";
        $html .= '      $(\'#'.$this->campo_numero_registros.'\').select2({language: "pt-BR", minimumResultsForSearch: -1});' . "\n";
        $html .= ' });' . "\n";
        $html .= '</script>' . "\n";


        $html .= '<section id="content">' . "\n";
        $html .= "\t" . '<section id="main2" >' . "\n";
        //iniciando tag form
        if ($this->permitir_form) {
            $html .= "\t\t" . "<form ";
            if (@count($this->form)) {
                foreach ($this->form as $index_form => $parametros_form) {
                    // gerando atributos da tag form
                    if ($index_form != "onsubmit") {
                        $html .= " $index_form = '$parametros_form' ";
                    }
                }
            }
            $html .= "onsubmit=\"$this->funcao_atualizar('',document.getElementById('$this->nome_campo_busca').value,'$this->filtro','$this->ordem');{$this->form['onsubmit']}\"";
            // finalizando tag form
            $html .= ">\n";
        }
        $html .=' <input type="hidden" name="pagina"  id="pagina"   value="'.$this->filtros_form['pagina']. '"/>';
        $html .=' <input type="hidden" name="ordem"  id="ordem"   value="'.$this->filtros_form['ordem']. '"/>';
        $html .=' <input type="hidden" name="filtro"  id="filtro"   value="'.$this->filtros_form['filtro'].'"/>';

        if(@count($this->campos_hidden) > 0)
        {
            foreach($this->campos_hidden as $campo)
            {
                $html .=' <input type="hidden" name="'.$campo['name'].'"  id="'.$campo['id'].'"   value="'.$campo['value'].'"/>';
            }
        }
        if ($this->titulo) {
            $html .= "\t\t" . '<h4 class="titulo-tabela">' . $this->titulo . '</h4>' . "\n";
        }
        if ($this->descricao) {
            $html .= "\t\t" . '<p>' . $this->descricao . '</p>' . "\n";
        }


        //Adiciona os botões apenas se existir pelo menos um botão a ser adicionado
        //Evita o espaço em branco caso nenhum esteja habilitado
        if ($this->permitir_adicionar || $this->permitir_excluir || $this->permitir_outros || $this->permitir_busca) {
            $html .= "\t\t" . '<div class="row text-right pb-4" >' . "\n";
            $html .= "\t\t" . '<div class="col-md-'.$tamanho.'" style="float: left">' . "\n";
            if ($this->permitir_excluir && $tb == 2) {
                $html .= "\t\t\t" . '<a  href="javascript:;" id="' . $this->id_botao_excluir . '" class="btn btn-danger  m-r-5 m-b-5 btn-sm  hover-scale" title="' . $this->rotulo_botao_excluir . '" ><i class="fas fa-times-circle"></i>  ' . $this->rotulo_botao_excluir . '</a>' . "\n";
            }
            if ($this->permitir_adicionar) {
                $html .= "\t\t\t" . '<a  href="javascript:;" id="' . $this->id_botao_adicionar . '" class="btn  btn-primary m-r-5 m-b-5 btn-sm  hover-scale" title="' . $this->rotulo_botao_adicionar . '" ><i class="bi bi-plus-circle-fill"></i>  ' . $this->rotulo_botao_adicionar . '</a>' . "\n";
            }
            if ($this->botao_imprimir_pagina) {
                $html .= "\t\t\t" . '<a   href="javascript:printDiv(\''.$this->tabela['id'].'\');" id="bt_imprimir_pagina" class="btn  btn-warning m-r-5 m-b-5 btn-sm  hover-scale" title="bt_imprimir_pagina" ><i class="fas fa-print"></i> Imprimir Página </a>' . "\n";
            }
            if ($this->permitir_config) {
                $html .= "\t\t\t" . '<a  href="javascript:;" id="' . $this->id_botao_config . '" class="btn  btn-dark m-r-5 m-b-5 btn-sm  hover-scale" title="teste" ><i class="fas fa-cog"></i></a>' . "\n";
            }
            if ($this->permitir_outros) {
                $html .= "\t\t\t" . $this->botao_outros . "\n";
            }
            if($tb == 1)
            {
                $html .= $this->GerarFiltro();

            }
            if($this->permitir_bt_tipo)
            {
                $html .= "<div  class=\"btn-group ml-1\" id=\"TipoVisualizacao\">";
                $html .= "  <button type=\"button\" class=\"btn btn-info btn-control dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">";
                $html .= "    ".ROTULO_TIPO." <span class=\"caret\"></span>";
                $html .= "  </button>";
                $html .= "  <div  class=\"dropdown-menu dropdown-menu-right\">";
                $html .= "    <a href=\"javascript:;\" class=\"dropdown-item\" onclick=\"$this->funcao_atualizar('',document.getElementById('$this->nome_campo_busca').value,'$this->filtro','$this->ordem',2)\">Grid</a>";
                $html .= "    <a href=\"javascript:;\" class=\"dropdown-item \"  onclick=\"$this->funcao_atualizar('',document.getElementById('$this->nome_campo_busca').value,'$this->filtro','$this->ordem',1)\">Box</a>";
                $html .= "  </div>";
                $html .= "</div>";
            }
            $html .= "</div>";

            $html .= "" . '</div><!-- .row -->' . "\n";
        }

        if($this->permitir_paginacao_top || $this->permitir_busca)
        {

            $html .= "\t\t" . '<div class="row pb-2">' . "\n";
            if($this->permitir_select_registros)
            {
                ($this->permitir_busca) ? $busca = "document.getElementById('$this->nome_campo_busca').value" : $busca = "''";

                $html .= "<div class='col-md-2 '><div class='input-group-sm input-group'> ". "\n";
                $html .= "<select name=\"{$this->campo_numero_registros}\" id=\"{$this->campo_numero_registros}\" onchange=\"$this->funcao_atualizar('',$busca,'$this->filtro','$this->ordem')\"  class=\"form-select\">" . "\n";
                $html .= '<option value="50" ';
                $html .= ($this->numeroRegistros == 50) ? 'selected = "selected"': '';
                $html .= '>50 Registros</option>' . "\n";
                $html .= '<option value="150" ';
                $html .= ($this->numeroRegistros == 150) ?  'selected = "selected"': '';
                $html .= '>150 Registros</option>' . "\n";
                $html .= '<option value="300" ';
                $html .=($this->numeroRegistros == 300) ?  'selected = "selected"': '';
                $html .= '>300 Registros</option>' . "\n";
                $html .= '</select>' . "\n";
                $html .= "</div></div>". "\n";
            }
            $html .= "<div class='col-md pt-1'>". "\n";
            if ($this->permitir_paginacao_top && @count($this->linhas) > 0) {
               $html .= $this->PaginacaoTop();
            }
            $html .="</div>";


            if ($this->permitir_busca) {
                $html .= "\t\t\t" . '<div class="form-search '.$this->tamanho_campo_busca.'" style=" margin-top: 0;margin-bottom: 0;" >' . "\n";
                $html .= "\t\t\t\t" . '<div class="input-group input-group-sm">' . "\n";
                $html .= "\t\t\t\t\t" . '<input type="text" value="' . $this->valor_campo_busca . '" class="form-control" id="' . $this->nome_campo_busca . '" name="' . $this->nome_campo_busca . '" placeholder="' . $this->ph_campo_busca . '">' . "\n";
                $html .= "\t\t\t\t\t" . "<span class=\"input-group-text\"   onclick=\"$this->funcao_atualizar('', document . getElementById('$this->nome_campo_busca') . value, '$this->filtro', '$this->ordem')\" style='cursor: pointer'><i class=\"fas fa-search\" ></i> </span>" . "\n";

                $html .= "\t\t\t\t" . '</div><!-- .form-group -->  ' . "\n";
                if ($this->msg_campo_busca) {
                    $html .= "\t\t\t\t" . '<small class="help-block"><i>' . $this->msg_campo_busca . '</i></small>' . "\n";
                }
                $html .= "\t\t" . '</div>' . "\n";
            }
            $html .= "\t\t" . '</div>' . "\n";


        }

        if ($this->div_form_bt != ""){
            $html .= "<div class='col-md-12'>".$this->div_form_bt."</div>";
        }
        if($tb == 1)
            $html .= $this->GerarBox();
        else
        {

            // iniciando tag table
            if ($this->divOverflow) {
                $html .= "\t\t\t<div id='th_congelado' class=' ".$this->travarThead." ' style='overflow-y:auto ; ".$this->travarTheadAltura."' >";
            }
            if($this->classTable) $html .= " <table  class=\"table  \"";
            else $html .= "<div class='table-responsive'> <table  class=\"table ".$this->travarTheadTable."  table-hover table-bordered table-striped  border \"";

            if (@count($this->tabela)) {
                foreach ($this->tabela as $index_tabela => $parametros) {
                    //gerando atributos da tag table
                    $html .= " $index_tabela = '$parametros' ";
                }
            }
            // finalizsando tag table
            $html .= ">\n";

            $html .= "\t\t\t\t<thead class=\" $this->classThead\">\n";
            $html .= $this->GerarColunas();
            $html .= "\t\t\t\t</thead>\n";
            $html .= "\t\t\t\t<tbody>\n";
            $html .= $this->GerarLinhas();
            $html .= "\t\t\t\t</tbody>\n";
            $html .= "\t\t\t\t<tfoot>\n";
            $html .= $this->GerarLinhasRodape();
            $html .= "\t\t\t\t</tfoot>\n";
            $html .= "\t\t\t</table></div>\n";
            if ($this->divOverflow) {
                $html .= "\t\t\t</div>\n";
            }
        }
        if ($this->permitir_paginacao && @count($this->linhas) > 0) {
            $html .= $this->Paginacao();
        }

        if ($this->permitir_form) {
            $html .= "\t\t</form>\n";
        }
        $html .= "\t\t<div class='row'><div class='col-md-12'>&nbsp;</div></div>\n";
        $html .= "\t" . '</section>';
        $html .= '</section>';

        if ($this->retornar_html) {
            return $html;
        } else {
            echo $html;
        }
    }
    public function PaginacaoTop()
    {
        ($this->permitir_busca) ? $busca = "document.getElementById('$this->nome_campo_busca').value" : $busca = "''";
        //$html = "<div class='row'>";

        $html = '<nav class="btn-group mr-2">';
        $html .= '<ul class="pagination pagination-sm fs-6">';
        //fazendo calculos para numero de registros e paginas
        if($this->permitir_paginacao) {
            $totalRegitrosPagina = $this->numeroRegistroIncio + $this->numeroRegistros;
            $total = ceil($this->totalRegistros / $this->numeroRegistros);
        }
        if ($this->pagina != 0)
        {
            $paginas = $this->pagina - 1;
            $html .= '<li  class="page-item  " id="data-table_previous" ' . "  " . "><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar('{$paginas}',{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\">Anterior</a></li>";
        }
        if ($total > 0) {
            // fazendo looping com paginas para montar o list
            for ($i = 1; $i <= $total; $i++) {
                $quantidade_esquerdo = ((int) ($this->pagina) -3);
                if($i <= $quantidade_esquerdo) continue;
                $resto = 3 - $quantidade_esquerdo;
                $resto = ($resto < 0)? 0:$resto;
                $quantidade_direito = $resto + 4;
                if($i > ((int) ($this->pagina) + (int)($quantidade_direito ))) break;

                $idpag = $i - 1;
                ($idpag == $this->pagina) ? $ac = "active" :$ac ="";
//                $html .= ' <li  class="page-item '.$ac.' " '." onclick=\"$this->funcao_atualizar('{$idpag}',{$busca},'$this->filtro','$this->ordem')\" " . ' >'.$i.'</li>';
                $html .= ' <li  class="page-item '.$ac.' " '."><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar('{$idpag}',{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\">$i</a></li>";
            }
        }



        if ($this->pagina < ($total - 1))
        {
            $paginas = (int) $this->pagina + 1;
            $html .= '<li  class="page-item " ' . "  " . "><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar('{$paginas}',{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\">Próximo</a></li>";
        }
        $paginaAtual         = (int) $this->pagina + 1;

        $html .= '</ul>';
        $html .= '&nbsp;';
        $html .= '<ul class="pagination pagination-sm">';
        $html .= '<li class="page-item"><a class="page-link">'. $paginaAtual . '/' . $total . '  ( ' . $this->totalRegistros . ' ' . RTL_REGISTROS . ')</a></li>';
        $html .= '</ul>';
        $html .= '</nav>';
        //$html .= '</div>';
        return $html;
    }
    public function GerarColunas()
    {
        $html = '';

        if($this->multiColunas)
        {
            foreach($this->colunas as $colunas)
                $html .= $this->GerarColunasDados($colunas);
        }
        else
        {
            $html .= $this->GerarColunasDados($this->colunas);
        }
        return $html;
    }

    private function GerarColunasDados($dadosColunas)
    {
        $html = '';

        if(@count($dadosColunas) > 0)
        {
            foreach($dadosColunas as $index => $valor)
            {
                $html .= "\t\t\t\t\t<tr  class='fw-semibold fs-6' ";
                // montando Tr para montar
                if($index == "dados_tr")
                {

                    foreach($valor as $index_tr => $parametros)
                    {
                        $html .= " $index_tr='$parametros' ";
                    }
                    $html .= ">\n";
                }
                $html .= ">\n";
                if($index == "dados_th")
                {
                    foreach($valor as $row)
                    {
                        // iniciando th
                        $th = "\t\t\t\t\t\t<th valign='middle'  ";
                        foreach($row as $index_td=> $parametros_td)
                        {
                            //ignorando tipos de campos que não é atributos da linha (Onclick e class precisam de um tratamento diferente)
                            if($index_td != "nome" && $index_td != "filtro" && $index_td != "tipo" && $index_td != "onclick" && $index_td != "class")
                                $th .= " $index_td='$parametros_td' ";
                        }
                        // verifica se existe filtro para esta coluna
                        if($row['filtro'] != "" )
                            $th .= " onclick=\"{$this->funcao_atualizar}('','{$this->valor_campo_busca}','{$row["filtro"]}','{$row["tipo"]}');$row[onclick]\"";

                        //checando qual coluna marcar com filtro
                        if($row['filtro'] != "")
                        {
                            if($row['filtro'] == $this->filtro)
                            {
                                if($this->ordem == "asc")
                                    $th .= "class='table-sort-asc {$row['class']}'";
                                else
                                    $th .= "class='table-sort-desc {$row['class']}'";
                            }
                            else
                                $th .= "class=' {$row['class']}'";
                        }
                        else
                            $th .= "class='{$row['class']}'";


                        //fechando tag <th>
                        $th .= ">";
                        if($row['nome'] === "box")
                        {
                            $th .= '<input  type="checkbox" onclick="Squall.GridListaCheckbox(document.getElementById(\''.$this->id_checkbox_master.'\'),document.getElementsByName(\''.$this->nome_lista_checkbox.'[]\'))" class=" form-check-input ms-3" name="'.$this->id_checkbox_master.'" id="'.$this->id_checkbox_master.'" />';
                        }
                        elseif($row['nome'] === "alterar" || $row['nome'] === "Alterar")
                        {
                            $th .= '#'."\n";
                        }
                        else
                            $th .= $row['nome']; //valor interno do TH  ex:  <th> valor interno</th>

                        // finalizando th
                        $th .= "</th>\n";

                        $html .= $th;
                    }
                    $html .= "\t\t\t\t\t</tr>\n";
                }
            }
        }

        return $html;
    }

    public function GerarLinhas ()
    {
        if (@count($this->linhas) > 0) {
            $html = "";
            foreach ($this->linhas as $valor) {

                $html .= "\t\t\t\t\t<tr ";
                if (@count($valor['dados_tr']) > 0) {
                    foreach ($valor['dados_tr'] as $index_tr => $parametros) {
                        $html .= "$index_tr='$parametros' ";
                    }
                }
                $html .= ">\n";

                foreach ($valor['dados_td'] as $row)
                {
                    $html .= "\t\t\t\t\t\t<td valign='middle'";
                    foreach ($row as $index_td => $parametros_td)
                    {

                        if ($index_td != "valor" && $index_td != "nome" && $index_td != "showbox")
                        {
                            if($index_td == 'style' && ($row['nome'] == "alterar" || $row['nome'] == "Alterar"))
                                $html .= "$index_td='$parametros_td ; white-space: nowrap;' ";
                            if($index_td == 'style')
                                $html .= "$index_td='$parametros_td ' ";
                            else
                                $html .= "$index_td='$parametros_td' ";
                        }
                        if(($row['nome'] == "alterar" || $row['nome'] == "Alterar"))
                            $html .= 'style="white-space: nowrap;"';
                    }

                    $html .= ">";
                    if ($row['nome'] == "box")
                    {
                        ($row['valor'] == "") ? $disabled = "disabled" : $disabled = "";

                        ($row['checked'] == "checked") ? $checked = "checked='checked'" : $checked = "";

                        $html .= '<input class="form-check-input ms-3" ' . $disabled . ' type="checkbox" value="' . $row['valor'] . '" name="' . $this->nome_lista_checkbox . '[]" id="' . $this->nome_lista_checkbox . '_' . $row['valor'] . '" ' . $checked . '/>';
                    }
                    elseif ($row['nome'] == "alterar" || $row['nome'] == "Alterar")
                    {
                        ($row['valor'] == "") ? $disabled = "style='pointer-events: none'" : $disabled = "";
                        ($row['valor'] == "") ? $theme = " disabled  btn-light '" : $theme = " btn-primary ";
                        ($row['title'] != "") ? $alterarTitle = $row['title'] : $alterarTitle = ROTULO_MODIFICAR;


                        if (@count($row['acoes'])) {
                            foreach ($row['acoes'] AS $acao) {
                                $html .= " " . $acao;
                            }
                        }
                        $html .= ' <a '.TAMANHO_BT_GRID.' class="btn btn-sm btn-icon '.$theme.'"  href="javascript:;" ' . $disabled . ' onclick="' . $this->funcao_modificar . '(' . $row['valor'] . ')" data-placement="top" title="' . $alterarTitle . '" data-original-title="' . $alterarTitle . '" data-bs-toggle="tooltip" ><i class="fas fa-edit  btn-sm btn-icon"  title="' . ROTULO_MODIFICAR . '"></i></a>' . "\n";
                    } else {
                        $html .= "$row[valor]";
                        if (@count($row['acoes'])) {
                            foreach ($row['acoes'] AS $acao) {
                                $html .= " " . $acao;
                            }
                        }
                    }
                    $html .= "</td>\n";
                }
                $html .= "\t\t\t\t\t</tr>\n";
            }
        } else {
            if($this->permitir_msg_nenhum_registro)
                    $html = '<tr><td colspan="' . @count($this->colunas['dados_th']) . '">' . TXT_NENHUM_REGISTRO_ENCONTRADO . '</td></tr>' . "\n";

        }

        return $html;
    }

    public function GerarLinhasRodape ()
    {
        if (@count($this->linhas_rodape) > 0) {
            $html = "";
            foreach ($this->linhas_rodape as $valor) {

                $html .= "\t\t\t\t\t<tr ";
                if (@count($valor['dados_tr']) > 0) {
                    foreach ($valor['dados_tr'] as $index_tr => $parametros) {
                        $html .= "$index_tr='$parametros' ";
                    }
                }
                $html .= ">\n";

                foreach ($valor['dados_td'] as $row) {
                    $html .= "\t\t\t\t\t\t<td ";
                    foreach ($row as $index_td => $parametros_td) {

                        if ($index_td != "valor") {
                            $html .= "$index_td='$parametros_td' ";
                        } else {
                            $value = $parametros_td;
                        }
                    }
                    $html .= ">";
                    $html .= "$value";

                    $html .= "</td>\n";
                }
                $html .= "\t\t\t\t\t</tr>\n";
            }
        }

        return $html;
    }

    public function Paginacao ()
    {

        ($this->permitir_busca) ? $busca = "document.getElementById('$this->nome_campo_busca').value" : $busca = "''";
        $html = "<div class='row'>";

        //fazendo calculos para numero de registros e paginas
        if($this->permitir_paginacao) {
            $totalRegitrosPagina = $this->numeroRegistroIncio + $this->numeroRegistros;
            $total = ceil($this->totalRegistros / $this->numeroRegistros);
        }
        $html .= '<nav class=" mt-2 col-md-3">';
//        $html .= '<ul class="pagination ">';
        $paginaAtual         = (int) $this->pagina + 1;
        $html .= '<h5>Página <b>'. $paginaAtual . '</b> de <b>' . $total . '</b>  &nbsp;|&nbsp;  <b>' . number_format($this->totalRegistros,'0',',','.'). '</b> Registros</h5>';
//        $html .= '</ul>';
        $html .= '</nav>';
        $html .= '<nav class=" mt-2 col-md-9">';
        $html .= '<ul class="pagination justify-content-end">';
        if ($this->pagina != 0)
        {
            $paginas = $this->pagina - 1;
            $html .= '<li  class="page-item  " id="data-table_previous" ' . "  " . "><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar('{$paginas}',{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\" data-placement=\"top\" title=\"Anterior\" data-original-title=\"Anterior\" data-bs-toggle=\"tooltip\"><i class=\"fa fa-backward\"></i></a></li>";
            $html .= '<li  class="page-item " ' . "  " . "><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar(0,{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\" data-placement=\"top\" title=\"Primeira\" data-original-title=\"Primeira\" data-bs-toggle=\"tooltip\"><i class=\"fa fa-step-backward\"></i></a></li>";
        }
        if ($total > 0) {
            // fazendo looping com paginas para montar o list
            $j = 0;
            $quantidade_paginas = 7;
            for ($i = 1; $i <= $total; $i++) {
                // quantidade de registros do lado esquerdo.
                $quantidade_esquerdo = ((int) ($this->pagina) - floor(($quantidade_paginas / 2)));
                if($i <= $quantidade_esquerdo ) continue;
                $resto = ($quantidade_esquerdo > 0) ? 0 : $quantidade_esquerdo;
                $quantidade_direito = abs($resto) + floor(($quantidade_paginas / 2) + 1);
                if($i > ((int) ($this->pagina) + (int)($quantidade_direito ))) break;
                $idpag = $i - 1;
                ($idpag == $this->pagina) ? $ac = "active" :$ac ="";
                $html .= ' <li  class="page-item '.$ac.' " '."><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar('{$idpag}',{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\">$i</a></li>";
                $j++;
            }
        }

        if ($this->pagina < ($total - 1))
        {
            $ultima = ($total - 1);
            $html .= '<li  class="page-item " ' . "  " . "><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar('{$ultima}',{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\" data-placement=\"top\" title=\"Última\" data-original-title=\"Última\" data-bs-toggle=\"tooltip\"><i class=\"fa fa-step-forward\"></i></a></li>";
            $paginas = (int) $this->pagina + 1;
            $html .= '<li  class="page-item " ' . "  " . "><a href=\"javascript:;\" onclick=\"$this->funcao_atualizar('{$paginas}',{$busca},'$this->filtro','$this->ordem')\" class=\"page-link\" data-placement=\"top\" title=\"Próximo\" data-original-title=\"Próximo\" data-bs-toggle=\"tooltip\"><i class=\"fa fa-forward\"></i></a></li>";
        }


        $html .= '</ul>';
        $html .= '</nav>';

        $html .= '</div>';
        return $html;


//
//        $html = '';
//        ($this->permitir_busca) ? $busca = "document.getElementById('$this->nome_campo_busca').value" : $busca = "''";
//
//        $html .= '<div class="row model-pagination">';
//        // gerando o link para pagina anterior
//        if($this->paginacao_abreviada)
//        {
//            $bt_voltar = '<i class="fas fa-backward"></i> ';
//            $bt_proximo = '<i class="fas fa-forward"></i> ';
//        }
//        else
//        {
//            $bt_voltar = '<i class="fas fa-backward"></i> Anterior ' ;
//            $bt_proximo = '<i class="fas fa-forward"></i> Próximo';
//        }
//
//
//        if ($this->pagina != 0)
//        {
//            $paginas = $this->pagina - 1;
//            $html .= '<div class="col-md-3" style="text-align: left;"><button ' . " onclick=\"$this->funcao_atualizar('{$paginas}',{$busca},'$this->filtro','$this->ordem')\" " . 'type="button"  class="btn waves-effect waves-light btn-secondary m-r-5 m-b-5">'.$bt_voltar.'</button></div>';
//
//        }
//        else
//        {
//            $html .= '<div class="col-md-3" style="text-align: left;"><button  disabled="disabled" type="button"  class="btn  btn-default m-r-5 m-b-5">'.$bt_voltar.'</button></div>';
//        }
//
//        //fazendo calculos para numero de registros e paginas
//        if($this->permitir_paginacao) {
//            $totalRegitrosPagina = $this->numeroRegistroIncio + $this->numeroRegistros;
//            $total = ceil($this->totalRegistros / $this->numeroRegistros);
//        }
//        $paginaAtual         = (int) $this->pagina + 1;
//        $colmd = 2;
//        if(!$this->paginacao_abreviada)
//        {
//            $html .= '<div class="col-md-3" style="text-align: right;padding-top: 10px"><div class="texto_registros"> Página ' . $paginaAtual . ' ' . RTL_DE . ' ' . $total . '  ( ' . $this->totalRegistros . ' Registros)</div></div>';
//
//        }
//        else
//            $colmd = 4;
//
//        $html .= '<div class="col-md-'.$colmd.'">';
//
//        $html .= "<select size=\"1\" class=\"form-control paginacao_grid\"  id=\"{$this->id_pagina}\" name=\"paginacao\" onchange=\"$this->funcao_atualizar(this.value,{$busca},'$this->filtro','$this->ordem')\" > \n";
//        // verificando se tem mais de uma pagina
//        if ($total > 0) {
//            // fazendo looping com paginas para montar o list
//            for ($i = 1; $i <= $total; $i++) {
//                $idpag = $i - 1;
//                $html .= "<option   value=\"$idpag\"  \n";
//                if ($idpag == $this->pagina) {
//                    $html .= "selected \n";
//                }
//                $html .= ">Página $i</option> \n";
//            }
//        }
//        $html .= '</select>';
//        $html .= '</div><!-- .form-group -->';
//
//        if ($this->pagina < ($total - 1))
//        {
//            $paginas = (int) $this->pagina + 1;
//            $html .= '<div class="col-md-4" style="text-align: right;"><button  type="button" ' . " onclick=\"$this->funcao_atualizar('{$paginas}',{$busca},'$this->filtro','$this->ordem')\" " . ' class="btn waves-effect waves-light btn-secondary m-r-5 m-b-5">'.$bt_proximo.'</button></div>';
//        } else
//        {
//            $html .= '<div class="col-md-4" style="text-align: right;"><button  type="button" disabled="disabled" class="btn btn-default m-r-5 m-b-5">'.$bt_proximo.'</button></div>';
//        }
//        $html .= '</div>';
//
//        return $html;
    }

    /**
     * Método que cria a tabela de configurações baseadas na sessão e nas colunas do relatório
     *
     * @author Fernando Carmo
     *
     * @param Array  $arrayRelatorio    Recebe o array de configuração da listagem
     * @param int    $limiteColunas     Limite de colunas que pode ser selecionado
     * @param string $existeCampoTexto  Flag sinalizando a existencia de campo texto na configuracao
     * @param string $tamanho_config    largura em px do modal
     * @return null (echo do html da tabela)
     * */
    public function GerarConfiguracoes($arrayRelatorio = Array(), $limiteColunas = 0, $existeCampoTexto = false, $tamanho_config = "", $colunas = 1)
    {
        if (@count($this->colunas) > 0) {
            $html = "
			<form method='post' action='#' id='configuracoes_relatorio' name='configuracoes_relatorio'>
			  <div class=\"form-body\">
                        <div class=\"card\">
                            <div class=\"card-body\">
                                <!-- Nav tabs -->
                                <ul class=\"nav nav-tabs \" role=\"tablist\">
                                    <li class=\"nav-item\"> <a class=\"nav-link active\" data-toggle=\"tab\" href=\"#conteudo_configuracao_listagem\" role=\"tab\"><span class=\"hidden-sm-up\"><i class=\"ti-home\"></i></span> <span class=\"hidden-xs-down\">Colunas Disponíveis</span> </a> </li>";
            if ($existeCampoTexto) $html .= "                       <li class=\"nav-item\"> <a class=\"nav-link\" data-toggle=\"tab\" href=\"#conteudo_configuracao_outros\" role=\"tab\"><span class=\"hidden-sm-up\"><i class=\"ti-user\"></i></span> <span class=\"hidden-xs-down\">Outras Configurações</span></a> </li>";
             $html .= "          </ul>
                          <div class=\"tab-content border border-top-0 p-3\">
                                  ";
            $html .= '  <div class="tab-pane active" id="conteudo_configuracao_listagem" role="tabpanel">
                            <div class="row">
                                ';
            $row = 0;
            $count      = 1;
            $htmlOutros = "";
            foreach ($this->colunas['dados_th'] as $dadosColuna)
            {
                if ($dadosColuna['value_texto'] == "")
                {

                    //Se existir a configuração personalizada, monta a tabela de acordo, caso contrário, se não existir padrão desativado, o checkbox será ativado
                    if (is_array($arrayRelatorio) && @count($arrayRelatorio) > 1)
                    {
                        if (array_search($dadosColuna["value"], $arrayRelatorio) !== false || $dadosColuna['obrigatorio'] != "") {
                            $checked = "checked";
                        } else {
                            $checked = "";
                        }
                    } else if ($dadosColuna['configuracao_padrao'] == "")
                    {
                        $checked = "";
                    } else {
                        $checked = "checked";
                    }
                    $html .='';
                    //Caso o campo seja obrigatório, desativa ele com status "checked" e cria um input hidden com o valor "on" para o post
                    //Inputs com o status "disabled" não são postados
                    if (isset($dadosColuna['obrigatorio'])) {
                        $disabled = "";
                        $onclick = " onclick=\"return false;\" ";
                        $style = "opacity:0.5";
                        $checked = "checked";
                    } else {
                        $disabled = "";
                        $hidden   = "";
                        $style = "";
                        $onclick = " onclick=\"return true;\" ";

                    }

                    if ($colunas > 0) {
                        $colMdLabel = round(12 / $colunas);
                    }

                  ///  $html .= "<div class='col-md-$colMdLabel form-group'>\n";
                    $html .= '<div class="custom-control custom-switch col-md-'.$colMdLabel.'" style="'.$style.'">
                                        <input type="checkbox" class="custom-control-input" id="' . $dadosColuna["name_id"] . '" name="' . $dadosColuna["name_id"] . '" ' . $checked . ' ' . $disabled . $onclick . ' value="' . $dadosColuna["value"] . '">
                                        <label class="custom-form-label" for="' . $dadosColuna["name_id"] . '">' . $dadosColuna["texto"] . '</label>'.$hidden.'
                                    </div>';

                    //Se existir muitas opcoes, gera uma coluna para cada 8 opcoes. Senão, pula uma linha por opção.
                    if ($count ==  $colunas) {
                        $html .= ""."</div>\n<div class='row'>\n";
                        $count = 1;
                    }
                    else
                        $count++;

                }
                else
                {
                    //Busca nos arrays de campos textuais se há configuracao personalizada
                    $configurado = false;
                    if (is_array($arrayRelatorio ))
                        foreach($arrayRelatorio AS $configuracao) {
                            if (!is_array($configuracao)) continue;

                            if ($dadosColuna['value'] == $configuracao['id_campo']) {
                                $configurado = true;
                                $valor = $configuracao['valor_campo'];
                            }
                        }

                    //Se não há config personalizada, recebe valor padrão
                    if (!$configurado) {
                        $valor = $dadosColuna["value_texto"];
                    }

                    //É criado um array, com a key 'id' para o id da tabela de uniao campo-modulo (campo hidden), e a key 'valor' contendo a config do usuário
                    $htmlOutros .= '<div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="' . $dadosColuna["name_id"] . '">' . $dadosColuna["texto"] . '</label>
                                            <input type="text" id="' . $dadosColuna["name_id"] . '" name="' . $dadosColuna["name_id"] . '[valor]" value="' . $valor . '" class="form-control"/>
                                            <input type="hidden" name="' . $dadosColuna["name_id"] . '[id]" value="' . $dadosColuna["value"] . '"/>
                                        </div>
                                    </div>';
                }
            }

            $html .= "
					</div>";
            if ($existeCampoTexto) $html .= "</div><div class='tab-pane fade'  id='conteudo_configuracao_outros'>
                                                $htmlOutros
                                                <div class='row'>
                                                    <div class='col-md-12 pt-2'>
                                                        <div class=\"alert alert-info alert-dismissible fade show\" role=\"alert\">
                                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                                                                <span aria-hidden=\"true\"><i class=\"fas fa-times\"></i></span>
                                                            </button>
                                                            60 segundos é o padrão mínimo para atualização do grid.
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>";
            $html .= "</div></div></div></div></div></div></form>";
            $html .= "<script>";
            if($tamanho_config != "")
            {
                $html .= "$(document).ready(function(){\n";
                $html .= "console.log($('#configuracoes_relatorio'))\n";
                $html .= "  $('.modal-dialog').css(\"max-width\", \"$tamanho_config\");\n";
                $html .= "  $('.modal-dialog').css(\"width\", \"$tamanho_config\");\n";
                $html .= "});\n";
            }
            //Remove o form submit ao apertar enter no campo de busca
            $html .= "$(':input').on('keyup keypress', function(e) {
                            var code = e.keyCode || e.which;
                            if (code == 13) {
                                e.preventDefault();
                                return false;
                            }
                        });
                    </script>";
            return $html;
        } else {
            return VALIDACAO_GRID_CONFIGURACAO_RELATORIOS;
        }
    }

    public function GerarBox()
    {

        //print_r($this->linhas);
        if (@count($this->linhas) > 0)
        {
            $countBox = 0;
            $colMd = floor(12/$this->limit_box_linha);
            $dv_adicionais =abs(floor(( ($colMd * $this->limit_box_linha) - 12) / 2));

            $html = "<div class='border-box'>";
            $html .= "<div class='row'>";

            foreach ($this->linhas as $valor)
            {


                if($valor['classe_panel'] == "" )
                    $classe = "info";
                else
                    $classe = $valor['classe_panel'];

                $titulo = mb_strtoupper($valor['dados_td'][$this->key_coluna_nome]['valor'], "UTF-8");
                if(strlen($titulo) > 20) {
                    $titulo = mb_substr($titulo,0,20, "UTF-8") . "...";
                    $cropped = true;
                }
                else
                    $cropped = false;

                if ($this->key_coluna_complemento_titulo != "") {
                    $titulo .= $valor['dados_td'][$this->key_coluna_complemento_titulo]['valor'];
                }
                if($dv_adicionais >= 1 && $countBox == 0)
                    $html .= "<div class=\"col-md-".$dv_adicionais."\"></div>";

                $html .= "<div class=\"col-md-".$colMd."\">";
                $html .= "<div class=\"card border mb-g\">";
                $html .= "<div class=\"card-header bg-fusion-500 pr-3 d-flex align-items-center flex-wrap\">"; //   <div class=\"panel panel-{$classe}\">";

                if ($cropped)
                    $html .= "      <div class=\"card-title\" data-toggle=\"popover\" data-placement=\"top\" title=\"".mb_strtoupper($valor['dados_td'][$this->key_coluna_nome]['valor'], "UTF-8")."\">{$titulo}</div>";
                else
                    $html .= "      <div class=\"card-title \">{$titulo}</div>";

                $html .= "    </div>";
                $html .= "    <div class=\"card-body \" align=\"center\">";
                $html .= "      <div class=\"row\" style=\"overflow:none\">";
//                echo"<pre>";
//                echo($valor['dados_td'][$this->key_coluna_imagem]['valor']);
                if($this->key_coluna_imagem)
                    $html .= "          <div class=\"col-md-12\" align=\"center\"> {$valor['dados_td'][$this->key_coluna_imagem]['valor']} </div>";
                $html .= "              <div class=\"col-md-12\"> ";
                $html .= "                  <table class=\"table table-user-information\" style='margin: 0 !important;'>";
                $html .= "                      <tbody>";
                foreach ($valor['dados_td'] as $id => $row)
                {
                    if($row["showbox"])
                    {
                        if (strlen($row[valor]) > 19 && $row["tagHtml"] != "true")
                            $row[valor] = substr($row[valor], 0, 19) . "...";
                        $html .= "      <tr>";
                        if ($row["hideLabel"]){
                            $html .= "          <td style='text-align:center' colspan='2'>$row[valor]&nbsp;</td>";
                        } else {
                            $html .= "          <td style='text-align: right; color: #000'><strong>{$this->colunas['dados_th'][$id]['nome']}:</strong></td>";
                            $html .= "          <td style='text-align:left; color: #000'>".($row[valor])."</td>";
                        }
                        $html .= "      </tr>";
                    }
                }
                $html .= "                      </tbody>";
                $html .= "                  </table>";
                $html .= "              </div>";
                $html .= "          </div>";
                // $html .= "    </div>";
                // $html .= "  <div class=\"panel-footer\">";
               //$html .= "<div>$valor[acoes]</div>";
               $html .= " <div class=\"mt-2 mb-2\">";
                if(is_array($row["acoes"])){
                    foreach($row["acoes"] as $botao){
                        $html .= "$botao";
                    }
                }    
               
                if($this->permitir_excluir){
                    $html .= "<a href='javascript:$this->funcao_excluir" . "(" . $valor['dados_td'][$this->key_coluna_id]['valor'] . ")" . "'    class='ico-action excluir  btn btn-danger btn-sm btn-icon  waves-effect waves-themed'    title='".RTL_EXCLUIR."'         data-bs-toggle=\"tooltip\" data-placement='top'>X</a>";
                }
                // $html .= "<a href='javascript:$this->funcao_modificar" . "(" . $valor['dados_td'][$this->key_coluna_id]['valor'] . ")" . "'    class='ico-action editar'    title='".ROTULO_MODIFICAR."'         data-bs-toggle="tooltip" data-placement='top'><i class=\"fa fa-edit\"></i></a>";
                $html .= ' <a class="btn '.$theme.' btn btn-primary btn-sm btn-icon  waves-effect waves-themed"  href="javascript:;"  onclick="' . $this->funcao_modificar . '(' . $row['valor'] . ')" data-placement="top" title="' . $alterarTitle . '" data-original-title="' . $alterarTitle . '" data-toggle="tooltip" ><i class="fas fa-edit"  title="' . ROTULO_MODIFICAR . '"></i></a>' . "\n";

                //$html .= "      <span class=\"box-align\">";
                //$html .= "      <a data-original-title=\"Broadcast Message\" data-toggle=\"tooltip\" type=\"button\" class=\"btn btn-sm btn-primary\"><i class=\"glyphicon glyphicon-envelope\"></i></a>";
                //$html .= "          <a href=\"#\" onclick=\"". $this->funcao_modificar . "(" . $valor['dados_td'][$this->key_coluna_id]['valor'] . ")\" data-original-title=\"".RTL_MODIFICAR."\" data-toggle=\"tooltip\" type=\"button\" class=\"btn btn-sm btn-info\"><i class=\"glyphicon glyphicon-edit\"></i></a>";
                //if($this->permitir_excluir)
                //    $html .= "          <a data-original-title=\"".RTL_EXCLUIR."\" data-toggle=\"tooltip\" onclick=\"". $this->funcao_excluir. "(" . $valor['dados_td'][0]['valor'] . ")\" type=\"button\" class=\"btn btn-sm btn-danger\"><i class=\"glyphicon glyphicon-remove\"></i></a>";
                //$html .= "      </span>";
                $html .= "</div>";
                $html .= "  </div>";
                $html .= "    ";
                $html .= " </div>";
                $html .= "</div>";
                $countBox++;

                if ($countBox == $this->limit_box_linha)
                {
                    if($dv_adicionais >= 1)
                        $html .= "<div class=\"col-md-".$dv_adicionais."\"></div>";
                    $html .= "</div>";


                    $html .= "<div class='row'>";
                    $countBox = 0;
                }

            }
        }
        $html .= "</div>";
        $html .= "</div><p style='clear:both'></p>";
        return $html;

    }
    public function GerarFiltro()
    {
        if (@count($this->colunas) > 0)
        {
            $html = "";
            $html .= "<div class=\"btn-group\" id=\"FiltrosListagemBox\">";
            $html .= "  <button type=\"button\" class=\"btn btn-danger btn-control dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">";
            $html .= "    ".ROTULO_ORDENACAO." <span class=\"caret\"></span>";
            $html .= "  </button>";
            $html .= "  <div class=\"dropdown-menu dropdown-menu-right\">";
            foreach ($this->colunas as $index => $valor)
            {
                if ($index == "dados_th")
                {
                    foreach ($valor as $row)
                    {
                        $html .= " <a href=\"javascript:;\" class=\"dropdown-item \" onclick=\"{$this->funcao_atualizar}('','{$this->busca_valor}','{$row[filtro]}','{$row[tipo]}','$this->tipo');\">$row[nome]</a>";
                    }
                }
            }
            $html .= "  </div>";
            $html .= "</div>";
        }

        return $html;

    }
}
