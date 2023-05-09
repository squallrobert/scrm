var directionsDisplay;
var endereco = [];
var cor      = "#0000ff";
var overlay;
function GoogleMapsV3()
{
	this.map;
	this.zoom     = 4;
	this.mapatype = google.maps.MapTypeId.ROADMAP;
	this.lat_zoom = -15.44051;
	this.lng_zoom = -46.23656;
	this.center   = new google.maps.LatLng(this.lat_zoom, this.lng_zoom);
	this.centro_raio;
	this.arrowheads                 = [];
	this.arrowheadsMult             = [];
	this.poly                       = [];
	this.linhasarrow                = [];
	this.linhasarrowmulti           = [];
	this._maxZoom                   = 15;
	this.limite                     = new google.maps.LatLngBounds();
	this.lista_pontos               = [];
	this.lista_cerca_rotas          = [];
	this.lista_poligono_cerca_rotas = [];
	this.lista_poligonos            = [];
	this.lista_pontos_mult          = [];
	this.lista_pontos_interesse     = [];
	this.lista_pontos_cerca_rotas   = [];
	this.circulo                    = [];
	this.plotandoPontos             = [];
	this.plotandoPontosInteresse    = [];
    this.geocoder = new google.maps.Geocoder();
	this.trafego;
	this.trafego_ativo = false;
	this.opdrag        = {draggable: true};
	this.pontos;
	this.gapPx;
	this.points;
	this.color;
	this.weight;
	this.opacity;
	this.prj;
	this.line1;
	this.poli;
	this.line2;
	this.marker = {};
	this.arrayMarker = [];
	this.zoomListener;
	this.showPOIlabel = false;
	var markers            = new Array();
	this.americaSul        = [];
	this.americaSulPolygon =

		this.georssLayer = new Array();

	directionsDisplay      = new google.maps.DirectionsRenderer(this.opdrag);
	this.directionsService = new google.maps.DirectionsService();
	/**/

	this.map_options =
	{
		zoom:                  this.zoom,
		center:                this.center,
		mapTypeId:             this.mapatype,
		zoomControl:           true,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
		}
	};

    this.reverseGeocode = function(position,campoDestino) {

		this.geocoder.geocode({'location': position}, function(results, status) {
            if (status === 'OK') {
                if (results[0]) {
                    campoDestino.val(results[0].formatted_address);
					return results[0].formatted_address;
                }
            }
        });
    };


	function ContextMenu(map, options){
		options=options || {};

		this.setMap(map);

		this.classNames_=options.classNames || {};
		this.map_=map;
		this.mapDiv_=map.getDiv();
		this.menuItems_=options.menuItems || [];
		this.pixelOffset=options.pixelOffset || new google.maps.Point(10, -5);
	}

	ContextMenu.prototype=new google.maps.OverlayView();


	ContextMenu.prototype.draw=function(){
		if(this.isVisible_){
			var mapSize=new google.maps.Size(this.mapDiv_.offsetWidth, this.mapDiv_.offsetHeight);
			var menuSize=new google.maps.Size(this.menu_.offsetWidth, this.menu_.offsetHeight);
			var mousePosition=this.getProjection().fromLatLngToDivPixel(this.position_);

			var left=mousePosition.x;
			var top=mousePosition.y;

			if(mousePosition.x>mapSize.width-menuSize.width-this.pixelOffset.x){
				left=left-menuSize.width-this.pixelOffset.x;
			} else {
				left+=this.pixelOffset.x;
			}

			if(mousePosition.y>mapSize.height-menuSize.height-this.pixelOffset.y){
				top=top-menuSize.height-this.pixelOffset.y;
			} else {
				top+=this.pixelOffset.y;
			}

			this.menu_.style.left=left+'px';
			this.menu_.style.top=top+'px';
		}
	};

	ContextMenu.prototype.getVisible=function(){
		return this.isVisible_;
	};

	ContextMenu.prototype.hide=function(){
		if(this.isVisible_){
			this.menu_.style.display='none';
			this.isVisible_=false;
		}
	};

	ContextMenu.prototype.onAdd=function(){
		function createMenuItem(values){
			var menuItem=document.createElement('div');
			menuItem.innerHTML=values.label;
			if(values.className){
				menuItem.className=values.className;
			}
			if(values.id){
				menuItem.id=values.id;
			}
			menuItem.style.cssText='cursor:pointer; white-space:nowrap';
			menuItem.onclick=function(){
				google.maps.event.trigger($this, 'menu_item_selected', $this.position_, values.eventName);
			};
			return menuItem;
		}
		function createMenuSeparator(){
			var menuSeparator=document.createElement('div');
			if($this.classNames_.menuSeparator){
				menuSeparator.className=$this.classNames_.menuSeparator;
			}
			return menuSeparator;
		}
		var $this=this;	//	used for closures

		var menu=document.createElement('div');
		if(this.classNames_.menu){
			menu.className=this.classNames_.menu;
		}
		menu.style.cssText='display:none; position:absolute';

		for(var i=0, j=this.menuItems_.length; i<j; i++){
			if(this.menuItems_[i].label && this.menuItems_[i].eventName){
				menu.appendChild(createMenuItem(this.menuItems_[i]));
			} else {
				menu.appendChild(createMenuSeparator());
			}
		}

		delete this.classNames_;
		delete this.menuItems_;

		this.isVisible_=false;
		this.menu_=menu;
		this.position_=new google.maps.LatLng(0, 0);

		google.maps.event.addListener(this.map_, 'click', function(mouseEvent){
			$this.hide();
		});

		this.getPanes().floatPane.appendChild(menu);
	};

	ContextMenu.prototype.onRemove=function(){
		this.menu_.parentNode.removeChild(this.menu_);
		delete this.mapDiv_;
		delete this.menu_;
		delete this.position_;
	};

	ContextMenu.prototype.show=function(latLng){
		if(!this.isVisible_){
			this.menu_.style.display='block';
			this.isVisible_=true;
		}
		this.position_=latLng;
		this.draw();
	};

	this.CalcularRota = function (pontos, delimitador)
	{
		var way     = [],
			request = null;

		if (pontos != "") {
			directionsDisplay.setMap(this.map);
			// Prende um evento ao serviço de direções aonde quando as direções mudarem, recalcula a distancia, os endereços e some a tela de carregando.
			google.maps.event.addListener(directionsDisplay, 'directions_changed', function ()
			{
				var route     = directionsDisplay.directions.routes[0];
				var legs      = route.legs;
				var endereco  = [];
				var distancia = [];
				for (i = 0; i < legs.length; i++) {
					endereco.push(legs[i].start_address);
					distancia.push(legs[i].distance);
					if (i == legs.length - 1) {
						endereco.push(legs[i].end_address);
					}

				}
				if (delimitador == 1) {
					AtualizarEndereco(endereco, distancia);
				}

				//Usado para mostrar a distancia total na tela Monitorar
				if (delimitador == 0) {
					$(".fa-map-marker").tooltip("destroy");
					$(".fa-map-marker").tooltip({title: "Distância: " + distancia[0].text, container: "body"});
				}

			});

			for (x = 1; x < (pontos.length - 1); x++) {
				way.push({
					location: pontos[x],
					stopover: true
				});
			}
			request = {
				origin:      pontos[0],
				destination: pontos[(pontos.length - 1)],
				waypoints:   way,
				travelMode:  google.maps.DirectionsTravelMode.DRIVING
			};

			this.directionsService.route(request, function (response, status)
			{
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(response);
				} else if (status == google.maps.DirectionsStatus.NOT_FOUND) {
					AlertBootStrap("Um ou mais pontos não podem ser encontrados.", "Atenção!", 2);
				}
				else if (status == google.maps.DirectionsStatus.ZERO_RESULTS) {
					AlertBootStrap("A rota escolhida não retornou resultado.", "Atenção!", 2);
				}
				else if (status == google.maps.DirectionsStatus.OVER_QUERY_LIMIT) {
					AlertBootStrap("Limite de requisições por minuto excedida.\nAguarde 60 segundos antes de fazer uma nova requisição.", "Atenção!", 2);
				}
				else if (status == google.maps.DirectionsStatus.REQUEST_DENIED) {
					AlertBootStrap("A página da web não tem permissão para utilizar o serviço de direções.", "Atenção!", 2);
				}
				else if (status == google.maps.DirectionsStatus.UNKNOWN_ERROR) {
					AlertBootStrap("A solicitação de rota não pôde ser processada devido ao numero de endereços requisitados.", "Atenção!", 2);
				}
				else {
					AlertBootStrap("A solicitação de rota não pôde ser processada devido a um erro no servidor.\nO pedido pode ter sucesso se você tentar novamente.", "Atenção!", 2);
				}
			});
		}

	};

    this.getRandomColor = function () {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
	this.inicialize = function (div_mapa)
	{
		this.map = new google.maps.Map(document.getElementById(div_mapa), this.map_options);
		mapz     = this.map;

		$(document).one("ajaxStop", function ()
		{
			setTimeout(function ()
			{
				google.maps.event.trigger(mapz, "resize");
			}, 2000);
		});
		directionsDisplay.setMap(this.map);
		overlay      = new google.maps.OverlayView();
		overlay.draw = function ()
		{
		};
		overlay.setMap(this.map);
	};

	this.SimplestInicialize = function (div_mapa)
	{
		this.map = new google.maps.Map(document.getElementById(div_mapa), {
			zoom:             this.zoom,
			center:           this.center,
			mapTypeId:        this.mapatype,
			disableDefaultUI: false,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				position: google.maps.ControlPosition.TOP_CENTER
            }


		});
		mapz     = this.map;

	};

	this.Reset = function ()
	{
		this.map.setZoom(4);
		directionsDisplay.setMap(null);
		this.map.setCenter(this.center);
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}
	};

	this.zerarPontos = function()
	{
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}
		// Limpa a polyline gerada pelo KML, se existir.
		if (typeof poly !== 'undefined')
			poly.setMap(null);
		// Limpa o array de endereços
			this.pontos = [];
		//limpa a rota gerada
			directionsDisplay.setMap(null);
	};

	// Adiciona ponto de interesse (Marker) no endereço escolhido.
	this.LocalizarEndereco = function (endereco) {
		var geocoder = new google.maps.Geocoder();
		var mapa = this.map;
		var that = this;

		directionsDisplay.setMap(null);

		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}

		geocoder.geocode({'address': endereco + ', Brasil', 'region': 'BR'}, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();

					$('#endereco_inicial_da_rota').val(results[0].formatted_address);

					var location = new google.maps.LatLng(latitude, longitude);
					 this.marker = new google.maps.Marker({
						position: location,
						map: mapa
					});

					markers.push(marker);

					var contextMenuOptions = {};
					contextMenuOptions.classNames = {
						menu: 'btn-save-dialog',
						menuSeparator: 'btn-save-dialog'
					};
					var menuItems = [];
					menuItems.push({
						eventName: 'rotas_ate_aqui',
						label: 'Gerar rota até aqui'
					});
					menuItems.push({className: 'btn-save-dialog', eventName: '', label: '_______________'});
					menuItems.push({
						eventName: 'rotas_a_partir_daqui',
						label: 'Gerar rota a partir daqui'
					});
					contextMenuOptions.menuItems = menuItems;
					var contextMenu = new ContextMenu(mapa, contextMenuOptions);
					google.maps.event.clearInstanceListeners(contextMenu);
					google.maps.event.addListener(marker, 'rightclick', function (mouseEvent) {
						contextMenu.show(mouseEvent.latLng);
					});
					google.maps.event.addListener(contextMenu, 'menu_item_selected', function (latLng, eventName) {
						switch (eventName) {
							case 'rotas_ate_aqui':
								$('#endereco_final_da_rota').val(results[0].formatted_address);
								dialogPrincipal = activeDialog = BootstrapDialog.show({
									id: 'dialogPesquisaRota',
									size: BootstrapDialog.SIZE_NORMAL,
									type: BootstrapDialog.TYPE_DEFAULT,
									title: "<div class='titulo_modal'>Pesquisar Rota:</div>",
									message: $('<div></div>').load('index_app.php?app_modulo=central_monitoramento&app_comando=gerar_rota&endereco_destino=' + encodeURI(results[0].formatted_address)),
									draggable: true,
									buttons: [{
										label: 'VER NO MAPA',
										cssClass: 'btn-lg btn-confirm',
										action: function (dialogRef) {
											if ($("#endereco_inicial").val() != "") {
												GerarRoteiro(that);
												dialogRef.close();
											}
										}
									}]
								});

								break;

							case 'rotas_a_partir_daqui':
								$('#endereco_inicial_da_rota').val(results[0].formatted_address);
								dialogPrincipal = activeDialog = BootstrapDialog.show({
									id: 'dialogPesquisaRota',
									size: BootstrapDialog.SIZE_NORMAL,
									type: BootstrapDialog.TYPE_DEFAULT,
									title: "<div class='titulo_modal'>Pesquisar Rota:</div>",
									message: $('<div></div>').load('index_app.php?app_modulo=central_monitoramento&app_comando=gerar_rota&endereco_partida=' + encodeURI(results[0].formatted_address)),
									draggable: true,
									buttons: [{
										label: 'VER NO MAPA',
										cssClass: 'btn-lg btn-confirm',
										action: function (dialogRef) {
											if ($("#endereco_inicial").val() != "") {
												GerarRoteiro(that);
												dialogRef.close();
											}
										}
									}]
								});
								break;
						}

					});

					that.centralizarPonto(latitude, longitude, 16);
				}
			}
		});
	}

		function GerarRoteiro(mapa) {

			// retira marker de pesquisa
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(null);
			}
			//Define o objeto Geocoder
			var geocoder = new google.maps.Geocoder();
			// Redefine o mapa para mostrar a rota que será gerada
			if (directionsDisplay.getMap() == null)
				directionsDisplay.setMap(mapa.map);
			// Limpa a polyline gerada pelo KML, se existir.
			if (typeof poly !== 'undefined')
				poly.setMap(null);
			// Limpa o array de endereços
			pontos = [];

			// Recebe cada campo de endereço e popula o array de endereços

			pontos.push($('#endereco_inicial_da_rota').val());
			pontos.push($('#endereco_final_da_rota').val());

			geocoder.geocode({
				'address': $('#endereco_inicial_da_rota').val() + ', Brasil',
				'region': 'BR'
			}, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#endereco_inicial_da_rota').val(results[0].formatted_address);
					}
				}
			});

			geocoder.geocode({
				'address': $('#endereco_final_da_rota').val() + ', Brasil',
				'region': 'BR'
			}, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						$('#endereco_final_da_rota').val(results[0].formatted_address);
					}
				}
			});

			mapa.CalcularRota(pontos, 0);
		}


	this.HabilitarTrafego = function ()
	{
		if (this.trafego_ativo) {
			this.trafego.setMap(null);
			this.trafego_ativo = false;
		}
		else {
			this.trafego = new google.maps.TrafficLayer();
			this.trafego.setMap(this.map);
			this.trafego_ativo = true;
		}

	}
	this.AnimarPonto      = function (pos)
	{

		if (this.lista_pontos.length > 0) {
			for (var y = 0; y < this.lista_pontos.length; y++) {
				this.lista_pontos[y].setAnimation(null);
			}
		}
		if (this.lista_pontos[pos].getAnimation() != null) {
			this.lista_pontos[pos].setAnimation(null);
		} else {
			this.lista_pontos[pos].setAnimation(google.maps.Animation.BOUNCE);
		}
	}

	this.barraEdicaoPoligon = function ()
	{
		var polyOptions =
			{
				strokeWeight: 0,
				fillOpacity:  0.45,
				editable:     true
			};
		// Creates a drawing manager attached to the map that allows the user to draw
		// markers, lines, and shapes.
		drawingManager = new google.maps.drawing.DrawingManager(
			{
				drawingMode:      google.maps.drawing.OverlayType.POLYGON,
				markerOptions:    {
					draggable: true
				},
				polylineOptions:  {
					editable: true
				},
				rectangleOptions: polyOptions,
				circleOptions:    polyOptions,
				polygonOptions:   polyOptions,
				map:              this.map
			});
	}
	this.AplicarTema        = function (tema)
	{
		var lay = new google.maps.KmlLayer(tema);
		lay.setMap(this.map);
		this.georssLayer.push(lay);
	}
	this.LimparTemas        = function ()
	{
		//alert(pontos.length);
		if (this.georssLayer.length > 0) {
			//for (i in this.lista_pontos)
			for (var i = 0; i < this.georssLayer.length; i++) {
				this.georssLayer[i].setMap(null);
			}
		}
	}
	this.centralizarPonto   = function (lat, lgn, zoom)
	{
		var latLng = new google.maps.LatLng(Number(lat), Number(lgn));
		this.map.panTo(latLng);
		this.map.setZoom(zoom);
	};

	// Funções de adicionar pontos no mapa
	this.adicionarPontoSimples = function (config)
	{
		var markerOptions = {};
		var marker        = new google.maps.Marker({
			position:  config.pos,
			map:       this.map,
			draggable: true
		});
		google.maps.event.addListener(marker, 'click', function ()
			{
				marker.setMap(null);
				for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
				markers.splice(i, 1);
				path.removeAt(i);
			}
		);
		google.maps.event.addListener(marker, 'dragend', function ()
			{
				for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
				path.setAt(i, marker.getPosition());
			}
		);
	};
	this.adicionarPontoVeiculo = function (pontos)
	{

		if (pontos.length > 0) {
			this.lista_pontos = [];
			var linha_ponto   = {};
			var markerOptions = {};
			for (var i = 0; i < pontos.length; ++i) {
				if (pontos[i].html) {
					markerOptions.html = pontos[i].html;
				}
				if (pontos[i].icon) {
					markerOptions.icon = pontos[i].icon;
				}
				if (pontos[i].draggable) {
					markerOptions.draggable = pontos[i].draggable;
				}
				markerOptions.title    = pontos[i].title;
				markerOptions.map      = this.map;
				markerOptions.position = new google.maps.LatLng(Number(pontos[i].lat), Number(pontos[i].lng));
				linha_ponto            = new google.maps.Marker(markerOptions);
				this.lista_pontos.push(linha_ponto);
				google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
			}
			this.plotandoPontos.push(new MarkerClusterer(this.map, this.lista_pontos, {maxZoom: 10, gridSize: 10}));
		}
	};
	this.adicionarPontoLora = function (pontos)
	{
		if (pontos.length > 0) {
			this.lista_pontos = [];
			var linha_ponto   = {};
			var markerOptions = {};
			for (var i = 0; i < pontos.length; ++i) {
				if (pontos[i].html) {
					markerOptions.html = pontos[i].html;
				}
				if (pontos[i].icon) {
					markerOptions.icon = pontos[i].icon;
				}
				if (pontos[i].draggable) {
					markerOptions.draggable = pontos[i].draggable;
				}
					var num = i.toString();
					markerOptions.label = {text: num, color: "white", fontSize: '12px', fontWeight: 'bold'};
					markerOptions.title = pontos[i].title;
					markerOptions.map = this.map;
					markerOptions.draggable = true,
					markerOptions.animation = google.maps.Animation.DROP,
					markerOptions.position = new google.maps.LatLng(Number(pontos[i].lat), Number(pontos[i].lng));
					linha_ponto = new google.maps.Marker(markerOptions);

				this.lista_pontos.push(linha_ponto);
				google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
			}
			this.plotandoPontos.push(new MarkerClusterer(this.map, this.lista_pontos, {maxZoom: 10, gridSize: 10}));
		}
	};
	this.adicionarPontoApp = function (pontos)
	{
		if (pontos.length > 0) {
			this.lista_pontos = [];
			var linha_ponto   = {};
			var markerOptions = {};
			const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			let labelIndex = 0;

			for (var i = 0; i < pontos.length; ++i) {
				if (pontos[i].html) {
					markerOptions.html = pontos[i].html;
				}
				if (pontos[i].icon) {
					markerOptions.icon = pontos[i].icon;
				}
				if (pontos[i].draggable) {
					markerOptions.draggable = pontos[i].draggable;
				}


				markerOptions.label = {text: labels[labelIndex++ % labels.length], color: "white", fontSize: '12px', fontWeight: 'bold'};
				markerOptions.title = pontos[i].title;
				markerOptions.map = this.map;
				markerOptions.draggable = false,
					markerOptions.animation = google.maps.Animation.DROP,
					markerOptions.position = new google.maps.LatLng(Number(pontos[i].lat), Number(pontos[i].lng));
				linha_ponto = new google.maps.Marker(markerOptions);

				this.lista_pontos.push(linha_ponto);
				google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
			}
			this.plotandoPontos.push(new MarkerClusterer(this.map, this.lista_pontos, {maxZoom: 10, gridSize: 10}));
		}
	};
	this.adicionarPontoVeiculoComparativo = function (pontos)
	{
		var linha_ponto = {},
			marker      = {},
			icon        = null,
			html        = null,
			arrayAuxiliar = [];

		if (pontos.length > 0) {

			for (var i = 0; i < pontos.length; ++i) {

				if (pontos[i][4]) {
					html = pontos[i][4];
				}
				if (pontos[i][2]) {
					icon = pontos[i][2];
				}


				marker = new google.maps.Marker({
					position:  new google.maps.LatLng(pontos[i][0].lat, pontos[i][0].lng),
					map:       this.map,
					draggable: false,
					icon:      icon,
					title:     i+'',
					html:      html
				});


				arrayAuxiliar.push(marker);
				google.maps.event.addListener(marker, 'click', this.clickPonto);

			}
			this.lista_pontos = arrayAuxiliar;
			this.plotandoPontos.push(new MarkerClusterer(this.map, arrayAuxiliar, {maxZoom: 10, gridSize: 10}));
		}

	};

	this.adicionarPontoVeiculoRastro = function (pontos)
	{

		var linha_ponto = {},
			marker      = {},
			icon        = null,
			html        = null,
			arrayAuxiliar = [];



		if (pontos.length > 0) {

			for (var i = 0; i < pontos.length; ++i) {

				if (typeof pontos[i] === "undefined") continue;
				if (pontos[i][4]) {
					html = pontos[i][4];

				}
				if (pontos[i][3]) {
					icon = pontos[i][3];
				}


				marker = new google.maps.Marker({
					position:  new google.maps.LatLng(pontos[i][0], pontos[i][1]),
					map:       this.map,
					draggable: false,
					icon:      icon,
					title:     i+'',
					html:      html
				});


				arrayAuxiliar.push(marker);
				google.maps.event.addListener(marker, 'click', this.clickPonto);

			}
			this.lista_pontos = arrayAuxiliar;
			this.plotandoPontos.push(new MarkerClusterer(this.map, arrayAuxiliar, {maxZoom: 10, gridSize: 10}));
		}

	};

	this.adicionarPontoInteresse = function (pontos)
	{
		this.lista_pontos_interesse = [];
		var linha_ponto             = {},
			markerOptions           = {},
			latlngBounds            = new google.maps.LatLngBounds();

		if (pontos.length > 0) {

			for (var i = 0; i < pontos.length; ++i) {

                //se ativada a opcao de mostrar o label com o nome da cerca no mapa
                if (this.showPOIlabel){
                    markerWithLabelOptions = {};
                    if (pontos[i].html){
                        markerWithLabelOptions.html = pontos[i].html;
                    }
                    if (pontos[i].icon) {
                        markerWithLabelOptions.icon = pontos[i].icon;
                    }
                    var titulo = pontos[i].title.size <= 10 ? pontos[i].title : pontos[i].title.substring(0,10) + "...";

                    markerWithLabelOptions.title = pontos[i].title;
                    markerWithLabelOptions.position = new google.maps.LatLng(parseFloat(pontos[i].lat), parseFloat(pontos[i].lng));
                    markerWithLabelOptions.labelContent = "<div style='border: solid; border-width: 1px; border-color: white; background: black; color:white;padding: 3px'>"+ titulo +"</div>";
                    markerWithLabelOptions.labelAnchor = new google.maps.Point(30, 0);
                    markerWithLabelOptions.labelInBackground = false;
                    markerWithLabelOptions.map = objMapaMonitorar.map;

                    latlngBounds.extend(markerWithLabelOptions.position);
                    linha_ponto = new MarkerWithLabel(markerWithLabelOptions);
                }else{
                    markerOptions = {};
                    if (pontos[i].html) {
                        markerOptions.html = pontos[i].html;
                    }
                    if (pontos[i].icon) {
                        markerOptions.icon = pontos[i].icon;
                    }
                    if (pontos[i].draggable) {
                        markerOptions.draggable = pontos[i].draggable;
                    }
                    if (pontos[i].color) {
                        this.color = pontos[i].color;
                    }
                    markerOptions.title    = pontos[i].title;
                    markerOptions.map      = this.map;
                    markerOptions.position = new google.maps.LatLng(parseFloat(pontos[i].lat), parseFloat(pontos[i].lng));
                    latlngBounds.extend(markerOptions.position);

                    linha_ponto = new google.maps.Marker(markerOptions);
                }

				this.lista_pontos_interesse.push(linha_ponto);
				google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
				if (pontos[i].raio) {
					this.mostrarRaio(pontos[i].lat, pontos[i].lng, pontos[i].raio);
				}
			}

			this.plotandoPontosInteresse.push(new MarkerClusterer(this.map, this.lista_pontos_interesse, {
				maxZoom:  10,
				gridSize: 10
			}));
			this.map.fitBounds(latlngBounds);
		}
	};
	this.adicionarCercaPoligono  = function (pontos)
	{
		this.lista_poligonos = [];
		if (pontos.length > 0) {
			for (var i = 0; i < pontos.length; ++i) {
				var polygonOptions = {};
				var path           = [];
				for (var j in pontos[i].path) {
					var latlong = pontos[i].path[j].split(",");
					path.push(new google.maps.LatLng(parseFloat(latlong[0].replace("(", " ")), parseFloat(latlong[1].replace(")", " "))));
				}
				polygonOptions.path          = path;
				polygonOptions.strokeOpacity = 0.8;
				polygonOptions.strokeWeight  = 3;
				polygonOptions.fillOpacity   = 0.35;
				polygonOptions.map           = this.map;
				polygonOptions.fillColor     = this.getRandomColor();
				if (pontos[i].html) {
					polygonOptions.html = pontos[i].html;
				}
				if (pontos[i].strokeColor) {
					polygonOptions.strokeColor = pontos[i].strokeColor;
				}
				if (pontos[i].fillColor) {
					polygonOptions.fillColor = pontos[i].fillColor;
				}
				var poligono = new google.maps.Polygon(polygonOptions);
				google.maps.event.addListener(poligono, 'click', this.clickCercaPoligono.bind(this));
				this.lista_poligonos.push(poligono);
			}
		}
	};
	this.adicionarCercaRotas     = function (pontos)
	{
		this.lista_pontos               = [];
		this.lista_cerca_rotas          = [];
		this.lista_poligono_cerca_rotas = [];
		if (pontos.length > 0) {
			var coordenadas, latlong, marker;
			var path = [];
			for (var i = 0; i < pontos.length; ++i) {
				path = [];
				for (var j in pontos[i].path) {
					latlong     = pontos[i].path[j].split(",");
					coordenadas = new google.maps.LatLng(parseFloat(latlong[0].replace("(", " ")), parseFloat(latlong[1].replace(")", " ")));
					path.push(coordenadas);
					// Adiciona markers no inicio  e no final da rota
					if (j == 0) {
						marker = new google.maps.Marker({
							position:  coordenadas,
							map:       this.map,
							draggable: false,
							icon:      "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-a.png&text=A&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48&scale=1",
							title:     'Início'
						});
						this.lista_pontos_cerca_rotas.push(marker);
					}
					if (j >= pontos[i].path.length - 1) {
						marker = new google.maps.Marker({
							position:  coordenadas,
							map:       this.map,
							draggable: false,
							icon:      "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-b.png&text=B&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48&scale=1",
							title:     'Fim'
						});
						this.lista_pontos_cerca_rotas.push(marker);
					}
				}
				/*                var polinha = new google.maps.Polyline({
				 path: path,
				 strokeColor: "blue",
				 strokeOpacity: 0,
				 strokeWeight: 0
				 });*/
				this.carregarLinhasParalelas(path, "#0000FF", 3, 0.3, pontos[i].raio / 2);
				//this.lista_cerca_rotas.push(polinha);
			}

		}
	};
	// Funções de enquadramento da view do googleMaps, centralizando o mapa nos pontos disponiveis.
	this.EnquadrarPontos          = function ()
	{
		this.limite = new google.maps.LatLngBounds();
		//for(var i in this.lista_pontos)
		for (var i = 0; i < this.lista_pontos.length; i++) {
			this.limite.extend(this.lista_pontos[i].getPosition());
		}
		this.map.fitBounds(this.limite);
		//Zoom aceitável para um único ponto
		if (this.lista_pontos.length == 1) {
			this.map.setZoom(16);
		}
		/*this.map.addListener('click', function ()
		{
			this.setCenter(new google.maps.LatLng(-15.44051, -36.23656));
		});*/
		//google.maps.event.trigger(this.map, 'resize');
	};
	this.EnquadrarPoligonos       = function ()
	{
		this.limite = new google.maps.LatLngBounds();
		//for(var i in this.lista_pontos)
		var points = [];
		for (var i = 0; i < this.lista_poligonos.length; i++) {
			var paths = this.lista_poligonos[i].getPath();
			for (var j = 0; j < paths.length; j++) {
				points = new google.maps.LatLng(paths.getAt(j).lat(), paths.getAt(j).lng());
				this.limite.extend(points);
			}
		}
		this.map.fitBounds(this.limite);
	};
	this.EnquadrarPontosInteresse = function ()
	{
		this.limite = new google.maps.LatLngBounds();
		for (var i = 0; i < this.circulo.length; i++) {
			this.limite.union(this.circulo[i].getBounds());
		}
		this.map.fitBounds(this.limite);
	};
	this.EnquadrarCercaRotas      = function ()
	{
		this.limite = new google.maps.LatLngBounds();
		var points  = [];
		for (var i = 0; i < this.lista_poligono_cerca_rotas.length; i++) {
			var paths = this.lista_poligono_cerca_rotas[i].getPath();
			for (var j = 0; j < paths.length; j++) {
				points = new google.maps.LatLng(paths.getAt(j).lat(), paths.getAt(j).lng());
				this.limite.extend(points);
			}
		}
		this.map.fitBounds(this.limite);
	};

	this.EnquadrarPontosMult = function (rota)
	{
		//for(var i in this.lista_pontos)
		for (var i = 0; i < this.lista_pontos_mult[rota].length; i++) {
			this.limite.extend(this.lista_pontos_mult[rota][i].getPosition());
		}
		this.map.fitBounds(this.limite);
	};

	this.adicionarPontoDrag = function (lat, lng, nome) //Old
	{
		this.lista_pontos = [];
		var ponto         = new google.maps.Marker({
			position:  new google.maps.LatLng(lat, lng),
			map:       this.map,
			draggable: true,
			animation: google.maps.Animation.DROP,
			title:     nome
		});
		this.lista_pontos.push(ponto);

		google.maps.event.addListener(ponto, 'dragend', function ()
		{
			$('#latitude').val(ponto.getPosition().lat());
			$('#longitude').val(ponto.getPosition().lng());
		});
	};

	this.add_pontos               = function (pontos)
	{

		this.lista_pontos = [];
		if (pontos.length > 0) {
			for (var i = 0; i < pontos.length; ++i) {
				var latLng      = new google.maps.LatLng(Number(pontos[i].latitude), Number(pontos[i].longitude)),
					linha_ponto = new google.maps.Marker({
						position:  latLng,
						draggable: false,
						map:       this.map
					});
				this.lista_pontos.push(linha_ponto);
				google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
			}
		}

		this.plotandoPontos.push(new MarkerClusterer(this.map, this.lista_pontos, {maxZoom: 10, gridSize: 10}));
	};
	this.add_pontos_label         = function (pontos, enquandra)
	{
		//alert(pontos);
		this.lista_pontos = [];
		if (pontos.length > 0) {
			for (var i = 0; i < pontos.length; ++i) {
				var latLng      = new google.maps.LatLng(pontos[i][0].lat, pontos[i][0].lng)
				var linha_ponto = new MarkerWithLabel({
					position:          latLng,
					draggable:         false,
					map:               this.map,
					title:             pontos[i][1],
					icon:              pontos[i][2],
					html:              pontos[i][4],
					raiseOnDrag:       true,
					labelContent:      pontos[i][7],
					labelAnchor:       new google.maps.Point(30, 0),
					labelClass:        "labels", // the CSS class for the label
					labelInBackground: false
				});
				this.lista_pontos.push(linha_ponto);
				google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
			}
		}

		this.plotandoPontos.push(new MarkerClusterer(this.map, this.lista_pontos, {maxZoom: 10, gridSize: 10}));
	};




	this.add_pontos_multi         = function (pontos, rota)
	{
		//alert(pontos);
		this.lista_pontos_mult[rota] = [];
		for (var i = 0; i < pontos.length; ++i) {
			var latLng      = new google.maps.LatLng(pontos[i][0].lat, pontos[i][0].lng)
			var linha_ponto = new google.maps.Marker({
				position:  latLng,
				draggable: false,
				title:     pontos[i][1],
				icon:      pontos[i][2],
				html:      pontos[i][4]
			});
			this.lista_pontos_mult[rota].push(linha_ponto);

			google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
		}
		this.plotandoPontos.push(new MarkerClusterer(this.map, this.lista_pontos_mult[rota], {
			maxZoom:  10,
			gridSize: 10
		}));
	};
	this.add_pontos_icone_default = function (pontos, enquandra)
	{
		//alert(pontos);
		this.lista_pontos = [];
		for (var i = 0; i < pontos.length; ++i) {
			var latLng      = new google.maps.LatLng(pontos[i][0].lat, pontos[i][0].lng)
			var linha_ponto = new google.maps.Marker({
				position:  latLng,
				draggable: false,
				title:     pontos[i][1],
				html:      pontos[i][4]
			});
			this.lista_pontos.push(linha_ponto);

			google.maps.event.addListener(linha_ponto, 'click', this.clickPonto);
		}

		this.plotandoPontos.push(new MarkerClusterer(this.map, this.lista_pontos, {maxZoom: 10, gridSize: 10}));
	};

	this.add_cerca_rota        = function (cercas)
	{
		this.lista_pontos               = [];
		this.lista_cerca_rotas          = [];
		this.lista_poligono_cerca_rotas = [];

		var marker       = null,
			latlong      = null,
			position     = null,
			latlngBounds = new google.maps.LatLngBounds(),
			path         = [];

		for (var x = 0; x < cercas.length; x++) {
			for (var y = 0; y < cercas[x].pontos.length; y++) {
				latlong  = cercas[x].pontos[y].split(",");
				position = new google.maps.LatLng(parseFloat(latlong[0]), parseFloat([latlong[1]]));
				path.push(position);
				latlngBounds.extend(position);
				if (y == 0) {
					marker = new google.maps.Marker({
						position:  position,
						map:       this.map,
						draggable: false,
						icon:      "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-a.png&text=A&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48&scale=1",
						title:     'Início'
					});
					this.lista_pontos_cerca_rotas.push(marker);
				}
				if (y >= cercas[x].pontos.length - 1) {
					marker = new google.maps.Marker({
						position:  position,
						map:       this.map,
						draggable: false,
						icon:      "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-waypoint-b.png&text=B&psize=16&font=fonts/Roboto-Regular.ttf&color=ff333333&ax=44&ay=48&scale=1",
						title:     'Fim'
					});
					this.lista_pontos_cerca_rotas.push(marker);
				}
			}
			this.lista_cerca_rotas.push(this.carregarLinhasParalelas(path, "#0000FF", 3, 0.3, cercas[x].raio / 2));
			path = [];

			if (cercas.length > 0) {
				this.map.fitBounds(latlngBounds);
			}
		}
	};
	this.add_poligonos         = function (cercas)
	{

		this.lista_poligonos = [];
		var latlong          = null,
			path             = [],
			poligono         = null,
			that             = this,
			position         = null,
			latlngBounds     = new google.maps.LatLngBounds(),
			pontos = null;

		for (var x = 0; x < cercas.length; x++) {
			if (typeof cercas[x].pontos == 'undefined') {
				pontos = cercas[x][0].pontos;
			} else {
				pontos = cercas[x].pontos;
			}

			for (var y = 0; y < pontos.length; y++) {
				latlong  = pontos[y].split(",");
				position = new google.maps.LatLng(Number(latlong[0]), Number(latlong[1]));
				path.push(position);
				latlngBounds.extend(position);
			}

			poligono = new google.maps.Polygon({
				paths:         path,
				strokeColor:   "black",
				strokeOpacity: 0.8,
				strokeWeight:  3,
				fillColor:     this.getRandomColor(),
				fillOpacity:   0.35,
				html:          '<strong>Cerca: </strong>' + cercas[x].nome + '<br/>'
			});
			poligono.setMap(this.map);

			google.maps.event.addListener(poligono, 'click', function (event)
			{
				var contentString = this.html +
					'<strong>Posição do ponto: </strong>' + event.latLng.lat() + ',' + event.latLng.lng() +
					'<br>';

				// Replace the info window's content and position.
				infoWindow.setContent(contentString);
				infoWindow.setPosition(event.latLng);

				infoWindow.open(that.map);
			});
			infoWindow = new google.maps.InfoWindow();
			this.lista_poligonos.push(poligono);

			path = [];
		}

		if (cercas.length > 0) {
			this.map.fitBounds(latlngBounds);
		}
	};
	this.deletarPoligonos      = function ()
	{
		if (this.lista_poligonos.length > 0) {
			//for (y in this.lista_pontos)
			for (var y = 0; y < this.lista_poligonos.length; y++) {
				this.lista_poligonos[y].setMap(null);
			}
		}
	};
	this.PadronizarCoordenadas = function (longitude, latitude)
	{
		return {lng: longitude, lat: latitude};
	};
	this.clickPonto            = function ()
	{
		this.infoWindow = new google.maps.InfoWindow;
		var marker      = this;
		var latLng      = marker.getPosition();
		this.infoWindow.setContent(marker.html);
		this.infoWindow.open(this.map, marker);
	};
	this.clickCercaPoligono    = function (event)
	{
		this.infoWindow = new google.maps.InfoWindow;
		var html        = "";
		if (this.html) {
			html = this.html;
		}
		var contentString = html +
			'Posição do ponto: <br>' +
			event.latLng.lat() + ',' + event.latLng.lng() +
			'<br>';
		this.infoWindow.setContent(contentString);
		this.infoWindow.setPosition(event.latLng);
		this.infoWindow.open(this.map);
	};

	this.limparRota     = function (rota)
	{
		this.poly[rota].setMap(null);
		for (var it in this.lista_pontos_mult[rota]) {
			this.lista_pontos_mult[rota][it].setVisible(false);
		}
		for (var it in this.linhasarrowmulti[rota]) {
			this.linhasarrowmulti[rota][it].setMap(null);
		}
		this.plotandoPontos[rota].repaint();

	};
	this.mostrarRota    = function (rota)
	{
		this.poly[rota].setMap(this.map);
		for (var it in this.lista_pontos_mult[rota]) {
			this.lista_pontos_mult[rota][it].setVisible(true);
		}
		for (var it in this.linhasarrowmulti[rota]) {
			this.linhasarrowmulti[rota][it].setMap(this.map);
		}
		this.plotandoPontos[rota].repaint();
	};
	this.esconderIcones = function ()
	{
		if (this.lista_pontos_mult.length > 1) {
			for (var i = 0; i < this.lista_pontos_mult.length; i++) {
				for (var it in this.lista_pontos_mult[i]) {
					this.lista_pontos_mult[i][it].setVisible(false);
				}
				this.plotandoPontos[i].repaint();
			}
		}
		else if (this.lista_pontos) {
			for (var i = 0; i < this.lista_pontos.length; i++) {
				this.lista_pontos[i].setVisible(false);
			}
			this.plotandoPontos[0].repaint();
		}
	};
	this.mostrarIcones  = function ()
	{
		if (this.lista_pontos_mult.length > 1) {
			for (var i = 0; i < this.lista_pontos_mult.length; i++) {
				for (var it in this.lista_pontos_mult[i]) {
					this.lista_pontos_mult[i][it].setVisible(true);
				}
				this.plotandoPontos[i].repaint();
			}
		}
		else if (this.lista_pontos.length > 0) {
			for (var i = 0; i < this.lista_pontos.length; i++) {
				this.lista_pontos[i].setVisible(true);
			}
			this.plotandoPontos[0].repaint();
		}
	};
	this.limparRoteiro  = function ()
	{
		directionsDisplay.setMap(null);
	}
	// Removes the overlays from the map, but keeps them in the array
	this.limparPontos = function ()
	{
		if (this.lista_pontos) {
			//for (i in this.lista_pontos)
			for (var i = 0; i < this.lista_pontos.length; i++) {
				this.lista_pontos[i].setMap(null);
			}
		}
		if (this.plotandoPontos) {
			//for (i in this.lista_pontos)
			for (var i = 0; i < this.plotandoPontos.length; i++) {

				//this.plotandoPontos[i].setMap(null);
			}
		}
	};

	this.deletarCercaRotas = function ()
	{
		if (this.lista_cerca_rotas) {
			for (var i = 0; i < this.lista_cerca_rotas.length; i++) {
				if (typeof this.lista_cerca_rotas[i] != "undefined") {
					this.lista_cerca_rotas[i].setMap(null);
				}
			}
			this.lista_cerca_rotas = [];
		}
		if (this.lista_poligono_cerca_rotas) {
			for (var i = 0; i < this.lista_poligono_cerca_rotas.length; i++) {
				if (typeof this.lista_poligono_cerca_rotas[i] != "undefined") {
					this.lista_poligono_cerca_rotas[i].setMap(null);
				}
			}
			this.lista_poligono_cerca_rotas = [];
		}
		if (this.lista_pontos_cerca_rotas) {
			for (var i = 0; i < this.lista_pontos_cerca_rotas.length; i++) {
				this.lista_pontos_cerca_rotas[i].setMap(null);
			}
			this.lista_pontos_cerca_rotas = [];
		}
	};
	this.limparEventos     = function ()
	{
		//google.maps.event.clearInstanceListeners(directionsDisplay);
	}

	// Shows any overlays currently in the array
	this.mostrarPontos = function ()
	{

		if (this.lista_pontos) {
			//for (i in this.lista_pontos)
			for (var i = 0; i < this.lista_pontos.length; i++) {
				this.lista_pontos[i].setMap(this.map);
			}
		}
		if (this.plotandoPontos) {
			//for (i in this.lista_pontos)
			//for(var i  = 0; i < this.plotandoPontos.length; i++)
			//{
			// this.plotandoPontos[i].resetViewport();

			//this.plotandoPontos[i].redraw();
			// }
		}
	};

	// Deletes all markers in the array by removing references to them
	this.deletarPontos = function ()
	{

		if (this.lista_pontos.length > 0) {

			//for (y in this.lista_pontos)
			for (var y = 0; y < this.lista_pontos.length; y++) {
				this.lista_pontos[y].setMap(null);
			}

		}
		if (this.plotandoPontos.length > 0) {
			//for (y in this.lista_pontos)
			for (var y = 0; y < this.plotandoPontos.length; y++) {
				this.plotandoPontos[y].clearMarkers();
			}

		}
		if (this.linhasarrow.length) {
			//for (v in this.linhasarrow)
			for (var v = 0; v < this.linhasarrow.length; v++) {
				this.linhasarrow[v].setMap(null);

			}
			this.linhasarrow.length = 0;
		}
		if (this.poly.length > 0) {
			//for (i in this.poly)
			for (var i = 0; i < this.poly.length; i++) {
				this.poly[i].setMap(null);
			}
			this.poly.length = 0;
		}
		// if (this.circulo.length > 0) {
		// 	//for (i in this.poly)
		// 	for (var i = 0; i < this.circulo.length; i++) {
		// 		this.circulo[i].setMap(null);
		// 	}
		// 	this.circulo.length = 0;
		// }
	};

	//Deleta os pontos de interesse mapa relatorios

	this.deletarPontosInteresse = function ()
	{
		if (this.lista_pontos_interesse.length > 0) {
			for (var y = 0; y < this.lista_pontos_interesse.length; y++) {
				this.lista_pontos_interesse[y].setMap(null);
			}
		}
		if (this.plotandoPontosInteresse.length > 0) {
			for (var y = 0; y < this.plotandoPontosInteresse.length; y++) {
				this.plotandoPontosInteresse[y].clearMarkers();
			}

		}
		if (this.circulo.length > 0) {
			for (var i = 0; i < this.circulo.length; i++) {
				this.circulo[i].setMap(null);
			}
			this.circulo.length = 0;
		}
	}

	this.mostrarRaio       = function (lat, lng, raio)
	{
        var newcolor = '';
		if(this.color != '')
			newcolor = this.color;
		else
			newcolor = this.getRandomColor();

		this.centro_raio = new google.maps.LatLng(lat, lng);
		this.RaioOptions =
		{
			strokeColor:   newcolor,
			strokeOpacity: 0.8,
			strokeWeight:  2,
			fillColor:     newcolor,
			fillOpacity:   0.35,
			map:           this.map,
			center:        this.centro_raio,
			radius:        parseInt(raio)
		};
		this.circulo.push(new google.maps.Circle(this.RaioOptions));

	};
	this.tratarCoordenadas = function (dados)
	{
		var vetor = [];
		for (x = 0; x < dados.length; x++) {
			vetor.push(new google.maps.LatLng(dados[x].latitude, dados[x].longitude));
		}
		return vetor;
	};
	this.formarVetorGoogle = function (dados)
	{
		var vetor = [];
		for (x = 0; x < dados.length; x++) {
			if (typeof dados[x] == "undefined") continue;
			vetor.push(new google.maps.LatLng(dados[x][0], dados[x][1]));
		}
		return vetor

	};

	this.criarLinha        = function (pontos, mode, color)
	{
		this.pontos = pontos;
        if (color == "") {
            color = 'black';
        }
        path = this.formarVetorGoogle(pontos);
        poli = new google.maps.Polyline({
            strokeColor:   color,
            strokeOpacity: 0.8,
            strokeWeight:  3,
            map:           this.map,
            path:          path
        });
        this.load(path, mode);
        this.poly.push(poli);
        return poli;
	};
	this.criarLinhaLora        = function (mode, color)
	{
		if (color == "") {
			color = 'black';
		}
		path = this.formarVetorGoogle(this.pontos);
		this.poli = new google.maps.Polyline({
			strokeColor:   color,
			strokeOpacity: 0.8,
			strokeWeight:  3,
			map:           this.map,
			path:          path
		});

		this.load(path, mode);
		this.poly.push(this.poli);
		return this.poli;
	};
	this.removerLinha        = function ()
	{
		this.poli.setMap(null);
		for (var i = 0; i < this.arrayMarker.length; i++ ) {
			this.arrayMarker[i].setMap(null);
		}
		this.arrayMarker.length = 0;

	};

	this.salvarPontos       = function (pontos, mode, color)
	{
		this.pontos = pontos;
	};

	this.criarLinhaMult = function (pontos, mode, color, rota)
	{
		if (color == "") {
			color = 'black';
		}
		path = this.formarVetorGoogle(pontos);
		poli = new google.maps.Polyline({
			strokeColor:   color,
			strokeOpacity: 0.8,
			strokeWeight:  3,
			map:           this.map,
			path:          path
		});
		this.loadMult(path, mode, rota);
		this.poly.push(poli);
		this.lista_pontos = path;
		this.enquadrarLatLng(path);
	};

	this.enquadrarLatLng = function (pontos)
	{
		this.limite = new google.maps.LatLngBounds();
		for (var i = 0; i < this.lista_pontos.length; i++) {
			this.limite.extend(pontos[i]);

		}
		this.map.fitBounds(this.limite);

	};

    this.criarDirectionServices = function(pontos,color, colorMarker, stroke)
    {
        /*Com a nova alteração do plano do google, o limit de waipoints é de 10 contendo 25 pontos,
        ou seja, 250 lat/lng. caso um dia venha a aumentar esse limit,é só passar o 'pontos' direto para a função de formarVetor*/
        if(pontos.length > 240)
        {
            var vetor = [];
            vetor.push(pontos[0]);
            for(var v = 1, div =  Math.ceil(pontos.length/240);v < pontos.length;v = v + div)
            	vetor.push(pontos[v]);
        }else
            vetor = pontos;

        var path = this.formarVetorGoogle(vetor);
        service = this.directionsService;
        var map = this.map;
        //TRECHO REMOVIDO POIS BUGAVA ALGUMAS SITUACOES
        //esse laço for calcula a distancia entre cada ponto para não considerar pontos a menos de 5 metros
        // var path=[];
        //adiciona obrigatoriamente o primeiro ponto
        // path.push(pathAntesDeFormatar[0]);
        // for (var q = 0;q<pathAntesDeFormatar.length-1;q++){
        // if(google.maps.geometry.spherical.computeDistanceBetween(pathAntesDeFormatar[q],pathAntesDeFormatar[q+1])>5){
        // 	path.push(pathAntesDeFormatar[q+1]);
        // }
        // }
        //adiciona obrigatoriamente o ultimo ponto também
        // path.push(pathAntesDeFormatar[pathAntesDeFormatar.length-1]);

        //funcao para dividir todos os pontos em partes de 25 pois a api do google limita para esse valor
        //entao sao criados varios caminhos de 25 pontos
        for (var i = 0, parts = [], max = 24; i < path.length; i = i + max)
            parts.push(path.slice(i, i + max + 1));

        var service_callback = function(response, status) {
            if (status != 'OK') {
                console.log('Directions request failed due to ' + status);
                return;
            }
			if(color)
				color = color;
			else
				color = "black";

			if (stroke)
				stroke = stroke;
			else
				stroke = 2;

            var renderer = new google.maps.DirectionsRenderer;
            renderer.setMap(map);
            renderer.setOptions({ suppressMarkers: true, preserveViewport: true, polylineOptions:{strokeColor:color,strokeWeight:stroke}});
            renderer.setDirections(response);
            fx(response.routes[0]); //funcao para desenhar as flechas no caminho
        };

        for (var j = 0; j < parts.length; j++) {
            // Waypoints does not include first station (origin) and last station (destination)
            var waypoints = [];
            for (var k = 1; k < parts[j].length - 1; k++){
                waypoints.push({location: parts[j][k], stopover: true});
            }

            var service_options = {
                origin: parts[j][0],
                destination: parts[j][parts[j].length - 1],
                waypoints: waypoints,
                travelMode: 'DRIVING'
            };
            // Send request
            service.route(service_options, service_callback);
        }
        if (colorMarker)
        	colorMarker = colorMarker
		else
			colorMarker = 'azul';
        //funcao para desenhar as arrows
        function fx(o)
        {
            if(o && o.legs)
            {
                for(l=0;l<o.legs.length;++l)
                {
                    var leg=o.legs[l];
                    for(var s=0;s<leg.steps.length;s++)
                    {
                        var step=leg.steps[s];
                        var a=(step.lat_lngs.length)?step.lat_lngs[0]:step.start_point;
                        var z=(step.lat_lngs.length)?step.lat_lngs[1]:step.end_point;
                        var dir=((Math.atan2(z.lng()-a.lng(),z.lat()-a.lat())*180)/Math.PI)+360;
                        var ico=((dir-(dir%3))%120);
                        new google.maps.Marker({
                            position: a,
							// "img/setas/" + file
                            icon: new google.maps.MarkerImage('img/setas/'+colorMarker+'/dir_'+ico+'.png',
                                new google.maps.Size(24,24),
                                new google.maps.Point(0,0),
                                new google.maps.Point(12,12)
                            ),
                            map: map,
                            title: Math.round((dir>360)?dir-360:dir)+'°'
                        });

					}
                }

			}
        }
	}

	//fim directions
}
/**
 * INICIO DAS FUNÇÕES DE SETAS PARA POLYLINE
 */

	// Extends OverlayView from the Maps API
