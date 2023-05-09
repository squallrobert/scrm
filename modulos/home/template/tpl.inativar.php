<?php
$pagina_login = $_SESSION['pagina_login'];
list($pagina_login, $tmp) = explode("?", $pagina_login);
session_destroy();
?>
<script>
    window.location.href = '<?=$pagina_login?>';
</script>