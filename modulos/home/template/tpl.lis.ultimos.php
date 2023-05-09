<div class="list-group border-active-dark ">
    <?php
    $numeroRegistros = 10;
    $numeroInicioRegistro = $pagina * $numeroRegistros;
    $parametros['status_notin'] = '3,4';
    $objServicos = new Servicos();

    $parametros['corretor'] =  ($_SESSION['usuario']['id_usuario_tipo'] == 4 && $_SESSION['usuario']['master'] != 1) ? $_SESSION['usuario']['id'] :  $_REQUEST['corretor'];

    $listar = $objServicos->ListarPaginacao($_SESSION['usuario']['id_grupo'], $numeroRegistros, $numeroInicioRegistro, $busca, 'id', 'desc',$parametros);
    $x = 0;
    if(@count($listar[0])> 0) {
        foreach ($listar[0] as $linha) {
            $theme = "badge-".ServicosStatus::GetStatus($linha['status']);
            $status = '<span class="badge badge-sm  '.$theme.'" style="font-size:10px">'.$linha['nome_status'].'</span>';
            $valor = ($_SESSION['usuario']['id_usuario_tipo'] != 2 && $_SESSION['usuario']['id_usuario_tipo'] != 3) ? '<strong>Valor: R$</strong> ' . number_format($linha['valor'], 2, ',', '.') : '';
            $valor_servico = ($_SESSION['usuario']['id_usuario_tipo'] != 4 && $_SESSION['usuario']['id_usuario_tipo'] != 3) ? '<strong>Valor de serviço: R$</strong> ' . number_format($linha['valor_fornecedor'], 2, ',', '.') : '';
            $disabled_cancelado = ($linha['status'] == 4) ? 'disabled' : '';
            $nome = ($_SESSION['usuario']['id_usuario_tipo'] == 4) ? "<strong>Fotógrafo(a):</strong> " . $linha['nome_fotografo'] : "<strong>Corretor(a):</strong> " . $linha['nome_corretor'];
            $foto = ($_SESSION['usuario']['id_usuario_tipo'] == 2) ? $linha['foto'] : $linha['foto_corretor'];

            $foto = (file_exists($foto)) ? $foto : 'assets/media/avatars/blank.png';
            $endereco = $linha['endereco'] . ' , ' . $linha['numero'] . ' ' . $linha['bairro'] . ' ' . $linha['cidade'] . ' ' . $linha['estado'];

            echo '<a href="#index_xml.php?app_modulo=servicos&app_comando=visualizar_servico&app_codigo=' . $linha["id"] . '" class="list-group-item list-group-item-action list-group-item-light mb-2" aria-current="true">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1  flex-grow-1 pe-1 text-primary">' . $endereco . '</h5>
                                <small>' . Conexao::PrepararDataPHP($linha["data_prevista"] . " 06:00:00", $_SESSION["usuario"]["id_fuso_horario"], 'd/m/y') . '</small>
                                <small class="text-dark">  &nbsp;<strong>' . substr($linha["hora_prevista"], 0, 5) . '</strong></small>
                            </div>
                            
                            <p class="mb-1">' . $nome . " " . $status . ' </p>
                            <small >' . $valor . '  ' . $valor_servico . '</small>
                        </a>';
        }
    }
    ?>
</div>