GoogleMapsV3.prototype = new google.maps.OverlayView();

// Draw is inter alia called on zoom change events.
// So we can use the draw method as zoom change listener
GoogleMapsV3.prototype.draw = function ()
{
	if (this.arrowheads.length > 0) {
		for (var i = 0, m; m = this.arrowheads[i]; i++) {
			m.setOptions({position: this.usePixelOffset(m.p1, m.p2)});
		}
	}
};

// Computes the length of a polyline in pixels
// to adjust the position of the 'head' arrow
GoogleMapsV3.prototype.usePixelOffset = function (p1, p2)
{
	var proj = this.getProjection();
	var g    = google.maps;
	var dist = 12; // Half size of triangle icon

	var pix1   = proj.fromLatLngToContainerPixel(p1);
	var pix2   = proj.fromLatLngToContainerPixel(p2);
	var vector = new g.Point(pix2.x - pix1.x, pix2.y - pix1.y);
	var length = Math.sqrt(vector.x * vector.x + vector.y * vector.y);
	var normal = new g.Point(vector.x / length, vector.y / length);
	var offset = new g.Point(pix2.x - dist * normal.x, pix2.y - dist * normal.y);

	return proj.fromContainerPixelToLatLng(offset);
};

// Returns the triangle icon object
GoogleMapsV3.prototype.addIcon = function (file)
{
	var g    = google.maps;
	var icon = new g.MarkerImage("img/setas/" + file,
		new g.Size(24, 24), null, new g.Point(12, 12));
	return icon;
};

