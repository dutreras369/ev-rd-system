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
                    <div class="mb-3">
                        <label for="amount" class="form-label">Monto</label>
                        <input type="number" class="form-control" id="amount" name="amount" required placeholder="Ingrese el monto">
                    </div>
                    <div class="mb-3">
                        <label for="movement-type" class="form-label">Tipo</label>
                        <select class="form-select" id="movement-type" name="movement-type" required>
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Retiro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Monto</label>
                        <input type="number" class="form-control" id="amount" name="amount" required placeholder="Ingrese el monto">
                    </div>
                    <div class="mb-3">
                        <label for="timestamp" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="timestamp" name="timestamp" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
