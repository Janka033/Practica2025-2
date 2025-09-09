document.addEventListener('DOMContentLoaded', () => {
  // Permite usar cualquiera de los dos IDs (según lo que tengas en la vista)
  const btn = document.getElementById('btnConfirmarReserva') || document.getElementById('btnProcesar');
  if (!btn) return;

  const urlConfirmar = btn.getAttribute('data-url-confirmar') || (typeof base_url !== 'undefined' ? base_url + 'reserva/confirmar' : '');
  const urlRedirect  = btn.getAttribute('data-redirect')      || (typeof base_url !== 'undefined' ? base_url + 'dashboard' : '');
  if (!urlConfirmar || !urlRedirect) {
    console.warn('Faltan data-url-confirmar o data-redirect en el botón de reserva.');
  }

  let enProceso = false;
  const textoOriginal = btn.textContent;

  btn.addEventListener('click', () => {
    if (enProceso) return;

    // Verifica que SweetAlert2 esté presente
    if (typeof Swal === 'undefined') {
      alert('No está cargado SweetAlert2. Verifica los scripts.');
      return;
    }

    Swal.fire({
      title: 'Confirmar Reserva',
      text: '¿Estás seguro que quieres hacer esta reserva?',
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
          'Accept': 'application/json, text/plain, */*'
          // Agrega cabecera CSRF si tu backend la requiere
        }
      })
        .then(async r => {
          const raw = await r.text();
          let data;
            try { data = JSON.parse(raw); } catch { data = { raw }; }
          return { ok: r.ok, data };
        })
        .then(({ ok, data }) => {
          const exito =
            (ok && data.type === 'success') ||
            (typeof data.raw === 'string' && data.raw.trim().toLowerCase() === 'ok');

          if (exito) {
            Swal.fire({
              icon: 'success',
              title: 'Reserva confirmada',
              text: 'Redirigiendo...',
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