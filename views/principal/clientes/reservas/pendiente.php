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
            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo RUTA_PRINCIPAL . 'assets/img/habitaciones/' . $data['habitacion']['foto']; ?>" class="img-fluid rounded-top" alt="" />

                    <!-- Hover added -->
                    <div class="list-group">
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

                    </div>

                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Notas</label>
                        <textarea class="form-control" id="descripcion" rows="3" placeholder="Notas"></textarea>
                    </div>

                    <button type="button" class="btn btn-primary" id="btnProcesar">
                        Procesar Pago
                    </button>



                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                <strong>Aviso!</strong> No tienes ninguna reserva pendiente
            </div>
        <?php } ?>
    </div>
</div>

<?php include_once 'views/template/footer-cliente.php'; ?>

<script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENTE_ID; ?>"></script>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>

</body>

</html>