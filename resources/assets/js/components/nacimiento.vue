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
                        <i class="fa fa-align-justify"></i>Nacimientos
                        <button type="button" @click="abrirModal('servicio','registrar')" class="btn btn-primary">
                            <i class="fa fa-plus-square"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombres">nombres</option>
                                      <option value="anio">año</option>
                                        <option value="nombres_ciudadano">nombre del padre </option>
                                        <option value="nombres_madre">nombre de la madre</option>
            
                                      
                                    </select>
                                             <input type="text" v-model="buscar" @keyup.enter="listarNacimiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarNacimiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    
                                   
                                    
                                   
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th>Opciones</th>  
                                    <th>Paginas</th>
                                    <th>numero de partida</th>
                                    <th>Año</th>
                                    <th>Nombres</th>
                                    <th>Sexo</th>
                                    <th>Hora</th>
                                    <th>Fecha</th>
                                    <th>Nombre del padre</th>
                                    <th>apellidos</th>
                                     <th>Nombre de la madre</th>
                                    <th>apellidos madre</th>

                                                              </tr>
                            </thead>
                            <tbody>
                                <tr v-for="nacimiento in arrayNacimiento" :key="nacimiento.id">
                                      <td class="align-middle" align="center">
                                        <button type="button" @click="abrirModal('servicio','actualizar',nacimiento)" class="btn btn-warning btn-sm">
                                          <i class="fa fa-edit"></i>
                                        </button>
                                          <button type="button" @click="pdfnacimiento(nacimiento.id)" class="btn btn-info btn-sm">
                             <i class="fa fa-file-pdf-o"></i>
                            </button> &nbsp;
                                    </td>
                                    <td class="align-middle" v-text="nacimiento.paginas"></td>
                                       <td class="align-middle" v-text="nacimiento.numeropartida"></td>
                                    <td class="align-middle" v-text="nacimiento.anio"></td>
                                    <td class="align-middle" v-text="nacimiento.nombres"></td>
                                        <td class="align-middle" v-text="nacimiento.sexo"></td>
                                    <td class="align-middle" v-text="nacimiento.hora"></td>
                                        <td class="align-middle" v-text="nacimiento.fecha"></td>
                                          <td class="align-middle" v-text="nacimiento.nombres_padre"></td>
                                     <td class="align-middle" v-text="nacimiento.apellidos_padre"></td>                                           <td class="align-middle" v-text="nacimiento.nombres_madre"></td>
                                     <td class="align-middle" v-text="nacimiento.apellidos_madre"></td>


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
                            <form-wizard next-button-text="Siguiente" title="Registro familiar" subtitle="partidas de nacimiento" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <tab-content title="Detalles de la partida">
                                     <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">paginas</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="paginas" class="form-control" placeholder="Paginas">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">numero de partida</label>
                                    <div class="col-md-9">
                                      <input type="number" min="1" v-model="numeropartida" class="form-control" placeholder="Numero de partida">                                                                      
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombres" class="form-control" placeholder="nombres">                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Sexo (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="sexo">
                                                                                <option value="0">*** Seleccione el sexo***
