<div id="user-management-section" class="p-3 bg-light rounded shadow">
    <h5 class="text-primary">Gestión de Trabajadores</h5>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
        <i class="bi bi-person-plus"></i> Agregar Trabajador
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
            <tbody id="workers-table-body">
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>juan.perez@example.com</td>
                    <td>Usuario</td>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewAttendanceModal" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editWorkerModal" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-danger" data-bs-toggle="modal" data-bs-target="#deleteWorkerModal" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>María López</td>
                    <td>maria.lopez@example.com</td>
                    <td>Administrador</td>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewAttendanceModal" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editWorkerModal" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-danger" data-bs-toggle="modal" data-bs-target="#deleteWorkerModal" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Carlos Ramírez</td>
                    <td>carlos.ramirez@example.com</td>
                    <td>Usuario</td>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewAttendanceModal" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#editWorkerModal" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#" class="btn-icon btn-danger" data-bs-toggle="modal" data-bs-target="#deleteWorkerModal" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/modals/user_accion_modal.php'; ?>