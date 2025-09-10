let tblHabitaciones, editorDescripcion;

// Referencias principales del formulario
const formulario = document.querySelector("#formulario");
const btnAccion = document.querySelector("#btnAccion");
const btnNuevo = document.querySelector("#btnNuevo");

const id = document.querySelector("#id");
const estilo = document.querySelector("#estilo");
const descripcion = document.querySelector("#descripcion");
const precio = document.querySelector("#precio");
const foto = document.querySelector("#foto");
const foto_actual = document.querySelector("#foto_actual");
const containerPreview = document.querySelector("#containerPreview");

// Estos dos NO estaban en tu JS enviado
const capacidad = document.querySelector("#capacidad");
const numero = document.querySelector("#numero");

// Modal galería (modal original)
const modalGaleria = new bootstrap.Modal(
  document.getElementById("modalGaleria")
);

// Elementos galería inline
const galeriaAdicional = document.getElementById("galeriaAdicional");
const dzGaleriaInlineEl = document.getElementById("dzGaleriaInline");
const btnSubirGaleria = document.getElementById("btnSubirGaleria");
const idHabitacionGaleria = document.getElementById("idHabitacionGaleria");
const containerGaleriaInline = document.getElementById(
  "containerGaleriaInline"
);

document.addEventListener("DOMContentLoaded", function () {
  // DataTable
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
          const icon =
            parseInt(data.capacidad) > 1
              ? '<i class="fas fa-users"></i>'
              : '<i class="fas fa-user"></i>';
          return `<span class="badge bg-primary">${data.capacidad} - ${icon}</span>`;
        },
      },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            const f = data.foto == null ? "default.png" : data.foto;
            return `<img class="img-thumbnail" src="${ruta_principal}assets/img/habitaciones/${f}" width="50">`;
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
                <button class="btn btn-danger btn-sm" type="button" onclick="eliminarHabit(${data.id})">
                  <i class="fas fa-trash"></i>
                </button>
                <button class="btn btn-info btn-sm" type="button" onclick="editarHabit(${data.id})">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-success btn-sm" type="button" onclick="agregarImages(${data.id})">
                  <i class="fas fa-images"></i>
                </button>
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

  // CKEditor
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
    .catch((error) => console.error(error));

  // Vista previa foto principal
  foto.addEventListener("change", function (e) {
    foto_actual.value = "";
    if (
      e.target.files[0] &&
      ["image/png", "image/jpg", "image/jpeg"].includes(e.target.files[0].type)
    ) {
      const tmpUrl = URL.createObjectURL(e.target.files[0]);
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

  // Botón Nuevo
  btnNuevo.addEventListener("click", function () {
    id.value = "";
    btnAccion.textContent = "Registrar";
    formulario.reset();
    deleteImg();
    if (galeriaAdicional) {
      galeriaAdicional.style.display = "none";
      idHabitacionGaleria.value = "";
      containerGaleriaInline.innerHTML = "";
    }
  });

  // Submit (Registrar / Actualizar)
  formulario.addEventListener("submit", function (e) {
    e.preventDefault();

    // Asegurar que la descripción actual del editor llegue
    if (editorDescripcion) {
      descripcion.value = editorDescripcion.getData();
    }

    if (
      estilo.value.trim() === "" ||
      capacidad.value.trim() === "" ||
      numero.value.trim() === "" ||
      precio.value.trim() === ""
    ) {
      alertaSW("TODO LOS CAMPOS CON * SON REQUERIDOS", "warning");
      return;
    }

    const url = base_url + "habitaciones/registrar";
    insertarRegistros(url, this, id, tblHabitaciones, btnAccion, false);

    // Si se estaba en modo actualización y no estaba visible la galería, la mostramos después de éxito
    // (Puedes mover esto a la respuesta de éxito dentro de insertarRegistros si lo controlas allí).
  });

  // Dropzone Modal (galería antigua)
  const myDropzone = new Dropzone("#modalGaleria .dropzone", {
    dictDefaultMessage: "Arrastrar y Soltar Imágenes",
    acceptedFiles: ".png,.jpg,.jpeg",
    maxFiles: 10,
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 10,
  });

  const btnProcesar = document.getElementById("btnProcesar");
  btnProcesar.addEventListener("click", function () {
    myDropzone.processQueue();
  });

  myDropzone.on("complete", function (file) {
    myDropzone.removeFile(file);
  });

  myDropzone.on("queuecomplete", function () {
    Swal.fire("Aviso", "Imágenes subidas", "success");
    setTimeout(() => modalGaleria.hide(), 1200);
  });

  // Dropzone Galería Inline
  if (dzGaleriaInlineEl) {
    const dzInline = new Dropzone("#dzGaleriaInline", {
      url: base_url + "habitaciones/galeriaImagenes",
      dictDefaultMessage: "Arrastra aquí las imágenes adicionales",
      acceptedFiles: ".png,.jpg,.jpeg",
      maxFiles: 10,
      addRemoveLinks: true,
      autoProcessQueue: false,
      parallelUploads: 10,
    });

    if (btnSubirGaleria) {
      btnSubirGaleria.addEventListener("click", function () {
        if (!idHabitacionGaleria.value) {
          Swal.fire("Aviso", "Primero guarda la habitación.", "info");
          return;
        }
        dzInline.processQueue();
      });
    }

    dzInline.on("sending", function (file, xhr, formData) {
      formData.append("idHabitacion", idHabitacionGaleria.value);
    });

    dzInline.on("queuecomplete", function () {
      Swal.fire("Éxito", "Imágenes subidas", "success");
      cargarGaleriaInline(idHabitacionGaleria.value);
      dzInline.removeAllFiles(true);
    });

    // Exponer para usar luego si hiciera falta
    window.__dzInline = dzInline;
  }
});

// Eliminar preview foto principal
function deleteImg() {
  foto.value = "";
  containerPreview.innerHTML = "";
  foto_actual.value = "";
}

// CRUD auxiliares
function eliminarHabit(idHabitacion) {
  const url = base_url + "habitaciones/eliminar/" + idHabitacion;
  eliminarRegistros(url, tblHabitaciones);
}

function editarHabit(idHabitacion) {
  const url = base_url + "habitaciones/editar/" + idHabitacion;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const res = JSON.parse(this.responseText);

      id.value = res.id;
      estilo.value = res.estilo;
      editorDescripcion && editorDescripcion.setData(res.descripcion || "");
      capacidad.value = res.capacidad;
      numero.value = res.numero;
      precio.value = res.precio;
      foto_actual.value = res.foto;

      containerPreview.innerHTML = `<img class="img-thumbnail" src="${ruta_principal}assets/img/habitaciones/${res.foto}" width="200">
        <button class="btn btn-danger" type="button" onclick="deleteImg()"><i class="fas fa-trash"></i></button>`;

      btnAccion.textContent = "Actualizar";
      firstTab.show();

      // Mostrar galería inline
      if (galeriaAdicional) {
        galeriaAdicional.style.display = "block";
        idHabitacionGaleria.value = res.id;
        cargarGaleriaInline(res.id);
      }
    }
  };
}

