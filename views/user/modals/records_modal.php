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
                    <!-- Campo oculto para el usuario (se llenará con localStorage) -->
                    <input type="hidden" id="user_id" name="user_id">

                    <!-- Tipo de Movimiento -->
                    <div class="mb-3">
                        <label for="movement-type" class="form-label">Tipo</label>
                        <select class="form-select" id="movement-type" name="movement-type" required>
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