</option>
                            <option value="femenino">Femenino</option>
                            <option value="masculino" >Masculino</option>
                          </select>                               
                           </div>
                            </div>

                        <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Hora de nacimiento</label>
                                    <div class="col-md-9">
                                      <input type="time" v-model="hora" class="form-control" placeholder="hora">                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de nacimiento</label>
                                    <div class="col-md-9">
                                      <input type="date" v-model="fecha" class="form-control" placeholder="fecha">                                                                      
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lugar de nacimiento</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="lugarnacimiento" class="form-control" placeholder="lugar">                                                                      
                                    </div>
                                </div>


                                       
                               
                   </tab-content>
                    <tab-content title="Datos del padre" >Datos del padre
                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombres_padre" class="form-control" placeholder="Nombres">                                        
                                    </div>
                                      </div>

                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="apellidos_padre" class="form-control" placeholder="Apellidos">                                        
                                    </div>
                                      </div>
                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Edad</label>
                                    <div class="col-md-9">
                                        <input type="number" min="1" max="150" v-model="edad_padre" class="form-control" placeholder="edad">

                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Oficio </label>
                                    <div class="col-md-9">
                                        <input type="text" min="1" max="150" v-model="oficio_padre" class="form-control" placeholder="Oficio">                                        
                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lugar de nacimiento </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="lugarorigen_padre" class="form-control" placeholder="Lugar de nacimiento">                                        
                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dui </label>
                                    <div class="col-md-9">
                                        <input type="text"  maxlength="9" v-model="dui_padre" class="form-control" placeholder="Dui" pattern="[0-9]{9}" required>                                        
                                    </div>
                                      </div>

      

      

      

      

      
                    </tab-content>

                    <tab-content title="Datos de la madre" >
                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombres_madre" class="form-control" placeholder="Nombres">                                        
                                    </div>
                                      </div>

                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="apellidos_madre" class="form-control" placeholder="Apellidos">                                        
                                    </div>
                                      </div>
                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Edad</label>
                                    <div class="col-md-9">
                                        <input type="number" min="1" max="150" v-model="edad_madre" class="form-control" placeholder="edad">

                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Oficio </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="oficio_madre" class="form-control" placeholder="Oficio">                                        
                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lugar de nacimiento </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="lugarorigen_madre" class="form-control" placeholder="Lugar de nacimiento">                                        
                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dui </label>
                                    <div class="col-md-9">
                                        <input type="text" maxlength="9" v-model="dui_madre" class="form-control" placeholder="Dui" pattern="[0-9]{9}" required>                                        
                                    </div>
                                      </div>

      

      

      

      

      
                    </tab-content>
                     <tab-content title="Descripcion del domicilio y nacionalidad de los padres" >

                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                    <div class="col-md-9">
                                       <textarea type="text" v-model="descripcion" class="form-control" placeholder="Ingrese el contenido referente a los domicilios de los padres y su nacionalidad" cols="10" rows="10">
                                       </textarea>                              
                                    </div>
                                </div>
                                                    </tab-content>

                                                     <tab-content title="Datos del tutor" >

                                                                           <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombres </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombres_tutor" class="form-control" placeholder="Nombres">                                        
                                    </div>
                                      </div>

                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="apellidos_tutor" class="form-control" placeholder="Apellidos">                                        
                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Quien manifiesta </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="manifiesto_tutor" class="form-control" placeholder="Oficio">                                        
                                    </div>
                                      </div>

                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dui </label>
                                    <div class="col-md-9">
                                        <input type="text"  maxlength="9" v-model="dui_tutor" class="form-control" placeholder="Dui" pattern="[0-9]{9}" required>                                        
                                    </div>
                                      </div>
                                      
                                                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Expedido </label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="expedido_tutor" class="form-control" placeholder="Lugar donde fue expedido el dui">                                        
                                    </div>
                                      </div>
                                       

                                        



                                                    </tab-content>
                                                    
                                                    <tab-content title="Guardar partida">
                                                         <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarNacimiento()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarNacimiento()">Actualizar</button>
                                                 <div v-show="errorNacimiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjNacimiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                                                    </tab-content>



                            </form-wizard>
                        </div>
                        <div class="modal-footer" style="padding: 1em">
                         
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

                nacimiento_id: 0,
                paginas : '',
                numeropartida : '',
                anio:1900,
                nombres : '',
                sexo: 0,
                hora: '',
                fecha: '',
                lugarnacimiento: '',
                descripcion: '',
                arrayNacimiento : [],
                nombres_padre:'',
                apellidos_padre:'',
                edad_padre:'',
                oficio_padre:'',
                lugarorigen_padre:'',
                dui_padre:'',
                nombres_madre:'',
                apellidos_madre:'',
                edad_madre:'',
                oficio_madre:'',
                lugarorigen_madre:'',
                dui_madre:'',
                  nombres_tutor:'',
                apellidos_tutor:'',
                manifiesto_tutor:'',
                dui_tutor:'',
                expedido_tutor:'',


                modal : 0,
                            tituloModal : '',
                              tipoAccion : 0,
                errorNacimiento : 0,
                errorMostrarMsjNacimiento : [],
                
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombres',
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
              listarNacimiento (page,buscar,criterio){
                let me=this;
                var url= '/nacimiento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayNacimiento = respuesta.nacimientos.data;
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
                me.listarNacimiento(page,buscar,criterio);
            },

            registrarNacimiento(){
                if (this.validarNacimiento()){
                    return;
                }
                
                let me = this;

                axios.post('/nacimiento/registrar',{
                    'paginas': this.paginas,
                    'numeropartida':this.numeropartida,
                    'anio':this.anio,
                    'nombres' : this.nombres,      
                    'sexo': this.sexo, 
                    'hora': this.hora,
                    'fecha': this.fecha,
                    'lugarnacimiento': this.lugarnacimiento,
                    'descripcion':this.descripcion,
                    'nombres_padre':this.nombres_padre,
                    'apellidos_padre':this.apellidos_padre,
                    'edad_padre':this.edad_padre,
                    'oficio_padre':this.oficio_padre,
                    'lugarorigen_padre':this.lugarorigen_padre,
                    'dui_padre':this.dui_padre,
                  'nombres_madre':this.nombres_madre,
                    'apellidos_madre':this.apellidos_madre,
                    'edad_madre':this.edad_madre,
                    'oficio_madre':this.oficio_madre,
                    'lugarorigen_madre':this.lugarorigen_madre,
                    'dui_madre':this.dui_madre,
                    'nombres_tutor':this.nombres_tutor,
                    'apellidos_tutor':this.apellidos_tutor,
                    'manifiesto_tutor':this.manifiesto_tutor,
                    'dui_tutor':this.dui_tutor,
                    'expedido_tutor':this.expedido_tutor




                
                }).then(function (response) {

                    console.log(response);
                    me.cerrarModal();
                    me.listarNacimiento(1,'','nombres');
                }).catch(function (error) {
                    console.log(error);
                });
            },
             actualizarNacimiento(){
               if (this.validarNacimiento()){
                    return;
                }
                
                let me = this;

                axios.put('/nacimiento/actualizar',{
                     'paginas': this.paginas,
                    'numeropartida':this.numeropartida,
                    'anio':this.anio,
                    'nombres' : this.nombres,      
                    'sexo': this.sexo, 
                    'hora': this.hora,
                    'fecha': this.fecha,
                    'lugarnacimiento': this.lugarnacimiento,
                    'id': this.nacimiento_id,
                                        'descripcion':this.descripcion,
                    'nombres_padre':this.nombres_padre,
                    'apellidos_padre':this.apellidos_padre,
                    'edad_padre':this.edad_padre,
                    'oficio_padre':this.oficio_padre,
                    'lugarorigen_padre':this.lugarorigen_padre,
                    'dui_padre':this.dui_padre,
                  'nombres_madre':this.nombres_madre,
                    'apellidos_madre':this.apellidos_madre,
                    'edad_madre':this.edad_madre,
                    'oficio_madre':this.oficio_madre,
                    'lugarorigen_madre':this.lugarorigen_madre,
                    'dui_madre':this.dui_madre,
                    'nombres_tutor':this.nombres_tutor,
                    'apellidos_tutor':this.apellidos_tutor,
                    'manifiesto_tutor':this.manifiesto_tutor,
                    'dui_tutor':this.dui_tutor,
                    'expedido_tutor':this.expedido_tutor

                }).then(function (response) {
                    me.cerrarModal();
                    me.listarNacimiento(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
             pdfnacimiento(id){
                window.open('/nacimiento/pdf/'+ id);
            },

             cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.errorNacimiento=0;

            },
               cerrarModal2(){
                this.modal2=0;
                this.tituloModal='';
              

            },

             abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "servicio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar nacimiento';
                               this.paginas='';
                               this.numeropartida='';
                               this.hora='';
                               this.lugarnacimiento='';
                               this.fecha='';
                               this.anio=1900;
                               this.sexo=0;
                               
                                                              this.nombres='';
                               this.descripcion='';
                                this.tipoAccion = 1;
                                this.nombres_padre='';
                this.apellidos_padre='';
                this.edad_padre='';
                this.oficio_padre='';
                this.lugarorigen_padre='';
                this.dui_padre='';
                this.nombres_madre='';
                this.apellidos_madre='';
                this.edad_madre='';
                this.oficio_madre='';
                this.lugarorigen_madre='';
                this.dui_madre='';
                this.  nombres_tutor='';
                this.apellidos_tutor='';
                this.manifiesto_tutor='';
                this.dui_tutor='';
                this.expedido_tutor='';
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar nacimiento';
                                this.tipoAccion=2;
                                this.nacimiento_id=data['id'];
                                this.paginas = data['paginas'];
                                this.numeropartida =data['numeropartida'];
                                this.anio = data['anio'];
                                this.nombres= data['nombres'];
                                this.sexo=data['sexo'];
                                this.hora=data['hora'];
                                this.fecha=data['fecha'];
                                this.lugarnacimiento=data['lugarnacimiento'];
                                this.descripcion=data['descripcion'];
                    this.nombres_padre=data['nombres_padre'];
                    this.apellidos_padre=data['apellidos_padre'];
                    this.edad_padre=data['edad_padre'];
                    this.oficio_padre=data['oficio_padre'];
                    this.lugarorigen_padre=data['lugarorigen_padre'];
                    this.dui_padre=data['dui_padre'];
                    this.nombres_madre=data['nombres_madre'];
                    this.apellidos_madre=data['apellidos_madre'];
                    this.edad_madre=data['edad_madre'];
                    this.oficio_madre=data['oficio_madre'];
                    this.lugarorigen_madre=data['lugarorigen_madre'];
                    this.dui_madre=data['dui_madre'];
                    this.nombres_tutor=data['nombres_tutor'];
                    this.apellidos_tutor=data['apellidos_tutor'];
                    this.manifiesto_tutor=data['manifiesto_tutor'];
                    this.dui_tutor=data['dui_tutor'];
                  this.expedido_tutor=data['expedido_tutor'];


                                
                                break;
                            }
                             case "filtrar":
                                    {
                                        this.modal2 = 1;
                                     this.tituloModal2 = 'Registrar nacimiento';
                               this.paginas='';
                               this.numeropartida='';
                               this.anio;
                               this.nombres='';
                                this.tipoAccion = 1;
                                    }
                        }
                    }
                }
            },

             validarNacimiento(){
                this.errorNacimiento=0;
                this.errorMostrarMsjNacimiento =[];

                if (!this.paginas) this.errorMostrarMsjNacimiento.push("debe escribir las paginas");
                if (!this.numeropartida) this.errorMostrarMsjNacimiento.push("Debe escribir el numero de la partida");
                if (!this.anio) this.errorMostrarMsjNacimiento.push("Debe escribir un año");
                if (!this.nombres) this.errorMostrarMsjNacimiento.push("Debe escribir los nombres");
                if (this.sexo==0) this.errorMostrarMsjNacimiento.push("Debe seleccionar al menos un sexo");
                if (!this.sexo) this.errorMostrarMsjNacimiento.push("Debe seleccionar el sexo");
                if (!this.hora) this.errorMostrarMsjNacimiento.push("Debe escribir la hora de nacimiento");
                if (!this.fecha) this.errorMostrarMsjNacimiento.push("Debe escribir la fecha");
                if (!this.lugarnacimiento) this.errorMostrarMsjNacimiento.push("Debe escribir el lugar");
                    if (!this.descripcion) this.errorMostrarMsjNacimiento.push("Debe escribir una descripcion");
                if( !this.nombres_madre)this.errorMostrarMsjNacimiento.push("Debe escribir los nombres del madre");
                if( !this.apellidos_madre)this.errorMostrarMsjNacimiento.push("Debe escribir los apellidos del madre");
             if( !this.edad_madre)this.errorMostrarMsjNacimiento.push("Debe escribir la edad de la madre");
                if( !this.oficio_madre)this.errorMostrarMsjNacimiento.push("Debe escribir el oficio de la madre");
                if( !this.lugarorigen_madre)this.errorMostrarMsjNacimiento.push("Debe escribir el lugar de nacimiento de la madre");
                if( !this.dui_madre)this.errorMostrarMsjNacimiento.push("Debe escribir el dui de la madre");  
                  if( !this.nombres_padre)this.errorMostrarMsjNacimiento.push("Debe escribir los nombres de la padre");
                 if( !this.apellidos_padre)this.errorMostrarMsjNacimiento.push("Debe escribir los apellidos del padre");
                 if( !this.edad_padre)this.errorMostrarMsjNacimiento.push("Debe escribir la edad del padre");
                if( !this.oficio_padre)this.errorMostrarMsjNacimiento.push("Debe escribir el oficio del padre");
                if( !this.lugarorigen_padre)this.errorMostrarMsjNacimiento.push("Debe escribir el lugar de nacimiento del padre");
                if( !this.dui_padre)this.errorMostrarMsjNacimiento.push("Debe escribir el dui del padre");
               if( !this.nombres_tutor)this.errorMostrarMsjNacimiento.push("Debe escribir los nombres del tutor");
                 if( !this.apellidos_tutor)this.errorMostrarMsjNacimiento.push("Debe escribir los apellidos del tutor");
                  if( !this.dui_tutor)this.errorMostrarMsjNacimiento.push("Debe escribir el dui del tutor");
                  if(!this.manifiesto_tutor)this.errorMostrarMsjNacimiento.push("Escriba el manifiesto");
                  if(!this.validarDui(this.dui_padre))this.errorMostrarMsjNacimiento.push("El dui del padre no tiene el formato correcto");
                    if(!this.validarDui(this.dui_madre))this.errorMostrarMsjNacimiento.push("El dui de la madre no tiene el formato correcto");
                      if(!this.validarDui(this.dui_tutor))this.errorMostrarMsjNacimiento.push("El dui del tutor no tiene el formato correcto");


                if (this.errorMostrarMsjNacimiento.length) this.errorNacimiento = 1;

                return this.errorNacimiento;
            },
            
            validarDui(dui){
                var re = /^([0-9]{9})$/;
      return re.test(dui);



            }
            ,

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
                        me.listarNacimiento(1,'','nombre');
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
            this.listarNacimiento(1,this.buscar,this.criterio);
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
