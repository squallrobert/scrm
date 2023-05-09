
<script type="text/javascript">

    var pontosVetor = [];
    var objMapa                 = new GoogleMaps();

    $(document).ready(function ()
    {
        Inicializar('map');
    });

    // Após inicializar o mapa será inicializado os pontos que serão mostrados,
    // no módulo mapa é adicionado os pontos e aqui eles serão impressos em javascript
    // para serem adicionados no mapa através da variavel $adicionaPontos e pontosVetor
    function MostrarPontos()
    {
        // var pontosVetor = [
        //     {lng: -49.334473, lat: -25.43346, title: "", icon: "assets/images/rota_meio.png", html: "<div style='font: bold 12px verdana,arial,sans-ser…Mapa(-25.43346, -49.334473)'></span><p></p></div>"},
        //     {lng: -49.334461, lat: -25.4335, title: "", icon: "upload/icones/462171555462849.png", html: "<div style='font: bold 12px verdana,arial,sans-ser…oMapa(-25.4335, -49.334461)'></span><p></p></div>"}];

        <?php

            echo  $adicionaPontos ;

            echo "
            
            if(pontosVetor.length > 0) {
                console.log(pontosVetor);
            objMapa.adicionarPontoVeiculo(pontosVetor);
            objMapa.EnquadrarPontos();
            }
        ";
        ?>;
    }

    function Inicializar(mapa)
    {

        objMapa.lista_pontos    = [];
        objMapa.plotandoPontos  = [];
        objMapa.poly            = [];
        objMapa.lat_zoom = -25.44051;
        objMapa.lng_zoom = -49.23656;
        objMapa.inicialize(mapa);
        MostrarPontos();
    }

    (function() {


        var trows = document.getElementById('myTable').rows, t = trows.length, trow, nextrow,
            addEvent = (function(){return window.addEventListener? function(el, ev, f){
                el.addEventListener(ev, f, false); //modern browsers
            }:window.attachEvent? function(el, ev, f){
                el.attachEvent('on' + ev, function(e){f.apply(el, [e]);}); //IE 8 and less
            }:function(){return;}; //a very old browser (IE 4 or less, or Mozilla, others, before Netscape 6), so let's skip those
            })();

        while (--t > -1) {
            trow = trows[t];
            trow.className = 'normal';
            addEvent(trow, 'click', highlightRow);
        }//end while

        function highlightRow(gethighlight) { //now dual use - either set or get the highlighted row
            gethighlight = gethighlight === true;
            var t = trows.length;
            while (--t > -1) {
                trow = trows[t];
                if(gethighlight && trow.className === 'highlighted'){return t;}
                else if (!gethighlight && trow !== this) { trow.className = 'normal'; }
            }//end while

            return gethighlight? null : this.className = this.className === 'highlighted'? 'normal' : 'highlighted';
        }//end function

        function movehighlight(way, e){
            e.preventDefault && e.preventDefault();
            e.returnValue = false;

            var idx = highlightRow(true); //gets current index or null if none highlighted

            if(typeof idx === 'number'){//there was a highlighted row
                idx += way; //increment\decrement the index value
                if (idx && (nextrow = trows[idx]))
                {
                    var table = document.getElementById("myTable");
                    objMapa.AnimarPonto(idx);
                    var latlong=table.rows[idx].cells[6].innerHTML.replace("<small>","").replace("</small>","").split(",");

                    //alert(latlong[0]);
                    objMapa.centralizarPonto(latlong[0],latlong[1],15);

                    //alert(table.rows[idx].cells[6].innerHTML.replace("<small>","").replace("</small>",""));
                    return highlightRow.apply(nextrow);
                } //index is > 0 and a row exists at that index
                else
                if(idx)
                {
                    var table = document.getElementById("myTable");
                    objMapa.AnimarPonto(idx);
                    var latlong=table.rows[idx].cells[6].innerHTML.replace("<small>","").replace("</small>","").split(",");

                    //alert(latlong[0]);
                    objMapa.centralizarPonto(latlong[0],latlong[1],15);

                    //alert(table.rows[idx].cells[6].innerHTML.replace("<small>","").replace("</small>",""));
                    return highlightRow.apply(trows[1]);
                } //index is out of range high, go to first row
                var table = document.getElementById("myTable");
                objMapa.AnimarPonto(idx);
                var latlong=table.rows[idx].cells[6].innerHTML.replace("<small>","").replace("</small>","").split(",");

                //alert(latlong[0]);
                objMapa.centralizarPonto(latlong[0],latlong[1],15);
                return highlightRow.apply(trows[trows.length - 1]); //index is out of range low, go to last row
            }
            var table = document.getElementById("myTable");
            objMapa.AnimarPonto(idx);

            var latlong=table.rows[idx].cells[6].innerHTML.replace("<small>","").replace("</small>","").split(",");

            //alert(latlong[0]);
            objMapa.centralizarPonto(latlong[0],latlong[1],15);
            return highlightRow.apply(trows[way > 0? 1 : trows.length - 1]); //none was highlighted - go to 1st if down arrow, last if up arrow
        }//end function

        function processkey(e){
            switch(e.keyCode){
                case 38: {//up arrow
                    return movehighlight(-1, e)
                }
                case 40: {//down arrow
                    return movehighlight(1, e);
                }
                default: {
                    return true;
                }
            }
        }//end function

        addEvent(document, 'keydown', processkey);

    }/* end function */)();//execute function and end script

</script>