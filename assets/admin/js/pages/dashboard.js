const anio = document.querySelector('#anio');
document.addEventListener('DOMContentLoaded', function(){
    reporteReserva();
})

function formatCOP(valor){
  const n = Number(valor) || 0;
  return new Intl.NumberFormat('es-CO',{
    style: 'currency',
    currency: 'COP',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(n);
}

document.addEventListener('DOMContentLoaded', function(){
    reporteReserva();
})

function reporteReserva() {
  const url = base_url + "dashboard/reporteReserva/" + anio.value;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);

      document.querySelector("#totalreserva").textContent =
        formatCOP(res.totalReservas);

      var ctx = document.getElementById("chart5").getContext("2d");

      var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke2.addColorStop(0, "#f80759");
      gradientStroke2.addColorStop(1, "#bc4e9c");

      var myChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: [
            "Ene","Feb","Mar","Abr","May","Jun",
            "Jul","Ago","Sep","Oct","Nov","Dic",
          ],
          datasets: [
            {
              label: "Reservas",
              data: [
                res.reserva.ene,res.reserva.feb,res.reserva.mar,res.reserva.abr,
                res.reserva.may,res.reserva.jun,res.reserva.jul,res.reserva.ago,
                res.reserva.sep,res.reserva.oct,res.reserva.nov,res.reserva.dic,
              ],
              pointBorderWidth: 2,
              pointBackgroundColor: "transparent",
              pointHoverBackgroundColor: gradientStroke2,
              backgroundColor: gradientStroke2,
              borderColor: gradientStroke2,
              borderWidth: 2,
            },
          ],
        },
        options: {
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(ctx){
                  return formatCOP(ctx.parsed.y);
                }
              }
            }
          },
          legend: {
            display: false
          },
          tooltips: {
            displayColors: false,
          },
        },
      });
    }
  };
}