<?php

class Componente
{

    static private function GetActions()
    {
        return array("onfocus", "onblur", "onclick");
    }

    static public function GerarCombo($registros, $campoData = "id", $campoLabel = "nome", $selecionado = 0, $dataPrimeiro = NULL, $labelPrimeiro = NULL, $selecionados = array(), $desabilitados = array())
    {
        $retorno = "";
        if ($dataPrimeiro !== NULL) {
            $retorno .= "<option value=\"$dataPrimeiro\" id='option_$dataPrimeiro'>$labelPrimeiro</option>";
        }

        $qtd = count($registros);

        for ($i = 0; $i < $qtd; $i++) {
            //$rotulo = iconv("ISO-8859-1", "UTF-8",$registros[$i][$campoLabel]);
            if (is_array($campoLabel)) {
                $rotulo = "";
                foreach ($campoLabel as $campo) {
                    $rotulo .= " - " . $registros[$i][$campo];
                }
                $rotulo = substr($rotulo, 2);
            } else {
                $rotulo = $registros[$i][$campoLabel];
            }
            if ($registros[$i][$campoData] == $selecionado) {
                $selected = "selected = 'selected'";
            } else {
                $selected = "";
            }

            if (is_array($selecionados) && count($selecionados) > 0) {

                if (array_search($registros[$i][$campoData], $selecionados) !== false) {
                    $selected = "selected=\"selected\"";
                } else {
                    $selected = "";
                }
            }
            if (is_array($desabilitados) && count($desabilitados) > 0) {

                if (array_search($registros[$i][$campoData], $desabilitados) !== false) {
                    $disabled = "disabled=\"disabled\"";
                } else {
                    $disabled= "";
                }
            }
            $retorno .= "\n<option value=\"{$registros[$i][$campoData]}\" $selected $disabled id='option_{$registros[$i][$campoData]}'>{$rotulo}</option>";
        }

        return $retorno;
    }

    static public function GerarComboPdo($registros, $getData = "getId", $getLabel = "getNome", $selecionado = 0, $dataPrimeiro = NULL, $labelPrimeiro = NULL, $selecionados = array())
    {
        $retorno = "";

        if ($dataPrimeiro !== NULL) {
            $retorno .= "<option value=\"$dataPrimeiro\">$labelPrimeiro</option>";
        }

        $qtd = count($registros);

        for ($i = 0; $i < $qtd; $i++) {
            //echo "####__".$registros[$i]->$getLabel()."__######";
            if ($registros[$i]->$getData() == $selecionado) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            if (count($selecionados) > 0) {
                if (array_search($registros[$i]->$getData(), $selecionados) !== false) {
                    $selected = "selected=\"selected\"";
                } else {
                    $selected = "";
                }
            }
            $retorno .= "\n<option value=\"" . $registros[$i]->$getData() . "\" $selected>";
            if (defined($registros[$i]->$getLabel())) {
                $retorno .= constant($registros[$i]->$getLabel());
            } else {
                $retorno .= $registros[$i]->$getLabel();
            }
            $retorno .= "</option>";
        }

        return $retorno;
    }

    /**
     * Gerando combo a partir do FetchAlll do PDO
     * $nome = nome do campo
     * $id = id do campo
     * $class =  classe de CSS do campo
     * $Registros = Retorno do FechtAll do PDO
     * $selecionados = Registros que serão marcados como selecionados ex: array(1,5,8)
     * $primeiro = array("","--Selecione--");  VALUE , NOME
     * $campos = array('id','nome') por padrão ele vai tentar pegar id e nome do objeto fetch all
     */

    static public function GerarSelectArray($nome = "selecione", $id = "selecione", $style = "", $registros = array(), $selecionados = array(), $primeiro = array(), $campos = array('id', "nome"), $multiple = true, $class = "select_style ui-multiselect ui-widget ui-state-default ui-corner-all")
    {
        $campo = '';
        $dados = '';
        $campo .= "<select name='" . $nome . "' id='" . $id . "'  class='" . $class . "' style='" . $style . "'";
        if ($multiple) {
            $campo .= " multiple='multiple'";
        }
        $campo .= " class ='" . $class . "'>\n";
        //se existir um item a ser adicionado como primeiro.
        if (count($primeiro) > 0) {
            $campo .= "<option value='$primeiro[0]'>$primeiro[1]</option>\n";
        }

        if (count($registros) > 0) {
            foreach ($registros as $row) {

                $campo .= "<option value='" . $row[$campos[0]] . "'";
                if (array_search($row[$campos[0]], $selecionados) !== false) {
                    $campo .= "selected = 'selected'";
                    //print_r($selecionados);
                }
                $campo .= ">";
                $campo .= $row[$campos[1]] . "</option>\n";
            }
        }
        $campo .= "</select>\n";

        return $campo;
    }


