<div id="records-section" class="p-3 bg-light rounded shadow">
    <h5 class="text-primary">Tablero de Registros</h5>
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
            <tbody id="records-table-body">
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>$ 120</td>
                    <td>$ 110</td>
                    <td>$ 100</td>
                    <td class="d-flex justify-content-center align-items-center">
                        <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" title="Ver Detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>María López</td>
                    <td>$ 950</td>
                    <td>$ 900</td>
                    <td>$ 500</td>
                    <td class="d-flex justify-content-center align-items-center">
                         <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" title="Ver Detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Carlos Ramírez</td>
                    <td>$ 750</td>
                    <td>$ 700</td>
                    <td>$ 500</td>
                    <td class="d-flex justify-content-center align-items-center">
                        <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" title="Ver Detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/modals/user_details_modal.php'; ?>