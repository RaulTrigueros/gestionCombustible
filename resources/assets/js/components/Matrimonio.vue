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
                        <i class="fa fa-align-justify"></i>Partidas de Matrimonio
                        <button type="button" @click="abrirModal('matrimonio','registrar')" class="btn btn-primary">
                            <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="anio">año</option>
                                      <option value="nombreEsposo">Nombre esposo</option>
                                      <option value="apellido1Esposo">Apellido esposo</option>
                                      <option value="nombreEsposa">Nombre esposa</option>
                                      <option value="apellido1Esposa">Apellido esposa</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarMatrimonio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarMatrimonio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle;">Opciones</th>  
                                    <th style="vertical-align:middle;">Pagina</th>
                                    <th style="vertical-align:middle;">Año</th>
                                    <th style="vertical-align:middle;">numero de partida</th>
                                    <th style="vertical-align:middle;">Fecha</th> 
                                    <th style="vertical-align:middle;">Nombre Esposo</th>
                                    <th style="vertical-align:middle;">Nombre Esposa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="matrimonio in arrayMatrimonio" :key="matrimonio.id">
                                      <td class="align-middle" align="center">
                                        <button type="button" @click="abrirModal('matrimonio','actualizar',matrimonio)" class="btn btn-warning btn-sm">
                                          <i class="fa fa-edit"></i>
                                        </button>
                                          <button type="button" @click="pdfmatrimonio(matrimonio.id)" class="btn btn-info btn-sm">
                             <i class="fa fa-file-pdf-o"></i>
                            </button> &nbsp;
                                    </td>
                                    <td class="align-middle" v-text="matrimonio.pagina"></td>
                                    <td class="align-middle" v-text="matrimonio.anio"></td>                                    
                                    <td class="align-middle" v-text="matrimonio.numeropartida"></td>
                                    <td class="align-middle">{{matrimonio.fechaPartida | fecha}}</td>
                                    <td class="align-middle" v-text="matrimonio.nombreEsposo +' '+ matrimonio.apellido1Esposo+' '+ matrimonio.apellido2Esposo"></td>
                                    <td class="align-middle" v-text="matrimonio.nombreEsposa +' '+ matrimonio.apellido1Esposa+' '+ matrimonio.apellido2Esposa"></td>


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
                        <form-wizard next-button-text="Siguiente" title="Registro familiar" subtitle="partidas de matrimonio" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                             <tab-content title="Datos Partida"> <!--Formulario 1-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">pagina (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="pagina" class="form-control" placeholder="Paginas" required>                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">año (*)</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="anio" class="form-control" placeholder="1999" min="1" maxlength="4">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">numero de partida (*)</label>
                                    <div class="col-md-9">
                                      <input type="number" v-model="numeropartida" class="form-control" placeholder="Numero de partida" min="1" maxlength="2" required>                                                                      
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de partida (*)</label>
                                    <div class="col-md-9">
                                     <input type="date" v-model="fechaPartida" class="form-control" placeholder="">
                                    </div>
                                </div>

                            </tab-content>
                            <tab-content title="Datos del Esposo"> <!--Formulario 2-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres Esposo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombreEsposo" class="form-control" placeholder="nombres">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Primer Apellido Esposo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellido1Esposo" class="form-control" placeholder="Primer apellido">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Segundo Apellido Esposo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellido2Esposo" class="form-control" placeholder="Segundo Apellido">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Edad (*)</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="edadEsposo" class="form-control" placeholder="año" min="1" maxlength="2">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ocupación Esposo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="ocupacionEsposo" class="form-control" placeholder="Ocupación">                                                                      
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado Civil (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="estadoCivilEsposo">
                                    <option value="soltero">Soltero</option>
                                    <option value="divorciado">Divorciado</option>
                                    <option value="viudo" >Viudo</option>
                                    </select>                               
                                </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DUI (*)</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="duiEsposo" class="form-control" placeholder="DUI" min="1" maxlength="9">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nacionalidad (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nacionalidadEsposo" class="form-control" placeholder="salvadoreño">                                                                      
                                    </div>
                                </div>

                            </tab-content>
                            <tab-content title="Padres del Esposo"> <!--Formulario 3-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres del padre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombrePadreEsposo" class="form-control" placeholder="nombres del padre">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos del padre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellidoPadreEsposo" class="form-control" placeholder="apellidos del padre">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres de la madre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombreMadreEsposo" class="form-control" placeholder="nombres de la madre">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos de la madre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellidoMadreEsposo" class="form-control" placeholder="apellidos de la madre">                                                                      
                                    </div>
                                </div>

                            </tab-content>

                            <tab-content title="Datos de la Esposa"> <!--Formulario 4-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres Esposa (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombreEsposa" class="form-control" placeholder="nombres">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Primer Apellido Esposa (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellido1Esposa" class="form-control" placeholder="Primer apellido">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Segundo Apellido Esposa (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellido2Esposa" class="form-control" placeholder="Segundo Apellido">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Edad (*)</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="edadEsposa" class="form-control" placeholder="año" min="1" maxlength="2" >                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ocupación Esposa (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="ocupacionEsposa" class="form-control" placeholder="Ocupación">                                                                      
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Estado Civil (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="estadoCivilEsposa">
                                    <option value="soltera">Soltera</option>
                                    <option value="divorciada">Divorciada</option>
                                    <option value="viuda" >Viuda</option>
                                    </select>                               
                                </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DUI (*)</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="duiEsposa" class="form-control" placeholder="DUI" min="1" maxlength="9">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nacionalidad (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nacionalidadEsposa" class="form-control" placeholder="salvadoreña">                                                                      
                                    </div>
                                </div>

                            </tab-content>
                            <tab-content title="Padres de la Esposa"> <!--Formulario 5-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres del padre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombrePadreEsposa" class="form-control" placeholder="nombres del padre">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos del padre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellidoPadreEsposa" class="form-control" placeholder="apellidos del padre">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres de la madre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombreMadreEsposa" class="form-control" placeholder="nombres de la madre">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos de la madre (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellidoMadreEsposa" class="form-control" placeholder="apellidos de la madre">                                                                      
                                    </div>
                                </div>

                            </tab-content>
                            <tab-content title="Condiciones del matrimonio"> <!--Formulario 6-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellido de Casada (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="apellidoCasada">
                                    <option value=1>SI</option>
                                    <option value=0>NO</option>
                                    </select>                               
                                </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Régimen Patrimonial (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="regimen">
                                    <option value="SEPARACION DE BIENES">SEPARACION DE BIENES</option>
                                    <option value="COMUNIDAD DIFERIDA">COMUNIDAD DIFERIDA</option>
                                    <option value="PARTICIPACION EN LAS GANANCIAS" >PARTICIPACION EN LAS GANANCIAS</option>
                                    </select>                               
                                </div>
                                </div>

                            </tab-content>
                            <tab-content title="Datos Primer Testigo"> <!--Formulario 7-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres Primer Testigo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombreTestigo1" class="form-control" placeholder="nombres">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos Primer Testigo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellidoTestigo1" class="form-control" placeholder="apellidos">                                                                      
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DUI (*)</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="duiTestigo1" class="form-control" placeholder="DUI" min="1" maxlength="9">                                                                      
                                    </div>
                                </div>

                            </tab-content>

                            <tab-content title="Datos Segundo Testigo"> <!--Formulario 8-->

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres Segundo Testigo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombreTestigo2" class="form-control" placeholder="nombres">                                                                      
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos Segundo Testigo (*)</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="apellidoTestigo2" class="form-control" placeholder="apellidos">                                                                      
                                    </div>
                                </div>

                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DUI (*)</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="duiTestigo2" class="form-control" placeholder="DUI" min="1" maxlength="9">                                                                      
                                    </div>
                                </div>

                            </tab-content>

                            <div v-show="errorMatrimonio" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjMatrimonio" :key="error" v-text="error">

                                    </div>
                                </div>
                             </div>


                        </form-wizard>
                        </div>
                        <div class="modal-footer" style="padding: 1em">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMatrimonio()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMatrimonio()">Actualizar</button>
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

                matrimonio_id: 0,
                pagina : '',
                anio:1900,
                numeropartida : '', 
                fechaPartida : '',
                nombreEsposo : '',
                apellido1Esposo: '',
                apellido2Esposo: '',
                edadEsposo: '',
                ocupacionEsposo: '',
                estadoCivilEsposo: 'Soltero',
                duiEsposo: '',
                nacionalidadEsposo: '',
                nombrePadreEsposo: '',
                apellidoPadreEsposo: '',
                nombreMadreEsposo: '',
                apellidoMadreEsposo: '',
                nombreEsposa : '',
                apellido1Esposa: '',
                apellido2Esposa: '',
                edadEsposa: '',
                ocupacionEsposa: '',
                estadoCivilEsposa: 'Soltera',
                duiEsposa: '',
                nacionalidadEsposa: '',
                nombrePadreEsposa: '',
                apellidoPadreEsposa: '',
                nombreMadreEsposa: '',
                apellidoMadreEsposa: '',
                apellidoCasada: 1,
                regimen: 'COMUNIDAD DIFERIDA',
                nombreTestigo1: '',
                apellidoTestigo1: '',
                duiTestigo1: '',
                nombreTestigo2: '',
                apellidoTestigo2: '',
                duiTestigo2: '',
                arrayMatrimonio : [],
                modal : 0,
                modal2:0,
                tituloModal : '',
                tituloModal2 : '',
                tipoAccion : 0,
                errorMatrimonio : 0,
                errorMostrarMsjMatrimonio : [],
                
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombreEsposo',
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
              listarMatrimonio (page,buscar,criterio){
                let me=this;
                var url= '/matrimonio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayMatrimonio = respuesta.matrimonio.data;
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
                me.listarMatrimonio(page,buscar,criterio);
            },

            registrarMatrimonio(){
                if (this.validarMatrimonio()){
                    return;
                }
                
                let me = this;

                axios.post('/matrimonio/registrar',{
                    'pagina': this.pagina,
                    'numeropartida':this.numeropartida,
                    'numeropartida':this.numeropartida,                    
                    'anio' : this.anio,      
                    'fechaPartida': this.fechaPartida,

                    'nombreEsposo': this.nombreEsposo,
                    'apellido1Esposo': this.apellido1Esposo,
                    'apellido2Esposo': this.apellido2Esposo,
                    'edadEsposo': this.edadEsposo,
                    'ocupacionEsposo': this.ocupacionEsposo,
                    'estadoCivilEsposo' : this.estadoCivilEsposo,
                    'duiEsposo': this.duiEsposo,
                    'nacionalidadEsposo': this.nacionalidadEsposo,
                    'nombrePadreEsposo': this.nombrePadreEsposo,
                    'apellidoPadreEsposo': this.apellidoPadreEsposo,
                    'nombreMadreEsposo': this.nombreMadreEsposo,
                    'apellidoMadreEsposo': this.apellidoMadreEsposo,

                    'nombreEsposa': this.nombreEsposa,
                    'apellido1Esposa': this.apellido1Esposa,
                    'apellido2Esposa': this.apellido2Esposa,
                    'edadEsposa': this.edadEsposa,
                    'ocupacionEsposa': this.ocupacionEsposa,
                    'estadoCivilEsposa' : this.estadoCivilEsposa,
                    'duiEsposa': this.duiEsposa,
                    'nacionalidadEsposa': this.nacionalidadEsposa,
                    'nombrePadreEsposa': this.nombrePadreEsposa,
                    'apellidoPadreEsposa': this.apellidoPadreEsposa,
                    'nombreMadreEsposa': this.nombreMadreEsposa,
                    'apellidoMadreEsposa': this.apellidoMadreEsposa,

                    'apellidoCasada': this.apellidoCasada,
                    'regimen': this.regimen,

                    'nombreTestigo1': this.nombreTestigo1,
                    'apellidoTestigo1': this.apellidoTestigo1,
                    'duiTestigo1': this.duiTestigo1,

                    'nombreTestigo2': this.nombreTestigo2,
                    'apellidoTestigo2': this.apellidoTestigo2,
                    'duiTestigo2': this.duiTestigo2,

                }).then(function (response) {

                    console.log(response);
                    me.cerrarModal();
                    me.listarMatrimonio(1,'','nombreEsposo');
                }).catch(function (error) {
                    console.log(error);
                });
            },
             actualizarMatrimonio(){
               if (this.validarMatrimonio()){
                    return;
                }
                
                let me = this;

                axios.put('/matrimonio/actualizar',{'pagina': this.pagina,
                    'numeropartida':this.numeropartida,
                    'numeropartida':this.numeropartida,                    
                    'anio' : this.anio,      
                    'fechaPartida': this.fechaPartida,

                    'nombreEsposo': this.nombreEsposo,
                    'apellido1Esposo': this.apellido1Esposo,
                    'apellido2Esposo': this.apellido2Esposo,
                    'edadEsposo': this.edadEsposo,
                    'ocupacionEsposo': this.ocupacionEsposo,
                    'estadoCivilEsposo' : this.estadoCivilEsposo,
                    'duiEsposo': this.duiEsposo,
                    'nacionalidadEsposo': this.nacionalidadEsposo,
                    'nombrePadreEsposo': this.nombrePadreEsposo,
                    'apellidoPadreEsposo': this.apellidoPadreEsposo,
                    'nombreMadreEsposo': this.nombreMadreEsposo,
                    'apellidoMadreEsposo': this.apellidoMadreEsposo,

                    'nombreEsposa': this.nombreEsposa,
                    'apellido1Esposa': this.apellido1Esposa,
                    'apellido2Esposa': this.apellido2Esposa,
                    'edadEsposa': this.edadEsposa,
                    'ocupacionEsposa': this.ocupacionEsposa,
                    'estadoCivilEsposa' : this.estadoCivilEsposa,
                    'duiEsposa': this.duiEsposa,
                    'nacionalidadEsposa': this.nacionalidadEsposa,
                    'nombrePadreEsposa': this.nombrePadreEsposa,
                    'apellidoPadreEsposa': this.apellidoPadreEsposa,
                    'nombreMadreEsposa': this.nombreMadreEsposa,
                    'apellidoMadreEsposa': this.apellidoMadreEsposa,

                    'apellidoCasada': this.apellidoCasada,
                    'regimen': this.regimen,

                    'nombreTestigo1': this.nombreTestigo1,
                    'apellidoTestigo1': this.apellidoTestigo1,
                    'duiTestigo1': this.duiTestigo1,

                    'nombreTestigo2': this.nombreTestigo2,
                    'apellidoTestigo2': this.apellidoTestigo2,
                    'duiTestigo2': this.duiTestigo2,
                    
                    'id': this.matrimonio_id,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarMatrimonio(1,'','nombreEsposo');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
             pdfmatrimonio(id){
                window.open('/matrimonio/pdf/'+ id);
            },

             cerrarModal(){
                this.modal=0;this.pagina='';
                               this.numeropartida;
                               this.anio='';                               
                               this.fechaPartida='';

                               this.nombreEsposo=''; 
                               this.apellido1Esposo='';
                               this.apellido2Esposo='';
                               this.edadEsposo='';
                               this.ocupacionEsposo='';
                               this.estadoCivilEsposo='';
                               this.duiEsposo='';
                               this.nacionalidadEsposo='';
                               this.nombrePadreEsposo='';
                               this.apellidoPadreEsposo='';
                               this.nombreMadreEsposo='';
                               this.apellidoMadreEsposo='';

                               this.nombreEsposa=''; 
                               this.apellido1Esposa='';
                               this.apellido2Esposa='';
                               this.edadEsposa='';
                               this.ocupacionEsposa='';
                               this.estadoCivilEsposa='';
                               this.duiEsposa='';
                               this.nacionalidadEsposa='';
                               this.nombrePadreEsposa='';
                               this.apellidoPadreEsposa='';
                               this.nombreMadreEsposa='';
                               this.apellidoMadreEsposa='';

                               this.apellidoCasada='';
                               this.regimen='';

                               this.nombreTestigo1='';
                               this.apellidoTestigo1='';
                               this.duiTestigo1='';

                               this.nombreTestigo2='';
                               this.apellidoTestigo2='';
                               this.duiTestigo2='';
                this.errorMatrimonio=0;

            },
               cerrarModal2(){
                this.modal2=0;
                this.tituloModal='';
              

            },

             abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "matrimonio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                               this.modal = 1;
                               this.tituloModal = 'Registrar Partida de Matrimonio';
                               this.pagina='';
                               this.numeropartida;
                               this.anio='';                               
                               this.fechaPartida='';

                               this.nombreEsposo=''; 
                               this.apellido1Esposo='';
                               this.apellido2Esposo='';
                               this.edadEsposo='';
                               this.ocupacionEsposo='';
                               this.estadoCivilEsposo='';
                               this.duiEsposo='';
                               this.nacionalidadEsposo='';
                               this.nombrePadreEsposo='';
                               this.apellidoPadreEsposo='';
                               this.nombreMadreEsposo='';
                               this.apellidoMadreEsposo='';

                               this.nombreEsposa=''; 
                               this.apellido1Esposa='';
                               this.apellido2Esposa='';
                               this.edadEsposa='';
                               this.ocupacionEsposa='';
                               this.estadoCivilEsposa='';
                               this.duiEsposa='';
                               this.nacionalidadEsposa='';
                               this.nombrePadreEsposa='';
                               this.apellidoPadreEsposa='';
                               this.nombreMadreEsposa='';
                               this.apellidoMadreEsposa='';

                               this.apellidoCasada='';
                               this.regimen='';

                               this.nombreTestigo1='';
                               this.apellidoTestigo1='';
                               this.duiTestigo1='';

                               this.nombreTestigo2='';
                               this.apellidoTestigo2='';
                               this.duiTestigo2='';

                               this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Partida de Matrimonio';
                                this.tipoAccion=2;

                                this.matrimonio_id=data['id'];
                                this.pagina = data['pagina'];
                                this.numeropartida = data['numeropartida'];
                                this.anio= data['anio'];                               
                                this.fechaPartida= data['fechaPartida'];

                                this.nombreEsposo= data['nombreEsposo']; 
                                this.apellido1Esposo= data['apellido1Esposo'];
                                this.apellido2Esposo= data['apellido2Esposo'];
                                this.edadEsposo= data['edadEsposo'];
                                this.ocupacionEsposo= data['ocupacionEsposo'];
                                this.estadoCivilEsposo= data['estadoCivilEsposo'];
                                this.duiEsposo= data['duiEsposo'];
                                this.nacionalidadEsposo= data['nacionalidadEsposo'];
                                this.nombrePadreEsposo= data['nombrePadreEsposo'];
                                this.apellidoPadreEsposo= data['apellidoPadreEsposo'];
                                this.nombreMadreEsposo= data['nombreMadreEsposo'];
                                this.apellidoMadreEsposo= data['apellidoMadreEsposo'];

                                 this.nombreEsposa= data['nombreEsposa']; 
                                this.apellido1Esposa= data['apellido1Esposa'];
                                this.apellido2Esposa= data['apellido2Esposa'];
                                this.edadEsposa= data['edadEsposa'];
                                this.ocupacionEsposa= data['ocupacionEsposa'];
                                this.estadoCivilEsposa= data['estadoCivilEsposa'];
                                this.duiEsposa= data['duiEsposa'];
                                this.nacionalidadEsposa= data['nacionalidadEsposa'];
                                this.nombrePadreEsposa= data['nombrePadreEsposa'];
                                this.apellidoPadreEsposa= data['apellidoPadreEsposa'];
                                this.nombreMadreEsposa= data['nombreMadreEsposa'];
                                this.apellidoMadreEsposa= data['apellidoMadreEsposa'];

                                this.apellidoCasada= data['apellidoCasada'];
                                this.regimen= data['regimen'];

                                this.nombreTestigo1= data['nombreTestigo1'];
                                this.apellidoTestigo1= data['apellidoTestigo1'];
                                this.duiTestigo1= data['duiTestigo1'];

                                this.nombreTestigo2= data['nombreTestigo2'];
                                this.apellidoTestigo2= data['apellidoTestigo2'];
                                this.duiTestigo2= data['duiTestigo2'];

                                
                                break;
                            }
                             //case "filtrar":
                                 //   {
                                   //     this.modal2 = 1;
                                //     this.tituloModal2 = 'Registrar nacimiento';
                               //this.paginas='';
                               //this.numeropartida='';
                               //this.anio;
                              // this.nombres='';
                               // this.tipoAccion = 1;
                                    //}
                        }
                    }
                }
            },

             validarMatrimonio(){
                this.errorMatrimonio=0;
                this.errorMostrarMsjMatrimonio =[];

                if (!this.pagina) this.errorMostrarMsjMatrimonio.push("debe escribir las paginas PASO 1");
                if (!this.numeropartida) this.errorMostrarMsjMatrimonio.push("Escribir el número de la partida PASO 1");
                if (!this.anio) this.errorMostrarMsjMatrimonio.push("Escribir el año de partida PASO 1");
                if (!this.fechaPartida) this.errorMostrarMsjMatrimonio.push("debe seleccionar la fecha PASO 1");

                if (!this.nombreEsposo) this.errorMostrarMsjMatrimonio.push("Escribir el nombre del Esposo PASO 2");
                if (!this.apellido2Esposo) this.errorMostrarMsjMatrimonio.push("Escribir primer apellido de Esposo PASO 2");
                if (!this.apellido2Esposo) this.errorMostrarMsjMatrimonio.push("Escribir segundo apellido de Esposo PASO 2");
                if (!this.edadEsposo) this.errorMostrarMsjMatrimonio.push("Escribir la edad del Esposo en PASO 2");
                if (!this.ocupacionEsposo) this.errorMostrarMsjMatrimonio.push("Escribir la ocupacion del Esposo en PASO 2");
                if (!this.estadoCivilEsposo) this.errorMostrarMsjMatrimonio.push("Seleccionar el estado civil del Esposo PASO 2");
                if (!this.duiEsposo) this.errorMostrarMsjMatrimonio.push("Escribir DUI del Esposo en PASO 2");
                if (!this.nacionalidadEsposo) this.errorMostrarMsjMatrimonio.push("Escribir la nacionalidad Esposo en PASO 2");

                
                if (!this.nombrePadreEsposo) this.errorMostrarMsjMatrimonio.push("Escribir nombres del padre PASO 3");
                if (!this.apellidoPadreEsposo) this.errorMostrarMsjMatrimonio.push("Escribir apellidos del padre PASO 3");
                if (!this.nombreMadreEsposo) this.errorMostrarMsjMatrimonio.push("Escribir nombres de la madre PASO 3");
                if (!this.apellidoMadreEsposo) this.errorMostrarMsjMatrimonio.push("Escribir apellidos de la madre PASO 3");

                if (!this.nombreEsposa) this.errorMostrarMsjMatrimonio.push("Escribir el nombre de la Esposa PASO 4");
                if (!this.apellido1Esposa) this.errorMostrarMsjMatrimonio.push("Escribir primer apellido de la Esposa PASO 4");
                if (!this.apellido2Esposa) this.errorMostrarMsjMatrimonio.push("Escribir segundo apellido de la Esposa PASO 4");
                if (!this.edadEsposa) this.errorMostrarMsjMatrimonio.push("Escribir la edad del la Esposa en PASO 4");
                if (!this.ocupacionEsposa) this.errorMostrarMsjMatrimonio.push("Escribir la ocupacion de la Esposa en PASO 4");
                if (!this.estadoCivilEsposa) this.errorMostrarMsjMatrimonio.push("Seleccionar el estado civil de la Esposa PASO 4");
                if (!this.duiEsposa) this.errorMostrarMsjMatrimonio.push("Escribir DUI de la Esposa en PASO 4");
                if (!this.nacionalidadEsposa) this.errorMostrarMsjMatrimonio.push("Escribir la nacionalidad de la Esposa en PASO 4");
                
                if (!this.nombrePadreEsposa) this.errorMostrarMsjMatrimonio.push("Escribir nombres de la madre PASO 5");  
                if (!this.apellidoPadreEsposa) this.errorMostrarMsjMatrimonio.push("Escribir apellidos de la madre PASO 5");
                if (!this.nombreMadreEsposa) this.errorMostrarMsjMatrimonio.push("Escribir nombres del padre PASO 5");    
                if (!this.apellidoMadreEsposa) this.errorMostrarMsjMatrimonio.push("Escribir apellidos del padre PASO 5");

                if (!this.apellidoCasada) this.errorMostrarMsjMatrimonio.push("Seleccionar Apellido de casada PASO 6");
                if (!this.regimen) this.errorMostrarMsjMatrimonio.push("Selecionar Régimen patrimonial PASO 6");
                    
                if (!this.nombreTestigo1) this.errorMostrarMsjMatrimonio.push("Escribir nombre testigo PASO 7");
                if (!this.apellidoTestigo1) this.errorMostrarMsjMatrimonio.push("Escribir apellidos testigo PASO 7");
                if (!this.duiTestigo1) this.errorMostrarMsjMatrimonio.push("Escribir DUI testigo PASO 7");
                    
                if (!this.nombreTestigo2) this.errorMostrarMsjMatrimonio.push("Escribir nombre testigo PASO 8");
                if (!this.apellidoTestigo2) this.errorMostrarMsjMatrimonio.push("Escribir apellidos testigo PASO 8");
                if (!this.duiTestigo2) this.errorMostrarMsjMatrimonio.push("Escribir DUI testigo PASO 8");
                    
                if (this.errorMostrarMsjMatrimonio.length) this.errorMatrimonio = 1;

                return this.errorMatrimonio;
            },

            

        },
                 
        mounted() {
            this.listarMatrimonio(1,this.buscar,this.criterio);
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
        .nacimientomodal{
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