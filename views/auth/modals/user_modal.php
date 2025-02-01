<div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- CABECERA DEL MODAL -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userInfoModalLabel">Información del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- CUERPO DEL MODAL -->
            <div class="modal-body">
                <div class="text-center">
                    <!-- Foto de perfil -->
                    <img src="<?php echo IMG_URL; ?>/user.png" class="rounded-circle mb-3" alt="Foto de Usuario" width="100">
                    
                    <!-- Información del Usuario -->
                    <h6 class="text-primary">Nombre: <span id="userName">Cargando...</span></h6>
                    <p class="mb-1"><strong>Correo:</strong> <span id="userEmail">Cargando...</span></p>
                    <p class="mb-1"><strong>Rol:</strong> <span id="userRole">Cargando...</span></p>
                    <p class="mb-1"><strong>Entrada:</strong> <span id="userHoursIn">Cargando...</span></p>
                </div>
            </div>

            <!-- PIE DEL MODAL -->
            <div class="modal-footer d-flex justify-content-center">
                <a id="logoutButton" class="btn btn-danger text-white">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</div>
