<div id="page" class="app">
    <div class="announcement-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <button id="sidebarToggle" class="sidebar-toggle d-none d-lg-block" aria-expanded="false">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    <header id="masthead" class="p-3 site-header text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between main-navigation">
                <nav id="primary-menu" class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <ul class="nav-item">
                        <li><a href="<?php echo BASE_URL; ?>" class="nav-link px-2"><i class="bi bi-house"></i></a></li>
                        <li id="menuUser" style="display: none;"><a href="<?php echo BASE_URL; ?>/public/dashboard/user.php" class="nav-link px-2">Registrar</a></li>
                        <li id="menuAdmin" style="display: none;"><a href="<?php echo BASE_URL; ?>/public/dashboard/admin.php" class="nav-link px-2">Administración</a></li>
                    </ul>
                </nav>

                <div class="text-end d-flex align-items-center gap-2">
                    <a class="text-white text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#userInfoModal" title="Perfil de Usuario" id="userIcon" style="display: none;">
                        <i class="bi bi-person rounded-circle"></i>
                    </a>
                    <a id="logoutButton" class="text-white text-decoration-none" style="display: none;">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/public/login.php" class="btn btn-primary" id="loginButton">
                        Iniciar Sesión
                    </a>
                </div>

                <button id="sidebarToggleMobile" class="sidebar-toggle d-lg-none" aria-expanded="false" style="display: none;">
                    <i class="bi bi-hdd"></i>
                </button>
            </div>
        </div>
    </header>
</div>