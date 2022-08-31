<template>
            <main class="">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <a href="/" target="_blank"><li class="breadcrumb-item">Ir a Portal</li></a>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Estructura Organizacional
                        <button type="button" @click="abrirModal('organigrama','registrar')" class="btn btn-primary">
                            <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="descripcion">Descripcion</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarOrganigrama(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarOrganigrama(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle;" >Opciones</th>  
                                    <th style="vertical-align:middle;" >Descripcion</th>
                                    <!--  <th>imagen</th> -->
                                    <th style="vertical-align:middle;" >Estado</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="organigrama in arrayOrganigrama" :key="organigrama.id">
                                      <td class="align-middle" align="center">
                                        <button type="button" @click="abrirModal('organigrama','actualizar',organigrama)" class="btn btn-warning btn-sm">
                                          <i class="fa fa-edit"></i>
                                        </button>
                                        <template v-if="organigrama.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarOrganigrama(organigrama.id)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarOrganigrama(organigrama.id)">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td class="align-middle" v-text="organigrama.descripcion"></td>
                                    <td class="align-middle">
                                    <div v-if="organigrama.estado">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-9">
                                       <textarea type="text" v-model="descripcion" class="form-control" placeholder="Ingrese la descripción del organigrama" cols="80" rows="10">
                                       </textarea>                              
                                    </div>
                                </div>                               
                            <div class="form-group row">
                                <div v-if="!imagen">
                                    <div class="col-md-9">
                                        <input id="imagen" type="file" placeholder="Seleccione una imagen" @change="onFileChange">
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="col-md-9">
                                        <img :src="imagen" width="150" height="200"/>
                                        <button @click="quitarImagen">Quitar imagen</button>
                                    </div>
                                </div>
                            </div>
    
                                <div v-show="errorOrganigrama" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjOrganigrama" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer" style="padding: 1em">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarOrganigrama()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarOrganigrama()">Actualizar</button>
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
                organigrama_id: 0,
                descripcion : '',
                imagen : '',   
                arrayOrganigrama : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorOrganigrama : 0,
                errorMostrarMsjOrganigrama : [],
                bandImagenValida: false,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'titulo',
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
            listarOrganigrama (page,buscar,criterio){
                let me=this;
                var url= '/organigrama?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayOrganigrama = respuesta.organigramas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
             onFileChange(e) {
                this.bandImagenValida = true;
                this.errorOrganigrama=0;
                this.errorMostrarMsjOrganigrama=[];
                
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return ;
                
                if(!(files[0]['type']==='image/png' || files[0]['type']==='image/jpg' || files[0]['type']==='image/jpeg')){
                    this.errorMostrarMsjOrganigrama.push("Solo se permiten imagenes png, jpg y jpeg");
                    if(this.errorMostrarMsjOrganigrama.length) this.errorOrganigrama = 1;
                    e.preventDefault();
                    this.bandImagenValida = false;
                }

                if(files[0]['size'] > 1936 * 1291){
                   this.errorMostrarMsjOrganigrama.push("El tamaño del archivo tiene que ser menor o igual a 200 KB");
                   if(this.errorMostrarMsjOrganigrama.length) this.errorOrganigrama = 1;
                   e.preventDefault();
                   this.bandImagenValida = false;
                }
                
                this.crearImagen(files[0]);
            },
            crearImagen(file) {
                var imagen = new Image();
                var reader = new FileReader();
                var vm = this;

                reader.onload = (e) => {
                vm.imagen = e.target.result;
                };
                reader.readAsDataURL(file); 
            },
            quitarImagen: function (e) {
                this.errorOrganigrama=0;
                this.errorMostrarMsjOrganigrama=[];
                this.imagen = '';
                this.bandImagenValida = true;
            },
         
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarOrganigrama(page,buscar,criterio);
            },
            registrarOrganigrama(){
                if (this.validarOrganigrama()){
                    return;
                }
                
                let me = this;

                axios.post('/organigrama/registrar',{
                    'descripcion' : this.descripcion,
                    'imagen' : this.imagen,
                    
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarOrganigrama(1,'','descripcion');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarOrganigrama(){
               if (this.validarOrganigrama()){
                    return;
                }
                
                let me = this;

                axios.put('/organigrama/actualizar',{
                    'descripcion' : this.descripcion,
                    'imagen' : this.imagen,
                    'id': this.organigrama_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarOrganigrama(1,'','descripcion');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },            
            validarOrganigrama(){
                this.errorOrganigrama=0;
                this.errorMostrarMsjOrganigrama =[];

                
                if (this.errorMostrarMsjOrganigrama.length) this.errorOrganigrama = 1;

                return this.errorOrganigrama;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.descripcion='';
                this.imagen=''; 
                this.errorOrganigrama=0;

            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "organigrama":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Organigrama';
                                this.descripcion='';
                                this.imagen='';
                                this.bandImagenValida = true;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Organigrama';
                                this.tipoAccion=2;
                                this.organigrama_id=data['id'];
                                this.descripcion = data['descripcion'];
                                this.imagen = data['imagen'];
                                this.bandImagenValida = true;
                                break;
                            }
                        }
                    }
                }
            },
            desactivarOrganigrama(id){
               swal.fire({
                title: 'Esta seguro de desactivar este Organigrama?',
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

                    axios.put('/organigrama/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarOrganigrama(1,'','descripcion');
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
            activarOrganigrama(id){
               swal.fire({
                title: 'Esta seguro de activar este Organigrama?',
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

                    axios.put('/organigrama/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarOrganigrama(1,'','descripcion');
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
            this.listarOrganigrama(1,this.buscar,this.criterio);
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


