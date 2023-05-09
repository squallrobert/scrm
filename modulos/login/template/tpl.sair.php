<?php
/**
 * Created by PhpStorm.
 * User: Felipe Bomfim (@felipehbomfim)
 * Date: 08/12/2022
 * Time: 11:42
 */
session_name('WEBCOP-SESSION');
session_start();
$pagina_login = $_SESSION['pagina_login'];
list($pagina_login, $tmp) = explode("?", $pagina_login);
session_destroy();
//header("location: $pagina_login");
?>
<script>
    window.location.href = '<?=$pagina_login?>';
</script>
