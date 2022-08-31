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
                        <i class="fa fa-align-justify"></i>Partidas de Defunci&oacuten
                        <button type="button" @click="abrirModal('defuncion','registrar')" class="btn btn-primary">
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
                                      <option value="nombrepadre">Padre</option>
                                      <option value="nombremadre">Madre</option>
                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarDefuncion(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDefuncion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                                <tr>
                                    <th style="vertical-align:middle;">Opciones</th>  
                                    <th style="vertical-align:middle;">Paginas</th>
                                    <th style="vertical-align:middle;">Año</th>
                                    <th style="vertical-align:middle;">numero de partida</th> 
                                    <th style="vertical-align:middle;">Nombres</th>
                                    <th style="vertical-align:middle;">Sexo</th>
                                    <th style="vertical-align:middle;">Edad</th>
                                    <th style="vertical-align:middle;">Domicilio</th>
                                    <th style="vertical-align:middle;">Nacionalidad</th>
                                    <th style="vertical-align:middle;">Nombre del Padre</th>
                                    <th style="vertical-align:middle;">Nombre de la Madre</th>
                                    <th style="vertical-align:middle;">Fecha Fallecimiento</th>
                                    

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="defuncion in arrayDefuncion" :key="defuncion.id">
                                      <td class="align-middle" align="center">
                                        <button type="button" @click="abrirModal('defuncion','actualizar',defuncion)" class="btn btn-warning btn-sm">
                                          <i class="fa fa-edit"></i>
                                        </button>
                                          <button type="button" @click="pdfdefuncion(defuncion.id)" class="btn btn-info btn-sm">
                             <i class="fa fa-file-pdf-o"></i>
                            </button> &nbsp;
                                    </td>
                                    <td class="align-middle" v-text="defuncion.paginas"></td>
                                    <td class="align-middle" v-text="defuncion.anio"></td>
                                    <td class="align-middle" v-text="defuncion.numeropartida"></td>                                    
                                    <td class="align-middle" v-text="defuncion.nombres"></td>
                                    <td class="align-middle" v-text="defuncion.sexo"></td>
                                    <td class="align-middle" v-text="defuncion.edad"></td>
                                    <td class="align-middle" v-text="defuncion.domicilio"></td>
                                    <td class="align-middle" v-text="defuncion.nacionalidad"></td>
                                    <td class="align-middle" v-text="defuncion.nombrepadre"></td>
                                    <td class="align-middle" v-text="defuncion.nombremadre"></td>                                           
                                    <td class="align-middle">{{defuncion.fechamuerte | fecha}}</td>

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
                            <form-wizard next-button-text="Siguiente" title="Registro familiar" subtitle="partidas de defunción" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                
                                <tab-content title="Datos principales"> <!--Formulario 1-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">paginas</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="paginas" class="form-control" placeholder="Paginas">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">año</label>
                                    <div class="col-md-9">
                                      <input type="number"  v-model="anio" class="form-control" placeholder="año" min="1" >                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">numero de partida</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="numeropartida" class="form-control" placeholder="Numero de partida">                                                                      
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
                                    <option value="femenino">Femenino</option>
                                    <option value="masculino" >Masculino</option>
                                  </select>                               
                                   </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Edad</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="edad" class="form-control" placeholder="edad">                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Estado familiar (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="estadofam">
                                    <option value="soltero">Soltero</option>
                                    <option value="casado" >Casado</option>
                                    <option value="divorciado">Divorciado</option>
                                    <option value="viudo" >Viudo</option>
                                  </select>                               
                                   </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Profesión/Oficio</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="oficio" class="form-control" placeholder="profesión u oficio">                                                                      
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lugar de Origen</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="lugarorigen" class="form-control" placeholder="Origen">                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Domicilio</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="domicilio" class="form-control" placeholder="Domicilio">                                                                      
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nacionalidad</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nacionalidad" class="form-control" placeholder="Nacionalidad">                                                                      
                                    </div>
                                </div>

                            
                                <div v-show="errorDefuncion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDefuncion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                   </tab-content>
                    <tab-content title="Datos de Secundarios"> <!--Formulario 2-->
                       <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre del padre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombrepadre" class="form-control" placeholder="Nombre del padre">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre de la madre</label>
                                    <div class="col-md-9">
                                      <input type="text"  v-model="nombremadre" class="form-control" placeholder="Nombre de la madre"  >                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Hora de fallecimiento</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="hora" class="form-control" placeholder="Hora de fallecimiento">                                                                      
                                    </div>
                                </div>                               
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de fallecimiento</label>
                                    <div class="col-md-9">
                                      <input type="date" v-model="fechamuerte" class="form-control" placeholder="Fecha de fallecimiento">                                                                      
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Lugar de fallecimiento</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="lugarmuerte" class="form-control" placeholder="Lugar donde falleció">                                                                      
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Causa de la muerte</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="causa" class="form-control" placeholder="Causa de la muerte">                                                                      
                                    </div>
                                </div>
                                                            
                                <div v-show="errorDefuncion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDefuncion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
      
                    </tab-content>
                                <tab-content title="Datos de Finales">   <!--Formulario 1-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Documento de identidad del fallecido</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="duifallecido" class="form-control" placeholder="Documento de identidad del fallecido">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Encargado/a de dar los datos</label>
                                    <div class="col-md-9">
                                      <input type="text"  v-model="nombreregistrador" class="form-control" placeholder="Nombre de la persona que proporciona los datos"  >                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Parentesco con el fallecido</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="parentesco" class="form-control" placeholder="Parentesco con el fallecido">                                                                      
                                    </div>
                                </div>                               
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Documento de identidad </label>
                                    <div class="col-md-9">
                                      <input type="number" v-model="duiregistrador" class="form-control" placeholder="Documento de identidad de la persona que da los datos">                                                                      
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre de testigo</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="nombretestigo" class="form-control" placeholder="Nombre del testigo">                                                                      
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Documento de identidad de testigo</label>
                                    <div class="col-md-9">
                                      <input type="number" v-model="duitestigo" class="form-control" placeholder="Documento de identidad del testigo">                                                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de creación de partida</label>
                                    <div class="col-md-9">
                                      <input type="Date" v-model="fechapartida" class="form-control" placeholder="Fecha en que se crea la partida">                                                                      
                                    </div>
                                </div>
                               <!-- <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha de expedición de la partida</label>
                                    <div class="col-md-9">
                                      <input type="text" v-model="fechaexpe" class="form-control" placeholder="Fecha de retiro de la partida">                                                                      
                                    </div>
                                </div-->
                                                            
                                <div v-show="errorDefuncion" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDefuncion" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                               
                               <!--Boton para guardar los datos en el modal-->
                               <div class="modal-footer" style="padding: 1em">
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDefuncion()">Guardar</button>
                               </div>
                    </tab-content>
                            </form-wizard>
                        </div>
                        <div class="modal-footer" style="padding: 1em">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                           <!-- <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDefuncion()">Guardar</button>-->
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDefuncion()">Actualizar</button>
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

                defuncion_id: 0,
                paginas : '',
                anio:1900,
                numeropartida : '',                
                nombres : '',
                sexo: 'Masculino',
                edad: '',
                estadofam: 'Casado',
                oficio: '',
                lugarorigen: '',
                domicilio: '',
                nacionalidad: '',
                nombrepadre: '',
                nombremadre: '',
                hora: '',
                fechamuerte: '',
                lugarmuerte: '',
                causa: '',
                duifallecido: '',
                nombreregistrador: '',
                parentesco: '',
                duiregistrador: '',
                nombretestigo: '',
                duitestigo: '',
                fechapartida: '',
               // fechaexpe: '',
                arrayDefuncion : [],
                modal : 0,
                modal2:0,
                tituloModal : '',
                tituloModal2 : '',
                tipoAccion : 0,
                errorDefuncion : 0,
                errorMostrarMsjDefuncion : [],
                
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
              listarDefuncion (page,buscar,criterio){
                let me=this;
                var url= '/defuncion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDefuncion = respuesta.defuncion.data;
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
                me.listarDefuncion(page,buscar,criterio);
            },

            registrarDefuncion(){
                if (this.validarDefuncion()){
                    return;
                }
                
                let me = this;

                axios.post('/defuncion/registrar',{
                    'paginas': this.paginas,
                    'anio':this.anio,
                    'numeropartida':this.numeropartida,                    
                    'nombres' : this.nombres,      
                    'sexo': this.sexo, 
                    'edad': this.edad,
                    'estadofam': this.estadofam,
                    'oficio': this.oficio,
                    'lugarorigen': this.lugarorigen,
                    'domicilio': this.domicilio,
                    'nacionalidad' : this.nacionalidad,
                    'nombrepadre': this.nombrepadre,
                    'nombremadre': this.nombremadre,
                    'hora': this.hora,
                    'fechamuerte': this.fechamuerte,
                    'lugarmuerte': this.lugarmuerte,
                    'causa': this.causa,
                    'duifallecido': this.duifallecido,
                    'nombreregistrador': this.nombreregistrador,
                    'parentesco': this.parentesco,
                    'duiregistrador': this.duiregistrador,
                    'nombretestigo': this.nombretestigo,
                    'duitestigo': this.duitestigo,
                    'fechapartida': this.fechapartida,
                    //'fechaexpe': this.fechaexpe,
                }).then(function (response) {

                    console.log(response);
                    me.cerrarModal();
                    me.listarDefuncion(1,'','nombres');
                }).catch(function (error) {
                    console.log(error);
                });
            },
             actualizarDefuncion(){
               if (this.validarDefuncion()){
                    return;
                }
                
                let me = this;

                axios.put('/defuncion/actualizar',{
                     'paginas': this.paginas,
                     'anio':this.anio,
                    'numeropartida':this.numeropartida,                    
                    'nombres' : this.nombres,      
                    'sexo': this.sexo, 
                    'edad': this.edad,
                    'estadofam': this.estadofam,
                    'oficio': this.oficio,
                    'lugarorigen': this.lugarorigen,
                    'domicilio': this.domicilio,
                    'nacionalidad' : this.nacionalidad,
                    'nombrepadre': this.nombrepadre,
                    'nombremadre': this.nombremadre,
                    'hora': this.hora,
                    'fechamuerte': this.fechamuerte,
                    'lugarmuerte': this.lugarmuerte,
                    'causa': this.causa,
                    'duifallecido': this.duifallecido,
                    'nombreregistrador': this.nombreregistrador,
                    'parentesco': this.parentesco,
                    'duiregistrador': this.duiregistrador,
                    'nombretestigo': this.nombretestigo,
                    'duitestigo': this.duitestigo,
                    'fechapartida': this.fechapartida,
                    //'fechaexpe': this.fechaexpe,
                    
                    'id': this.defuncion_id,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarDefuncion(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
             pdfdefuncion(id){
                window.open('/defuncion/pdf/'+ id);
            },

             cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                
                this.errorDefuncion=0;

            },
               cerrarModal2(){
                this.modal2=0;
                this.tituloModal='';
              

            },

             abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "defuncion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                               this.modal = 1;
                               this.tituloModal = 'Registrar Partida de Defunción';
                               this.paginas='';
                               this.anio;
                               this.numeropartida='';                               
                               this.nombres='';
                               this.sexo=''; 
                               this.edad='';
                               this.estadofam='';
                               this.oficio='';
                               this.lugarorigen='';
                               this.domicilio='';
                               this.nacionalidad='';
                               this.nombrepadre='';
                               this.nombremadre='';
                               this.hora='';
                               this.fechamuerte='';
                               this.lugarmuerte='';
                               this.causa='';
                               this.duifallecido='';
                               this.nombreregistrador='';
                               this.parentesco='';
                               this.duiregistrador='';
                               this.nombretestigo='';
                               this.duitestigo='';
                               this.fechapartida='';
                               //this.fechaexpe='';
                               this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Partida de Defunción';
                                this.tipoAccion=2;
                                this.defuncion_id=data['id'];
                                this.paginas = data['paginas'];
                                this.anio = data['anio'];
                                this.numeropartida= data['numeropartida'];                               
                                this.nombres= data['nombres'];
                                this.sexo= data['sexo']; 
                                this.edad= data['edad'];
                                this.estadofam= data['estadofam'];
                                this.oficio= data['oficio'];
                                this.lugarorigen= data['lugarorigen'];
                                this.domicilio= data['domicilio'];
                                this.nacionalidad= data['nacionalidad'];
                                this.nombrepadre= data['nombrepadre'];
                                this.nombremadre= data['nombremadre'];
                                this.hora= data['hora'];
                                this.fechamuerte= data['fechamuerte'];
                                this.lugarmuerte= data['lugarmuerte'];
                                this.causa= data['causa'];
                                this.duifallecido= data['duifallecido'];
                                this.nombreregistrador= data['nombreregistrador'];
                                this.parentesco= data['parentesco'];
                                this.duiregistrador= data['duiregistrador'];
                                this.nombretestigo= data['nombretestigo'];
                                this.duitestigo= data['duitestigo'];
                                this.fechapartida= data['fechapartida'];
                                //this.fechaexpe= data['fechaexpe'];

                                
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

             validarDefuncion(){
                this.errorDefuncion=0;
                this.errorMostrarMsjDefuncion =[];

                if (!this.paginas) this.errorMostrarMsjDefuncion.push("debe escribir el numero de paginas PASO 1");
                if (!this.numeropartida) this.errorMostrarMsjDefuncion.push("Debe escribir el numero de la partida PASO 1");
                if (!this.anio) this.errorMostrarMsjDefuncion.push("Debe escribir un año PASO 1");
                if (!this.nombres) this.errorMostrarMsjDefuncion.push("Debe escribir los nombres PASO 1");
                if (!this.sexo) this.errorMostrarMsjDefuncion.push("Debe seleccionar el sexo PASO 1");
                if (!this.hora) this.errorMostrarMsjDefuncion.push("Debe escribir la hora de fallecimiento PASO 2");
                if (!this.fechamuerte) this.errorMostrarMsjDefuncion.push("Debe escribir la fecha de fallecimiento PASO 2");
                if (!this.lugarorigen) this.errorMostrarMsjDefuncion.push("Debe escribir el lugar de origen PASO 1");
                if (!this.oficio) this.errorMostrarMsjDefuncion.push("Debe escribir la profesión u oficio PASO 1");
                if (!this.domicilio) this.errorMostrarMsjDefuncion.push("Debe escribir el domicilio PASO 1");
                if (!this.nacionalidad) this.errorMostrarMsjDefuncion.push("Debe escribir la nacionalidad PASO 1");
                if (!this.nombrepadre) this.errorMostrarMsjDefuncion.push("Debe escribir el nombre del padre PASO 2");
                if (!this.nombremadre) this.errorMostrarMsjDefuncion.push("Debe escribir el nombre de la madre PASO 2");
                if (!this.lugarmuerte) this.errorMostrarMsjDefuncion.push("Debe escribir el lugar de fallecimiento PASO 2");
                if (!this.causa) this.errorMostrarMsjDefuncion.push("Debe escribir la causa de la muerte PASO 2");
                if (!this.nombreregistrador) this.errorMostrarMsjDefuncion.push("Debe escribir el nombre de quien brinda los datos PASO 3");
                if (!this.parentesco) this.errorMostrarMsjDefuncion.push("Debe escribir el parentesco de la registradora con el fallecido PASO 3");
                if (!this.duiregistrador) this.errorMostrarMsjDefuncion.push("Escriba el DUI de la persona que brinda los datos PASO 3");
                if (!this.nombretestigo) this.errorMostrarMsjDefuncion.push("Escriba el nombre del testigo PASO 3");
                if (!this.duitestigo) this.errorMostrarMsjDefuncion.push("Escriba el DUI del testigo PASO 3");
                 if (!this.fechapartida) this.errorMostrarMsjDefuncion.push("Debe escribir la fecha de la partida PASO 3");
                 //if (!this.fechaexpe) this.errorMostrarMsjDefuncion.push("Debe escribir la fecha de expedicion de la partida PASO 3");
                


                if (this.errorMostrarMsjDefuncion.length) this.errorDefuncion = 1;

                return this.errorDefuncion;
            },

            

        },
                 
        mounted() {
            this.listarDefuncion(1,this.buscar,this.criterio);
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