// Creates markers with corresponding triangle icons
GoogleMapsV3.prototype.create = function (p1, p2, mode)
{
	this.markerpos;
	var cor = '';
	var g   = google.maps;
	if (mode == "onset") {
		this.markerpos = p1;
	} else if (mode == "head") {
		this.markerpos = this.usePixelOffset(p1, p2);
	} else if (mode == "midline") {
		cor            = "azul/";
		this.markerpos = g.geometry.spherical.interpolate(p1, p2, .5);
	}
	else if (mode == "red") {
		this.markerpos = g.geometry.spherical.interpolate(p1, p2, .5);
		cor            = "vermelho/";
	}
	// Compute the bearing of the line in degrees
	var dir = g.geometry.spherical.computeHeading(p1, p2).toFixed(1);
	// round it to a multiple of 3 and correct unusable numbers
	dir = Math.round(dir / 3) * 3;
	if (dir < 0) {
		dir += 240;
	}
	if (dir > 117) {
		dir -= 120;
	}
	// use the corresponding icon
	var icon   = this.addIcon("" + cor + "dir_" + dir + ".png");
	var marker = new g.Marker({
		position: this.markerpos,
		map:      this.map, icon: icon, clickable: false
	});
	if (mode == "head") {
		// Store markers with 'head' arrows to adjust their offset position on zoom change
		marker.p1 = p1;
		marker.p2 = p2;
		// marker.setValues({ p1: p1, p2: p2 });
		this.arrowheads.push(marker);
	}
	this.arrayMarker.push(marker);

	return marker;
};

