const f_llegada = document.querySelector('#f_llegada');
const f_salida = document.querySelector('#f_salida');
const habitacion = document.querySelector('#habitacion');

document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("calendar");

  window.calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
    },
    locale: 'es',
    navLinks: true,
    businessHours: true,
    editable: false,        // Mejor no editable para reservas de disponibilidad
    selectable: false,
    events: base_url + 'reserva/listar/' + f_llegada.value + '/' + f_salida.value + '/' + habitacion.value
  });

  window.calendar.render();
});