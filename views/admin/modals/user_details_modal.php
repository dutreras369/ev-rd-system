<!-- Detalles de trabajador -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Detalles de Registros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterDetailsForm" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="filter-date" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="filter-date" name="filter-date">
                        </div>
                        <div class="col-md-6">
                            <label for="filter-user" class="form-label">Usuario</label>
                            <select class="form-select" id="filter-user" name="filter-user">
                                <!-- Opciones dinámicas -->
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
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
