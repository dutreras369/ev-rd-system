<?php
$mostrar_mensaje = (new DateTime() < new DateTime('2025-07-01'));
?>

<main>
    <section id="loginSection" class="login py-5">
        <div class="container">
            <div class="text-center mb-4">
                <div class="icon-container mx-auto mb-4">
                    <img src="<?php echo IMG_URL; ?>/logo.jpg" alt="User Icono" class="icon-img w-30">
                </div>
                <h2 class="text-primary">Bienvenido/a RD</h2>
            </div>

            <?php if ($mostrar_mensaje): ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-warning text-center shadow-sm mb-4" role="alert">
                        El administrador del sistema solicita tomar contacto vía <strong>Telegram</strong> para actualizar los datos de acceso.<br>
                        Puedes escribirnos directamente a:
                        <a href="https://t.me/TU_USUARIO_TELEGRAM" class="d-block mt-2 btn btn-outline-primary btn-sm" target="_blank">
                            <i class="bi bi-telegram"></i> Contactar vía Telegram
                        </a>
                        <div class="mt-2 small text-muted">Este mensaje estará disponible hasta el 01/07/2025</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div id="loginAlert"></div>
                    <div class="p-4 bg-light rounded shadow-lg">
                        <form id="loginForm" class="needs-validation mt-4" novalidate action="" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="Ingresa tu correo electrónico">
                                <div class="invalid-feedback">Por favor ingrese un correo electrónico válido</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Ingresa tu contraseña">
                                <div class="invalid-feedback">Por favor ingrese su contraseña</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Ingresar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
