import axios from 'axios';
<template>
  <main class>
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/" target="_blank">Ir a Portal</a>
      </li>
    </ol>
    <div class="container">
      <h1>Gestión de Documentos de insumos</h1>
      <ul class="nav nav-tabs" id="opciones">
        <li class="nav-item">
          <a class="nav-link" href="#" @click="activarDocumentos()">Documentos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" @click="activarSubirDoc()">Subir Nuevo</a>
        </li>
      </ul>
      <div id="documentos" class="row col-10 m-0 m-auto">
        <h1 class="col-12">Documentos</h1>
        <hr />
        <div class="card" v-for="doc in listaDocumentos" :key="doc.id">
          <h3 class="card-header" v-text="doc.nombre"></h3>
          <div class="card-body">
            <p class="card-text" v-text="doc.descripcion"></p>
          </div>
          <div class="card-footer">
            <div class="row justify-content-around">
              <a
                :href="doc.archivo"
                class="btn btn-secondary col-3"
                v-bind:download="doc.nombrearchivo +'.pdf'"
              >Descargar PDF</a>
              <a class="btn btn-danger col-3" @click="eliminarDoc(doc.id, $event)" href="#">Eliminar</a>
              <a class="btn btn-success col-3" @click="editarDoc(doc)" href="#">Editar</a>
              <a
                name
                id
                class="btn btn-primary col-3"
                href="#"
                role="button"
                @click="loadPDF(doc.archivo)"
              >Ver PDF</a>
            </div>
          </div>
        </div>
        <!-- <h3 class="text-info" v-if="listarDocumentos.length === 0">No hay documentos</h3> -->
      </div>

      <div class="modal" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editando...</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" class="col-12 align-self-center">
                <div class="form-group">
                  <label for>Nombre (*)</label>
                  <input
                    type="text"
                    name
                    id="nombreEdit"
                    class="form-control"
                    placeholder="nombre del documento"
                    required
                    @keyup="validarNombre($event)"
                  />
                  <div class="invalid-feedback" style="display:none">Nombre no valido</div>
                  <small class="text-muted"></small>
                </div>
                <div class="form-group">
                  <label for>Descripción (*)</label>
                  <textarea
                    class="form-control"
                    name
                    id="descripcionEdit"
                    rows="3"
                    required
                    @keyup="validarDescripcion($event)"
                  ></textarea>
                  <small class="text-muted">Breve descripción del documento</small>
                </div>
                <small class="form-text text-warning my-3">(*) Son campos obligatorios</small>
                <button
                  type="button"
                  class="btn btn-primary form-control"
                  @click="guardarCambios()"
                >Guardar Cambios</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div
        class="modal fade bd-example-modal-lg"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalLarge"
        aria-hidden="true"
        id="modalPDF"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div id="my_pdf_viewer">
              <div id="canvas_container">
                <canvas id="pdf_renderer"></canvas>
              </div>
              <div id="navigation_controls" class="row justify-content-around">
                <button id="go_previous" @click="paginaAnterior()" class="btn btn-primary">Anterior</button>
                <!-- <input id="current_page" value="1" type="number"  @keypress="irPagina($event)"/> -->
                <span id="numPaginas">1/1</span>
                <button id="go_next" @click="paginaSiguiente()" class="btn btn-primary">Siguiente</button>
              </div>
              <!-- <div id="zoom_controls">  
                        <button id="zoom_in">+</button>
                        <button id="zoom_out">-</button>
              </div>-->
            </div>
          </div>
        </div>
      </div>

      <div id="subirArchivo" style="display:none" class="row col-10 m-0 m-auto">
        <h1 class="col-12">subir archivo</h1>
        <hr />
        <form
          method="post"
          class="col-12 align-self-center"
          id="formDoc"
          enctype="multipart/form-data"
        >
          <div class="form-group">
            <label for>Nombre (*)</label>
            <input
              type="text"
              v-model="nombre"
              name
              id
              class="form-control"
              placeholder="nombre del documento"
              required
              @keyup="validarNombre($event)"
            />
            <div class="invalid-feedback" style="display:none">Nombre no valido</div>
            <small class="text-muted"></small>
          </div>
          <div class="form-group">
            <label for>Descripción (*)</label>
            <textarea
              class="form-control"
              v-model="descripcion"
              name
              id
              rows="3"
              required
              @keyup="validarDescripcion($event)"
            ></textarea>
            <div class="invalid-feedback" style="display:none">descripcion no valido</div>
            <small class="text-muted">Breve descripción del documento</small>
          </div>
          <div class="form-group">
            <label for>Archivo PDF (*)</label>
            <div class="custom-file">
              <input
                type="file"
                class="custom-file-input"
                id="customFile"
                @change="validarPDF($event)"
                required
              />
              <label class="custom-file-label" for="customFile" id="labelFile">Documento PDF</label>
              <div class="invalid-feedback" style="display:none">Documento no valido</div>
              <small
                class="form-text text-muted"
              >Seleccionar archivo en formato PDF y peso menor a 512 kb</small>
            </div>
          </div>
          <small class="form-text text-warning my-3">(*) Son campos obligatorios</small>
          <button
            type="button"
            class="btn btn-primary form-control"
            @click="subirDocumento()"
          >Subir Documento</button>
        </form>
      </div>
    </div>
  </main>
