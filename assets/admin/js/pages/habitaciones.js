let tblHabitaciones, editorDescripcion;
const formulario = document.querySelector("#formulario");
const btnAccion = document.querySelector("#btnAccion");
const btnNuevo = document.querySelector("#btnNuevo");

const id = document.querySelector("#id");
const estilo = document.querySelector("#estilo");
const descripcion = document.querySelector("#descripcion");
const precio = document.querySelector("#precio");
const foto = document.querySelector("#foto");
const foto_actual = document.querySelector("#foto_actual");
const foto_temp = document.querySelector("#foto_temp");
const containerPreview = document.querySelector("#containerPreview");

const modalGaleria = new bootstrap.Modal(
  document.getElementById("modalGaleria")
);

document.addEventListener("DOMContentLoaded", function () {
  //cargar datos con el plugin datatables
  tblHabitaciones = new DataTable("#tblHabitaciones", {
    processing: true,
    ajax: {
      url: base_url + "habitaciones/listar",
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "estilo" },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<span class="badge bg-warning">${data.numero}</span>`;
          }
          return data;
        },
      },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            let capacidad =
              parseInt(data.capacidad) > 1
                ? '<i class="fas fa-users"></i>'
                : '<i class="fas fa-user"></i>';
            return `<span class="badge bg-primary">${
              data.capacidad + " - " + capacidad
            }</span>`;
          }
          return data;
        },
      },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            let foto = data.foto == null ? "default.png" : data.foto;
            return `<img class="img-thumbnail" src="${
              ruta_principal + "assets/img/habitaciones/" + foto
            }" width="50">`;
          }
          return data;
        },
      },
      { data: "precio" },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<div class="btn-group" role="group" aria-label="Button group">
                            <button class="btn btn-danger btn-sm" type="button" onclick="eliminarHabit(${data.id})"><i class="fas fa-trash"></i></button>
                            <button class="btn btn-info btn-sm" type="button" onclick="editarHabit(${data.id})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-success btn-sm" type="button" onclick="agregarImages(${data.id})"><i class="fas fa-images"></i></button>
                        </div>`;
          }
          return data;
        },
      },
    ],
    language: {
      url: "/reservas/assets/admin/i18n/es-ES.json",
    },
    responsive: true,
    order: [[0, "desc"]],
  });

  //Inicializar un Editor
  ClassicEditor.create(document.querySelector("#descripcion"), {
    toolbar: {
      items: [
        "selectAll",
        "|",
        "heading",
        "|",
        "bold",
        "italic",
        "bulletedList",
        "numberedList",
        "alignment",
        "|",
        "link",
        "blockQuote",
        "insertTable",
        "mediaEmbed",
      ],
      shouldNotGroupWhenFull: true,
    },
  })
    .then((editor) => {
      editorDescripcion = editor;
    })
    .catch((error) => {
      console.error(error);
    });
  //vista Previa
  foto.addEventListener("change", function (e) {
    foto_actual.value = "";
    if (
      e.target.files[0].type == "image/png" ||
      e.target.files[0].type == "image/jpg" ||
      e.target.files[0].type == "image/jpeg"
    ) {
      const url = e.target.files[0];
      const tmpUrl = URL.createObjectURL(url);
      containerPreview.innerHTML = `<img class="img-thumbnail" src="${tmpUrl}" width="200">
            <button class="btn btn-danger" type="button" onclick="deleteImg()"><i class="fas fa-trash"></i></button>`;
    } else {
      foto.value = "";
      alertaPersonalizada(
        "warning",
        "SOLO SE PERMITEN IMG DE TIPO PNG-JPG-JPEG"
      );
    }
  });
  //limpiar campos
  btnNuevo.addEventListener("click", function () {
    id.value = "";
    btnAccion.textContent = "Registrar";
    formulario.reset();
    deleteImg();
  });
  //registrar habitaciones
  formulario.addEventListener("submit", function (e) {
    e.preventDefault();
    if (
      estilo.value == "" ||
      capacidad.value == "" ||
      numero.value == "" ||
      precio.value == ""
    ) {
      alertaSW("TODO LOS CAMPOS CON * SON REQUERIDOS", "warning");
    } else {
      const url = base_url + "habitaciones/registrar";
      insertarRegistros(url, this, id, tblHabitaciones, btnAccion, false);
    }
  });

  //########### Galeria de imagenes ########
  let myDropzone = new Dropzone(".dropzone", {
    dictDefaultMessage: "Arrastar y Soltar Imagenes",
    acceptedFiles: ".png, .jpg, .jpeg",
    maxFiles: 10,
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 10,
  });
  btnProcesar.addEventListener("click", function () {
    myDropzone.processQueue();
  });
  myDropzone.on("complete", function (file) {
    myDropzone.removeFile(file);
    Swal.fire("Aviso?", "IMAGENES SUBIDA", "success");
    setTimeout(() => {
      modalGaleria.hide();
    }, 1500);
  });
});

// ====== NUEVO: Galería integrada en el formulario ======
  // Solo se activará cuando la habitación ya tenga ID (modo edición).
  let dzInline = new Dropzone("#dzGaleriaInline", {
    dictDefaultMessage: "Arrastra aquí las imágenes adicionales",
    acceptedFiles: ".png, .jpg, .jpeg",
    maxFiles: 10,
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 10,
  });

  const btnSubirGaleria = document.getElementById("btnSubirGaleria");
  const idHabitacionGaleria = document.getElementById("idHabitacionGaleria");
  const galeriaAdicional = document.getElementById("galeriaAdicional");
  const containerGaleriaInline = document.getElementById("containerGaleriaInline");

  if (btnSubirGaleria) {
    btnSubirGaleria.addEventListener("click", function () {
      if (!idHabitacionGaleria.value) {
        Swal.fire("Aviso", "Primero guarda la habitación (no tiene ID).", "info");
        return;
      }
      dzInline.processQueue();
    });
  }

  dzInline.on("sending", function(file, xhr, formData) {
    // Aseguramos que el id se envíe (ya hay un input hidden, pero por seguridad)
    formData.append("idHabitacion", idHabitacionGaleria.value);
  });

  dzInline.on("complete", function (file) {
    dzInline.removeFile(file);
  });

  dzInline.on("queuecomplete", function() {
    Swal.fire("Éxito", "Imágenes subidas", "success");
    cargarGaleriaInline(idHabitacionGaleria.value);
  });

  function cargarGaleriaInline(id) {
    if (!id) return;
    const url = base_url + "habitaciones/verGaleria/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        let html = "";
        let destino = ruta_principal + "assets/img/habitaciones/" + id + "/";
        if (res.length === 0) {
          html = '<p class="text-muted">No hay imágenes adicionales.</p>';
        } else {
          for (let i = 0; i < res.length; i++) {
            const fullPath = id + "/" + res[i];
            html += `
              <div class="col-6 col-sm-4 col-md-3">
                <div class="position-relative border rounded p-1 h-100 d-flex flex-column">
                  <img src="${destino + res[i]}" class="img-fluid mb-2 rounded" style="object-fit:cover; aspect-ratio:1/1;" alt="img">
                  <button class="btn btn-sm btn-outline-danger w-100 mt-auto btnEliminarImagen" 
                      type="button" data-id="${id}" data-name="${fullPath}">
                      <i class="fas fa-trash-alt"></i> Eliminar
                  </button>
                </div>
              </div>`;
          }
        }
        containerGaleriaInline.innerHTML = html;
        activarBotonesEliminarInline();
      }
    };
  }

  function activarBotonesEliminarInline() {
    const btns = containerGaleriaInline.querySelectorAll(".btnEliminarImagen");
    btns.forEach(b => {
      b.addEventListener("click", function() {
        const nombre = this.getAttribute("data-name");
        eliminarImgGaleria(nombre);
      });
    });
  }

  function eliminarImgGaleria(rutaParcial) {
    Swal.fire({
      title: "¿Eliminar imagen?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar"
    }).then(result => {
      if (!result.isConfirmed) return;
      fetch(base_url + "habitaciones/eliminarImg", {
        method: "POST",
        body: JSON.stringify({ url: rutaParcial }),
      })
      .then(r => r.json())
      .then(json => {
        if (json.icono === "success") {
          Swal.fire("Eliminada", json.msg, "success");
          cargarGaleriaInline(idHabitacionGaleria.value);
        } else {
          Swal.fire("Error", json.msg, "error");
        }
      })
      .catch(() => Swal.fire("Error", "No se pudo eliminar", "error"));
    });
  }

  // Exponer funciones si necesitas (no obligatorio)
  window.cargarGaleriaInline = cargarGaleriaInline;

  // ====== FIN: Galería integrada ======


function deleteImg() {
  foto.value = "";
  containerPreview.innerHTML = "";
  foto_actual.value = "";
}

function eliminarHabit(idHabitacion) {
  const url = base_url + "habitaciones/eliminar/" + idHabitacion;
  eliminarRegistros(url, tblHabitaciones);
}

function editarHabit(idHabitacion) {
  const url = base_url + "habitaciones/editar/" + idHabitacion;
  //hacer una instancia del objeto XMLHttpRequest
  const http = new XMLHttpRequest();
  //Abrir una Conexion - POST - GET
  http.open("GET", url, true);
  //Enviar Datos
  http.send();
  //verificar estados
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      id.value = res.id;
      estilo.value = res.estilo;
      editorDescripcion.setData(res.descripcion);
      capacidad.value = res.capacidad;
      numero.value = res.numero;
      precio.value = res.precio;
      foto_actual.value = res.foto;
      containerPreview.innerHTML = `<img class="img-thumbnail" src="${
        ruta_principal + "assets/img/habitaciones/" + res.foto
      }" width="200">
            <button class="btn btn-danger" type="button" onclick="deleteImg()"><i class="fas fa-trash"></i></button>`;
      btnAccion.textContent = "Actualizar";
      firstTab.show();
    }
  };
}

function agregarImages(idHabitacion) {
  const url = base_url + "habitaciones/verGaleria/" + idHabitacion;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      document.querySelector("#idHabitacion").value = idHabitacion;
      let html = "";
      let destino =
        ruta_principal + "assets/img/habitaciones/" + idHabitacion + "/";
      for (let i = 0; i < res.length; i++) {
        html += `<div class="col-md-3">
                    <img class="img-thumbnail" src="${destino + res[i]}">
                    <div class="d-grid">
                        <button class="btn btn-danger btnEliminarImagen" type="button" data-id="${idHabitacion}" data-name="${
          idHabitacion + "/" + res[i]
        }">Eliminar</button>
                    </div>     
                </div>`;
      }
      containerGaleria.innerHTML = html;
      eliminarImagen();
      modalGaleria.show();
    }
  };
}

function eliminarImagen() {
  let lista = document.querySelectorAll(".btnEliminarImagen");
  for (let i = 0; i < lista.length; i++) {
    lista[i].addEventListener("click", function () {
      let idHabit = lista[i].getAttribute("data-id");
      let nombre = lista[i].getAttribute("data-name");
      eliminar(idHabit, nombre);
    });
  }
}

function eliminar(idHabit, nombre) {
  const url = base_url + "habitaciones/eliminarImg";
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(
    JSON.stringify({
      url: nombre,
    })
  );
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      const res = JSON.parse(this.responseText);
      Swal.fire("Aviso?", res.msg, res.icono);
      if (res.icono == "success") {
        agregarImages(idHabit);
      }
    }
    
  };
}