// Creates markers with corresponding triangle icons
GoogleMapsV3.prototype.createMult = function (p1, p2, mode, rota)
{
	this.markerpos;
	var cor = '';
	var g   = google.maps;
	if (mode == "onset") {
		this.markerpos = p1;
	} else if (mode == "head") {
		this.markerpos = this.usePixelOffset(p1, p2);
	} else if (mode == "midline") {
		cor            = "azul/";
		this.markerpos = g.geometry.spherical.interpolate(p1, p2, .5);
	}
	else if (mode == "red") {
		this.markerpos = g.geometry.spherical.interpolate(p1, p2, .5);
		cor            = "vermelho/";
	}
	// Compute the bearing of the line in degrees
	var dir = g.geometry.spherical.computeHeading(p1, p2).toFixed(1);
	// round it to a multiple of 3 and correct unusable numbers
	dir = Math.round(dir / 3) * 3;
	if (dir < 0) {
		dir += 240;
	}
	if (dir > 117) {
		dir -= 120;
	}
	// use the corresponding icon
	var icon   = this.addIcon("" + cor + "dir_" + dir + ".png");
	var marker = new g.Marker({
		position: this.markerpos,
		map:      this.map, icon: icon, clickable: false
	});

	if (mode == "head") {
		// Store markers with 'head' arrows to adjust their offset position on zoom change
		marker.p1 = p1;
		marker.p2 = p2;
		// marker.setValues({ p1: p1, p2: p2 });
		this.arrowheadsMult[rota].push(marker);
	}
	return marker;
};