    /**
     * Gerando combo a partir do FetchAlll do PDO
     * $nome = nome do campo
     * $id = id do campo
     * $class =  classe de CSS do campo
     * $Registros = Retorno do FechtAll do PDO
     * $selecionados = Registros que serão marcados como selecionados ex: array(1,5,8)
     * $primeiro = array("","--Selecione--");  VALUE , NOME
     * $campos = array('id','nome') por padrão ele vai tentar pegar id e nome do objeto fetch all (obs.: o nome da
     * coluna da tabela no BD)
     * $outros = caso seu select venha a ter alguma outra função, adicionar ela aqui.
     */

    static public function  GerarSelectPDO($nome = "selecione", $id = "selecione", $style = "", $registros = array(), $selecionados = array(), $primeiro = array(), $campos = array('id', "nome"), $multiple = true, $class = "form-control", $outros = "", $urlBusca = "")
    {
        $registros = json_decode(json_encode($registros), false);
        $campo = '';
        $campo .= "<select data-size=\"6\" data-live-search=\"true\" data-style=\"btn-default\" name='" . $nome . "' id='" . $id . "'  class='" . $class . "' $outros style='" . $style . "'";
        if ($multiple) {
            $campo .= " multiple='multiple'";
        }
        $campo .= " class ='" . $class . "'>\n";
        //se existir um item a ser adicionado como primeiro.
        if (@count($primeiro) > 0) {
            $campo .= "<option value='$primeiro[0]'>$primeiro[1]</option>\n";
        }

        if (@count($registros) > 0) {
            foreach ($registros as $row) {

                $icone = ($row->icone != "") ? "data-icon='$row->icone'":"";
                $subtext = ($row->subtext != "")? "data-subtext='$row->subtext'":"";
                $campoAux = $campos[2] ? $row->{$campos[2]} : "";
                $campo .= "<option $icone $subtext value='" . $row->{$campos[0]} . "' data-value='".$campoAux."'";
                if (@array_search($row->{$campos[0]}, $selecionados) !== false) {
                    $campo .= "selected = 'selected'";
                    //print_r($selecionados);
                }
                $campo .= ">";
                $campo .= $row->{$campos[1]} . "</option>\n";
            }
        }
        $campo .= "</select>\n";
        
        if (!empty($urlBusca)){
            $script = "<script> 
                        $(document).ready(function() {
                            $(".$id.").select2({language: \"pt-BR\"});
                            AutoComplete2 (".$id.", '$urlBusca');
                        });
                        </script>";
        }
        return $campo . $script;
    }

    /**
     * Gerando combo a partir do FetchAlll do PDO para bootstrap
     * @autor Squall Rober (Francisco José)
     *
     * @param $nome         = name do select
     * @param $id           = id do select
     * @param $style        = css adicional
     * @param $registros    = array de arrays de "value" e "text" das options do select
     * @param $selecionados = campos previamente selecionados (array de values dos registros selecionados
     * @param $primeiro     = primeiro campo a ser mostrado no select
     * @param $campos       = atributos do array de objetos que serão utilizados, respectivamente, como "value" e
     *                      "text"
     * @param $multiple     = permite múltipla seleção (utilizar em conjunto com o name com sufixo '[]')
     * @param $class        = classe de css
     * @param $busca        = habilita o campo de busca do multiselect
     * @param $buscaAjax    = desabilita a busca padrão do plugin e a sobrepõe por busca via servidor
     * @param $urlBusca     = url que retorna o echo json_encode de um array de: "label", "title" e "value" para montar
     *                      o select
     * @param $idCampoBusca = "id" do campo de busca do multiselect --> usar quando mais de um componente for criado na
     *                      mesma página
     * @param $tamanho      = tamanho do botão em %
     *
     * @return string = html do select
     */

    static public function GerarComboOptGroup($registros, $id = "id", $nome = "nome", $selecionado = 0, $dataPrimeiro = null, $labelPrimeiro = null, $selecionados = array(),$ignorar = "",$class = "",$style = "")
    {
        if (!empty($dataPrimeiro))
            $campo .= "<option value=\"$dataPrimeiro\">$labelPrimeiro</option>";
        $x = 0;
        foreach($registros as $indice)
        {
            $selected = ($selecionado == $indice['id'] ? "selected" : "");
            $opt_group = (count($indice['filhos']) > 0 ? "<optgroup label=\"".$indice['nome']."\">" : "");
            $campo .= $opt_group;
            $campo .= "<option value=\"".$indice['id']."\" $selected >".$indice['nome']."</option>";
            if(count($indice['filhos']) > 0 ) {
                $campo .= Componente::GerarComboOptGroup($indice['filhos'],'','',$selecionado,'','','','','','');
                $campo .= "</optgroup>";
            }

            $x++;
        }
        return $campo;
    }
    
    static public function GerarSelectBootstrap($nome = "selecione", $id = "selecione", $style = "", $registros = array(), $selecionados = array(), $primeiro = array(), $campos = array('id', "nome"), $multiple = true, $class = "form-control", $busca = true, $buscaAjax = false, $urlBusca = "", $idCampoBusca = "", $tamanho = "100%", $onchange = false)
    {
        $registros = json_decode(json_encode($registros), false);
        $campo = '';
        $campo .= "<select name='" . $nome . "' id='" . $id . "'  class='" . $class . "' size='2' style='" . $style . "'";
        if ($multiple) {
            $campo .= " multiple='multiple'";
        }
        if ($onchange){
            $campo .= $onchange;
        }
        if ($idCampoBusca == "") {
            $idCampoBusca = md5($id);
        }

		//cria um hash md5 do id do multiselect, adiciona "fn" ao fim e remove qualquer caractere numérico
		//Isso é necessário para utilizar vários searchs de ajax na mesma tela
        $nomeFuncaoRebuild = preg_replace('/[0-9]/',"",md5($id))."fn";


        $campo .= " class ='" . $class . "'>\n";
        //se existir um item a ser adicionado como primeiro.
        if (count($primeiro) > 0) {
            $campo .= "<option disabled value='$primeiro[0]'>$primeiro[1]</option>\n";
        } else {
            if ($multiple == false){
                $campo .= "<option value=' '>- - ".ROTULO_LIMPAR_SELECAO." - -</option>\n";
            }

        }

        if (count($registros) > 0) {
            foreach ($registros as $row) {
                $campo .= "<option value='" . $row->{$campos[0]} . "'";
                if (array_search($row->{$campos[0]}, $selecionados) !== false) {
                    $campo .= "selected = 'selected'";
                    //print_r($selecionados);
                }
                $campo .= ">";

				$auxTexto = (defined($row->{$campos[1]}))?constant($row->{$campos[1]}):$row->{$campos[1]};
                $campo .= $auxTexto . "</option>\n";
            }
        }
        $campo .= "</select>\n";

        $script = "
        <script>
                $('#$id').multiselect({
                buttonWidth: '$tamanho',
                nonSelectedText: '" . ROTULO_NENHUM_SELECIONADO . "',
                nSelectedText: '" . ROTULO_SELECIONADOS . "',
                allSelectedText:'" . ROTULO_TODOS . "',
                searchInputId:'" . $idCampoBusca . "',
                filterPlaceholder:'" . ROTULO_BUSCAR . "',
                selectAllText:'" . ROTULO_SELECIONAR_TODOS . "'";

        if ($multiple) {
            $script .= ",checkboxName: '$id" . '[]' . "' ";
        }
        if (!$busca) {
            $script .= ",enableFiltering: false, enableCaseInsensitiveFiltering: false";
        }
        if ($buscaAjax) {
            $script .= ",ajaxSearch: true";
        } //desabilita a busca padrão do plugin

        $script .= "
            });

			//seleciona a primeira opcao por padrao
            //$('#$id').multiselect('select', {$registros[0]->$campos[0]});";

        if ($buscaAjax && $urlBusca != "") {
            $script .= "

            //Muda o ID do campo de busca para o parâmetro da função
            //$('#multiselectSearch').attr('id', '$idCampoBusca');

            //Tem o mesmo efeito de resetar o timeout a cada keyup. Executa a função depois de 1500ms do último input
            $('#$idCampoBusca').keyup(_.debounce($nomeFuncaoRebuild , 1500));

            function $nomeFuncaoRebuild()
            {
                $.post('$urlBusca',
                {
                    busca: $('#$idCampoBusca').val()
                },
                function(response)
                {
                    //Builda o select a partir de um array de objetos em JSON
                    $('#$id').multiselect('dataprovider', response);
                    $('#$id').multiselect('rebuild');

                    //Como o multiselect inteiro é reconstruído, o campo volta a ser o ID padrão, precisando ser mudado e ter o evento bindado novamente
                    $('#$idCampoBusca').off().keyup(_.debounce($nomeFuncaoRebuild , 1500));
                }
                , 'json'
                );
            }

            //Remove o form submit ao apertar enter no campo de busca
            $(':input').on('keyup keypress', function(e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });";
        }

        $script .= "</script>";

        return $campo . $script;
    }

    /**
     *  $nome = nome do campo
     *  $id = id do campo sem #
     *  $class = class de CSS do campo
     *  $style = style do campo
     *  $multiple = define se o multiselect vai ser multiplo ou não
     *  $app_modulo = modulo com case (atualizar_multiselect) em caso de busca
     *  $busca = busca ou filtro, EM CASO DE BUSCA ADICIONE A CONSULTA NO MODULO COM O NOME:
     *  ATUALIZAR_MULTISELECT_{ID_CAMPO} EX: ATUALIZAR_MULTISELECT_MOTIVOS
     *  $registros = em caso de filtro passe os valores do multiselect EM FORMATO DE PDO
     *  $selecionados = Registros que serão marcados como selecionados ex: array(1,5,8)
     *  $campos = array('id','nome') por padrão ele vai tentar pegar id e nome do objeto fetch all
     *  $opcionais = comandos opcionais como ex: selectedList: e etc.
     */
    static public function GerarJqueryMultiselect($nome, $id = "", $class, $style, $multiple, $app_modulo, $busca, $registros = array(), $selecionados = array(), $campos = array('id', 'nome'), $opcionais = "")
    {
        $campo  = '';
        $update = '';
        $timer  = "";
        $dados  = "";
        $label  = 'Filtro: ';
        $campo .= "<select name='" . $nome . "' id='" . $id . "'  class='" . $class . "' style='" . $style . "'";
        $campo .= " multiple='multiple'";
        $campo .= " class ='" . $class . "'>";
        $campo .= "</select>\n";
        $filtro = '';

        // Declarando timer
        if ($busca) {
            $timer  = "
            var dados = '';
            var selecionados = '';
            var timer = 
            {
                remind: function(event) 
                {
                    selecionados = $('#" . $id . "');
                    BuscarDadosMultiselect(dados,'" . $app_modulo . "',selecionados,'" . $id . "','" . $campos[0] . "','" . $campos[1] . "');
                    delete this.timeoutID;
                },
        
                setup: function(event) 
                {
                    this.cancel();
                    var self = this;
                    this.timeoutID = window.setTimeout(function(event){self.remind(event);}, 1000);
                },
        
                cancel: function() 
                {
                    if(typeof this.timeoutID == 'number') 
                    {
                        window.clearTimeout(this.timeoutID);
                        delete this.timeoutID;
                    }
                }
            };";
            $update = " $('#" . $id . "').multiselectfilter('setUpdatable');";
            $label  = 'Busca: ';
            $filtro = "
                    if(event.hasOwnProperty('currentTarget'))
                    {
                        if(event.currentTarget.value != dados)
                        {
                            dados = event.currentTarget.value;
                            timer.setup(dados); 
                        }
                    }
                ";
        }
        if (count($registros) > 0) {
            $opt = "";
            // IsObject ou isArray
            if (is_object($registros[0])) {
                foreach ($registros as $row) {
                    $selected = "";
                    if (array_search($row->$campos[0], $selecionados) !== false) {
                        $selected = "selected: 'selected',
                             ";
                    }
                    $opt = "$('<option />',
                        {
                            
                            value: '" . $row->$campos[0] . "',
                            " . $selected . "
                            text: '" . addslashes($row->$campos[1]) . "'
                            })
                        ";
                    $dados .= "$opt.appendTo(select);";
                }
            } else {
                foreach ($registros as $row) {
                    $selected = "";
                    if (array_search($row[$campos[0]], $selecionados) !== false) {
                        $selected = "selected: 'selected',
                             ";
                    }
                    $opt = "$('<option />',
                        {
                            
                            value: '" . $row[$campos[0]] . "',
                            " . $selected . "
                            text: '" . addslashes($row[$campos[1]]) . "'
                            })
                        ";
                    $dados .= "$opt.appendTo(select);";
                }
            }


            $dados .= "select.multiselect('refresh');";
        }
        // Declarando o multiselect jQuery
        if ($opcionais != "") {
            $opcionais = $opcionais . ',';
        }

        $multiselect = "
        <script type='text/javascript'>
        $(document).ready(function()
        {
        $timer   
        var select = $('#" . $id . "').multiselect({
            $opcionais
            multiple: '$multiple'
            });
            select.multiselectfilter(
            {
                label: '$label ',
                filter: function(event, matches)
                {
                    $filtro
                }
            });
        $dados
        $update
        });
        </script>
        ";

        return $campo . $multiselect;
    }

