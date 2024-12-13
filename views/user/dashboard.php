<main>
    <section id="user-dashboard" class="py-5">
        <div class="container">
            <!-- Horario de Ingreso y Salida -->
            <div class="mb-4">
                <h5 class="text-primary">Horario de Trabajo</h5>
                <div class="d-flex flex-wrap gap-3">
                    <div class="card bg-light shadow-sm" style="width: 18rem;">
                        <div class="card-body">
                            <h6 class="card-title">Horario de Ingreso</h6>
                            <p class="card-text fs-5 text-success"><strong id="check-in-time">08:00 AM</strong></p>
                        </div>
                    </div>
                    <div class="card bg-light shadow-sm" style="width: 18rem;">
                        <div class="card-body">
                            <h6 class="card-title">Horario de Salida</h6>
                            <p class="card-text fs-5 text-danger"><strong id="check-out-time">05:00 PM</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once __DIR__ . '/records.php'; ?>

        </div>
    </section>
</main>

<?php require_once __DIR__ . '/modals/records_modal.php'; ?>