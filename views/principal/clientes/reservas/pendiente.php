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
                    <div class="col-md-6">
                    <button type="button" class="btn btn-primary" id="btnProcesar">
                        Reservar
                    </button>

                </div>
                
                <script>
const btnProcesar = document.getElementById('btnProcesar');
let bloqueado = false;

btnProcesar.addEventListener('click', () => {
  if (bloqueado) return;

  Swal.fire({
    title: 'Confirmar Reserva?',
    text: '¿Estas seguro que quieres hacer esta reserva?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, reservar!',
    cancelButtonText: 'Cancelar'
  }).then(result => {
    if (!result.isConfirmed) {
      return; // Cancelado
    }

    bloqueado = true;
    btnProcesar.disabled = true;
    const textoOriginal = btnProcesar.textContent;
    btnProcesar.textContent = 'Procesando...';

    fetch('<?php echo RUTA_PRINCIPAL; ?>reserva/confirmar', {
      method: 'POST'
    })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            title: 'Reserva confirmada',
            icon: 'success',
            timer: 1600,
            showConfirmButton: false
          });

          // Ajusta la ruta si quieres otra distinta a 'dashboard'
            // Opción 1 (actual dashboard):
            // const destino = '<?php echo RUTA_PRINCIPAL; ?>dashboard';
            // Opción 2 (si tu "perfil" fuera otra ruta, por ejemplo perfil):
            // const destino = '<?php echo RUTA_PRINCIPAL; ?>perfil';

          const destino = '<?php echo RUTA_PRINCIPAL; ?>dashboard';

          setTimeout(() => {
            window.location.href = destino;
          }, 1600);
        } else {
          Swal.fire('Error', data.msg || 'No se pudo confirmar', 'error');
        }
      })
      .catch(e => {
        Swal.fire('Error', 'Error de red: ' + e.message, 'error');
      })
      .finally(() => {
        bloqueado = false;
        btnProcesar.disabled = false;
        btnProcesar.textContent = 'Reservar';
      });
  });
});
</script>

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

<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>

</body>

</html>