    static public function GerarCheckbox($nome, $registros, $campoData = "id", $campoLabel = "nome", $selecionado = 0, $dataPrimeiro = NULL, $labelPrimeiro = NULL)
    {
        $retorno = "";

        if ($dataPrimeiro != NULL) {
            $retorno .= "<input type=\"checkbox\" id=\"$nome\" name=\"$nome\" value=\"$dataPrimeiro\" />$labelPrimeiro";
        }

        $qtd = count($registros);

        for ($i = 0; $i < $qtd; $i++) {
            if ($registros[$i][$campoData] == $selecionado) {
                $selected = "checked";
            } else {
                $selected = "";
            }

            $actions = "";
            foreach (Componente::GetActions() as $action) {
                if ($registros[$i][$action] != "") {
                    $actions .= "$action=\"" . $registros[$i][$action] . ";\"";
                }
            }

            $retorno .= "\n<input type=\"checkbox\" id=\"$nome\" name=\"$nome\" value=\"{$registros[$i][$campoData]}\" $selected $actions />{$registros[$i][$campoLabel]}";
        }

        return $retorno;
    }

    /**
     * Gerando select a partir do FetchAlll do PDO
     * $nome = nome e id do campo
     * $multiple =  define se o campo vai ser de multipla escolha ou não
     * $Registros = Retorno do FechtAll do PDO
     * $selecionados = Registros que serão marcados como selecionados ex: array(1,5,8)
     * $primeiro = array("","--Selecione--");  VALUE , NOME
     * $campos = array('id','nome') por padrão ele vai tentar pegar id e nome do objeto fetch all
     */


