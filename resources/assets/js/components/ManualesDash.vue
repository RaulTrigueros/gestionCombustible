<template>
  <div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/" target="_blank">Ir a Portal</a>
      </li>
    </ol>

    <h1>Manuales Municipales</h1>
    <hr />
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Manuales
          <button
            type="button"
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#manualModal"
            @click="cleanManual"
          >
            &nbsp;
            <i class="fa fa-plus-square"></i>&nbsp;Nuevo Manual
          </button>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-8">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="nombre">Nombre</option>
                  <option value="descripcion">Descripcion</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  class="form-control"
                  placeholder="Texto a buscar"
                />
                <button
                  type="submit"
                  @click="getManuales(1, buscar, criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <br />
          <!-- INICIO DEL LISTADO-->
          <table class="table table-bordered table-striped table-sm table-responsive">
            <thead>
              <tr>
                <th style="vertical-align:middle;">Nombre</th>
                <th style="vertical-align:middle;">Descripción</th>
                <th style="vertical-align:middle;">Archivo</th>
                <th style="vertical-align:middle;">Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="manual in manuales" :key="manual.id">
                <td class="align-middle">{{manual.nombre | upper}}</td>
                <td class="align-middle" v-text="manual.descripcion"></td>
                <td class="align-middle">
                  <button class="btn btn-info" @click="verPDF(manual.archivo)">
                    Ver
                    <i class="fa fa-file-pdf-o"></i>
                  </button>
                </td>
                <td class="align-middle">
                  <button
                    class="btn btn-primary"
                    @click="setManual(manual)"
                    data-toggle="modal"
                    data-target="#manualModal"
                  >Editar</button>
                  <button class="btn btn-danger" @click="deleteManual(manual.id)">Eliminar</button>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page,buscar,criterio)"
                  v-text="page"
                ></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                >Sig</a>
              </li>
            </ul>
          </nav>
          <!-- FIN DEL LISTADO-->
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal"
      id="manualModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="manualModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="manualModalLabel" v-if="manual.id === 0">Nuevo Manual</h5>
            <h5 class="modal-title" id="manualModalLabel" v-if="manual.id !== 0">Editando Manual</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Nombre</label>
              <input type="text" class="form-control" v-model="manual.nombre" />
            </div>
            <div class="form-group">
              <label>Descripción</label>
              <input type="text" class="form-control" v-model="manual.descripcion" />
            </div>
            <div class="custom-file" v-if="manual.id == 0">
              <input type="file" class="custom-file-input" id="customFile" @change="setFile"/>
              <label class="custom-file-label" for="customFile">Eliga un PDF, menor de 512KB</label>
            </div>
            <div class="form-group mt-3">
              <input
                type="submit"
                class="form-control btn btn-success"
                value="Guardar"
                v-if="manual.id === 0"
                @click="createManual"
              />
              <input
                type="submit"
                class="form-control btn btn-success"
                value="Actualizar"
                v-if="manual.id !== 0"
                @click="updateManual"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal pdf -->
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
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      manual: {
        id: 0,
        nombre: "",
        descripcion: "",
        archivo: ""
      },
      manuales: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 5, //cuantos registros en tabla
      criterio: "nombre",
      buscar: "",
      myPdfState: {
        pdf: null,
        currentPage: 1,
        zoom: 1
      }
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }

      return pagesArray;
    }
  },
  filters: {
    upper: function(value) {
      return value.toUpperCase();
    },
    fecha: function(fecha) {
      const date = new Date(fecha);
      return date.toLocaleString(["es-ES"], {
        day: "2-digit",
        month: "2-digit",
        year: "numeric"
      });
    }
  },
  methods: {
    cambiarPagina(page, buscar, criterio) {
      this.pagination.current_page = page;
      this.getManuales();
    },
    async getManuales(page = 1, buscar = "", criterio = "nombre") {
      const me = this;
      const respuesta = await axios
        .get(
          "/manuales/buscar?page=" +
            page +
            "&buscar=" +
            buscar +
            "&criterio=" +
            criterio
        )
        .catch(err => console.log(err));

      this.manuales = respuesta.data.manual_organizacion.data;
      this.pagination = respuesta.data.pagination;
    },
    createManual() {
      console.log(this.manual);
      if (!this.validarManualCrear()) {
        return;
      }

      axios
        .post("/manuales", this.manual)
        .then(res => {
          console.log(res);

          Swal.fire({
            position: "center",
            type: "success",
            title: "Manual creado Exitosamente",
            showConfirmButton: true
          });

          this.cerrarModal();
          this.cleanManual();
          this.getManuales();
        })
        .catch(err => {
          Swal.fire({
            position: "center",
            type: "error",
            title: "No se pudo crear el manual",
            showConfirmButton: true
          });
          this.cerrarModal();
          this.cleanManual();
          return;
        });
    },
    validarManualCrear() {
      const errores = [];
      if (
        this.manual.nombre !== "" &&
        this.manual.descripcion !== "" &&
        this.manual.archivo !== ""
      ) {
        return true;
      } else {
        if (this.manual.nombre === "") {
          errores.push(" *Nombre esta vacio");
        }
        if (this.manual.descripcion === "") {
          errores.push(" *Descripcion esta vacio");
        }
        if (this.manual.archivo === "") {
          errores.push(" *No se ha seleccionado archivo");
        }
      }
      let mensaje = "";
      errores.forEach(error => {
        mensaje += error;
      });
      Swal.fire("Error", mensaje, "error");
      return false;
    },
    validarManualUpdate() {
      const errores = [];
      if (
        this.manual.nombre !== "" &&
        this.manual.descripcion !== ""
      ) {
        return true;
      } else {
        if (this.manual.nombre === "") {
          errores.push(" *Nombre esta vacio");
        }
        if (this.manual.descripcion === "") {
          errores.push(" *Descripcion esta vacio");
        }
      }
      let mensaje = "";
      errores.forEach(error => {
        mensaje += error;
      });
      Swal.fire("Error", mensaje, "error");
      return false;
    },
    async updateManual() {
      console.log(this.manual);
      if(!this.validarManualUpdate())
        return;

      try {
        const respuesta = await axios
        .put("/manuales/" + this.manual.id, this.manual);
        Swal.fire("", "Actualizacion correta", "success");
      } catch (error) {
        err => console.log(err)
        Swal.fire("No se pudo actualizar", "Ocurrio un error al actualizar", "error");
      }
      this.cerrarModal();
      this.cleanManual();
      this.getManuales();
    },
    deleteManual(id) {
      Swal.fire({
        title: "Desea Eliminar Este Manual?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminalo",
        cancelButtonText: "Cancelar"
      }).then(result => {
        if (result.value) {
          axios
            .delete("/manuales/" + id)
            .then(res => {
              Swal.fire("Manual Eliminado!", "Exito", "success");
              this.getManuales();
            })
            .catch(err => console.log(err));
        }
      });
    },
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

    verPDF(pdf) {
      this.loadPDF(pdf);
      // Swal.fire("Proximamente", "Comming Soon", "info");
    },
    mostrarModal(id) {
      $(id).modal("show");
    },
    cerrarModal() {
      $("#manualModal").modal("hide");
      $(".modal-backdrop").remove();
    },
    cleanManual() {
      this.manual = {
        id: 0,
        nombre: "",
        descripcion: "",
        archivo: ""
      };
      $("#customFile").next().text('Eliga un PDF, menor de 512KB');
    },
    setManual(manual) {
      this.manual = manual;
      this.manual.archivo = "";
    },
    setFile(input) {
      let archivo = input.target.files;
      let tipo = "";
      console.log(input.target.files);
      

      if (archivo.length !== 0) {
        archivo = archivo[0];
        tipo = archivo.type;
        if (tipo === "application/pdf") {
          if (archivo.size < 512000) {
            let reader = new FileReader();
            reader.readAsDataURL(archivo);
            reader.onload = e => { 
              this.manual.archivo = e.target.result;
              Swal.fire("", "Exito", "success");
              $(input.target).next().text(archivo.name);              
            };
          } else {
            console.log("archivo muy pesado");
            Swal.fire(
              "Mucho peso",
              "El PDF tiene un peso mayor a los 512KB",
              "error"
            );
          }
        } else {
          console.log("no es pdf");
          Swal.fire(
            "Documento no soportado",
            "El archivo seleccionado no tiene formato PDF",
            "error"
          );
        }
      } else {
        console.log("files vacio");
        Swal.fire("Vacio", "Por favor escoja un documento", "error");
      }
    }
  },
  mounted() {
    this.getManuales();
  }
};
</script>