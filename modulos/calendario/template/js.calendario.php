<script>
    $(function (){
        Squall.autoComplete('#fotografo', 'index_xml.php?app_modulo=usuario&app_comando=popup_localizar_usuarios_mapas');
        $('#status').select2();
        GerarCalendario();

        $('[data-toggle="tooltip"]').tooltip();	$("#busca").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                GerarCalendario();
                return false;
            } else {
                return true;
            }
        });
    });

    function PegarEventos(calendar){
        var url = 'index_xml.php?app_modulo=calendario&app_comando=listar_itens_calendario';
        var eventos = [];

        $.ajax({
            type: 'POST',
            url: url,
            async: false,
            data: $('#frm-calendario').serializeArray(),
            success: function (data) {
                var corpo = JSON.parse(data);
                eventos = corpo;
            }
        });
        return eventos

    }
    function GerarCalendario() {
        // Define variables
        var calendarEl = document.getElementById("kt_docs_fullcalendar_locales");

        // initialize the calendar -- for more info please visit the official site: https://fullcalendar.io/demos
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
            },
            // themeSystem: 'bootstrap5',
            defaultDate: moment(),
            locale: 'pt-br',
            themeSystem: 'bootstrap5',
            initialView: 'timeGridDay',
            buttonIcons: false, // show the prev/next text
            weekNumbers: true,
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            dayMaxEvents: true, // allow "more" link when too many events
            eventTimeFormat:
            {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            events:(arg, callback)=>{
                $('#data_hora_inicio').val(moment(arg.start).format("YYYY-MM-DD HH:mm:ss"));
                $('#data_hora_fim').val(moment(arg.end).format("YYYY-MM-DD HH:mm:ss"));
                var eventos = PegarEventos(calendar);
                if(eventos != null){
                    callback(eventos)
                }
            },
            eventClick: function(info) {
                window.location.href = '#index_xml.php?app_modulo=servicos&app_comando=visualizar_servico&app_codigo='+info.event._def.publicId;
            }
        });

        calendar.render();
    }



</script>
