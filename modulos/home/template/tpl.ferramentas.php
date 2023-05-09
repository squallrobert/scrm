<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 18/11/2021
 * Time: 17:33
 */
$objMenu = new Menu();
$ferramentas = $objMenu->ListarFerramentas();
foreach ($ferramentas as $linha) {

    if (!file_exists($linha->icone))
        continue;

    if ($linha->favorito == 1){
        $class = "fa";
        $onclick = "RemoverFavorito($linha->id_modulo, {$_SESSION['usuario']['id']})";
        $title = "Remover dos favoritos";
    }else{
        $class = "fal";
        $onclick = "AdicionarFavorito($linha->id_modulo, {$_SESSION['usuario']['id']})";
        $title = "Adicionar aos favoritos";
    }
    $url = "#$linha->index.php?app_modulo=$linha->diretorio&app_comando=$linha->acao&app_codigo=";
    echo "<div class=\"col-4 col-sm-3 col-md-3 col-lg-2 col-xl-1 justify-content-center align-items-center mb-g element-item transition metal\" onclick=\"AbrirApp(event, '#index_xml.php?app_modulo=$linha->diretorio&app_comando=$linha->acao');\" data-category=\"alkaline-earth\">";
    echo "    <a href=\"#index_xml.php?app_modulo=$linha->diretorio&app_comando=$linha->acao\" class=\" rounded bg-white p-0 m-0 d-flex flex-column w-100 h-100 js-showcase-icon shadow-hover-2\"  id=\"$linha->id_modulo\" title=\"$linha->nome_modulo\" data-id_app_modulo=\"$linha->id_app_modulo\" ";
    echo "       data-toggle=\"modal\" data-target=\"#iconModal\" data-filter-tags=\"address-book\">";
    echo "        <div class=\"rounded-top color-fusion-300 w-100 bg-primary-300\">";
    echo "            <div class=\"rounded-top d-flex align-items-center justify-content-center w-100 pt-3 pb-3 pr-2 pl-2 fa-3x hover-bg\"><img src='$linha->icone' class='img-fluid'><span class=\"badge rounded-pill bg-success position-absolute pos-top pos-right\" style='font-size: 10px; right: 8px; top:8px' onclick='$onclick' id='add_favorito' data-toggle='tooltip' title='$title'><i id='icon_add_favorito' class='$class fa-star text-white icon_add_favorito_$linha->id_modulo'></i></span>";
    echo "            </div>";
    echo "        </div>";
    echo "        <div class=\"rounded-bottom p-1 w-100 d-flex justify-content-center align-items-center text-center\"><span class=\"d-block text-truncate text-muted\">$linha->nome_modulo</span>";
    echo "        </div>";
    echo "    </a>";
    echo "</div>";
}