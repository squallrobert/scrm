<script>
    $(document).ready(function (){
        ListarUltimas();
        ListarContador();
        ContadorDiario();
    });
    function ListarUltimas()
    {
        $('#div_ultimas').load('index_xml.php?app_modulo=home&app_comando=listar_ultimas');
    }
    function ListarContador()
    {
        $('#contador').load('index_xml.php?app_modulo=home&app_comando=listar_contador');
    }
    function ContadorDiario()
    {
        $.getJSON("index_xml.php?app_modulo=home&app_comando=contador_diario", function(result) {
            result = result || 0;
            $('#contador_confirmado').html(result[1] || 0);
            $('#contador_andamento').html(result[2] || 0);
            $('#contador_realizado').html(result[7] || 0);
            $('#contador_concluido').html(result[3] || 0);
        });
    }
</script>