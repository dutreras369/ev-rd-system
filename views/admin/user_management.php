<div id="user-management-section" class="p-3 bg-light rounded shadow">
    <h5 class="text-primary">Gestión de Usuario</h5>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
        <i class="bi bi-person-plus"></i> Agregar Usuario
    </button>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <!-- Los datos se llenarán dinámicamente desde el backend -->
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/modals/user_accion_modal.php'; ?>