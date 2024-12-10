<?php
    $title = "Inicio de Sesión";
    include 'templates/header.php';
?>
<body>
    <main>
        <section id="login" class="login py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <div class="icon-container rounded-circle mx-auto mb-4">
                        <img src="<?php echo IMG_URL; ?>/user.png" alt="User Icono" class="icon-img">
                    </div>
                    <h2 class="text-primary">Bienvenido/a</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <!-- Sección para mostrar alertas -->
                        <div id="loginAlert"></div>
                        <div class="p-4 bg-light rounded shadow-lg">
                            <form id="loginForm" class="needs-validation mt-4" novalidate action="controllers/AuthController.php" method="POST">
                                <div class="mb-3">
                                    <label for="user" class="form-label">Usuario:</label>
                                    <input type="text" class="form-control" id="user" name="user" required placeholder="Ingresa tu usuario">
                                    <div class="invalid-feedback">Por favor ingrese un usuario válido</div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña:</label>
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Ingresa tu contraseña">
                                    <div class="invalid-feedback">Por favor ingrese su contraseña</div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Recordar contraseña</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-3">Ingresar</button>
                            </form>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="#" class="text-primary" id="forgot-password-link">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include 'templates/footer.php'; ?>
</body>
</html>
