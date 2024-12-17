<!-- Detalles de trabajador -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Detalles de Registros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de filtro -->
                <form id="filterDetailsForm" class="mb-4">
                    <div class="row">
                        <!-- Fecha Desde -->
                        <div class="col-md-4">
                            <label for="filter-date-from" class="form-label">Desde</label>
                            <input type="date" class="form-control" id="filter-date-from" name="filter-date-from" required>
                        </div>
                        <!-- Fecha Hasta -->
                        <div class="col-md-4">
                            <label for="filter-date-to" class="form-label">Hasta</label>
                            <input type="date" class="form-control" id="filter-date-to" name="filter-date-to" required>
                        </div>
                        <!-- Tipo (Carga/Retiro) -->
                        <div class="col-md-4">
                            <label for="filter-type" class="form-label">Tipo</label>
                            <select class="form-select" id="filter-type" name="filter-type" required>
                                <option value="" disabled selected>Seleccione Tipo</option>
                                <option value="carga">Carga</option>
                                <option value="retiro">Retiro</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filtro de Usuario -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="filter-user" class="form-label">Usuario</label>
                            <select class="form-select" id="filter-user" name="filter-user">
                                <!-- Opciones dinámicas -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="filter-user" class="form-label">Estado</label>
                            <select class="form-select" id="filter-status" name="filter-status">
                                <option value="true">Correcto</option>
                                <option value="false">Incorrecto</option>
                            </select>                            
                        </div>
                    </div>

                    <!-- Botón Filtrar -->
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabla de resultados -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="details-table-body">
                            <!-- Filas dinámicas -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
