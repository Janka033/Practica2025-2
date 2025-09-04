let tblReservas;
const myModal = new bootstrap.Modal(document.getElementById("modalTicket"));
const contentTicket = document.querySelector("#content-ticket");

document.addEventListener("DOMContentLoaded", function () {
  //cargar datos con el plugin datatables
  tblReservas = $("#tblReservas").DataTable({
    ajax: {
      url: base_url + "reservas/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "estilo" }, // el nombre de la habitación, NO el id
      { data: "numero" },
      { data: "fecha_reserva" },
      { data: "monto" },
      { data: "fecha_ingreso" },
      { data: "fecha_salida" },
      { data: "cliente" },
    ],
    language: {
      url: "/reservas/assets/admin/i18n/es-ES.json",
    },
    responsive: true,
    order: [[0, "desc"]],
    dom: "Pfrtip", // Agregar los botones de SearchPanes
    searchPanes: {
      cascadePanes: true,
      viewTotal: true,
      columns: [1, 7],
    },
    initComplete: function () {
      var counts = {};

      // Count the number of entries for each office
      tblReservas
        .column(1, { search: "applied" })
        .data()
        .each(function (val) {
          if (counts[val]) {
            counts[val] += 1;
          } else {
            counts[val] = 1;
          }
        });

      // And map it to the format highcharts uses
      var countMap = $.map(counts, function (val, key) {
        return {
          name: key,
          y: val,
        };
      });

      // Crear el gráfico de Highcharts
      Highcharts.chart("highcharts-container", {
        // Configura tus datos aquí
        chart: {
          type: "pie",
        },
        title: {
          text: "Título del Gráfico",
        },
        series: [
          {
            data: countMap,
          },
        ],
      });
    },
  });
});
