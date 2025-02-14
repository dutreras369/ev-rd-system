<!-- Agregar Usuario -->
<div class="modal fade" id="addWorkerModal" tabindex="-1" aria-labelledby="addWorkerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWorkerModalLabel">Agregar Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addWorkerForm">
                    <div class="mb-3">
                        <label for="worker-name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="worker-name" name="worker-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="worker-email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="worker-email" name="worker-email" required>
                    </div>
                    <div class="mb-3">
                        <label for="worker-role" class="form-label">Rol</label>
                        <select class="form-select" id="worker-role" name="worker-role" required>
                            <option value="user">Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="worker-password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="worker-password" name="worker-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Editar Usuario -->
<div class="modal fade" id="editWorkerModal" tabindex="-1" aria-labelledby="editWorkerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWorkerModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editWorkerForm">
                    <input type="hidden" id="edit-worker-id"> <!-- ID oculto para referencia -->

                    <div class="mb-3">
                        <label for="edit-worker-name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit-worker-name" name="edit-worker-name" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="edit-worker-email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="edit-worker-email" name="edit-worker-email" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="edit-worker-role" class="form-label">Rol</label>
                        <select class="form-select" id="edit-worker-role" name="edit-worker-role" required>
                            <option value="user">Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-worker-password" class="form-label">Nueva Contraseña (opcional)</label>
                        <input type="password" class="form-control" id="edit-worker-password" name="edit-worker-password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirmar Eliminacion -->
<div class="modal fade" id="deleteWorkerModal" tabindex="-1" aria-labelledby="deleteWorkerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteWorkerModalLabel">Eliminar Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar a este trabajador y todos sus registros?</p>
                <form id="deleteWorkerForm">
                    <input type="hidden" id="delete-worker-id" name="delete-worker-id">
                    <button type="submit" class="btn btn-danger w-100">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Registro de asistencias -->
<div class="modal fade" id="viewAttendanceModal" tabindex="-1" aria-labelledby="viewAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAttendanceModalLabel">Registros de Asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Hora de Entrada</th>
                                <th>Hora de Salida</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="attendance-table-body">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <nav>
                    <ul class="pagination" id="attendance-pagination">
                        <!-- Botones de paginación dinámicos -->
                    </ul>
                </nav>
            </div>

        </div>
    </div>
</div>