<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 04/10/2021
 * Time: 08:06
 */
?>
<script type="text/javascript">
    $(function()
    {
        //LoadDiv("#conteudo_configuracao_campos");
        AtualizarGridConfiguracaoCampos(0,"");
        AtualizarGridConfiguracaoRelatoriosCampos(0,"");
    });

    function AtualizarGridConfiguracaoCampos(pagina,busca,filtro,ordem)
    {
        var registros = $('#numero_registros').val();
        if(filtro == "" || filtro === undefined)  filtro = "";
        if(ordem == "" || ordem  === undefined)  ordem = "";

        var toPost = {
        pagina: pagina,
			busca: busca,
			filtro: filtro,
			ordem: ordem,
            numero_registros:registros
		};
        $("#conteudo_configuracao_campos").load("ajax/configuracao_modulos/ajax_listar_configuracao_campos/", toPost);
    }
    function AtualizarGridConfiguracaoRelatoriosCampos(pagina,busca,filtro,ordem)
    {
        var registros = $('#numero_registros').val();
        if(filtro == "" || filtro === undefined)  filtro = "";
        if(ordem == "" || ordem  === undefined)  ordem = "";

        var toPost = {
        pagina: pagina,
			busca: busca,
			filtro: filtro,
			ordem: ordem,
            numero_registros:registros
		};

        $("#conteudo_configuracao_relatorios_campos").load("ajax/configuracao_modulos/ajax_listar_configuracao_modulos/", toPost);
    }

</script>
