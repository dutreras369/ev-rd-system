<?php
$title = "Inicio de Sesión";

require_once __DIR__ . '/../views/templates/header.php';

?>


<div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
    <h4 class="alert-heading">¡Importante!</h4>
    <p>Hemos actualizado nuestro numero de Telegram. Por favor tomemos contacto a traves de este nuevo numero.</p>
    <a href="https://t.me/dutreras369" class="btn btn-primary btn-lg mt-3" target="_blank">
        <i class="bi bi-telegram"></i> Acceder a Telegram
    </a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<?php

require_once __DIR__ . '/../views/auth/login.php';

require_once __DIR__ . '/../views/templates/footer.php';





