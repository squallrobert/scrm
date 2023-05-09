<?php
$objApp = new App();
$configTitulo['titulo_agrupamento_modulo'] = "Configurações";
$configTitulo['titulo_modulo'] = "Agenda de serviços";
echo $objApp->GerarBreadCrumb($configTitulo);

//$parametros['fotografo'] = ($_SESSION['usuario']['id_usuario_tipo'] == 2) ? $_SESSION['usuario']['id'] : $_REQUEST['fotografo'];

if($_SESSION['usuario']['id_usuario_tipo'] == 2)
    $campo = '<input name="fotografo" id="fotografo" type="hidden" value="'.$_SESSION['usuario']['id'] .'">';
?>
    <form name="frm-calendario" id="frm-calendario">
        <input name="data_hora_inicio" id="data_hora_inicio" type="hidden" value="">
        <input name="data_hora_fim" id="data_hora_fim" type="hidden" value="">
        <input type="hidden" name="retomar_filtro" id="retomar_filtro" value="<?=$_REQUEST['retomar_filtro']?>">
        <input type="hidden" name="numero_registro_hidden" id="numero_registro_hidden" value="">
        <?php
        echo $campo;
        ?>
        <div id="kt_app_content_container" class="app-container  p-0">
            <div class="card shadow-sm">
                <div class="card-body" id="filtro">
                    <div class="row">

                        <?php
                        if($_SESSION['usuario']['id_usuario_tipo'] != 2)
                        {
                            echo '     <div class="col-md-7  mb-2">
                            <div class="form-group">
                                <label class="form-label" for="nome">Fotógrafo</label>
                                <select name="fotografo" id="fotografo"  class=" form-select" data-placeholder="Todos os Fotógrafos" data-validar="select2"  >
                                </select>
                            </div>
                        </div>';
                        }
                        ?>

                        <div class="col-md-4 mb-2  ">
                            <label class="form-label" for="status">Status</label>
                            <?
                            $status      = new ServicosStatus();
                            $registros = $status->ListarCombo();
                            echo Componente::GerarSelectPDO("status", "status", "", $registros, array($_REQUEST['status']), array('','Todos os Status'), array("id", "nome"), false, 'form-select  m-b-20 m-r-10','');
                            ?>
                        </div>
                        <div class="form-search col-md-10" style=" margin-top: 0;margin-bottom: 0;">
                            <label class="form-label" for="nome">Buscar por:</label>
                            <div class="input-group ">
                                <input type="text" value="" class="form-control" id="busca" name="busca" placeholder="Busca por: Nome, grupo ou usuário">
                                <span class="input-group-text cursor-pointer"  onclick="AtualizarGridServicos(0,'');"><i class="fas fa-search"></i> </span>
                            </div><!-- .form-group -->
                        </div>
                        <div class="col-md-2 pt-10">
                            <button type="button" class="btn btn-sm btn-success ms-3"  id="bt_filtrar" onclick="GerarCalendario();"> <i class="fas fa-filter"></i> Filtrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"> Agenda de serviços</h3>
                    <div class="card-toolbar">
                        <a href='#index_xml.php?app_modulo=dropbox_settings&app_comando=listar_dropbox_settings'  class="btn btn-sm btn-light "> <i class="fas fa-arrow-circle-left"></i> Voltar para listagem</a>
                    </div>
                </div>
                <div class="card-body" id="formulario_dropbox_settings">

                    <!--begin::Fullcalendar-->
                    <div id="kt_docs_fullcalendar_locales" class="mb-10"></div>
                    <!--end::Fullcalendar-->
                </div>
            </div>
        </div>
    </form>
<?php
include_once('modulos/calendario/template/js.calendario.php');

