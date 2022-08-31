import axios from 'axios';
<template>
    <div class="container">
        <h1>Solicitud de Insumos en Servicios Generales</h1>
        <hr>
        <h3 v-if="listaDocumentos.length === 0">Actualmente no hay documentos disponibles</h3>
        <h3 v-else></h3>
        <div class="card my-3" v-for="doc in listaDocumentos" :key="doc.id">
                <h3 class="card-header" v-text="doc.nombre"></h3>
                <div class="card-body">
                    <p class="card-text" v-text="doc.descripcion"></p>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-around">
                        <a :href="doc.archivo" class="btn btn-secondary col-4" v-bind:download="doc.nombrearchivo +'.pdf'">Descargar PDF</a>
                        <a name="" id="" class="btn btn-primary col-4" href="#" role="button" @click="loadPDF(doc.archivo)">Ver PDF</a>
                    </div>
                </div>
        </div>

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="modalLarge" aria-hidden="true" id="modalPDF">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div id="my_pdf_viewer">
                    <div id="canvas_container">
                        <canvas id="pdf_renderer"></canvas>
                    </div>
                    <div id="navigation_controls" class="row justify-content-around">
                        <button id="go_previous" @click="paginaAnterior()" class="btn btn-primary">Anterior</button>
                        <span id="numPaginas">1/1</span>
                        <button id="go_next" @click="paginaSiguiente()" class="btn btn-primary">Siguiente</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <h4>Solicitar otro documento -> <a href="/solicitudDocumentoRegistro">Formulario de solicitud</a></h4>
    </div>
</template>
<script>
export default {
    data() {
        return {
            listaDocumentos:[],
            myPdfState : {
                pdf: null,
                currentPage: 1,
                zoom: 1
            }
        }
    },
    methods: {
        listarDocumentos(){
            axios.get("/get-documentos")
            .then(respuesta => {
                this.listaDocumentos = respuesta.data
                // console.log(this.listaDocumentos);
            })
            .catch(error => error);
        },
        loadPDF(doc){
            pdfjsLib.getDocument(doc).then((pdf) => {
                this.myPdfState.pdf = pdf;
                this.myPdfState.currentPage = 1;
                this.zoom = 1;
                this.renderPDF(0);
            });
        },
        renderPDF(modal){
            this.myPdfState.pdf.getPage(this.myPdfState.currentPage).then((page) => {
                let canvas = document.getElementById("pdf_renderer");
                let ctx = canvas.getContext('2d');
                let viewport = page.getViewport(this.myPdfState.zoom);
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                page.render({
                    canvasContext: ctx,
                    viewport: viewport
                });
                if(!modal){
                    this.mostrarModal("#modalPDF");
                }
                $("#numPaginas").text(this.myPdfState.currentPage +' / '+this.myPdfState.pdf._pdfInfo.numPages);
            });
        },
        mostrarModal(id){
            $(id).modal('show');
        },
        cerrarModal(id){
            $(id).modal('hide');
            $('.modal-backdrop').remove();
        },
        paginaAnterior(){
            if(this.myPdfState.pdf == null
               || this.myPdfState.currentPage == 1) return;
            this.myPdfState.currentPage -= 1;
            this.renderPDF(1);
        },
        paginaSiguiente(){
            if(this.myPdfState.pdf == null ||
            this.myPdfState.currentPage >= this.myPdfState.pdf._pdfInfo.numPages)
               return;

            this.myPdfState.currentPage += 1;
            this.renderPDF(1);
        },
    },
    mounted() {
        this.listarDocumentos();
    },
}
</script>
<style >
    #canvas_container {
        width: 800px;
        height: 90vh;
        overflow: auto;
        background: #333;
        text-align: center;
        border: solid 3px;
    }
</style>
