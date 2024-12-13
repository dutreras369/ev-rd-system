
<?php
$title = "Erroe";

require_once __DIR__ . '/../views/templates/header.php'; ?>


<main>
    <section id="error" class="py-5 text-center">
        <h1 class="text-danger">403 - Acceso Denegado</h1>
        <p>No tienes permiso para acceder a esta página.</p>
        <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
    </section>
</main>

<?php 
require_once __DIR__ . '/../views/templates/footer.php';
