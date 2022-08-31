<template>
    <main class="">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Ir a Portal</a></li>
            </ol>
    <div>
        <div class="container-fluid">
          <div class="animated fadeIn">           
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                        <i class="fa fa-align-justify"></i> Solicitudes de partidas
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                  <option value="nombre">Nombre</option>
                                  <option value="dui">DUI</option>
                                  <option value="telefono">Teléfono</option>
                                  <option value="anombrede">A nombre de</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarSolicitudPartida(1,buscar,criterio)"  class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listarSolicitudPartida(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                     </div>
                    <br>
                      <!-- INICIO DEL LISTADO-->
                    <table class="table table-bordered table-striped table-sm table-responsive">
                      <thead>
                        <tr>
                          <th style="vertical-align:middle;">Opciones</th>
                          <th style="vertical-align:middle;">Nombre</th>
                          <th style="vertical-align:middle;">DUI</th>
                          <th style="vertical-align:middle;">Email</th>
                          <th style="vertical-align:middle;">Teléfono</th>
                          <th style="vertical-align:middle;">Partida solicitada</th>
                          <th style="vertical-align:middle;">A nombre de</th>
                          <th style="vertical-align:middle;">Fecha de partida</th>
                          <th style="vertical-align:middle;">Estado</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="solicitudPartida in arraySolicitudPartida" :key="solicitudPartida.id">
                           <td class="align-middle">
                            <template v-if="solicitudPartida.estado">
                                <button  type="button" class="btn btn-info btn-sm" @click="desactivarSolicitudPartida(solicitudPartida.id)">
                                   <i class="fa fa-circle-o"></i>
                               </button>
                            </template>
                            <template v-else>
                                <button  type="button" class="btn btn-primary" @click="activarSolicitudPartida(solicitudPartida.id)">
                                   <i class="fa fa-check"></i>
                               </button>
                            </template>
                          </td>
                          <td class="align-middle" v-text="solicitudPartida.nombre"></td>
                          <td class="align-middle" v-text="solicitudPartida.dui"></td>
                          <td class="align-middle" v-text="solicitudPartida.email"></td>
                          <td class="align-middle" v-text="solicitudPartida.telefono"></td>
                          <td class="align-middle" v-text="solicitudPartida.nombre_tipodocumento"></td>
                          <td class="align-middle" v-text="solicitudPartida.anombrede"></td>
                          <td class="align-middle">{{solicitudPartida.fecha_realizado | fecha}}</td>
                          <td class="align-middle">
                           <div v-if="solicitudPartida.estado">
                               <span class="badge badge-success">Activa</span>
                            </div>
                            <div v-else>
                                <span class="badge badge-danger">Procesada</span>
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
    </div>
    </main>
</template>

<script>
import axios from 'axios'

    export default{
         data(){
            return{
                idtipodocumento: 0,
                solicitudPartida_id: 0,
                nombre: '',
                dui: '',
                email: '',
                telefono: '',
                anombrede: '',
                fecha_realizado: '',
                arraySolicitudPartida: [],
                modal :0,
                tituloModal : '',
                tipoAccion : 0,
                errorSolicitudPartida: 0,
                errorMostrarMsjSolicitudPartida: [],
                pagination:{
                    'total' : 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to' :0
                },
                offset : 3,
                criterio: 'nombre',
                buscar: ''
            };
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
            listarSolicitudPartida(page, buscar, criterio){
                let me = this;
                var url = '/solicitudpartida?page='+ page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                        me.arraySolicitudPartida = respuesta.solicitudpartidas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarSolicitudPartida(page,buscar,criterio);
            },
            desactivarSolicitudPartida(id){
                swal.fire({
                title: 'Esta seguro de dar por procesada la solicitud?',
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

                    axios.put('/solicitudpartida/desactivar',{
                        'id': id
                    }).then(function(response){
                        me.listarSolicitudPartida(1,'','nombre');
                         swal.fire(
                            'Procesada!',
                            'Solicitud procesada con éxito!',
                            'success'
                            );
                    }).catch(function(error){
                        console.log(error);
                    });
                   
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                }
                });
            },
            activarSolicitudPartida(id){
                swal.fire({
                title: 'Esta seguro de volver a activar la solicitud?',
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

                    axios.put('/solicitudpartida/activar',{
                        'id': id
                    }).then(function(response){
                        me.listarSolicitudPartida(1,'','nombre');
                         swal.fire(
                            'Activada!',
                            'Solicitud fue activada con éxito!',
                            'success'
                            );
                    }).catch(function(error){
                        console.log(error);
                    });
                   
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                }
                });
            }
        },
        mounted() {
            this.listarSolicitudPartida(1,this.buscar,this.criterio);
        }            
    }
</script>

     