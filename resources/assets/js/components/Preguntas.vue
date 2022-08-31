<template>
    <main class="">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a target="_blank" href="/">Ir a Portal</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Preguntas frecuentes
                    <button type="button" @click="abrirModal('pregunta','registrar')" class="btn btn-primary">
                        <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="pregunta">Pregunta</option>
                                    <option value="respuesta">Respuesta</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarPregunta(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarPregunta(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                        <tr>
                            <th style="vertical-align:middle;">Pregunta</th>
                            <th style="vertical-align:middle;">Respuesta</th>
                            <th style="vertical-align:middle;">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="pregunta in arrayPregunta" :key="pregunta.id">
                            <td class="align-middle" v-text="pregunta.pregunta"></td>
                            <td class="align-middle" v-text="pregunta.respuesta"></td>
                            <td class="align-middle" align="center">
                                <button type="button" @click="abrirModal('pregunta', 'actualizar', pregunta)" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </button>&nbsp;
                                <button  type="button" class="btn btn-danger btn-sm" @click="eliminarPregunta(pregunta.id)">
                                   <i class="fa fa-trash"></i>
                               </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding:1em">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" >Pregunta (*)</label>
                                <div class="col-md-9">
                                    <textarea v-model="pregunta" maxlength="254" class="form-control" placeholder="Ingrese la pregunta" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Respuesta (*)</label>
                                <div class="col-md-9">
                                    <textarea v-model="respuesta" maxlength="254" class="form-control" placeholder="Ingrese la respuesta" />
                                </div>
                            </div>
                            <div v-show="errorPregunta" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPregunta" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer" style="padding: 1em">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPregunta()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPregunta()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal registrar-->

    </main>
</template>

<script>
    export default {
        data (){
            return {
                pregunta_id: 0,
                pregunta : '',
                respuesta : '',
                arrayPregunta : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPregunta : 0,
                errorMostrarMsjPregunta : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'pregunta',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;

            }
        },
        methods : {
            listarPregunta(page, buscar, criterio){
                let me = this;
                var url= '/getpreguntas?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPregunta = respuesta.preguntas.data;
                    me.pagination= respuesta.pagination;
                }).catch(function (error) {
                        console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPregunta(page, buscar, criterio);
            },
            registrarPregunta(){
                if (this.validarPregunta()){
                    return;
                }

                let me = this;
                axios.post('/pregunta/registrar',{
                    'pregunta': this.pregunta,
                    'respuesta' : this.respuesta

                }).then(function (response) {
                    console.log(response);
                    me.cerrarModal();
                    me.listarPregunta(1, '', 'pregunta');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarPregunta(){
                if (this.validarPregunta()){
                    return;
                }

                let me = this;
                axios.put('/pregunta/actualizar',{
                    'pregunta': this.pregunta,
                    'respuesta' : this.respuesta,
                    'id': this.pregunta_id
                }).then(function (response) {
                    me.listarPregunta(1, '', 'pregunta');
                    me.cerrarModal();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            eliminarPregunta(id){
                swal.fire({
                title: 'Esta seguro de eliminar esta pregunta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: "#d33",
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/pregunta/eliminar',{
                        'id': id
                    }).then(function(response){
                        me.listarPregunta(1,'','pregunta');
                         swal.fire(
                            'Eliminado!',
                            'Pregunta eliminada con éxito!',
                            'success'
                            )
                    }).catch(function(error){
                        console.log(error);
                    });
                   
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                }
                })
            },
            validarPregunta(){
                this.errorPregunta=0;
                this.errorMostrarMsjPregunta =[];

                if(!this.pregunta) this.errorMostrarMsjPregunta.push("Debe digitar la pregunta");
                if(this.errorMostrarMsjPregunta.length) this.errorPregunta = 1;

                if(this.errorPregunta === 0){
                    if(!this.respuesta) this.errorMostrarMsjPregunta.push("Debe digitar la respuesta");
                    if(this.errorMostrarMsjPregunta.length) this.errorPregunta = 1;   
                }

                return this.errorPregunta;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.pregunta='';
                this.respuesta='';
                this.errorPregunta=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "pregunta":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Pregunta';
                                this.pregunta= '';
                                this.respuesta='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tituloModal='Actualizar Pregunta';
                                this.tipoAccion=2;
                                this.pregunta_id=data['id'];
                                this.pregunta = data['pregunta'];
                                this.respuesta = data['respuesta'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarPregunta(1, this.buscar, this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        overflow-y: scroll;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