GoogleMapsV3.prototype.load     = function (points, mode)
{
	for (var i = 0; i < points.length - 1; i++) {
		var p1 = points[i],
			p2 = points[i + 1];
		this.linhasarrow.push(this.create(p1, p2, mode));
	}
	//alert(this.linhasarrow.length);
};
GoogleMapsV3.prototype.loadMult = function (points, mode, rota)
{
	this.linhasarrowmulti[rota] = [];
	for (var i = 0; i < points.length - 1; i++) {
		var p1 = points[i],
			p2 = points[i + 1];
		this.linhasarrowmulti[rota].push(this.createMult(p1, p2, mode, rota));
	}
	//alert(this.linhasarrow.length);
};
/**
 * FIM DAS SETASS
 */
/*GoogleMapsV3.prototype.PegarEnderecoRota = function(result)
 {
 alert(result);
 }

 */

// LINHAS PARALELAS

GoogleMapsV3.prototype.carregarLinhasParalelas = function (points, color, weight, opacity, gapPx)
{
	this.gapPx                      = gapPx;
	this.points                     = points;
	this.color                      = color;
	this.weight                     = weight;
	this.opacity                    = opacity;
	this.prj                        = null;
	this.line1                      = null;
	this.line2                      = null;
	this.cerca_rota                 = null;
	this.zoomListener               = null;
	this.lista_poligono_cerca_rotas = [];
	this.montarLinhas();
};

