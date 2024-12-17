<div id="page" class="site">
    <div class="announcement-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Botón de apertura/cierre del Sidebar en Desktop -->
            <button class="sidebar-toggle d-none d-lg-block" aria-expanded="false">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    <header id="masthead" class="p-3 site-header text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between main-navigation">
                <!-- Menú de Navegación -->
                <nav id="primary-menu" class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <ul class="nav-item">
                        <li><a href="<?php echo BASE_URL; ?>" class="nav-link px-2"><i class="bi bi-house"></i></a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/login.php" class="nav-link px-2">Login</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/dashboard/user.php" class="nav-link px-2">Usuario</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/dashboard/admin.php" class="nav-link px-2">Administrador</a></li>
                    </ul>
                </nav>

                <!-- Icono de Usuario y Botón de Salida -->
                <div class="text-end d-flex align-items-center gap-2">
                    <!-- Ícono de Usuario -->
                    <a class="text-white text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#userInfoModal" title="Perfil de Usuario">
                        <i class="bi bi-person rounded-circle"></i>
                    </a>

                    <!-- Botón de Salida -->
                    <a class="btn d-flex align-items-center justify-content-center" href="logout.php" title="Cerrar Sesión">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>


                <!-- Botones Responsive -->
                <button class="col menu-toggle d-lg-none" aria-controls="primary-menu" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>

                <button class="sidebar-toggle d-lg-none" aria-expanded="false">
                    <i class="bi bi-hdd"></i>
                </button>
            </div>
        </div>
    </header>
</div>