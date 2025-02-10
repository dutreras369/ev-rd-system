<div id="records-section" class="p-3 bg-light rounded shadow">
    <h5 class="text-primary">Tablero de Registros por Usuario</h5>
    <!-- Cabecera de Filtro General -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-primary m-0">Filtro General</h5>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewDetailsModal">
            <i class="bi bi-funnel"></i> Filtrar
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Registros del Mes</th>
                    <th>Correctos</th>
                    <th>Incorrectos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="user-analysis-table-body">
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/modals/record_details_modal.php'; ?>