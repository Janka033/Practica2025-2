(function(){
  // Normalizar hoy a medianoche local
  const today = new Date();
  today.setHours(0,0,0,0);

  // Helper para formatear a yyyy-mm-dd
  function toISO(date){
    const d = new Date(date.getTime() - date.getTimezoneOffset()*60000);
    return d.toISOString().split('T')[0];
  }

  // Inicializaciones existentes ya corrieron en custom.js.
  // Aquí sólo ajustamos opciones dinámicas.
  if ($.fn.datepicker) {
    // Reconfigurar (setStartDate) sin volver a destruir el componente
    $('#datetimepicker-1').datepicker('setStartDate', today);
    $('#datetimepicker-2').datepicker('setStartDate', today);

    // Enlazar cambio de llegada
    $('#datetimepicker-1').on('changeDate', function(e){
      const start = e.date;
      // Forzar fecha de salida si es anterior
      const salidaVal = $('#f_salida').val();
      if (salidaVal) {
        const salidaDate = new Date(salidaVal);
        if (salidaDate < start) {
          // Por defecto: un día después
          const next = new Date(start);
            next.setDate(next.getDate() + 1);
          $('#f_salida').val(toISO(next));
          $('#datetimepicker-2').datepicker('update', next);
        }
      } else {
        // Si no había valor en salida, ponemos +1 día
        const next = new Date(start);
        next.setDate(next.getDate() + 1);
        $('#f_salida').val(toISO(next));
        $('#datetimepicker-2').datepicker('update', next);
      }
      // Actualizar min para salida
      $('#datetimepicker-2').datepicker('setStartDate', start);
      triggerCalendarReload();
    });

    // Cambio en salida -> sólo recargar calendario
    $('#datetimepicker-2').on('changeDate', function(){
      const lleg = $('#f_llegada').val();
      const sal = $('#f_salida').val();
      // Asegurar lógica mínima (salida >= llegada)
      if (lleg && sal && new Date(sal) < new Date(lleg)) {
        $('#f_salida').val(lleg);
        $('#datetimepicker-2').datepicker('update', lleg);
      }
      triggerCalendarReload();
    });
  }

  // Reactivar si se cambia manualmente el input (por teclado)
  ['#f_llegada','#f_salida'].forEach(sel=>{
    $(sel).on('blur', function(){
      const val = $(this).val();
      if (!/^\d{4}-\d{2}-\d{2}$/.test(val)) return;
      const d = new Date(val);
      if (d < today) {
        $(this).val(toISO(today));
        if (sel === '#f_llegada') {
          const next = new Date(today); next.setDate(next.getDate()+1);
          $('#f_salida').val(toISO(next)).datepicker('update', next);
        }
      }
      triggerCalendarReload();
    });
  });

  // Reloader para FullCalendar (si existe)
  function triggerCalendarReload(){
    if (window.calendar) {
      const llegada = $('#f_llegada').val();
      const salida = $('#f_salida').val();
      const habitacion = $('#habitacion').val();
      // Ajusta tu endpoint si requiere validaciones extra
      window.calendar.removeAllEventSources();
      window.calendar.addEventSource(base_url + 'reserva/listar/' + llegada + '/' + salida + '/' + habitacion);
      window.calendar.refetchEvents();
    }
  }

  // Cambio de habitación debe recargar
  $('#habitacion').on('change', triggerCalendarReload);

  // Inicial: garantizar salida >= llegada +1 (opcional)
  (function ensureInitial(){
    const llegV = $('#f_llegada').val();
    const salV  = $('#f_salida').val();
    if (llegV) {
      if (!salV || new Date(salV) <= new Date(llegV)) {
        const base = new Date(llegV);
        base.setDate(base.getDate()+1);
        $('#f_salida').val(toISO(base));
        if ($.fn.datepicker) {
          $('#datetimepicker-2').datepicker('update', base);
          $('#datetimepicker-2').datepicker('setStartDate', new Date(llegV));
        }
      }
    }
  })();

})();