GoogleMapsV3.prototype.montarLinhas = function ()
{
	this.map.setZoom(15);
	this.setProjection();
	this.onRemove();
	var foo        = this;
	var zoomRecalc = function ()
	{
		foo.onRemove();
		foo.setProjection();
	};
	this.setProjection();
	//this.zoomListener = google.maps.event.addListener(this.map, 'zoom_changed', zoomRecalc);
};

GoogleMapsV3.prototype.setProjection = function ()
{
	var overlay  = new google.maps.OverlayView();
	overlay.draw = function ()
	{
	};
	overlay.setMap(this.map);
	this.prj = overlay.getProjection();
	this.draww(this.map);
};

GoogleMapsV3.prototype.onRemove = function ()
{
	if (this.cerca_rota) {
		this.cerca_rota.setMap(null);
		this.cerca_rota = null;
	}
	if (this.line2) {
		this.line2.setMap(null);
		this.line2 = null;
	}
	if (this.line1) {
		this.line1.setMap(null);
		this.line1 = null;
	}
	if (this.prj) {
		this.prj = null;
	}
	if (this.zoomListener != null) {
		google.maps.event.removeListener(this.zoomListener);
	}
};
GoogleMapsV3.prototype.draww    = function (map)
{
	if (this.cerca_rota) {
		this.cerca_rota.setMap(null);
		this.cerca_rota = null;
	}
	if (this.line1) {
		this.line1.setMap(null);
		this.line1 = null;
	}
	if (this.line2) {
		this.line2.setMap(null);
		this.line2 = null;
	}
	this.recalc();
	return;
}

