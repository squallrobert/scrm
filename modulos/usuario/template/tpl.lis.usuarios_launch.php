<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 07/02/2022
 * Time: 08:54
 */
?>
<form action="#" method="post" id="frm_busca_grupos" name="frm_busca_grupos">

    <div class='row form-group'>
        <?
        echo "
        <div class='container'>
            <p class='h4'> NÍVEL ACESSO: " . $_SESSION['usuario']['nome_grupo']."</p>
        </div>";

        $objUsuario = new Usuario();
        $objUsuario->setId($_SESSION['usuario']['id']);
        $usuarios = $objUsuario->listarErpUsuarioLaunch();
        $x = 1;
        foreach($usuarios AS $usuario) {
            if ($usuario['sistema'] == 1){
                $url = "http://seguro.linkmonitoramento.com.br/matriz/includes/confirm.php?email={$usuario['usuario']}&cript={$usuario['senha']}&emular=sim";
                $onClick = "onclick='EmularMatriz(\"$url\")'";
                $color = "";
                $title = "Login Matriz";
            }else{
                $url = "/link_report/includes/checar.login.php?email={$usuario['usuario']}&cript={$usuario['senha']}&emular=sim&md5=1";
                $onClick = "onclick='Emular(\"$url\")'";
                $color = "text-danger";
                $title = "Login Report";
            }
            echo "
			<div class='col-md-3 text-center logar-outro-grupo' $onClick>
				<div style='border: 1px solid; padding: 5px 10px 5px 10px; cursor:pointer;' data-attr-id='{$usuario['id']}'>
                    <label for='{$usuario['id']}' style='width: 100%; overflow: hidden; display: -webkit-box; -webkit-line-clamp:1; -webkit-box-orient: vertical'>{$usuario['rotulo']}</label>
				    <a class='center-block rounded-circle' data-toggle='tooltip' title='{$title}' width='70px' height='70px'><i class='fas fa-user fa-3x $color'></i></a>
				    <p class='h6'>{$usuario['usuario']}</p>
				</div>
			</div>
			";
            if ($x % 4 == 0) echo "</div><div class='row form-group'>";
            $x++;
        }
        ?>
    </div>
</form>
<script>
    $(document).ready(e => {
        $('[data-toggle="tooltip"]').tooltip();

    });

    function Emular(url){
        var dados = '';
        $.post(url, dados,
            function (data) {
                if (data['tipo'] == 1){
                    toastr.warning(data.mensagem, 'Atenção!');
                }else{
                    toastr.success(data.mensagem, 'Sucesso!');
                    window.location.reload();
                }
            },
            'json'
        );
    }

    function EmularMatriz(url){
      window.open(url);
    }
</script>