    static public function GerarRadio($nome, $registros, $campoData = "id", $campoLabel = "nome", $selecionado = 0, $dataPrimeiro = NULL, $labelPrimeiro = NULL)
    {
        $retorno = "";

        if ($dataPrimeiro != NULL) {
            $retorno .= "<input type=\"radio\" id=\"$nome\" name=\"$nome\" value=\"$dataPrimeiro\" />$labelPrimeiro";
        }

        $qtd = count($registros);

        for ($i = 0; $i < $qtd; $i++) {
            if ($registros[$i][$campoData] == $selecionado) {
                $selected = "checked";
            } else {
                $selected = "";
            }

            $actions = "";
            foreach (Componente::GetActions() as $action) {
                if ($registros[$i][$action] != "") {
                    $actions .= "$action=\"" . $registros[$i][$action] . ";\"";
                }
            }

            $retorno .= "\n<input type=\"radio\" id=\"$nome\" name=\"$nome\" value=\"{$registros[$i][$campoData]}\" $selected $actions /> {$registros[$i][$campoLabel]}";
        }

        return $retorno;
    }

    static public function CortarTexto($texto)
    {
        if (strlen($texto) > 15) {
            return substr($texto, 0, 13) . "...";
        }

        return $texto;
    }

    /**
     * Gera um componente de lista dupla com request de ajax
     * @autor Squall Rober (Francisco José)
     *
     * @param string    $nomeSelect         atributo name do <select>
     * @param string    $idSelect           atributo id do <select>
     * @param string    $idBusca            atributo id do <input type="text"> de busca
     * @param string    $textoPlaceholder   texto do atributo placeholder do input de busca
     * @param string    $url                url para request Ajax -- o parâmetro para recuperar o parâmetro de busca é $_GET['buscar']
     * @param array     $selecionados       Array de itens pré selecionados. Deve conter id e rotulo
     * @param boolean   $validarSelecao     Adiciona classe html para ser validada pelo ValidateForm()
     *
     * @return string
     * */
    static public function GerarListaDuplaBootstrap($nomeSelect, $idSelect, $idBusca, $textoPlaceholder, $url, $selecionados = Array(), $validarSelecao = false, $dynamic = false)
    {
        $html   = "";
        $script = "";

        //print_r($selecionados);

        //cria um hash md5 do id do multiselect, adiciona "fn" ao fim e remove qualquer caractere numérico
        //Isso é necessário para utilizar vários searchs de ajax na mesma tela
        $fnSelectAll = preg_replace('/[0-9]/',"",md5($idSelect))."sAll";
        $fnDeselectAll = preg_replace('/[0-9]/',"",md5($idSelect))."dsAll";

        //Cria o campo de busca com placeholder e ícone de busca
        $html .= "
            <div class='input-group'>
                <input id='$idBusca' type='text' class='form-control' placeholder='$textoPlaceholder' aria-label='$textoPlaceholder' aria-describedby='basic-addon2'>               
                <div class='input-group-append'>
                    <span class='input-group-text' ><i class='fal fa-search'></i></span>
                </div>
            </div>
            <br/>
            <div class='col-md-12 text-center' style='margin-right: 13px'>
                <a class='btn btn-danger-500 bg-danger-500 text-white' onclick='{$fnDeselectAll}()'><i class='fal fa-times'></i> Desselecionar todos</a>
                <a class='btn btn-success-500 bg-success-500 text-white' onclick='{$fnSelectAll}()'><i class='fal fa-check'></i> Selecionar Todos</a>
            </div>
            <br/>
            <div class='row col-md-12' >
                <div class='col-md-6 ' >
                    <label style='margin-left: 35%' >Disponiveis <span id='{$idSelect}_disponiveis'></span></label>                     
                </div>
                <div class='col-md-6' >
                    <label style='margin-left: 53%'>Selecionados <span id='{$idSelect}_selecionados'></span></label>
                </div>
            </div>";

        //Classe de valicação para duallist - utilizar ValidateForm()
        ($validarSelecao)
            ? $classValidacao = 'validar-duallist'
            : $classValidacao = '';

        //Criação do select
        $html .= "<select name='$nomeSelect' id='$idSelect' multiple class='$classValidacao'>";
        if (is_array($selecionados) && @count($selecionados) > 0) {
            foreach ($selecionados as $option) {
                if (is_object($option)) {
                    $option = (array)$option;
                }

                $html .= "<option value='{$option['id']}'>{$option['rotulo']}</option>";
            }
        }
        $html .= "</select>";

        if (!isset($_SESSION['quantidade_veiculos']) || $dynamic) {
            $scriptDynamic = "dynamic: true,";
        } else {
            $scriptDynamic = "dynamic: true, generateOnLoad: true, ";
        }
        //Javascript dos componentes
        $script .= "
        <script>
			//Cria a double list e seleciona todos os itens que foram criados no select (itens pré listados que vieram do array selecionados)
			$('#$idSelect').dropInSelect();
			$('#$idSelect').dropInSelect('select_all');
			//Cria o campo de autocomplete para preenchimento das listas
			$('#$idBusca').typeahead_updated({
				minLength: 0,           //Mínimo de caracteres para iniciar a busca
				order: 'asc',           //Ordenação
				display: ['rotulo'],    //Campo da pesquisa a ser mostrado no dropdown
				accent: true,           //Ignora acentos: é = e, á = a
				cache: false,           //Mantém cache da pesquisa -- Se dynamic: true, cache é forçado para false
				{$scriptDynamic}
				delay: 1000,            //Delay entre o último input e a pesquisa
				maxItem: 1000,           //Limit de itens a serem mostrados
				source: {               //URL que contém o echo json_encode dos objetos
					url: ['" . $url . "']
				},
				callback: {             //Funções de callback, vide documentação
					onResult: function (node, query, result, resultCount) {
						var selectedLabel = []; //Textos dos options selecionados
						var selectedValue = []; //Valores dos options selecionados
						//Busca todos os elementos que estão do lado direito das listas
						$('#ms-$idSelect .ms-selection .ms-elem-selection').each(function()
						{
							//Pega os elementos selecionados.
							//O plugin gera todos os options de ambos os lados, escondendo do lado esquerdo os selecionados e do lado direito os não selecionados
							//A classe do selector é aplicada a todos os elementos da direita, e os visíveis são os selecionados
							if ($(this).is(':visible'))
								selectedLabel.push($(this).text());
						});

						//Procura nos elementos do select os elementos selecionados no plugin, filtrados acima e salva seus valores
						//Isso é feito em dois passos pois o plugin armazena apenas o rótulo, e não o valor
						$('#$idSelect option').each(function()
						{
							if ($.inArray($(this).text(), selectedLabel) >= 0)
							{
								selectedValue.push($(this).attr('value'));
							}
						});

						//Remove todas as opcoes do select para ser remontado com a nova pesquisa
						$('#$idSelect option').remove().end();

						//Para cada resultado, adiciona o mesmo ao select, ignora caso ele exista no vetor de selecionados
						$.each(result, function(key, value) {
							if ($.inArray(value.id, selectedValue) < 0)
							{
								$('#$idSelect')
								.append($('<option></option>')
									.attr('value',value.id)
									.text(value.rotulo));
							}
						});

						//Adiciona os elementos selecionados antes do <select> ser limpo
						for (var x = 0; x < selectedValue.length; x++)
						{
							$('#$idSelect')
							.append($('<option></option>')
								.attr('value',selectedValue[x])
								.text(selectedLabel[x]));
						}

						$('#$idSelect').dropInSelect('refresh');                //Recria o select
						$('#$idSelect').dropInSelect('deselect_all');           //Deseleciona todos os elementos por segurança (workaround de um bug do plugin)
						$('#$idSelect').dropInSelect('select', selectedValue);  //Seleciona manualmente os elementos salvos no vetor antes da pesquisa
						AtualizarContadorDualList$idSelect();
						$('#ms-$idSelect .ms-selectable .ms-list .ms-elem-selectable, #ms-$idSelect .ms-selection .ms-list .ms-selected').click(AtualizarContadorDualList$idSelect);
					}
				}
			});

			$('#$idBusca').focus();
			$('#$idBusca').trigger('input.typeahead');
            
			function {$fnSelectAll}()
			{
				$('#$idSelect').dropInSelect('select_all');
				setTimeout(function(){
					AtualizarContadorDualList$idSelect();
				}, 50);
			}

			function {$fnDeselectAll}()
			{
				$('#$idSelect').dropInSelect('deselect_all');
				setTimeout(function(){
					AtualizarContadorDualList$idSelect();
				}, 50);
			}
			function AtualizarContadorDualList$idSelect()
			{
				setTimeout(function() {
					$('#{$idSelect}_selecionados').text('(' + $('#ms-$idSelect .ms-selection .ms-list .ms-selected:visible').length + ')');
					$('#{$idSelect}_disponiveis').text('(' + $('#ms-$idSelect .ms-selectable .ms-list .ms-elem-selectable:visible').length + ')');
					$('#ms-$idSelect .ms-selectable .ms-list .ms-elem-selectable, #ms-$idSelect .ms-selection .ms-list .ms-selected').off().click(AtualizarContadorDualList$idSelect)
				}, 10);
			}
			
        </script>";

        return $html . $script;
    }


