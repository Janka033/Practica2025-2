(function() {
  if (!$.fn.datepicker) {
    console.warn('datepicker no está disponible');
    return;
  }

  // Hoy a medianoche local
  const today = new Date();
  today.setHours(0,0,0,0);

  function toISO(d){
    const x = new Date(d.getTime() - d.getTimezoneOffset()*60000);
    return x.toISOString().split('T')[0];
  }

  const $llegada = $('#f_llegada');
  const $salida  = $('#f_salida');

  // 1. Forzar que ninguna de las fechas esté en el pasado si se llegó con GET viejo
  let llegVal = $llegada.val();
  let salVal  = $salida.val();

  if (llegVal && new Date(llegVal) < today) {
    llegVal = toISO(today);
    $llegada.val(llegVal);
  }
  if (salVal && new Date(salVal) < today) {
    // Si la salida estaba antes de hoy la reasignamos (idealmente +1 día)
    const plus1 = new Date(today);
    plus1.setDate(plus1.getDate()+1);
    salVal = toISO(plus1);
    $salida.val(salVal);
  }

  // 2. Aplicar startDate (deshabilita visualmente días pasados)
  $('#datetimepicker-1').datepicker('setStartDate', today);
  $('#datetimepicker-2').datepicker('setStartDate', today);

  // 3. Asegurar relación llegada < salida
  function ensureSalida() {
    const l = $llegada.val();
    const s = $salida.val();
    if (!l) return;
    const dl = new Date(l);
    if (!s || new Date(s) <= dl) {
      const next = new Date(dl);
      next.setDate(next.getDate() + 1);
      $salida.val(toISO(next));
      $('#datetimepicker-2').datepicker('update', next);
    }
    // Ajustar startDate del segundo al valor de llegada (permite mantener salida >= llegada)
    $('#datetimepicker-2').datepicker('setStartDate', dl);
  }

  ensureSalida();

  // 4. Eventos
  $('#datetimepicker-1').on('changeDate', function(e){
    const date = e.date;
    if (date < today) {
      // Protección extra (no debería ocurrir si startDate funciona)
      $('#datetimepicker-1').datepicker('update', today);
      $llegada.val(toISO(today));
    } else {
      $llegada.val(toISO(date));
    }
    ensureSalida();
  });

  $('#datetimepicker-2').on('changeDate', function(e){
    const date = e.date;
    const lleg = $llegada.val();
    if (lleg && date <= new Date(lleg)) {
      ensureSalida();
      return;
    }
    if (date < today) {
      const plus1 = new Date(today);
      plus1.setDate(plus1.getDate()+1);
      $('#datetimepicker-2').datepicker('update', plus1);
      $salida.val(toISO(plus1));
      return;
    }
    $salida.val(toISO(date));
  });

  // 5. (Opcional) Bloquear edición manual por teclado (el usuario sólo usa el picker)
  $llegada.on('keydown paste', e => e.preventDefault());
  $salida.on('keydown paste', e => e.preventDefault());

  
})();