<?php
class App{
    public $include_topo = 'template/topo.php'; // caminho do arquuivo de header
    public $include_body = 'template/body.php'; // caminho do arquuivo de header
    public $include_footer = 'template/foot.php'; // caminho do arquivo de footer
    public $include_401 = 'template/401_unauthorized.php'; // caminho do arquivo de 401
    public $include_404 = 'template/404_unauthorized.php'; // caminho do arquivo de 404
    public $include_404_template = 'template/404_unauthorized_template.php'; // caminho do arquivo de 404
    public $modulo = 'home';
    public $acao = 'home';
    public $codigo = '';
    public $dir_idioma = DIR_IDIOMA;
    public $url_site = '';
    public $layout_show = true;
    public $js_header = false;
    public $js_footer = true;
    public $sessao ;
    public $modulo_login = "modulos/login/modulo.login.php" ;
    public $template_login = "template/login.php" ;
    public $template_cadastro = "template/cadastro.php" ;

    public function ExecutarModulo()
    {
        $app_comando = $this->acao;
        $app_codigo = $this->codigo;
        if($this->sessao)
        {
            $objUsuario = new Usuario();
            $objGrupo = new Grupo();
            // SE O USUÁRIO FOI CONFIGURADO COM PERMISSÕES ESPECIFICAS PARA USER
            if($this->sessao['usuario']['permissao_especifica'])
                $permissao = $objUsuario->ChecarPermissao($this->sessao['usuario']['id'], $this->acao);
            else
                $permissao = $objGrupo->ChecarPermissao($this->sessao['usuario']['id_grupo'], $this->acao);

            // verifica se é apra mostrar o topo
            if($this->layout_show)
            {
//                include_once $this->include_topo;
                include_once $this->include_body;
            }


            if (!$permissao && $this->acao != "" && $this->modulo != "home") {
                include $this->include_401;
            } else if (($permissao && $this->modulo  != "" && $this->acao  != "") || $this->modulo == 'home') {

                $arquivo = "modulos/" . $this->modulo . "/modulo." . $this->modulo . ".php";
                if (file_exists($arquivo) && !is_dir($arquivo)) {
                    $arquivoIdioma = $this->url_site . "idioma/" . $this->dir_idioma . "/modulos/$this->modulo.loc.php";
                    if (file_exists($arquivoIdioma)) {
                        include_once $arquivoIdioma;
                    }
                    include($this->url_site . $arquivo);
                } else {
                    include_once $this->include_404;
                }
                // variavel template vem de dentro do arquivo de módulo
                $temp = $this->url_site . "modulos/" . $this->modulo . "/template/" . $template;
                if (file_exists($temp) && !is_dir($temp)) {
                    include($temp);
                } else {
                    include_once $this->include_404;
                }
            }
            // verifica se e para mostrar o footer
            if($this->layout_show)
                include_once $this->include_footer;
        }
        else
        {
            if($app_comando == "salvar_autorizacao")
            {
                $arquivo = "modulos/drop_box/modulo.drop_box.php";

                // variavel template vem de dentro do arquivo de módulo
                $temp = $this->url_site . "modulos/drop_box/template/" . $template;
                if (file_exists($temp) && !is_dir($temp)) {
                    include($temp);
                } else {
                    include_once $this->include_404;
                }
            }
            else{
                if($app_comando == "")
                    $app_comando = "login";

                $arquivo = "modulos/login/modulo.login.php";
                if (file_exists($arquivo) && !is_dir($arquivo))
                    include($this->url_site . $arquivo);
                else
                    include_once $this->include_404;

                // variavel template vem de dentro do arquivo de módulo
                $temp = $this->url_site . "modulos/login/template/" . $template;
                if (file_exists($temp) && !is_dir($temp))
                    include($temp);
                else
                    echo '<script> window.location.href = "index.php"</script>';
            }


        }
//        else
//        {
//            if($app_comando == "cadastro")
//                include($this->url_site . $this->template_login);
//            else
//                include($this->url_site . $this->template_login);
//        }

    }

