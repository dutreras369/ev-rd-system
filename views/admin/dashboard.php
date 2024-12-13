<main>
    <section id="admin-dashboard" class="py-5">
        <div class="container">
            <h2 class="text-primary mb-4">Dashboard del Administrador</h2>

            <!-- Grilla principal -->
            <div class="row">
                <!-- Columna 1: Análisis -->
                <div class="col-lg-6 mb-4">
                    <?php require_once __DIR__ . '/analysis.php'; ?>
                </div>

                <!-- Columna 2: Gestión de Trabajadores -->
                <div class="col-lg-6 mb-4">
                    <?php require_once __DIR__ . '/user_management.php'; ?>
                </div>
            </div>

            <div class="row">
                <!-- Columna completa: Registros -->
                <div class="col-12">
                    <?php require_once __DIR__ . '/records.php'; ?>
                </div>
            </div>
        </div>
    </section>
</main>