</template>
<script>
export default {
  data() {
    return {
      listaDocumentos: [],
      idDoc: 0,
      nombre: "",
      nombreArchivo: "",
      descripcion: "",
      archivo: "",
      myPdfState: {
        pdf: null,
        currentPage: 1,
        zoom: 1
      }
    };
  },
  methods: {
    loadPDF(doc) {
      pdfjsLib
        .getDocument(doc)
        .then(pdf => {
          this.myPdfState.pdf = pdf;
          this.myPdfState.currentPage = 1;
          this.zoom = 1;
          this.renderPDF(0);
        })
        .catch(err => {
          console.log("Error al leer el pdf");
          Swal.fire(
            "Opps, algo salio mal",
            "Por favor recargue la pagina para corregir el problema",
            "warning"
          );
        });
    },
    renderPDF(modal) {
      this.myPdfState.pdf.getPage(this.myPdfState.currentPage).then(page => {
        let canvas = document.getElementById("pdf_renderer");
        let ctx = canvas.getContext("2d");
        let viewport = page.getViewport(this.myPdfState.zoom);
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        page.render({
          canvasContext: ctx,
          viewport: viewport
        });
        if (!modal) {
          this.mostrarModal("#modalPDF");
        }
        $("#numPaginas").text(
          this.myPdfState.currentPage +
            " / " +
            this.myPdfState.pdf._pdfInfo.numPages
        );
      });
    },
    mostrarModal(id) {
      $(id).modal("show");
    },
    cerrarModal(id) {
      $(id).modal("hide");
      $(".modal-backdrop").remove();
    },
    paginaAnterior() {
      if (this.myPdfState.pdf == null || this.myPdfState.currentPage == 1)
        return;
      this.myPdfState.currentPage -= 1;
      //document.getElementById("current_page").value = this.myPdfState.currentPage;
      this.renderPDF(1);
    },
    paginaSiguiente() {
      if (
        this.myPdfState.pdf == null ||
        this.myPdfState.currentPage >= this.myPdfState.pdf._pdfInfo.numPages
      )
        return;

      this.myPdfState.currentPage += 1;
      //document.getElementById("current_page").value = this.myPdfState.currentPage;
      this.renderPDF(1);
    },
    irPagina(event) {
      if (this.myPdfState.pdf == null) return;

      let code = event.keyCode ? event.keyCode : event.which;

      // If key code matches that of the Enter key
      if (code == 13) {
        let desiredPage = document.getElementById("current_page").valueAsNumber;

        if (
          desiredPage >= 1 &&
          desiredPage <= this.myPdfState.pdf._pdfInfo.numPages
        ) {
          this.myPdfState.currentPage = desiredPage;
          document.getElementById("current_page").value = desiredPage;
          this.renderPDF(1);
        }
      }
    },
    validarDescripcion(input) {
      if (input.target.value.length > 0) {
        $(input.target).removeClass("is-invalid");
        $(input.target).addClass("is-valid");
        $(input.target)
          .next()
          .css("display", "none");
      } else {
        $(input.target).removeClass("is-valid");
        $(input.target).addClass("is-invalid");
        $(input.target)
          .next()
          .css("display", "block")
          .text("Descripcion no valida");
      }
    },
    validarNombre(input) {
      let patron = /[a-zA-Z]/i;
      if (input.target.value.length > 0) {
        if (patron.test(input.target.value[0])) {
          this.nombreArchivo = input.target.value.replace(" ", "_");
          $(input.target).removeClass("is-invalid");
          $(input.target).addClass("is-valid");
          $(input.target)
            .next()
            .css("display", "none");
        } else {
          $(input.target).removeClass("is-valid");
          $(input.target).addClass("is-invalid");
          $(input.target)
            .next()
            .css("display", "block")
            .text("el nombre no puede comenzar con numeros ni simbolos");
        }
      } else {
        $(input.target).removeClass("is-valid");
        $(input.target).addClass("is-invalid");
        $(input.target)
          .next()
          .css("display", "block");
      }
    },
    validarPDF(input) {
      let archivo = input.target.files;
      let tipo = "";

      if (archivo.length !== 0) {
        archivo = archivo[0];
        tipo = archivo.type;
        if (tipo == "application/pdf") {
          if (archivo.size < 512000) {
            let reader = new FileReader();

            reader.onload = e => {
              this.archivo = e.target.result;
              console.log("exito");
            };
            reader.readAsDataURL(archivo);
            $(input.target)
              .next()
              .text(archivo.name);
            $(input.target).removeClass("is-invalid");
            $(input.target).addClass("is-valid");
            $(input.target)
              .next()
              .next()
              .css("display", "none");
          } else {
            // input.target.files= {};
            $(input.target)
              .next()
              .text("Seleccione PDF");
            $(input.target).removeClass("is-valid");
            $(input.target).addClass("is-invalid");
            $(input.target)
              .next()
              .next()
              .next()
              .css("display", "none");
            $(input.target)
              .next()
              .next()
              .css("display", "block")
              .text("Por favor que el archivo tenga un peso menor a 512 kb");
          }
        } else {
          // input.target.files ;
          $(input.target)
            .next()
            .text("Seleccione PDF");
          $(input.target).removeClass("is-valid");
          $(input.target).addClass("is-invalid");
          $(input.target)
            .next()
            .next()
            .next()
            .css("display", "none");
          $(input.target)
            .next()
            .next()
            .css("display", "block")
            .text("Por favor que el archivo sea PDF");
        }
      }
      // else{
      //     $(input.target).removeClass("is-valid");
      //     $(input.target).addClass("is-invalid");
      //     $(input.target).next().next().css("display","block");
      // }
    },
    limpiarDatosForm() {
      this.nombre = "";
      this.descripcion = "";
      this.archivo = "";
      $("#labelFile").text("Seleccione PDF");
      $("#formDoc").trigger("reset");
      $("#formDoc input").removeClass("is-valid");
      $("#formDoc textarea").removeClass("is-valid");
    },
    eliminarDoc(idDoc, event) {
      console.log(idDoc);

      Swal.fire({
        title: "Desea eliminar el documento?",
        text: "No se podra revertir esta accion",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Si, eliminalo!",
        cancelButtonText: "Cancelar"
      }).then(result => {
        if (result.value) {
          axios
            .post("/eliminarDoc", {
              id: idDoc
            })
            .then(respuesta => {
              //console.log(respuesta);

              //$(event.target).parent().parent().parent().remove();
              this.listarDocumentos();
              Swal.fire({
                position: "center",
                type: "success",
                title: "Se ha eliminado el documento correctamente",
                showConfirmButton: false,
                timer: 1500
              });
            })
            .catch(error => {
              Swal.fire({
                position: "center",
                type: "error",
                title: "No se pudo eliminar el documento",
                showConfirmButton: true
              });
              console.log(error);
            });
        }
      });
    },
    editarDoc(documento) {
      $("#nombreEdit").val(documento.nombre);
      $("#descripcionEdit").val(documento.descripcion);
      this.idDoc = documento.id;
      this.nombreArchivo = documento.nombrearchivo;
      this.mostrarModal("#modalEdit");
    },
    guardarCambios() {
      if ($("#modalEdit .is-invalid").length != 0) {
        Swal.fire({
          position: "center",
          type: "error",
          title: "Por favor ingrese datos correctos",
          showConfirmButton: true
        });
        return;
      }

      axios
        .post("/actualizarDoc", {
          id: this.idDoc,
          nombre: $("#nombreEdit").val(),
          nombrearchivo: this.nombreArchivo,
          descripcion: $("#descripcionEdit").val()
        })
        .then(respuesta => {
          this.idDoc = 0;
          this.cerrarModal("#modalEdit");
          this.listarDocumentos();
          Swal.fire({
            position: "center",
            type: "success",
            title: "Modificación exitosa",
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(error => {
          console.log(error);
          Swal.fire({
            position: "center",
            type: "error",
            title: "No se pudo modificar",
            showConfirmButton: true
          });
        });
    },
    subirDocumento() {
      if (this.archivo.length == 0) {
        console.log("no hay archivo");
        Swal.fire({
          position: "center",
          type: "error",
          title: "Por favor seleccione archivo correcto",
          showConfirmButton: true
        });
        return;
      }
      if ($("#subirArchivo .is-invalid").length != 0) {
        Swal.fire({
          position: "center",
          type: "error",
          title: "Por favor ingrese datos correctos",
          showConfirmButton: true
        });
        return;
      }

      axios
        .post("/registrarDoc", {
          nombre: this.nombre,
          descripcion: this.descripcion,
          archivo: this.archivo,
          nombrearchivo: this.nombreArchivo,
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
        .then(respuesta => {
          console.log(respuesta);

          this.limpiarDatosForm();
          Swal.fire({
            position: "center",
            type: "success",
            title: "Subida de archivo Exitosa",
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(error => {
          Swal.fire({
            position: "center",
            type: "error",
            title: "No se pudo Subir el archivo",
            showConfirmButton: true
          });
          console.log(error);
        });
      console.log("click a subir");
    },
    listarDocumentos() {
      axios
        .get("/get-documentos")
        .then(respuesta => {
          this.listaDocumentos = respuesta.data;
          // console.log(this.listaDocumentos);
        })
        .catch(error => error);
    },
    activarDocumentos() {
      $("#subirArchivo").css("display", "none");
      $("#documentos").css("display", "block");
      $($("#opciones").find("a")[0]).addClass("active");
      $($("#opciones").find("a")[1]).removeClass("active");
      this.listarDocumentos();
    },
    activarSubirDoc() {
      $("#documentos").css("display", "none");
      $("#subirArchivo").css("display", "block");
      $($("#opciones").find("a")[1]).addClass("active");
      $($("#opciones").find("a")[0]).removeClass("active");
    }
  },
  mounted() {
    this.activarDocumentos();
  }
};
</script>
<style >
#canvas_container {
  width: 800px;
  height: 550px;
  overflow: auto;
  background: #333;
  text-align: center;
  border: solid 3px;
}
</style>