    /**
     * Mostra/esconde as colunas e linhas de acordo com a configuração do usuário
     *
     * @autor Squall Rober (Francisco José)
     *
     * @param array   &$colunas     = Referência das colunas do relatório
     * @param array   &$linhas      = Referência das linhas do relatório
     * @param array   $configuracao = Array de configuração das colunas do relatório
     * @param boolean $existePadrao = flag sinalizando que existem colunas que são desativadas por padrão
     *
     * @return void
     */
    static public function FiltrarRelatorioConfiguracao(&$colunas, &$linhas, $configuracao, $existePadrao = false)
    {
        if ((!is_array($configuracao) || count($configuracao) <= 0) && !$existePadrao)
        {
            return null;
        }
        else if (!is_array($configuracao))
        {
            $configuracao = Array();
        }

        $index        = 0;
        $countColunas = count($colunas);
        $countLinhas  = count($linhas);

        if(count($colunas)>0)
        {
            foreach ($colunas as &$coluna)
            {
                foreach ($coluna as $dadosColuna)
                {
                    //Busca o campo filtro nas configurações
                    $configurado = array_key_exists($dadosColuna['configuracao'], $configuracao);
                    //Coluna/Células serão deletadas apenas se o usuário já configurou a tela OU se existir padrão de configuração
                    if ($configurado == false && (count($configuracao) > 1 || $dadosColuna['padrao_configuracao'] == "desativado") && $dadosColuna['padrao_configuracao'] != "ativado") {

                        //Se não encontrado, elimina a coluna
                        unset($colunas['dados_th'][$index]);

                        $x = 0;
                        while ($x < $countLinhas) {
                            if ($linhas[$x]['dados_td'][$index]['colspan'] != "") {
                                $x += $countColunas;
                                continue;
                            }

                            //Elimina todas as celulas correspondentes à coluna
                            unset($linhas[$x]['dados_td'][$index]);
                            $x += $countColunas;
                        }
                    }
                    $index++;
                }
            }
        }

    }


