<?php include_once 'views/template/header-cliente.php'; ?>

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 row-cols-xxl-4">
    <div class="col">
        <div class="card radius-10 bg-gradient-ibiza">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <p class="mb-0 text-white fw-bold">Total Habitaciones</p>
                        <h4 class="my-1 text-white"><?php echo $data['totales']['habitaciones']['total']; ?></h4>
                    </div>
                    <div id="chart2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tus reservas</h4>
        <hr>

        <?php
        // Ruta a la página principal (no hardcodear localhost)
        $rutaIniciarReserva = RUTA_PRINCIPAL; // Asegúrate que la constante exista
        ?>

        <?php if (!empty($_SESSION['reserva'])) { ?>
            <div class="alert alert-warning" role="alert">
                <strong>Reserva Pendiente</strong>
                <a href="<?php echo RUTA_PRINCIPAL . 'reserva/pendiente'; ?>" class="fw-semibold ms-2">CLICK AQUI</a>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3"
                 role="alert" style="border-left:6px solid #f0ad4e;">
                <div>
                    <strong>¿Sin reserva activa?</strong> Empieza ahora mismo y asegura tu habitación.
                </div>
                <div>
                    <a href="<?php echo $rutaIniciarReserva; ?>"
                       class="btn btn-sm btn-primary px-4 fw-semibold shadow-sm"
                       style="border-radius:30px;">
                        Ir a reservar
                        <i class='bx bx-right-arrow-alt align-middle'></i>
                    </a>
                </div>
            </div>
        <?php } ?>

        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Información</strong> Si deseas dejar una calificación doble click en la habitación
        </div>

        <div class="table-responsive">
            <table class="table table-primary nowrap" id="tblReservas" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Llegada</th>
                        <th>Fecha Salida</th>
                        <th>Monto</th>
                        <th>Habitación</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<?php include_once 'views/template/footer-cliente.php'; ?>

<script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/admin/js/pages/clientes/reservas.js'; ?>"></script>

</body>
</html>