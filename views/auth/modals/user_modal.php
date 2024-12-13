<div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userInfoModalLabel">Información del Usuario</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <!-- Foto de perfil -->
                    <img src="<?php echo IMG_URL; ?>/user.png" class="rounded-circle mb-3" alt="Foto de Usuario" width="100">
                    <!-- Información del Usuario -->
                    <h6 class="text-primary">Nombre: <span id="userName">Juan Pérez</span></h6>
                    <p class="mb-1"><strong>Correo:</strong> <span id="userEmail">juan.perez@example.com</span></p>
                    <p class="mb-1"><strong>Rol:</strong> <span id="userRole">Administrador</span></p>
                    <p class="mb-1"><strong>Última Conexión:</strong> <span id="userLastLogin">19/11/2024</span></p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="/historial" class="btn btn-outline-secondary">Ver Historial</a>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
