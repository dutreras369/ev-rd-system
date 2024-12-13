<div id="records-section" class="p-3 bg-light rounded shadow">
    <h5 class="text-primary">Tablero General de Registros</h5>
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
                    <td>120</td>
                    <td>110</td>
                    <td>10</td>
                    <td>
                        <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" title="Ver Detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>María López</td>
                    <td>95</td>
                    <td>90</td>
                    <td>5</td>
                    <td>
                        <a href="#" class="btn-icon btn-secondary" data-bs-toggle="modal" data-bs-target="#viewDetailsModal" title="Ver Detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Carlos Ramírez</td>
                    <td>75</td>
                    <td>70</td>
                    <td>5</td>
                    <td>
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
