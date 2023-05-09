<script type="text/javascript">

    var activeMap = null;   //variável global do mapa
	var latitude = null;    //latitude, longitude e zoom para resize
	var longitude = null;
	var zoom = null;

	$(function ()
	{
        //vw e vh significam largura e altura do viewport
        $('#div_usuario_config').parents('.modal-dialog').css("width", "55vw");
        // $('#div_usuario_config').parents('.modal-dialog').css("height", "50vh");

		dialogPrincipal.setTour(tourGeralUsuarioConfiguracao);
		//Tutorial Geral
		$('#help_medium').on("click",function()
		{
			hopscotch.startTour(tourGeralUsuarioConfiguracao);
		});
		dialogPrincipal.setButtons([{
			label:    '<?=ROTULO_SALVAR?>',
			cssClass: 'btn-lg btn-confirm',
			action:   function (dialogRef)
					  {
						  ExecutarAcao(dialogRef, "index_xml.php?app_modulo=usuario_configuracao&app_comando=alterar_configuracoes&app_codigo=" +<?=json_encode($_SESSION['usuario']['id'])?>);
					  }
		}]);

		FuncoesFormulario();
	});

	function FuncoesFormulario()
	{
		//botões de switch
		$('.js-switch').each(function ()
		{
			new Switchery(this, {
				size: 'small'
			});
		});

		//Verifica se existe latitude/longitude, caso o usuário esteja modificando um registro, para inicializar o mapa com as informações do registro
		var markerOptions;
		if ($("#latitude").val() != "" && $("#longitude").val() != "") {
			latitude      = Number($("#latitude").val());
			longitude     = Number($("#longitude").val());
			zoom          = 15;
			markerOptions = {
				draggable: true,
				visible:   true,
				position:  new google.maps.LatLng(latitude, longitude)
			};
		}
		//Opções padrão para mostrar o Brasil
		else {
			latitude      = -15.44051;
			longitude     = -53.23656;
			zoom          = 3;
			markerOptions = null;
		}

		//Instancia o autocomplete de endereço com mapa
		var addressPicker = new AddressPicker({
			map:              {
				id:            '#map',
				zoom:          zoom,
				center:        new google.maps.LatLng(latitude, longitude),
				displayMarker: true
			},
			marker:           markerOptions,
			zoomForLocation:  18,
			draggable:        true,
			reverseGeocoding: true
		});

		//Guarda o objeto do mapa numa variável global para facilitar acesso
		activeMap = addressPicker.map;

		//Ativa o autocomplete no campo de logradouro
		$('#logradouro').typeahead(null, {
			displayKey: 'description',
			source:     addressPicker.ttAdapter()
		});
		addressPicker.bindDefaultTypeaheadEvent($('#logradouro'));
		$(addressPicker).bind('addresspicker:selected', function (event, result)
		{
			$("#latitude").val(result.latitude);
			$("#longitude").val(result.longitude);
		});

	}

	function ReloadMapa()
	{
		//Workaround de um bug do GoogleMaps com o Dialog do bootstrap, onde o mapa não é mostrado corretamente dentro do dialog
		setTimeout(function ()
		{
			google.maps.event.trigger(activeMap, "resize");

			//Centraliza o mapa no Brasil
			activeMap.setCenter(new google.maps.LatLng(latitude, longitude));
			activeMap.setZoom(zoom);

		}, 1000);
	}

	function PrimeiroSms()
	{
		if ($("#flag_primeiro_sms").val() == "" && hopscotch.getState() != "tutorial_geral_usuario_configuracao:0") {
			$("#flag_primeiro_sms").val("false");
			AlertBootStrap(<?=json_encode(TXT_PRIMEIRO_SMS)?>, <?=json_encode(ROTULO_ATENCAO)?>, 2);
		}

	}
	/*
	 * Executa o post do formulário
	 * */
	function ExecutarAcao(dialog, url)
	{
		// ao clicar em salvar enviando dados por post via AJAX
		$.post(url,
			$("#log").serialize()
			,
			// pegando resposta do retorno do post
			function (response)
			{

				if (response["codigo"] == 0) {
					dialog.close();
					AlertBootStrap(response["mensagem"], "<?=ROTULO_MENSAGEM?>", 4);
				}
				else {
					msg = response["mensagem"];
					MensagensForm("#msg", msg, 2)
				}
			}
			, "json" // definindo retorno para o formato json
		);
	}

</script>