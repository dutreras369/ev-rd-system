<!-- Modal para Registrar Nuevo Ingreso/Egreso -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registrar Nuevo Movimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm">
                    <!-- Usuario -->
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required placeholder="Ingrese usuario">
                    </div>

                    <!-- Selector de Tipo de Movimiento con Botones -->
                    <div class="mb-3">
                        <label class="form-label">Tipo de Movimiento</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-success movement-type" data-type="carga">Carga</button>
                            <button type="button" class="btn btn-outline-danger movement-type" data-type="retiro">Retiro</button>
                        </div>
                        <!-- Select oculto para sincronización -->
                        <select class="form-select d-none" id="movement-type" name="movement-type" required>
                            <option value="carga">Carga</option>
                            <option value="retiro">Retiro</option>
                        </select>
                    </div>

                    <!-- Botones de Acceso Rápido y Monto -->
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <input type="number" class="form-control" id="amount" name="amount" required placeholder="Ingrese monto">
                        <div class="d-flex gap-2 mt-2 mb-2">
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="1000">1000</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="5000">5000</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="100000">100000</button>
                            <button type="button" class="btn btn-outline-primary quick-amount" data-amount="200000">200000</button>
                        </div>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="mb-3">
                        <label for="timestamp" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" required>
                    </div>

                    <!-- Botón Registrar -->
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
