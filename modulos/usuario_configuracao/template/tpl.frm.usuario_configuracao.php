<?
include_once("modulos/usuario_configuracao/template/tutorial.usuario_configuracao.php");
include_once("modulos/usuario_configuracao/template/js.frm.usuario_configuracao.php");
?>
<input type="hidden" id="flag_primeiro_sms"/>
<form action="#" method="post" name="log" id="log">
    <div class="panel with-nav-tabs panel-default" id="div_usuario_config">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <?if ($_SESSION['id_franquia'] != "") {?>
                	<li class="active"><a id="aba_sms" href="#conteudo_sms" data-toggle="tab"><? echo RTL_SMS ?></a></li>
					<li><a id="aba_prioridade" href="#conteudo_prioridade" data-toggle="tab"><? echo RTL_PRIORIDADE_EVENTO ?></a></li>
					<li><a id="aba_comando" href="#conteudo_comandos" data-toggle="tab"><? echo RTL_COMANDOS ?></a></li>
                <?} else {?>
					<li class="active"><a id="aba_prioridade" href="#conteudo_prioridade" data-toggle="tab"><? echo RTL_PRIORIDADE_EVENTO ?></a></li>
				<?}?>
                <li><a id="aba_centroid" href="#conteudo_centroid" data-toggle="tab" onclick="ReloadMapa()"><? echo RTL_CENTROID ?></a></li>
                <li><a id="aba_centroid" href="#conteudo_relatorio_email" data-toggle="tab" onclick="ReloadMapa()"><? echo RTL_RELATORIOS_EMAIL ?></a></li>
                <li><a id="aba_centroid" href="#conteudo_relatorios" data-toggle="tab" onclick="ReloadMapa()"><? echo RTL_RELATORIOS ?></a></li>
				<?if (!strpos($_SERVER['PHP_SELF'], 'index_app')){?><li><a id="help_medium" href="#"><span class="fa fa-question"></span></a></li><?}?>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
				<?if ($_SESSION['id_franquia'] != "") {?>
					<div class="tab-pane fade listagem-interna in active" id="conteudo_sms">
						<? include("tpl.sms.usuario_configuracao.php") ?>
					</div>
					<div class="tab-pane fade listagem-interna" id="conteudo_prioridade">
						<? include("tpl.prioridade.usuario_configuracao.php") ?>
					</div>
					<div class="tab-pane fade listagem-interna" id="conteudo_comandos">
						<? include("tpl.comandos.usuario_configuracao.php") ?>
					</div>
				<?} else {?>
					<div class="tab-pane fade listagem-interna in active" id="conteudo_prioridade">
						<? include("tpl.prioridade.usuario_configuracao.php") ?>
					</div>
				<?}?>

                <div class="tab-pane fade" id="conteudo_centroid">
                    <? include("tpl.centroid.usuario_configuracao.php") ?>
                </div>
				<div class="tab-pane fade" id="conteudo_relatorio_email">
                    <? include("tpl.relatorio_email.usuario_configuracao.php") ?>
                </div>
                <div class="tab-pane fade" id="conteudo_relatorios">
                    <? include("tpl.relatorios.usuario_configuracao.php") ?>
                </div>
            </div>
        </div>
    </div>
</form>
