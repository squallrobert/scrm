<script>
	/*
	 * Script de inicialização das variáveis de tutorial
	 * Todas as variáveis são globais
	 * Documentação hopscotch: http://linkedin.github.io/hopscotch/
	 *
	 * Para adicionar um tutorial, basta adicionar a propriedade 'tutorial: true' ao objeto de inicialização do dialog,
	 * e então, fazer a seguinte chamada: /referenciaDialog/.setTour(/objTour/);
	 * aba_sms
	 aba_prioridade
	 aba_centroid
	 * */

	//Tour tela principal
	var tourGeralUsuarioConfiguracao = {
		id:      "tutorial_geral_usuario_configuracao",
		onStart: function ()
				 {
					 backdropFade = $(".modal-backdrop")[0];			//Pega o primeiro overlay
					 backdropZIndex = backdropFade.style["z-index"];	//Guarda o z-index original
					 $(backdropFade).css("z-index", "4000");			//Aumenta o z-index para que ele fique entre o tutorial e o dialog
					 $("#aba_prioridade").click();
				 },
		//Passos do tutorial
		steps:   [
			{
				title:          "<?=RTL_PRIORIDADE_EVENTO?>",
				content:        "<?=TXT_TUTORIAL_ABA_PRIORIDADE_EVENTO?>",
				target:         "aba_prioridade",
				placement:      "bottom"
			},
			{
				title:       "<?=RTL_PRIORIDADE_EVENTO?>",
				content:     "<?=TXT_TUTORIAL_PRIORIDADE_EVENTO?>",
				target:      "TabelaPrioridade",
				placement:   "left",
				showNextButton: false,
				showCTAButton:  true,
				ctaLabel:       "<?=ROTULO_PROXIMO?>",
				onCTA:          function ()
				{
					$(document).one("shown.bs.tab", function ()
					{
						setTimeout(function ()
						{
							hopscotch.nextStep();
						}, 100);
					});
					$("#aba_centroid").click();
				}
			},
			{
				title:          "<?=RTL_CENTROID?>",
				content:        "<?=TXT_TUTORIAL_ABA_CENTROID?>",
				target:         "aba_centroid",
				placement:      "bottom"

			},
			{
				title:       "<?=RTL_CENTROID?>",
				content:     "<?=TXT_TUTORIAL_CENTROID?>",
				target:      "map",
				placement:   "top",
				xOffset:     "center",
				arrowOffset: "center"
			}
		]
	};

	/*
	 Cria um novo objeto adicionando as opções do objeto do tutorial ao default,
	 sobrescrevendo os defaults caso existam opções duplicadas
	 */
	tourGeralUsuarioConfiguracao = $.extend(true, {}, defaultTutorialOptions, tourGeralUsuarioConfiguracao);
</script>