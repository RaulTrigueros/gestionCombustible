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
                    <i class="fa fa-align-justify"></i> Contactos
                    <button type="button" @click="abrirModal('contacto','registrar')" class="btn btn-primary">
                        <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">Nombre</option>
                                    <option value="email">E-mail</option>
                                    <option value="telefono">Teléfono</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarContacto(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarContacto(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive">
                        <thead>
                        <tr>
                            <th style="vertical-align:middle;">Nombre</th>
                            <th style="vertical-align:middle;">E-mail</th>
                            <th style="vertical-align:middle;">Teléfono</th>
                            <th style="vertical-align:middle;">Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="contacto in arrayContacto" :key="contacto.id">
                            <td class="align-middle" v-text="contacto.nombre"></td>
                            <td class="align-middle" v-text="contacto.email"></td>
                            <td class="align-middle" v-text="contacto.telefono"></td>
                            <td class="align-middle" align="center">
                                <button type="button" @click="abrirModal('contacto', 'actualizar', contacto)" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i>
                                </button>&nbsp;
                                <button  type="button" class="btn btn-danger btn-sm" @click="eliminarContacto(contacto.id)">
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
                                <label class="col-md-3 form-control-label" >Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Ingrese el nombre del contacto">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">E-mail (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="email" maxlength="120" class="form-control" placeholder="Ingrese el e-mail">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telefono" maxlength="12" class="form-control" placeholder="Ingrese el número de teléfono">
                                </div>
                            </div>
                            <div v-show="errorContacto" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjContacto" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer" style="padding: 1em">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarContacto">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarContacto()">Actualizar</button>
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
                contacto_id: 0,
                nombre : '',
                email : '',
                telefono : '',
                arrayContacto : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorContacto : 0,
                errorMostrarMsjContacto : [],
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
            listarContacto(page, buscar, criterio){
                let me = this;
                var url= '/getcontactos?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayContacto = respuesta.contactos.data;
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
                me.listarContacto(page, buscar, criterio);
            },
            registrarContacto(){
                if (this.validarContacto()){
                    return;
                }

                let me = this;
                axios.post('/contacto/registrar',{
                    'nombre': this.nombre,
                    'email' : this.email,
                    'telefono' : this.telefono

                }).then(function (response) {
                    console.log(response);
                    me.cerrarModal();
                    me.listarContacto(1, '', 'nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarContacto(){
                if (this.validarContacto()){
                    return;
                }

                let me = this;
                axios.put('/contacto/actualizar',{
                    'nombre': this.nombre,
                    'email' : this.email,
                    'telefono' : this.telefono,
                    'id': this.contacto_id
                }).then(function (response) {
                    me.listarContacto(1, '', 'nombre');
                    me.cerrarModal();
                }).catch(function (error) {
                    console.log(error);
                });
            },
            eliminarContacto(id){
                swal.fire({
                title: 'Esta seguro de eliminar este contacto?',
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

                    axios.put('/contacto/eliminar',{
                        'id': id
                    }).then(function(response){
                        me.listarContacto(1,'','nombre');
                         swal.fire(
                            'Eliminado!',
                            'Contacto eliminado con éxito!',
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
            validarContacto(){
                this.errorContacto=0;
                this.errorMostrarMsjContacto =[];

                if(!this.email) this.errorMostrarMsjContacto.push("Debe ingresar el e-mail");
                if(this.errorMostrarMsjContacto.length) this.errorContacto = 1;

                return this.errorContacto;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.email='';
                this.telefono='';
                this.errorContacto=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "contacto":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Contacto';
                                this.nombre= '';
                                this.email='';
                                this.telefono='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tituloModal='Actualizar Contacto';
                                this.tipoAccion=2;
                                this.contacto_id=data['id'];
                                this.nombre = data['nombre'];
                                this.email = data['email'];
                                this.telefono = data['telefono'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarContacto(1, this.buscar, this.criterio);
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
