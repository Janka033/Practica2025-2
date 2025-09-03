var tablaReservas;
document.addEventListener("DOMContentLoaded", function () {
  tablaReservas = new DataTable("#tblReservas", {
    responsive: true,
    select: true,
    ajax: {
      url: base_url + "reserva/listarReservas",
      dataSrc: "",
    },
    columns: [
      { data: "item" },
      { data: "fecha_ingreso" },
      { data: "fecha_salida" },
      { data: "monto" },
      { data: "estilo" },
    ],
    language: {
  url: "/assets/admin/i18n/es-ES.json"
},
    initComplete: function() {
      var table = this.api();
      table.on('dblclick', 'tr', function() {
          var data = table.row(this).data();
          var id = data.id;
          window.location = base_url + 'dashboard/calificacion/' + id;
      });
    }
  });
});