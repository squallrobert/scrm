function Logradouro(elementos,iframe,app_modulo,app_comando,app_codigo,id_grupo,sem_referencia,separar_endereco)
{
	//sem referencia vem como 1 quando não quiser que tenha referência, o que acelera muito a busca
	this.fila = elementos;
	this.ifr_evento = iframe;
	this.app_comando = app_comando;
	this.app_modulo = app_modulo;
	this.app_codigo = app_codigo;
	this.id_grupo = id_grupo;
	this.sem_referencia = sem_referencia;
	//se passar esse parametro como 1 ele separa o endereco em rua, cidade e estado em campos separados
	this.separar_endereco = separar_endereco;
	this.GerarLogradouro = function()
	{
		if(this.fila.length)
		{
			$("#"+this.fila[0].id).html('<img src="assets/images/preload.gif" alt="preload" />');
			this.ifr_evento.location.href = "logradouro.php?app_comando="+this.app_comando+"&app_modulo="+this.app_modulo+"&app_codigo="+this.fila[0].tabela+"&latitude="+this.fila[0].lat+"&id_posicao="+this.fila[0].id_posicao+"&longitude="+this.fila[0].lon+"&embarcacao="+this.fila[0].embarcacao+"&id_grupo="+this.id_grupo+"&sem_referencia="+this.sem_referencia;
			$("#contador_logradouros").val(this.fila.length-1).trigger("change");
		}
	};
	this.SubstituirLogradouro = function(logradouro, latitude, longitude, id_posicao, atualizar, tbPosicao,logradouro_cidade,logradouro_estado,logradouro_endereco)
	{
		var att = " <span class='fa fa-spin fa-refresh' style='font-size: 14px; cursor: pointer;' data-toggle='tooltip' title='Atualizar' onclick='AtualizarLogradouroUltimas(" + latitude + ", " + longitude + ", " + id_posicao + ", this, " + tbPosicao + ")'></span>";

        if (atualizar) {
            $("#"+this.fila[0].id).html(logradouro + att);
        } else {
            if(separar_endereco){
                $("#"+this.fila[0].id).html(logradouro_endereco);
                $("#"+this.fila[0].id).parent().next('.logradouro_cidade').html(logradouro_cidade);
                $("#"+this.fila[0].id).parent().next('.logradouro_cidade').next('.logradouro_estado').html(logradouro_estado);
            }else{
                $("#"+this.fila[0].id).html(logradouro);
			}
        }
		this.fila.splice(0,1);
		this.GerarLogradouro();
	};
}

function PontoReferencia(elementos,iframe,id_grupo)
{
	this.fila = elementos;
	this.ifr_evento = iframe;
	this.id_grupo = id_grupo;
	this.GerarReferencia = function()
	{
		if(this.fila.length)
		{
			$("#"+this.fila[0].id).html('<img src="assets/images/preload.gif" alt="preload" />');
			this.ifr_evento.location.href = "pontos_proximos.php?latitude="+this.fila[0].lat+"&id_posicao="+this.fila[0].id_posicao+"&longitude="+this.fila[0].lon+"&id_grupo="+this.id_grupo+"&tabela="+this.fila[0].tabela;
		}
	};
	this.AdicionarReferencia = function(ponto_referencia)
	{
		$("#"+this.fila[0].id).html(ponto_referencia);
		this.fila.splice(0,1);
		this.GerarReferencia();
	};
}