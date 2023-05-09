<?php
/**
 * @author    Squall Robert
 * @copyright 2015
 */
include("modulos/usuario_configuracao/template/tutorial.usuario_configuracao.php");
include("modulos/usuario_configuracao/template/js.usuario_configuracao.php");
if (!strpos($_SERVER['PHP_SELF'], 'index_app'))
{
    ?>
    <header id="header" <?=$bg_funcao?>>
        <section class="header">
            <div class="open-menu"><?=RTL_USUARIO_CONFIGURACAO?></div>
            <h2><?=RTL_USUARIO_CONFIGURACAO?></h2>
            <?php
            include_once("template/usuario.topo.php");
            ?>
        </section><!-- .header -->
    </header><!-- #header -->

<?}?>
<div class="panel with-nav-tabs panel-default">
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#conteudo_usuario_configuracao"  data-toggle="tab"><? echo ROTULO_LISTAGEM ?></a></li>
            <?if (!strpos($_SERVER['PHP_SELF'], 'index_app')){?><li><a id="help_medium" href="#"><span class="fa fa-question"></span></a></li><?}?>
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="conteudo_usuario_configuracao"></div>
        </div>
    </div>
</div>