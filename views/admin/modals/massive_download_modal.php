<!-- Modal Exportar a Excel -->
<div class="modal fade" id="exportExcelModal" tabindex="-1" aria-labelledby="exportExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportExcelModalLabel">Exportar Registros a Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulario de filtro -->
                <form id="exportExcelForm" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="export-date-from" class="form-label">Desde</label>
                            <input type="date" class="form-control" id="export-date-from">
                        </div>
                        <div class="col-md-4">
                            <label for="export-date-to" class="form-label">Hasta</label>
                            <input type="date" class="form-control" id="export-date-to">
                        </div>
                        <div class="col-md-4">
                            <label for="export-type" class="form-label">Tipo</label>
                            <select class="form-select" id="export-type">
                                <option value="">Todos</option>
                                <option value="carga">Carga</option>
                                <option value="retiro">Retiro</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="export-user" class="form-label">Usuario</label>
                            <select class="form-select" id="export-user">
                                <option value="">Todos</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="export-status" class="form-label">Estado</label>
                            <select class="form-select" id="export-status">
                                <option value="">Todos</option>
                                <option value="correcto">Correcto</option>
                                <option value="incorrecto">Incorrecto</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Exportar a Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