GoogleMapsV3.prototype.redraw = function (force)
{
	return; //do nothing
}

GoogleMapsV3.prototype.recalc = function ()
{
   this.cerca_rota = new google.maps.Polyline({
       strokeWeight:  10,
       strokeOpacity: 0.5,
	   path: this.points,
	   geodesic: true,
	   strokeColor: 'blue'
    });

    this.cerca_rota.setMap(this.map);
    this.lista_poligono_cerca_rotas.push(this.cerca_rota);

   //ESSA PARTE DO CODIGO FOI COMENTADA POR DEIXAR ZUADAO O CERCA DE ROTA..

	// var zoom = this.map.getZoom();
	// //left and right swapped throughout!
	//
	// var pts1 = [];//left side of center
	// var pts2 = [];//right side of center
	//
	// //shift the pts array away from the centre-line by half the gap + half the line width
	// var o = (this.gapPx + this.weight) / 2;
	//
	// var p2l, p2r;
	//
	// for (var i = 1; i < this.points.length; i++) {
	// 	var p1lm1;
	// 	var p1rm1;
	// 	var p2lm1;
	// 	var p2rm1;
	// 	var thetam1;
	//
	// 	var p1    = this.prj.fromLatLngToContainerPixel(this.points[i - 1]);
	// 	var p2    = this.prj.fromLatLngToContainerPixel(this.points[i]);
	// 	var theta = Math.atan2(p1.x - p2.x, p1.y - p2.y) + (Math.PI / 2);
	// 	var dl    = Math.sqrt(((p1.x - p2.x) * (p1.x - p2.x)) + ((p1.y - p2.y) * (p1.y - p2.y)));
	// 	if (theta > Math.PI) {
	// 		theta -= Math.PI * 2;
	// 	}
	// 	var dx = Math.round(o * Math.sin(theta));
	// 	var dy = Math.round(o * Math.cos(theta));
	//
	// 	var p1l = new google.maps.Point(p1.x + dx, p1.y + dy);
	// 	var p1r = new google.maps.Point(p1.x - dx, p1.y - dy);
	// 	p2l     = new google.maps.Point(p2.x + dx, p2.y + dy);
	// 	p2r     = new google.maps.Point(p2.x - dx, p2.y - dy);
	//
	// 	if (i == 1) {   //first point
	// 		pts1.push(this.prj.fromContainerPixelToLatLng(p1l));
	// 		pts2.push(this.prj.fromContainerPixelToLatLng(p1r));
	// 	}
	// 	else { // mid points
	//
	// 		if (theta == thetam1) {
	// 			// adjacent segments in a straight line
	// 			pts1.push(this.prj.fromContainerPixelToLatLng(p1l));
	// 			pts2.push(this.prj.fromContainerPixelToLatLng(p1r));
	// 		}
	// 		else {
	// 			var pli = this.intersect(p1lm1, p2lm1, p1l, p2l);
	// 			var pri = this.intersect(p1rm1, p2rm1, p1r, p2r);
	//
	// 			var dlxi = (pli.x - p1.x);
	// 			var dlyi = (pli.y - p1.y);
	// 			var drxi = (pri.x - p1.x);
	// 			var dryi = (pri.y - p1.y);
	// 			var di   = Math.sqrt((drxi * drxi) + (dryi * dryi));
	// 			var s    = o / di;
	//
	// 			var dTheta = theta - thetam1;
	// 			if (dTheta < (Math.PI * 2)) {
	// 				dTheta += Math.PI * 2;
	// 			}
	// 			if (dTheta > (Math.PI * 2)) {
	// 				dTheta -= Math.PI * 2;
	// 			}
	//
	// 			if (dTheta < Math.PI) {
	// 				//intersect point on outside bend
	// 				pts1.push(this.prj.fromContainerPixelToLatLng(p2lm1));
	// 				pts1.push(this.prj.fromContainerPixelToLatLng(new google.maps.Point(p1.x + (s * dlxi), p1.y + (s * dlyi)), zoom));
	// 				pts1.push(this.prj.fromContainerPixelToLatLng(p1l));
	// 			}
	// 			else if (di < dl) {
	// 				pts1.push(this.prj.fromContainerPixelToLatLng(pli));
	// 			}
	// 			else {
	// 				pts1.push(this.prj.fromContainerPixelToLatLng(p2lm1));
	// 				pts1.push(this.prj.fromContainerPixelToLatLng(p1l));
	// 			}
	//
	// 			var dxi = (pri.x - p1.x) * (pri.x - p1.x);
	// 			var dyi = (pri.y - p1.y) * (pri.y - p1.y);
	// 			if (dTheta > Math.PI) {
	// 				//intersect point on outside bend
	// 				pts2.push(this.prj.fromContainerPixelToLatLng(p2rm1));
	// 				pts2.push(this.prj.fromContainerPixelToLatLng(new google.maps.Point(p1.x + (s * drxi), p1.y + (s * dryi)), zoom));
	// 				pts2.push(this.prj.fromContainerPixelToLatLng(p1r));
	// 			}
	// 			else if (di < dl) {
	// 				pts2.push(this.prj.fromContainerPixelToLatLng(pri));
	// 			} else {
	// 				pts2.push(this.prj.fromContainerPixelToLatLng(p2rm1));
	// 				pts2.push(this.prj.fromContainerPixelToLatLng(p1r));
	// 			}
	// 		}
	// 	}
	//
	// 	p1lm1   = p1l;
	// 	p1rm1   = p1r;
	// 	p2lm1   = p2l;
	// 	p2rm1   = p2r;
	// 	thetam1 = theta;
	//
	// }
	//
	// pts1.push(this.prj.fromContainerPixelToLatLng(p2l));//final point
	// pts2.push(this.prj.fromContainerPixelToLatLng(p2r));
	// pts2.reverse();
	// if (this.line1) {
	// 	this.line1.setMap(null);
	// }
	// if (this.cerca_rota) {
	// 	this.cerca_rota.setMap(null);
	// }
	//
	// /*
	//  this.line1 = new google.maps.Polyline({
	//  path: pts1,
	//  strokeColor: this.color,
	//  strokeOpacity: this.opacity,
	//  strokeWeight: this.weight
	//  });
	//  this.line1.setMap(this.map);
	//  if(this.line2)
	//  this.line1.setMap(null);
	//  this.line2 = new google.maps.Polyline({
	//  path: pts2,
	//  strokeColor: this.color,
	//  strokeOpacity: this.opacity,
	//  strokeWeight: this.weight
	//  });
	//  this.line2.setMap(this.map);
	//  */
	// var foobar      = pts1.concat(pts2);
    // var newcolor = this.getRandomColor();
	// this.cerca_rota = new google.maps.Polygon({
	// 	paths:         foobar,
	// 	strokeColor:   newcolor,
	// 	strokeOpacity: 0.0,
	// 	strokeWeight:  this.weight,
	// 	fillColor:     newcolor,
	// 	fillOpacity:   0.35
	// });
	// this.cerca_rota.setMap(this.map);
	// this.lista_poligono_cerca_rotas.push(this.cerca_rota);

};

