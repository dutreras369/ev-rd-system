<main>
    <section id="loginSection" class="login py-5">
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
