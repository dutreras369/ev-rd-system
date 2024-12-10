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
                    <!-- Logo -->
                    <a href="<?php echo BASE_URL; ?>" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none logo">
                        <img src="<?php echo IMG_URL; ?>/logo.png" alt="Logo del Sitio" class="site-logo">
                    </a>

                    <!-- Menú de Navegación -->
                    <nav id="primary-menu" class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <ul class="nav-item">
                            <li><a href="/ev-rd-system" class="nav-link px-2">Inicio</a></li>
                            <li><a href="/about.php" class="nav-link px-2">Acerca de</a></li>
                        </ul>
                    </nav>

                    <!-- Icono de Usuario -->
                    <div class="text-end">
                        <a class="text-white text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#userInfoModal">
                            <i class="bi bi-person rounded-circle"></i>
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