GoogleMapsV3.prototype.intersect = function (p0, p1, p2, p3)
{
// this function computes the intersection of the sent lines p0-p1 and p2-p3
// and returns the intersection point,

	var a1, b1, c1, // constants of linear equations
		a2, b2, c2,
		det_inv,  // the inverse of the determinant of the coefficient matrix
		m1, m2;    // the slopes of each line

	var x0 = p0.x;
	var y0 = p0.y;
	var x1 = p1.x;
	var y1 = p1.y;
	var x2 = p2.x;
	var y2 = p2.y;
	var x3 = p3.x;
	var y3 = p3.y;

// compute slopes, note the cludge for infinity, however, this will
// be close enough

	if ((x1 - x0) != 0) {
		m1 = (y1 - y0) / (x1 - x0);
	} else {
		m1 = 1e+10;
	}   // close enough to infinity

	if ((x3 - x2) != 0) {
		m2 = (y3 - y2) / (x3 - x2);
	} else {
		m2 = 1e+10;
	}   // close enough to infinity

// compute constants

	a1 = m1;
	a2 = m2;

	b1 = -1;
	b2 = -1;

	c1 = (y0 - m1 * x0);
	c2 = (y2 - m2 * x2);

// compute the inverse of the determinate

	det_inv = 1 / (a1 * b2 - a2 * b1);

// use Kramers rule to compute xi and yi

	var xi = ((b1 * c2 - b2 * c1) * det_inv);
	var yi = ((a2 * c1 - a1 * c2) * det_inv);

	return new google.maps.Point(Math.round(xi), Math.round(yi));





};