// Modal galería (antigua)
function agregarImages(idHabitacion) {
  const url = base_url + "habitaciones/verGaleria/" + idHabitacion;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const res = JSON.parse(this.responseText);
      document.querySelector("#idHabitacion").value = idHabitacion;
      let html = "";
      const destino =
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
  document.querySelectorAll(".btnEliminarImagen").forEach((btn) =>
    btn.addEventListener("click", function () {
      const idHabit = this.getAttribute("data-id");
      const nombre = this.getAttribute("data-name");
      eliminar(idHabit, nombre);
    })
  );
}

function eliminar(idHabit, nombre) {
  const url = base_url + "habitaciones/eliminarImg";
  const http = new XMLHttpRequest();
  http.open("POST", url, true);
  http.send(JSON.stringify({ url: nombre }));
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const res = JSON.parse(this.responseText);
      Swal.fire("Aviso", res.msg, res.icono);
      if (res.icono === "success") {
        agregarImages(idHabit);
      }
    }
  };
}

// Cargar galería inline
function cargarGaleriaInline(idHab) {
  if (!idHab) return;
  const url = base_url + "habitaciones/verGaleria/" + idHab;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const res = JSON.parse(this.responseText);
      let html = "";
      const destino = ruta_principal + "assets/img/habitaciones/" + idHab + "/";
      if (res.length === 0) {
        html = '<p class="text-muted mb-0">No hay imágenes adicionales.</p>';
      } else {
        for (let i = 0; i < res.length; i++) {
          const fullPath = idHab + "/" + res[i];
          html += `<div class="col-6 col-sm-4 col-md-3">
            <div class="position-relative border rounded p-1 h-100 d-flex flex-column">
              <img src="${
                destino + res[i]
              }" class="img-fluid mb-2 rounded" style="object-fit:cover;aspect-ratio:1/1;" alt="img">
              <button type="button" class="btn btn-sm btn-outline-danger w-100 mt-auto btnEliminarImagenInline" data-name="${fullPath}">
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
  document.querySelectorAll(".btnEliminarImagenInline").forEach((b) =>
    b.addEventListener("click", function (e) {
      e.preventDefault(); // evita cualquier submit accidental
      const rutaParcial = this.getAttribute("data-name");
      eliminarImgGaleria(rutaParcial);
    })
  );
}

function eliminarImgGaleria(rutaParcial) {
  Swal.fire({
    title: "¿Eliminar imagen?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;
    fetch(base_url + "habitaciones/eliminarImg", {
      method: "POST",
      body: JSON.stringify({ url: rutaParcial }),
    })
      .then((r) => r.json())
      .then((json) => {
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