    public function GerarBreadCrumb($dados,$adicional = "")
    {
        $html = "<!--begin::Toolbar-->\n";
        $html .= "<div id=\"kt_app_toolbar\" class=\"app-toolbar  py-3 py-lg-6 \">\n";
        $html .= "    <!--begin::Toolbar container-->\n";
        $html .= "    <div id=\"kt_app_toolbar_container\" class=\"app-container  container-fluid d-flex flex-stack \">\n";
        $html .= "        <!--layout-partial:layout/partials/_page-title.html-->\n";
        $html .= "        <!--begin::Page title-->\n";
        $html .= "        <div  class=\"page-title d-flex flex-column justify-content-center flex-wrap me-3 \">\n";
        $html .= "            <!--begin::Title-->\n";
        $html .= "            <h1 class=\"page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0\">\n";
        $html .= "                ".$dados['titulo_modulo']."\n";
        $html .= "            </h1>\n";
        $html .= "            <!--end::Title-->\n";
        $html .= "            <!--begin::Breadcrumb-->\n";
        $html .= "            <ul class=\"breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1\">\n";
        $html .= "                <!--begin::Item-->\n";
        $html .= "                <li class=\"breadcrumb-item text-muted\">\n";
        $html .= "                    <a href=\"?page=index\" class=\"text-muted text-hover-primary\"> ".$dados['titulo_agrupamento_modulo']."</a>\n";
        $html .= "                </li>\n";
        $html .= "                <!--end::Item-->\n";
        $html .= "                <!--begin::Item-->\n";
        $html .= "                <li class=\"breadcrumb-item\"> <span class=\"bullet bg-gray-400 w-5px h-2px\"></span></li>\n";
        $html .= "                <!--end::Item-->\n";
        $html .= "                <!--begin::Item-->\n";
        $html .= "                <li class=\"breadcrumb-item text-muted\">".$dados['titulo_modulo']."</li>\n";
        $html .= "                <!--end::Item-->\n";
        $html .= "            </ul>\n";
        $html .= "            <!--end::Breadcrumb-->\n";
        $html .= "        </div>\n";
        $html .= "        <!--end::Page title-->\n";
        if($adicional) $html .= $adicional;
        $html .= "    </div>\n";
        $html .= "    <!--end::Toolbar container-->\n";
        $html .= "</div>\n";
        $html .= "<!--end::Toolbar-->\n";
        return $html;
    }

    public function GerarCardContainer($dados,$conteudo = "",$footer = "",$cardtoolbar = "",$altura = "",$conteudo_header = "")
    {
        $html = "<!--begin::Content container-->\n";
        $html .= "<div id=\"kt_app_content_container\" class=\"app-container  p-0\">\n";
        if($conteudo_header!= "")
        {
            $html .= $conteudo_header;
        }
        $html .= "    <div class=\"card shadow-sm\">\n";
        $html .= "        <div class=\"card-header\">\n";
        $html .= "            <h3 class=\"card-title\"> ".$dados['titulo_card']."</h3>\n";
        $html .= "            <div class=\"card-toolbar\">\n";
//        $html .= "                <button type=\"button\" class=\"btn btn-sm btn-light\">\n";
        $html .= $cardtoolbar;
//        $html .= "                </button>\n";
        $html .= "            </div>\n";
        $html .= "        </div>\n";
        if($conteudo!= "")
        {
            $html .= "        <div class=\"card-body\" id=\"".$dados['id_card']."\">\n";
            $html .= $conteudo;
            $html .= "        </div>\n";
        }
        else
        {
            $html .= "        <div class=\"card-body $altura\" id=\"".$dados['id_card']."\">\n";
            $html .= "            <div class=\"fa-2x\"><i class=\"fa  fs-2x fa-solid fa-spinner fa-spin-pulse\"></i>  Carregando...</div>\n";
            $html .= "        </div>\n";
        }
       if($footer != ""){
           $html .= "        <div class=\"card-footer\">\n";
           $html .= $footer;
           $html .= "        </div>\n";
       }
       else
       {
           $html .= "        <div class=\"card-footer\">\n";
           $html .= "        </div>\n";
       }
        $html .= "    </div>\n";
        $html .= "</div>\n";
        return $html;
    }

}
