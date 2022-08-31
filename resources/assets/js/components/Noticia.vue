<template>
     <main class="">
            <!-- Breadcrumb -->
     <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/" target="_blank">Ir a Portal</a></li>
            </ol>
    <div>
        <div class="container-fluid">
          <div class="animated fadeIn">           
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                        <i class="fa fa-align-justify"></i> Noticias
                        <button type="button" @click="abrirModal('noticia','registrar')" class="btn btn-primary">
                            &nbsp;<i class="fa fa-plus-square"></i>&nbsp;Nuevo
                        </button>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                  <option value="titulo">Titulo</option>
                                  <option value="autor">Autor</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarNoticia(1,buscar,criterio)"  class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarNoticia(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                     </div>
                    <br>
                      <!-- INICIO DEL LISTADO-->
                    <table class="table table-bordered table-striped table-sm table-responsive">
                      <thead>
                        <tr>
                          <th style="vertical-align:middle;">Opciones</th>
                          <th style="vertical-align:middle;">titulo</th>
                          <th style="vertical-align:middle;">autor</th>
                          <th style="vertical-align:middle;">Fecha Noticia</th>
                          <th style="vertical-align:middle;">Estado</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="noticia in arrayNoticia" :key="noticia.id">
                           <td class="align-middle">
                            <button type="button" @click="abrirModal('noticia','actualizar',noticia)" class="btn btn-warning btn-sm">
                             <i class="fa fa-edit"></i>
                            </button> &nbsp;
                            
                            <template v-if="noticia.estado">
                                <button  type="button" class="btn btn-danger btn-sm" @click="desactivarNoticia(noticia.id)">
                                   <i class="fa fa-trash"></i>
                               </button>
                            </template>
                            <template v-else>
                                <button  type="button" class="btn btn-info btn-sm" @click="activarNoticia(noticia.id)">
                                   <i class="fa fa-check"></i>
                               </button>
                            </template>
                          </td>
                          <td class="align-middle" v-text="noticia.titulo"></td>
                          <td class="align-middle" v-text="noticia.autor"></td>
                          <td class="align-middle">{{noticia.fecha_noticia | fecha}}</td>
                          <td class="align-middle">
                           <div v-if="noticia.estado">
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
                       <!-- FIN DEL LISTADO-->
                  </div>
                </div>
              </div>
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
                                <label class="col-md-3 form-control-label" for="text-input">Titulo (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="titulo" class="form-control" placeholder="Ingrese el Titulo">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Autor (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="autor" class="form-control" placeholder="Ingrese el autor">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Fecha de noticia (*)</label>
                                <div class="col-md-9">
                                    <input type="date" v-model="fecha_noticia" class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Contenido (*)</label>
                                <div class="col-md-9">
                                    <textarea v-model="cuerpo" class="form-control" placeholder="Ingrese el cuerpo de la noticia" cols="80" rows="10">
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
                            <div v-show="errorNoticia" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjNoticia" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarNoticia()" >Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarNoticia()">Actualizar</button>
                    </div>
                </div> <!-- /.modal-content -->
            </div> <!-- /.modal-dialog -->
        </div> <!--Fin del modal-->
    </div>
     </main>
</template>


<script>
import axios from 'axios'

    export default{
         data(){
            return{
                noticia_id: 0,
                titulo: '',
                autor: '',
                fecha_noticia: '',
                cuerpo: '',
                imagen: '',
                arrayNoticia: [],
                modal :0,
                tituloModal : '',
                tipoAccion : 0,
                errorNoticia: 0,
                errorMostrarMsjNoticia: [],
                bandImagenValida: false,
                pagination:{
                    'total' : 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to' :0,
                },
                offset : 3,
                criterio: 'titulo',
                buscar: '',
                arrayTipoprograma: []
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
            listarNoticia(page, buscar, criterio){
                let me = this;
                var url = '/noticia?page='+ page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayNoticia = respuesta.noticias.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            onFileChange(e) {
                this.bandImagenValida = true;
                this.errorNoticia=0;
                this.errorMostrarMsjNoticia=[];
                
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return ;
                
                if(!(files[0]['type']==='image/png' || files[0]['type']==='image/jpg' || files[0]['type']==='image/jpeg')){
                    this.errorMostrarMsjNoticia.push("Solo se permiten imagenes png, jpg y jpeg");
                    if(this.errorMostrarMsjNoticia.length) this.errorNoticia = 1;
                    e.preventDefault();
                    this.bandImagenValida = false;
                }

                if(files[0]['size'] > 60 * 1024){
                   this.errorMostrarMsjNoticia.push("El tamaño del archivo tiene que ser menor o igual a 60 KB");
                   if(this.errorMostrarMsjNoticia.length) this.errorNoticia = 1;
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
                this.errorNoticia=0;
                this.errorMostrarMsjNoticia=[];
                this.imagen = '';
                this.bandImagenValida = true;
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarNoticia(page,buscar,criterio);
            },
            registrarNoticia(){

                if (this.validarNoticia()){
                   return;
                }
                     
                let me = this;
                axios.post('/noticia/registrar',{
                    'titulo': this.titulo,
                    'autor':this.autor,
                    'fecha_noticia':this.fecha_noticia,
                    'cuerpo':this.cuerpo,
                    'imagen' : this.imagen
                    
                }).then(function(response){
                    me.cerrarModal();
                    me.listarNoticia(1,'','titulo');
                }).catch(function(error){
                    console.log(error);
                });
                
            },
            actualizarNoticia(){
                
                if(this.validarNoticia()){
                    return;
                }
            
                let me = this;
                axios.put('/noticia/actualizar',{
                    'titulo': this.titulo,
                    'autor':this.autor,
                    'fecha_noticia':this.fecha_noticia,
                    'cuerpo':this.cuerpo,
                    'imagen' : this.imagen,
                    'id':this.noticia_id
                }).then(function(response){
                    me.cerrarModal();
                    me.listarNoticia(1,'','titulo');
                }).catch(function(error){
                    console.log(error);
                });

            },
            desactivarNoticia(id){
                swal.fire({
                title: 'Esta seguro de desactivar esta noticia?',
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

                    axios.put('/noticia/desactivar',{
                        'id': id
                    }).then(function(response){
                        me.listarNoticia(1,'','titulo');
                         swal.fire(
                            'Desactivado!',
                            'Noticia desactivada con éxito!',
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
            activarNoticia(id){
                swal.fire({
                title: 'Esta seguro de activar esta noticia?',
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

                    axios.put('/noticia/activar',{
                        'id': id
                    }).then(function(response){
                        me.listarNoticia(1,'','titulo');
                         swal.fire(
                            'Activado!',
                            'Noticia fue activada con éxito!',
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
            validarNoticia(){
                this.errorNoticia=0;
                this.errorMostrarMsjNoticia=[];
                
                if(this.errorNoticia===0){
                    if(!this.titulo) this.errorMostrarMsjNoticia.push("El titulo es necesario para la noticia");
                    if(this.errorMostrarMsjNoticia.length) this.errorNoticia = 1;
                }
                
                if(this.errorNoticia===0){
                    if(!this.autor) this.errorMostrarMsjNoticia.push("Es necesari especificar el autor ");
                    if(this.errorMostrarMsjNoticia.length) this.errorNoticia = 1;
                }

                if(this.errorNoticia===0){
                    if(!this.fecha_noticia) this.errorMostrarMsjNoticia.push("Fecha de noticia es necesaria");
                    if(this.errorMostrarMsjNoticia.length) this.errorNoticia = 1;
                }
                
                if(this.errorNoticia===0){
                    if(!this.cuerpo) this.errorMostrarMsjNoticia.push("Es necesario redactar la noticia");
                    if(this.errorMostrarMsjNoticia.length) this.errorNoticia = 1;
                }

                if(this.errorNoticia === 0 ){    
                    if(!(!this.imagen) && (this.bandImagenValida === false)){
                        this.errorMostrarMsjNoticia.push("El archivo de imagen es inválido");
                        if(this.errorMostrarMsjNoticia.length) this.errorNoticia = 1;
                    }
                }
                  
                return this.errorNoticia;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.titulo = '';
                this.autor='';
                this.fecha_noticia='';
                this.cuerpo='';
                this.estado = 0;
                this.imagen = '';
                this.errorPrograma=0;
                this.bandImagenValida = false;
            },
            abrirModal(modelo,accion,data = []){
                switch(modelo){
                    case "noticia":
                    {
                        switch(accion){
                            case "registrar":
                            {
                                this.modal = 1;
                                this.tipoAccion = 1;
                                this.tituloModal = 'Registrar Noticia';
                                this.titulo = '';
                                this.autor = '';
                                this.fecha_noticia='';
                                this.cuerpo = '';
                                this.imagen = '';
                                this.bandImagenValida = true;
                                break;

                            }
                            case "actualizar":
                            {
                                
                                this.modal = 1;
                                this.tipoAccion = 2;
                                this.tituloModal = 'Actualizar noticia';

                                this.noticia_id=data['id'];
                                this.titulo = data['titulo'],
                                this.autor =data['autor'];
                                this.fecha_noticia=data['fecha_noticia'];
                                this.cuerpo =data['cuerpo'];
                                this.imagen = data['imagen'];
                                this.bandImagenValida =true;
                                break;
                                    
                            }
                        }
                    }
                }
                
            }
        },
        mounted() {
            this.listarNoticia(1,this.buscar,this.criterio);
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
    .img{
        width: 30%;
        margin: auto;
        display: block;
        margin-bottom: 10px;
    }
</style>