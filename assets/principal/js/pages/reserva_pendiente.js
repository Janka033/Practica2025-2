// Manejo de confirmación y envío de la reserva pendiente
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnConfirmarReserva');
  if (!btn) return;

  const urlConfirmar = btn.getAttribute('data-url-confirmar');
  const urlRedirect  = btn.getAttribute('data-redirect');

  let enProceso = false;
  const textoOriginal = btn.textContent;

  btn.addEventListener('click', () => {
    if (enProceso) return;

    Swal.fire({
      title: 'Confirmar Reserva',
      text: '¿Estás seguro de confirmar esta reserva?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, reservar',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then(result => {
      if (!result.isConfirmed) return;

      enProceso = true;
      btn.disabled = true;
      btn.textContent = 'Procesando...';

      fetch(urlConfirmar, {
        method: 'POST',
        headers: {
          // Si implementas CSRF, agrega aquí: 'X-CSRF-TOKEN': token
          'Accept': 'application/json, text/plain, */*'
        }
      })
        .then(async resp => {
          // Intentar JSON primero
            let data;
            const text = await resp.text();
            try {
              data = JSON.parse(text);
            } catch (_) {
              // Si backend devuelve texto simple tipo "ok"
              data = { raw: text };
            }
            return { ok: resp.ok, data };
        })
        .then(({ ok, data }) => {
          // Interpretación flexible
          const exito =
            (ok && data.type === 'success') ||
            (typeof data.raw === 'string' && data.raw.trim().toLowerCase() === 'ok');

          if (exito) {
            Swal.fire({
              icon: 'success',
              title: 'Reserva confirmada',
              text: 'Redirigiendo a tu panel...',
              timer: 1400,
              showConfirmButton: false
            }).then(() => {
              window.location = urlRedirect;
            });
          } else {
            Swal.fire(
              'Aviso',
              (data && (data.msg || data.raw)) || 'No se pudo confirmar la reserva',
              (data && data.type) || 'warning'
            );
            restaurar();
          }
        })
        .catch(err => {
          console.error('Error reserva:', err);
          Swal.fire('Error', 'Fallo de comunicación con el servidor', 'error');
          restaurar();
        });
    });
  });

  function restaurar() {
    enProceso = false;
    btn.disabled = false;
    btn.textContent = textoOriginal;
  }
});