<?php include_once 'views/template/header-cliente.php'; ?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tu reserva</h4>
        <?php if (!empty($_SESSION['reserva'])) { ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Aviso!</strong> Tienes una reserva pendiente
            </div>
            <hr>

            <div class="row g-4">
                <!-- Columna Información -->
                <div class="col-md-6">
                    <div class="list-group mb-3">
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>Habitación: </strong>
                            <?php echo $data['habitacion']['estilo']; ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>Fecha Llegada: </strong>
                            <?php echo fechaPerzo($_SESSION['reserva']['f_llegada']); ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>Fecha Salida: </strong>
                            <?php echo fechaPerzo($_SESSION['reserva']['f_salida']); ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>Precio/Noche: </strong>
                            <?php echo $data['habitacion']['precio']; ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>Capacidad: </strong>
                            <?php echo $data['habitacion']['capacidad']; ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>N° Habitación: </strong>
                            <?php echo $data['habitacion']['numero']; ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>Descripción: </strong>
                            <?php echo $data['habitacion']['descripcion']; ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <strong>Total Estimado: </strong>
                            <?php echo number_format($data['total'], 2); ?>
                        </a>
                    </div>

                    <button
                        type="button"
                        class="btn btn-primary w-100 mb-3"
                        id="btnConfirmarReserva"
                        data-url-confirmar="<?php echo RUTA_PRINCIPAL; ?>reserva/confirmar"
                        data-redirect="<?php echo RUTA_PRINCIPAL; ?>dashboard">
                        Reservar
                    </button>
                </div>

                <!-- Columna Galería -->
                <div class="col-md-6">
                    <div class="room-gallery card">
                        <div class="card-body">
                            <?php if (!empty($data['galeria'])) { ?>
                                <div class="rg-main position-relative mb-3">
                                    <img
                                        id="rgMainImage"
                                        src="<?php echo RUTA_PRINCIPAL . $data['galeria'][0]['path']; ?>"
                                        class="img-fluid rounded rg-main-img"
                                        alt="Imagen habitación"
                                        data-index="0">
                                    <button class="rg-nav rg-prev btn btn-light rounded-circle shadow" type="button" aria-label="Anterior">
                                        <i class="bx bx-chevron-left"></i>
                                    </button>
                                    <button class="rg-nav rg-next btn btn-light rounded-circle shadow" type="button" aria-label="Siguiente">
                                        <i class="bx bx-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="rg-thumbs d-flex flex-wrap gap-2">
                                    <?php foreach ($data['galeria'] as $i => $img) { ?>
                                        <div class="rg-thumb-wrapper">
                                            <img
                                                src="<?php echo RUTA_PRINCIPAL . $img['path']; ?>"
                                                class="rg-thumb img-thumbnail <?php echo $i === 0 ? 'active' : ''; ?>"
                                                data-full="<?php echo RUTA_PRINCIPAL . $img['path']; ?>"
                                                data-index="<?php echo $i; ?>"
                                                style="width:95px; height:70px; object-fit:cover; cursor:pointer;"
                                                alt="thumb <?php echo $i; ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <p class="text-muted m-0">No hay imágenes adicionales.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php } else { ?>
            <p>No hay reserva en curso.</p>
        <?php } ?>
    </div>
</div>

<?php include_once 'views/template/footer-cliente.php'; ?>

<!-- JS de la reserva (ya existente) -->
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/js/pages/reserva_pendiente.js'; ?>"></script>
<!-- JS Galería -->
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/js/gallery-room.js'; ?>"></script>

</body>

</html>