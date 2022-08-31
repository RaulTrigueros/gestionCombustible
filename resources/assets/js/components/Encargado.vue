<template>
    <main class="">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Ir a Portal</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Encargados de proyectos
                    <button type="button" @click="abrirModal('encargado','registrar')" class="btn btn-primary">
                        <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="nit">NIT</option>
                                    <option value="telefono">Teléfono</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarEncargado(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarEncargado(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                        <tr>
                            <th style="vertical-align:middle;">Nombre</th>
                            <th style="vertical-align:middle;">NIT</th>
                            <th style="vertical-align:middle;">Teléfono</th>
                            <th style="vertical-align:middle;">Dirección</th>
                            <th style="vertical-align:middle;">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="encargado in arrayEncargado" :key="encargado.id">
                            <td class="align-middle" v-text="encargado.nombre"></td>
                            <td class="align-middle" v-text="encargado.nit"></td>
                            <td class="align-middle" v-text="encargado.telefono"></td>
                            <td class="align-middle" v-text="encargado.direccion"></td>
                            <td class="align-middle" align="center">
                                <button type="button" @click="abrirModal('encargado','actualizar',encargado)" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
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
                                <label class="col-md-3 form-control-label" >Nombre (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Ingrese el nombre">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">NIT (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nit" maxlength="30" class="form-control" placeholder="Ingrese el número de nit">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" maxlength="12" class="form-control" placeholder="Ingrese el número de teléfono">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="direccion" class="form-control" placeholder="Ingrese la dirección">
                                </div>
                            </div>
                            <div v-show="errorEncargado" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjEncargado" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer" style="padding: 1em">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEncargado()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEncargado()">Actualizar</button>
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
                encargado_id: 0,
                nombre : '',
                nit : '',
                telefono : '',
                direccion : '',
                arrayEncargado : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEncargado : 0,
                errorMostrarMsjEncargado : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
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
            listarEncargado (page,buscar,criterio){
                let me=this;
                var url= '/encargado?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEncargado = respuesta.encargados.data;
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
                me.listarEncargado(page,buscar,criterio);
            },
            registrarEncargado(){
                if (this.validarEncargado()){
                    return;
                }

                let me = this;
                axios.post('/encargado/registrar',{
                    'nombre': this.nombre,
                    'nit' : this.nit,
                    'telefono' : this.telefono,
                    'direccion' : this.direccion

                }).then(function (response) {
                    console.log(response);
                    me.cerrarModal();
                    me.listarEncargado(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarEncargado(){
                if (this.validarEncargado()){
                    return;
                }

                let me = this;
                axios.put('/encargado/actualizar',{
                    'nombre': this.nombre,
                    'nit' : this.nit,
                    'telefono' : this.telefono,
                    'direccion' : this.direccion,
                    'id': this.encargado_id
                }).then(function (response) {
                    me.listarEncargado(1,'','nombre');
                    me.cerrarModal();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarEncargado(){
                this.errorEncargado=0;
                this.errorMostrarMsjEncargado =[];

                if(!this.nombre) this.errorMostrarMsjEncargado.push("Debe ingresar el nombre.");
                if(this.errorMostrarMsjEncargado.length) this.errorEncargado = 1;

                if(this.errorEncargado===0){
                    if(!this.nit) this.errorMostrarMsjEncargado.push("Debe ingresar el número de nit");
                    if(this.errorMostrarMsjEncargado.length) this.errorEncargado = 1;
                }
                return this.errorEncargado;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.nit='';
                this.telefono='';
                this.direccion='';
                this.errorEncargado=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "encargado":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Encargado';
                                this.nombre= '';
                                this.nit='';
                                this.telefono='';
                                this.direccion='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tituloModal='Actualizar Encargado';
                                this.tipoAccion=2;
                                this.encargado_id=data['id'];
                                this.nombre = data['nombre'];
                                this.nit = data['nit'];
                                this.telefono = data['telefono'];
                                this.direccion = data['direccion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEncargado(1,this.buscar,this.criterio);
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
