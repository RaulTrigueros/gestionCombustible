<template>
    <main class="">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" target="_blank">Ir a Portal</a></li>
        </ol>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Proyectos
                        <button type="button" @click="abrirModal('proyecto','registrar')" class="btn btn-primary">
                            &nbsp;<i class="fa fa-plus-square"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarProyecto(1,buscar,criterio)"  class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarProyecto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- INICIO DEL LISTADO-->
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                            <tr>
                                <th style="vertical-align:middle;">Nombre</th>
                                <th style="vertical-align:middle;">Descripción</th>
                                <th style="vertical-align:middle;">Monto</th>
                                <th style="vertical-align:middle;">Encargado</th>
                                <th style="vertical-align:middle;">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(proyecto, index) in arrayProyecto" :key="proyecto.id">
                                <td class="align-middle" ><a class="nav-link" @click="actividades(proyecto.id)" href="#" v-text="proyecto.nombre"></a></td>
                                <td class="align-middle" v-text="proyecto.descripcion"></td>
                                <td class="align-middle" v-text="proyecto.montoEstimado"></td>
                                <td class="align-middle" v-text="proyecto.nombre_encargado"></td>
                                <td class="align-middle">
                                    <button type="button" @click="finalizarProyecto(proyecto.id)" class="btn btn-success btn-sm" v-if="arrayEstatus[index-1]">
                                            <i class="fa fa-check"></i>
                                    </button>
                                    <button type="button" @click="abrirModal('proyecto','actualizar',proyecto)" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    
                                    <button type="button" @click="finalizarProyecto(proyecto.id)" class="btn btn-success btn-sm" v-if="arrayEstatus[index-1]">
                                            <i class="fa fa-check"></i>
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
                        <!-- FIN DEL LISTADO-->
                    </div>
                </div>
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input id="nombre" type="text" v-model="nombre" class="form-control" placeholder="Ingrese el nombre">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
                                    <div class="col-md-9">
                                        <input id="descripcion" type="text" v-model="descripcion" class="form-control" placeholder="Ingrese la descripción">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="monto">Monto estimado (*)</label>
                                    <div class="col-md-9">
                                        <input id="monto" type="number" v-model="monto" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label">Encargado (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idEncargado">
                                            <option value="0">*** Seleccione al encargado ***</option>
                                            <option v-for="encargado in arrayEncargado" :key="encargado.id" :value="encargado.id" v-text="encargado.nombre">
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div v-show="errorProyecto" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjProyecto" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProyecto()" >Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProyecto()">Actualizar</button>
                        </div>
                    </div> <!-- /.modal-content -->
                </div> <!-- /.modal-dialog -->
            </div> <!--Fin del modal-->

    </main>
</template>

