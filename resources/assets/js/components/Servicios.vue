<template>
            <main class="">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" target="_blank">Ir a Portal</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Servicios
                        <button type="button" @click="abrirModal('servicio','registrar')" class="btn btn-primary">
                            <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">nombre</option>
                                      <option value="descripcion">descripcion</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarServicio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarServicio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th>Opciones</th>  
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                                              </tr>
                            </thead>
                            <tbody>
                                <tr v-for="servicio in arrayServicio" :key="servicio.id">
                                      <td class="align-middle" align="center">
                                        <button type="button" @click="abrirModal('servicio','actualizar',servicio)" class="btn btn-warning btn-sm">
                                          <i class="fa fa-edit"></i>
                                        </button>
                                          <template v-if="servicio.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarServicio(servicio.id)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarServicio(servicio.id)">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </template>
                                        
                                    </td>
                                    <td class="align-middle" v-text="servicio.nombre"></td>
                                    
                                    <td class="align-middle" v-text="servicio.descripcion"></td>
                                     <td>
                                    <div v-if="servicio.estado">
                                        <span class="badge badge-success">Activo</span>
                                     </div>
                                     <div v-else>
                                         <span class="badge badge-danger">Inactivo</span>
                                     </div>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del servicio">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-9">
                                       <textarea type="text" v-model="descripcion" class="form-control" placeholder="Ingrese el contenido del servicio" cols="10" rows="10">
                                       </textarea>                              
                                    </div>
                                </div>
                               
                            
                                <div v-show="errorServicio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjServicio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer" style="padding: 1em">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarServicio()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarServicio()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                servicio_id: 0,
                nombre : '',
                descripcion : '',
                arrayServicio : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorServicio : 0,
                errorMostrarMsjServicio : [],
                
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
              listarServicio (page,buscar,criterio){
                let me=this;
                var url= '/servicio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayServicio = respuesta.servicios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarServicio(page,buscar,criterio);
            },

            registrarServicio(){
                if (this.validarServicio()){
                    return;
                }
                
                let me = this;

                axios.post('/servicio/registrar',{
                    'nombre': this.nombre,
                    'descripcion' : this.descripcion,       
                }).then(function (response) {

                    console.log(response);
                    me.cerrarModal();
                    me.listarServicio(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
             actualizarServicio(){
               if (this.validarServicio()){
                    return;
                }
                
                let me = this;

                axios.put('/servicio/actualizar',{
                    'nombre': this.nombre,
                    'descripcion' : this.descripcion,
                    'id': this.servicio_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarServicio(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },

             cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion=''; 
                this.errorServicio=0;

            },

             abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "servicio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Servicio';
                                this.nombre= '';
                                this.descripcion='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Servicio';
                                this.tipoAccion=2;
                                this.servicio_id=data['id'];
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion'];
                                
                                break;
                            }
                        }
                    }
                }
            },

             validarServicio(){
                this.errorServicio=0;
                this.errorMostrarMsjServicio =[];

                if (!this.nombre) this.errorMostrarMsjServicio.push("debe escribir el nombre");
                if (!this.descripcion) this.errorMostrarMsjServicio.push("Debe escribir una descripcion");
                
                if (this.errorMostrarMsjServicio.length) this.errorServicio = 1;

                return this.errorServicio;
            },

             desactivarServicio(id){
               swal.fire({
                title: 'Esta seguro de desactivar este Servicio?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/servicio/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarServicio(1,'','nombre');
                        swal.fire(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarServicio(id){
               swal.fire({
                title: 'Esta seguro de activar este servicio?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/servicio/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarServicio(1,'','nombre');
                        swal.fire(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            }

            
          
          



        },
                 
        mounted() {
            this.listarServicio(1,this.buscar,this.criterio);
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
