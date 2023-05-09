<?
$periodo = $_REQUEST["periodo"];
$idveiculo = $_REQUEST["veiculo"];

$objHome = new Home();
$dadostrajeto = $objHome->GerarPosicao($idveiculo,$periodo);

$html = "
            <div class=\"row\">
                <div class=\"col-12\">
                    <div class=\"card\">
                         <div class=\"card-body\">
                            <h4 class=\"card-title\"><i class=\"fas fa-road\"></i> LISTAGEM DE POSIÇÃO</h4>
                                <table id=\"myTable\" class=\"table table-bordered table-striped\ ">
                                    <thead>
                                    <tr>
                                        <th style=\"font-size: 10px !important;\">RÓTULO</th>
                                        <th style=\"font-size: 10px !important;\">IGNICAO</th>
                                        <th style=\"font-size: 10px !important;\">VELOCIDADE</th>
                                        <th style=\"font-size: 10px !important;\">DATA</th>
                                        <th style=\"font-size: 10px !important;\">ODOMETRO</th>
                                        <th style=\"font-size: 10px !important;\">HORIMETRO</th>
                                        <th style=\"font-size: 10px !important;\">ENDEREÇO</th>
                                    </tr>
                                    </thead>
                                    <tbody>
            ";
$arrayLogradouros = " var vetor = new Array();";
echo($html);
$html1 = "";
$count_log = 0;
if(@count($dadostrajeto)> 0){
    foreach($dadostrajeto as $linha) {

        $rotulo = " " . $linha['placa'];
        if ($linha['ignicao']=="Ligado") {
            $ignicao = "<a  data-toggle='tooltip' style='cursor: default' alt='Ignição Ligada' title='" . $ini . "'><i class='fal fa-car fa-2x'style='color: green;'></i></a>";
        }else {
            $ignicao = "<a data-toggle='tooltip' style='cursor: default' alt='Ignição Desligada' title='" . $des . "'><i class='fal fa-car fa-2x ' style='color: red; ' ></i></a>";
        }
        $velocidade =" " . $linha['velocidade'] ." Km/h" ;
        $datahora=Conexao::PrepararDataPHP($linha["data_hora"], $_SESSION["usuario"]["id_fuso_horario"]);
        $odometro=round($linha['odometro']/1000,0);
        $horimetro=round($linha['horimetro']/60,0);

        if ($linha['logradouro'] == "") {
            $arrayLogradouros .= "\n	vetor.push({id:'logradouro{$count_log}',lat:{$linha['latitude']},lon:{$linha['longitude']},tabela:'{$linha['tabela']}',id_posicao:'{$linha['id']}',embarcacao:0, posicao_periodo: true});";
            $logradouro = "<div id=\"logradouro{$count_log}\">Aguarde...</div>";

        } else {
            //$logradouro = "<div data-toggle=\"tooltip\" title=\"".$logLigou."\" data-original-title=\"".$logLigou."\">{$logLigou} </div>";
            $logradouro = $linha['logradouro'];
        }
        $count_log++;

        $html1 = $html1 . "
              <tr>
                 <td><h4><span class=\"badge badge-inverse\"><i class=\"fas fa-car\"></i>$rotulo</span></h4></td>
                 <td><small> $ignicao</small></td>
                 <td>
                    <h4><span class=\"badge badge-inverse\"><i class=\"fas fa-tachometer-alt\"></i>$velocidade</span></h4></td>
                 </td>
                  <td>
                     <h4><span class=\"badge badge-inverse\"><i class=\"fas fa-watch\"></i> $datahora</span></h4>
                  </td>
                  <td >
                     <h5><span class=\"badge badge-warning\"><i class=\"fas fa-road\"></i> $odometro</span> </h5>
                  </td>
                  <td>
                    <h5><span class=\"badge badge-primary \"><i class=\"fas fa-clock\"></i> $horimetro</span> </h5>
                  </td>
                  <td><small>$logradouro</small></td>
              </tr>
        
        ";


    }
}

echo($html1);

$html2="
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

";

echo($html2);

?>
<script type="text/javascript">
    $(document).ready(function () {
        var vetor = new Array();
        <?
        echo $arrayLogradouros;
        ?>
        objLogradouro = new Logradouro(vetor, parent.ifrEvento, "relatorios", "obter_logradouro", "", <?=$_SESSION['usuario']['id_grupo']?>);
        objLogradouro.GerarLogradouro();
    });
</script>
<iframe width="0" height="0" frameborder="no" id="ifrEvento" style="width: 0;height: 0;" name="ifrEvento"></iframe>