<script>
    import axios from 'axios';
    import app from '../app';

    export default{
        data(){
            return{
                proyecto_id: 0,
                idEncargado: 0,
                nombre: '',
                descripcion: '',
                monto: 0.00,
                arrayProyecto: [],
                arrayEstatus:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorProyecto: 0,
                errorMostrarMsjProyecto: [],
                pagination:{
                    'total' : 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to' :0,
                },
                offset : 3,
                criterio: 'nombre',
                buscar: '',
                arrayEncargado: []
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page -this.offset;
                if(from<1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from ++;
                }

                return pagesArray;
            }
        },
        methods : {
            listarProyecto(page, buscar, criterio){
                let me = this;
                var url = '/proyecto?page='+ page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayProyecto = respuesta.proyectos.data;
                    me.pagination = respuesta.pagination;

                    me.arrayProyecto.forEach((proyecto, index) => {
                        me.getStatusProyecto(proyecto.id, index)
                    });
                    setTimeout(() => {
                        console.log(me.arrayEstatus);
                        
                    }, 50);           

                }).catch(function (error) {
                    console.log(error);
                })
            },
            actividades(idProyecto){
                app._data.idProyecto = idProyecto;
                app._data.menu = 22;
            },
            selectEncargado(){
                let me = this;
                var url = '/encargado/selectEncargado';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayEncargado = respuesta.encargados;
                }).catch(function (error) {
                        console.log(error);
                })
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarProyecto(page,buscar,criterio);
            },
            registrarProyecto(){
                if (this.validarProyecto()){
                    console.log("proyecto no correcto");
                    return;
                }

                let me = this;
                axios.post('/proyecto/registrar',{
                    'idEncargado': this.idEncargado,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'monto': this.monto
                }).then(function(response){
                    console.log(response);

                    me.cerrarModal();
                    me.listarProyecto(1,'','nombre');
                }).catch(function(error){
                    console.log(error);
                });

            },
            actualizarProyecto(){
                if(this.validarProyecto()){
                    return;
                }

                let me = this;
                axios.put('/proyecto/actualizar',{
                    'idEncargado':this.idEncargado,
                    'nombre': this.nombre,
                    'descripcion':this.descripcion,
                    'monto':this.monto,
                    'id':this.proyecto_id

                }).then(function(response){
                    me.cerrarModal();
                    me.listarProyecto(1,'','nombre');
                }).catch(function(error){
                    console.log(error);
                });

            },
            validarProyecto(){
                this.errorProyecto=0;
                this.errorMostrarMsjProyecto=[];

                if(!this.nombre) this.errorMostrarMsjProyecto.push("Debe digitar el nombre del proyecto");
                if(this.errorMostrarMsjProyecto.length) this.errorProyecto = 1;

                if(this.errorProyecto===0){
                    if(!this.monto || this.monto <= 0) this.errorMostrarMsjProyecto.push("Debe digitar el monto estimado");
                    if(this.errorMostrarMsjProyecto.length) this.errorProyecto = 1;
                }

                if(this.errorProyecto===0){
                    if(this.idEncargado===0) this.errorMostrarMsjProyecto.push("Seleccione al encargado");
                    if(this.errorMostrarMsjProyecto.length) this.errorProyecto = 1;
                }

                return this.errorProyecto;
            },
            finalizarProyecto(idProyecto){
                Swal.fire({
                title: 'Desea finalizar este proyecto?',
                text: "No se podra revertir esta accion",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, finalizalo!',
                cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                        axios.get("/proyecto/finalizar/"+idProyecto)
                        .then((respuesta) => {
                            this.listarProyecto(1,'','nombre');
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Se ha finalizado el proyecto exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        })
                        .catch(error => {
                            Swal.fire({
                            position: 'center',
                            type: 'error',
                            title: 'No se pudo Finalizar Proyecto',
                            showConfirmButton: true
                            })
                            console.log(error)
                        });
                    }
            })
            },
            getStatusProyecto(idProyecto, index){
                axios.get("/proyecto/estatus/"+idProyecto)
                        .then((respuesta) => {
                            if(respuesta.data[0].idTipoEstatus !== 3){
                                this.arrayEstatus[index] = true;                                
                                return true;
                            }else{
                                this.arrayEstatus[index] = false;
                                return false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            this.arrayEstatus[index] = false;
                            return false;
                        });
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.idEncargado=0;
                this.nombre = '';
                this.descripcion='';
                this.monto=0;
                this.errorProyecto=0;
                this.proyecto_id= 0;
            },
            abrirModal(modelo,accion,data = []){
                switch(modelo){
                    case "proyecto":
                    {
                        switch(accion)
                        {
                            case "registrar":
                            {
                                this.modal = 1;
                                this.tipoAccion = 1;
                                this.tituloModal = 'Registrar proyecto';
                                this.idEncargado=0;
                                this.nombre = '';
                                this.descripcion = '';
                                this.monto=0;
                                break;
                            }
                            case "actualizar":
                            {
                                this.modal = 1;
                                this.tipoAccion = 2;
                                this.tituloModal = 'Actualizar proyecto';
                                this.proyecto_id = data['id'];
                                this.idEncargado=data['idEncargado'];
                                this.nombre = data['nombre'],
                                this.descripcion =data['descripcion'];
                                this.monto=data['montoEstimado'];
                                break;
                            }
                        }
                    }
                }
                this.selectEncargado();
            }
        },
        mounted() {
            this.listarProyecto(1,this.buscar,this.criterio);
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

    .fechaPdf{
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
    .img{
        width: 30%;
        margin: auto;
        display: block;
        margin-bottom: 10px;
    }
</style>