    //Componente::GerarSelectAutoCompleteVeiculo($_SESSION['quantidade_veiculos'], $_SESSION['usuario']['id_grupo'], Array($_REQUEST['id_veiculo']));
    /**
     * Gera um campo de autocomplete ou um select, de acordo com a quantidade de veículos do usuário
     * @autor Squall Rober (Francisco José)
     *
     * @param int     $qtdVeiculos           Quantidade de veículos do usuario
     * @param int     $idGrupo               Grupo do usuário logado
     * @param array   $selecionado           Campo selecionado do select
     * @param boolean $obrigatorio           printa um * caso o campo seja obrigatório para gerar o relatório
     * @param string  $complementoUrl        complemento da url do autocomplete
     * @param string  $targetAutocomplete    id do campo para adicionar o autocomplete
     * @param string  $resultadoAutocomplete id do campo para onde vai o valor selecionado
     * @param string  $filtro					define campo adicional para filtro no banco Ex: veiculos que pertecem ao grupo , no caso o grupo esta no campo anteriror ao veiculos.
     *
     * @return string $html.$script         Concatenação do html com javascript para gerar os campos
     * */

    static public function GerarSelectAutoCompleteVeiculo($qtdVeiculos, $idGrupo, $selecionado, $obrigatorio = false, $complementoUrl = "", $targetAutocomplete = "nome_veiculo", $resultadoAutocomplete = "id_veiculo", $rotuloVeiculo = "", $resultadoGrupo="")
    {
        if ($qtdVeiculos > 0 === false) {
            if ($obrigatorio) {
                $obrigatorio = "*";
                $classObrigatorio = "validar-obrigatorio";
            } else {
                $obrigatorio = "";
                $classObrigatorio = "";
            }


            $html = '
			<label for="nome_veiculo">' . $obrigatorio . ' ' . ROTULO_VEICULO . '</label>
			<div class="input-group typeahead-container">
				<input type="text" id="' . $targetAutocomplete . '" name="' . $targetAutocomplete . '" class="form-control" value="' . $rotuloVeiculo . '" placeholder="' . ROTULO_PLACEHOLDER_BUSCA . '">
				<input type="hidden" id="' . $resultadoAutocomplete . '" name="' . $resultadoAutocomplete . '" value="' . $selecionado . '" class="'.$classObrigatorio.'">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-search"></span>
				</span>
			</div>';

            $script = "
			<script>
					AutoComplete ('#nome_grupo','#id_grupo','index_xml.php?app_modulo=grupo&app_comando=popup_localizar_grupo');
					AutoComplete ('#$targetAutocomplete','#$resultadoAutocomplete','index_xml.php?app_modulo=veiculo&app_comando=auto_complete_veiculo" . $complementoUrl . "','$resultadoGrupo');
                    $('#nome_grupo').change(function()
                    {
                        if ($(this).val() == '') {
                            $('#id_grupo').val('');
                        }
                    });
			</script>";
        } else {
            if ($obrigatorio) {
                $classObrigatorio = "validar-obrigatorio";
            } else {
                $classObrigatorio = "";
            }

            $veiculo = new Veiculo();
            $result  = $veiculo->ListarVeiculosSelect($idGrupo);
            $html    = '<label for="id_veiculo">' . ROTULO_VEICULO . '</label>';
            $html .= Componente::GerarSelectPDO("$resultadoAutocomplete", "$resultadoAutocomplete", "", $result, Array($selecionado), array("", ROTULO_SELECIONE), array("id", "nome_veiculo"), false, "form-control $classObrigatorio");
            $script = "
			<script>
					AutoComplete ('#nome_grupo','#id_grupo','index_xml.php?app_modulo=grupo&app_comando=popup_localizar_grupo');
			</script>";
        }

        return $html . $script;
    }
    static public function FiltroHolfding()
    {

    }

