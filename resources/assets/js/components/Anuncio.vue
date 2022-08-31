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
                        <i class="fa fa-align-justify"></i>Anuncios
                        <button type="button" @click="abrirModal('anuncio','registrar')" class="btn btn-primary">
                            <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="titulo">Titulo</option>
                                      <option value="autor">Autor</option>
                                      <option value="fecha_anuncio">Fecha de Publicación</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarAnuncio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarAnuncio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle;" >Opciones</th>  
                                    <th style="vertical-align:middle;" >Titulo</th>
                                    <th style="vertical-align:middle;" >Autor</th>
                                    <th style="vertical-align:middle;" >Descripcion</th>
                                    <th style="vertical-align:middle;" >Fecha de Publicación</th>
                                  <!--  <th>imagen</th> -->
                                    <th style="vertical-align:middle;" >Estado</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="anuncio in arrayAnuncio" :key="anuncio.id">
                                      <td class="align-middle" align="center">
                                        <button type="button" @click="abrirModal('anuncio','actualizar',anuncio)" class="btn btn-warning btn-sm">
                                          <i class="fa fa-edit"></i>
                                        </button>
                                        <template v-if="anuncio.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarAnuncio(anuncio.id)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarAnuncio(anuncio.id)">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td class="align-middle" v-text="anuncio.titulo"></td>
                                    <td class="align-middle" v-text="anuncio.autor"></td>
                                    <td class="align-middle" v-text="anuncio.descripcion"></td>
                                    <td class="align-middle">{{anuncio.fecha_anuncio | fecha}}</td>
                                    <td class="align-middle">
                                    <div v-if="anuncio.estado">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Título (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="titulo" class="form-control" placeholder="Titulo del anuncio">                                        
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Autor</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="autor" class="form-control" placeholder="Autor del anuncio">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-9">
                                       <textarea type="text" v-model="descripcion" class="form-control" placeholder="Ingrese el contenido del anuncio" cols="80" rows="10">
                                       </textarea>                              
                                    </div>
                                </div>
                               <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input"> Fecha de Publicación (*)</label>
                                <div class="col-md-9">
                                    <input type="date" v-model="fecha_anuncio" class="form-control" placeholder="">
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
    
                                <div v-show="errorAnuncio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjAnuncio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer" style="padding: 1em">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarAnuncio()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarAnuncio()">Actualizar</button>
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
                anuncio_id: 0,
                titulo : '',
                autor : '',
                descripcion : '',
                fecha_anuncio : '',
                imagen : '',   
                arrayAnuncio : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorAnuncio : 0,
                errorMostrarMsjAnuncio : [],
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
            listarAnuncio (page,buscar,criterio){
                let me=this;
                var url= '/anuncio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayAnuncio = respuesta.anuncios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
             onFileChange(e) {
                this.bandImagenValida = true;
                this.errorAnuncio=0;
                this.errorMostrarMsjAnuncio=[];
                
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return ;
                
                if(!(files[0]['type']==='image/png' || files[0]['type']==='image/jpg' || files[0]['type']==='image/jpeg')){
                    this.errorMostrarMsjAnuncio.push("Solo se permiten imagenes png, jpg y jpeg");
                    if(this.errorMostrarMsjAnuncio.length) this.errorAnuncio = 1;
                    e.preventDefault();
                    this.bandImagenValida = false;
                }

                if(files[0]['size'] > 100 * 1024){
                   this.errorMostrarMsjAnuncio.push("El tamaño del archivo tiene que ser menor o igual a 100 KB");
                   if(this.errorMostrarMsjAnuncio.length) this.errorAnuncio = 1;
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
                this.errorAnuncio=0;
                this.errorMostrarMsjAnuncio=[];
                this.imagen = '';
                this.bandImagenValida = true;
            },
         
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarAnuncio(page,buscar,criterio);
            },
            registrarAnuncio(){
                if (this.validarAnuncio()){
                    return;
                }
                
                let me = this;

                axios.post('/anuncio/registrar',{
                    'titulo': this.titulo,
                    'autor': this.autor,
                    'descripcion' : this.descripcion,
                    'fecha_anuncio' : this.fecha_anuncio,
                    'imagen' : this.imagen,
                    
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarAnuncio(1,'','titulo');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarAnuncio(){
               if (this.validarAnuncio()){
                    return;
                }
                
                let me = this;

                axios.put('/anuncio/actualizar',{
                    'titulo': this.titulo,
                    'autor': this.autor,
                    'descripcion' : this.descripcion,
                    'fecha_anuncio' : this.fecha_anuncio,
                    'imagen' : this.imagen,
                    'id': this.anuncio_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarAnuncio(1,'','titulo');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },            
            validarAnuncio(){
                this.errorAnuncio=0;
                this.errorMostrarMsjAnuncio =[];

                if (!this.titulo) this.errorMostrarMsjAnuncio.push("El título del anuncio no puede estar vacío.");
                if (!this.fecha_anuncio) this.errorMostrarMsjAnuncio.push("Debe seleccionar una fecha para el anuncio.");
                
                if (this.errorMostrarMsjAnuncio.length) this.errorAnuncio = 1;

                return this.errorAnuncio;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.titulo='';
                this.autor='';
                this.descripcion='';
                this.fecha_anuncio='';
                this.imagen=''; 
                this.errorAnuncio=0;

            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "anuncio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Anuncio';
                                this.titulo= '';
                                this.autor='';
                                this.descripcion='';
                                this.fecha_anuncio='';
                                this.imagen='';
                                this.bandImagenValida = true;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Anuncio';
                                this.tipoAccion=2;
                                this.anuncio_id=data['id'];
                                this.titulo = data['titulo'];
                                this.autor = data['autor'];
                                this.descripcion = data['descripcion'];
                                this.fecha_anuncio = data['fecha_anuncio'];
                                this.imagen = data['imagen'];
                                this.bandImagenValida = true;
                                break;
                            }
                        }
                    }
                }
            },
            desactivarAnuncio(id){
               swal.fire({
                title: 'Esta seguro de desactivar este Anuncio?',
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

                    axios.put('/anuncio/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarAnuncio(1,'','titulo');
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
            activarAnuncio(id){
               swal.fire({
                title: 'Esta seguro de activar este anuncio?',
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

                    axios.put('/anuncio/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarAnuncio(1,'','titulo');
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
            this.listarAnuncio(1,this.buscar,this.criterio);
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