    static public function GerarSelectGroupContaPagarReceber($registros){

        $retorno = '';
        foreach ($registros AS $pai){
            if ($pai['filhos'] !=''){
                $retorno .= "\n<optgroup label=\"{$pai['nome']}\">";
                foreach ($pai['filhos'] AS $filho){
                    if ($filho['filhos'] != ""){
                        $retorno .= "\n<optgroup label=\"{$filho['nome']}\">";
                        foreach ($filho['filhos'] AS $ff){
                            $retorno .= "\n<option value=\"{$ff['id']}\" >{$ff['nome']}</option>";
                        }

                    }else{
                        $retorno .= "\n<option value=\"{$filho['id']}\" >{$filho['nome']}</option>";
                    }
                }
                $retorno.= "</optgroup>";
            }else{
                $retorno .= "\n<option value=\"{$pai['id']}\" >{$pai['nome']}</option>";
            }
        }
        return $retorno;

    }
    static public function GerarBotao($param)
    {
        $tamanho_style = ($param['tamanho']) ? '--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .60rem;' : '';
        $html = '';
        $html .= "<a ";
        $html .= ($param['href'] != "") ? ' href="'.$param['href'].'"' : 'href="#"';
        $html .= ($param['onclick'] != "") ? ' onclick="'.$param['onclick'].'"' : '';
        $html .= ($param['target'] != "") ? ' target="'.$param['target'].'"' : '';
        $html .= ($param['style'] != "") ? ' style="'.$param['style']." ;".$tamanho_style.'"' : ' style="'.$tamanho_style.'"';
        $html .= ($param['id'] != "") ? ' id="'.$param['id'].'"' : '';
        $html .= ($param['data-placement'] != "") ? ' data-placement="'.$param['data-placement'].'"' : ' data-placement="top"';
        $html .= ($param['title'] != "") ? ' data-original-title="'.$param['title'].'"' : '';
        $html .= ($param['title'] != "") ? 'title="'.$param['title'].'"' : '';
        $html .= ($param['data-bs-toggle'] != "") ? ' data-bs-toggle="'.$param['data-bs-toggle'].'"' : 'data-bs-toggle="tooltip"';
        $html .= ($param['class'] != "") ? 'class="'.$param['class'].'"' : '';
        $html .= ">";
        $html .= "<i ";
        $html .= ($param['icon_class'] != "") ? 'class="'.$param['icon_class'].'"' : '';
        $html .= ($param['title'] != "") ? 'title="'.$param['title'].'"' : '';
        $html .= ">";
        $html .= "</i>";
        $html .= ($param['texto'] != "") ? " ".$param['texto'] : '';
        $html .= "</a>";

        return $html;
    }
}
