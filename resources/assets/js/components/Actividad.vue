
<template>
    <div>
        
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" target="_blank">Ir a Portal</a></li>
        </ol>

        <h1>Actividades de {{proyecto.nombre}}</h1>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Actividades
                        <button type="button" @click="abrirModal('actividad','registrar')" class="btn btn-primary" :class="{'d-none':!estatusProyecto}">
                            &nbsp;<i class="fa fa-plus-square"></i>&nbsp;Nueva Actividad
                        </button>
                        <button type="button" @click="finalizarProyecto(idProyecto)" class="btn btn-danger" v-if="estatusProyecto">
                                            Finalizar Proyecto
                        </button>
                        <button class="btn" v-if="!estatusProyecto">
                                 <span class="badge badge-success">Proyecto Finalizado</span>
                        </button>

                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="buscarActividades(1, buscar, criterio)"  class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="buscarActividades(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- INICIO DEL LISTADO-->
                        <table class="table table-bordered table-striped table-sm table-responsive">
                            <thead>
                            <tr>
                                <th style="vertical-align:middle;">Nombre</th>
                                <th style="vertical-align:middle;">Descripción</th>
                                <th style="vertical-align:middle;">Avance</th>
                                <th style="vertical-align:middle;">Costo</th>
                                <!-- <th style="vertical-align:middle;">Costo Real</th> -->
                                <th style="vertical-align:middle;">Inicio</th>
                                <th style="vertical-align:middle;">Fin</th>
                                <th style="vertical-align:middle;">Estatus</th>
                                <th style="vertical-align:middle;">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="actividad in actividades" :key="actividad.id">
                                <td class="align-middle" >{{actividad.nombre | upper}}</td>
                                <td class="align-middle" v-text="actividad.descripcion"></td>
                                <td class="align-middle" style="width:100px">
                                    <div class="progress">
                                        <div class="progress-bar" v-bind:class="{
                                            'bg-danger': actividad.avance < 25, 
                                            'bg-warning': actividad.avance < 50, 
                                            'bg-info': actividad.avance < 75,
                                            'bg-primary': actividad.avance < 100,
                                            'bg-success': actividad.avance == 100,
                                            }" role="progressbar" v-bind:style="{width: actividad.avance+'px'}" :aria-valuenow="actividad.avance" aria-valuemin="0" aria-valuemax="100">{{actividad.avance}}%</div>
                                    </div>
                                </td>
                                <td class="align-middle" v-text="'$ '+actividad.costoEstimado"></td>
                                <td class="align-middle" >{{actividad.inicio | fecha}}</td>
                                <td class="align-middle" >{{actividad.fin | fecha}}</td>
                                <td class="align-middle" ><span class="badge" v-bind:class="{
                                    'badge-secondary': actividad.idEstatus ==1,
                                    'badge-primary': actividad.idEstatus == 2,
                                    'badge-success': actividad.idEstatus == 3,
                                    'badge-warning': actividad.idEstatus == 4
                                    }">{{(tiposEstatus[actividad.idEstatus-1]).nombre}}</span></td>
                                <td class="align-middle" v-if="estatusProyecto">
                                    <button type="button" @click="abrirModal('actividad','actualizar',actividad)" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" @click="eliminarActividad(actividad.id)" class="btn btn-danger btn-sm">
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
                        <!-- FIN DEL LISTADO-->
                    </div>
                </div>
            </div>

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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="formActividad">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input id="nombre" type="text" v-model="nombre" class="form-control" placeholder="Ingrese un nombre" @keyup="validarString($event)" @blur="validarString($event)">
                                        <div class="invalid-feedback" style="display:none">
                                            El nombre no puede estar vacio
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="descripcion">Descripción (*)</label>
                                    <div class="col-md-9">
                                        <input id="descripcion" type="text" v-model="descripcion" class="form-control" placeholder="Ingrese una descripción" @keyup="validarString($event)" @blur="validarString($event)">
                                        <div class="invalid-feedback" style="display:none">
                                            Por favor ingrese una descripción
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="monto">Costo estimado (*)</label>
                                    <div class="col-md-9">
                                        <input id="monto" class="form-control" v-model.number="costoEstimado" type="number"  min="0" @keyup="validarNumber($event, 0)" @blur="validarNumber($event, 0)">
                                        <div class="invalid-feedback" style="display:none">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                  <label for="" class="col-md-3 form-control-label">Fecha Inicio</label>
                                  <div class="col-md-9">
                                        <input type="date" name="" id="" class="form-control" placeholder="" aria-describedby="helpId" v-model="inicio" @blur="validarFecha($event)" @change="validarFecha($event)">
                                        <div class="invalid-feedback" style="display:none">
                                        </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="" class="col-md-3 form-control-label">Fecha fin</label>
                                  <div class="col-md-9">
                                        <input type="date" name="" id="" class="form-control" placeholder="" aria-describedby="helpId" v-model="fin" @blur="validarFecha($event)" @change="validarFecha($event)">
                                        <div class="invalid-feedback" style="display:none">
                                        </div> 
                                  </div>
                                </div>
                                
                                <div class="form-group row">
                                  <label for="" class="col-md-3 form-control-label">Estatus</label>
                                  <div class="col-md-9">
                                        <select class="form-control" name="" id="" v-model="idEstatus" placeholder="Escoga el estatus" @change="validarStatus($event)" @blur="validarStatus($event)">
                                            <option v-for="(status, index) in tiposEstatus" :key="status.id" :value="status.id">{{status.nombre}}</option>
                                        </select>
                                        <div class="invalid-feedback" style="display:none" >
                                        </div>
                                  </div>
                                  
                                </div>
                                <div class="form-group row">
                                  <label for="" class="col-md-3 form-control-label">Avance</label>
                                  <div class="col-md-9">
                                        <input  v-model.number="avance"  class="form-control" type="number" min="0"  max="100" step="1" @keyup="validarNumber($event, 1)" @blur="validarNumber($event, 1)">
                                        <div class="invalid-feedback" style="display:none">
                                        </div>
                                  </div>
                                </div>
                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearActividad()" id="btnGuardar" disabled>Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarActividad()" id="btnActualizar" disabled>Actualizar</button>
                        </div>
                    </div> <!-- /.modal-content -->
                </div> <!-- /.modal-dialog -->
            </div> <!--Fin del modal-->
    </div>
</template>

<script>
import app from '../app';
import axios from 'axios';
import VueNumberInput from '@chenfengyuan/vue-number-input';




export default {
    
    data() {
        return {
            idProyecto:app._data.idProyecto,
            idActiTemp: 0,
            proyecto:'',
            actividades: [],
            tiposEstatus: [],
            estatusProyecto: true,
            idEstatus: 0,
            modal : 0,
            tituloModal: '',
            nombre: '',
            descripcion: '',
            avance: 0,
            costoEstimado: 0,
            costoReal: 0,
            inicio: '',
            fin: '',
            tipoAccion: 0,
            pagination:{
                    'total' : 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to' :0,
                },
            offset : 5,
            criterio: 'nombre',
            buscar: '',
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
    filters:{
        upper: function (value) {
            return value.toUpperCase();
        },
        fecha: function(fecha){
            const date = new Date(fecha);
            return date.toLocaleString(['es-ES'], {day: '2-digit',month: '2-digit',year: 'numeric'})
        }
    },
    
    methods: {
        listarActividades(){
            this.getProyecto();
            this.getTipoEstatus();
            // axios.get('/actividad/'+this.idProyecto)
            // .then(respuesta => {
            //     this.actividades = respuesta.data;
            // })
            // .catch(error => console.log(error));
            this.getStatusProyecto(this.idProyecto);
            this.buscarActividades();
        },
        cambiarPagina(page,buscar,criterio){
                this.pagination.current_page = page;
                this.buscarActividades(page,buscar,criterio);
        },
        buscarActividades(page = 1, buscar = '', criterio= 'nombre', idProyecto = this.idProyecto){
            axios.get('/actividades/buscar?page='+ page + 
            '&buscar=' + buscar + 
            '&criterio=' + criterio +
            '&idProyecto= '+idProyecto)
            .then( respuesta => {                
                this.actividades = respuesta.data.actividades.data;
                this.pagination = respuesta.data.pagination;
            })
            .catch(error => console.log(error));
        },
        getProyecto(){
            axios.get("/proyecto/"+this.idProyecto)
            .then( respuesta => {
                this.proyecto = respuesta.data;
            })
            .catch((error) => console.log(error));
        },
        getTipoEstatus(){
            axios.get('/tipo-estatus')
            .then(respuesta => {
                this.tiposEstatus = respuesta.data;
            })
            .catch(error => console.log(error));
        },
        finalizarProyecto(idProyecto){
                Swal.fire({
                title: 'Desea finalizar este proyecto?',
                text: "No se podra revertir esta accion",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, finalizalo!',
                cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                        axios.get("/proyecto/finalizar/"+idProyecto)
                        .then((respuesta) => {
                            this.listarActividades();
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Se ha finalizado el proyecto exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        })
                        .catch(error => {
                            Swal.fire({
                            position: 'center',
                            type: 'error',
                            title: 'No se pudo Finalizar Proyecto',
                            showConfirmButton: true
                            })
                            console.log(error)
                        });
                    }
            })
            },
            getStatusProyecto(idProyecto){
                axios.get("/proyecto/estatus/"+idProyecto)
                        .then((respuesta) => {
                            if(respuesta.data[0].idTipoEstatus !== 3){                           
                                this.estatusProyecto = true;
                            }else{
                                this.estatusProyecto = false;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            this.estatusProyecto = false;
                        });
            },
        crearActividad(){
            this.costoReal = this.costoEstimado;
            console.log(this.avance);
            
            axios.post("/actividad",{
                'idProyecto':this.idProyecto,
                'nombre': this.nombre,
                'descripcion':this.descripcion,
                'avance':this.avance,
                'costoEstimado':this.costoEstimado,
                'costoReal':this.costoReal,
                'inicio':this.inicio,
                'fin': this.fin,
                'idEstatus': this.idEstatus,
            }).then(
                respuesta =>{
                    Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Actividad Creada',
                    showConfirmButton: false,
                    timer: 1500
                    })
                    console.log(respuesta);
                    
                    this.cerrarModal();
                    this.limpiarForm();
                    this.listarActividades();
                }
            )
            .catch(error => console.log(error));
        },
        actualizarActividad(){
            this.costoReal = this.costoEstimado;
            axios.put("/actividad/"+this.idActiTemp,{
                'idProyecto':this.idProyecto,
                'nombre': this.nombre,
                'descripcion':this.descripcion,
                'avance':this.avance,
                'costoEstimado':this.costoEstimado,
                'costoReal':this.costoReal,
                'inicio':this.inicio,
                'fin': this.fin,
                'idEstatus': this.idEstatus,
            }).then(
                respuesta =>{
                    Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Actividad Actualizada',
                    showConfirmButton: false,
                    timer: 1500
                    })

                    this.cerrarModal();
                    this.limpiarForm();
                    this.listarActividades();
                }
            )
            .catch(error => console.log(error));
        },
        eliminarActividad(idActi){
            Swal.fire({
                title: 'Desea eliminar esta actividad?',
                text: "No se podra revertir esta accion",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, eliminala!',
                cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.value) {
                        axios.delete("/actividad/"+idActi)
                        .then((respuesta) => {
                            this.listarActividades();
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'Se ha eliminado la actividad correctamente',
                                showConfirmButton: false,
                                timer: 1500
                                })
                        })
                        .catch(error => {
                            Swal.fire({
                            position: 'center',
                            type: 'error',
                            title: 'No se pudo eliminar la actividad',
                            showConfirmButton: true
                            })
                            console.log(error)
                        });
                    }
            })
        },
        validarForm(){
            if(this.nombre.length != 0 &&
            this.descripcion.length !=0 &&
            this.costoEstimado.length !=0 &&
            this.inicio.length != 0 &&
            this.fin.length != 0 &&
            this.idEstatus != 0 &&
            this.avance.length != 0){

                if($(".is-invalid").length == 0){
                    $("#btnGuardar").removeAttr('disabled');
                    $("#btnActualizar").removeAttr('disabled');
                }else{
                    $("#btnGuardar").attr('disabled','disabled');
                    $("#btnActualizar").attr('disabled','disabled');
                }
            }else{
                $("#btnGuardar").attr('disabled','disabled');
                $("#btnActualizar").attr('disabled','disabled');
            }
        },
        validarString(input){
            if(input.target.value != ""){
                $(input.target).removeClass("is-invalid");
                $(input.target).addClass("is-valid");
                $(input.target).next().css("display","none");
                
            }else{
                $(input.target).removeClass("is-valid");
                $(input.target).addClass("is-invalid");
                $(input.target).next().css("display","block");
                
            }
            this.validarForm()
        },
        validarNumber(input, param = 0){
            let valor = -1;
            if(input.target.value == ''){
                $(input.target).removeClass("is-valid");
                $(input.target).addClass("is-invalid");
                $(input.target).next().css("display","block").text("Por favor ingrese un numero");
                return;
            }
            try {
               valor = Number(input.target.value);
            } catch (error) {
                console.log(error);
                $(input.target).next().css("display","block").text("Por favor ingrese un número");
                return;
            }
            if(param == 1){
                if(valor >= 0 && valor <= 100){
                    $(input.target).removeClass("is-invalid");
                    $(input.target).addClass("is-valid");
                    $(input.target).next().css("display","none");
                }else{
                    $(input.target).removeClass("is-valid");
                    $(input.target).addClass("is-invalid");
                    $(input.target).next().css("display","block").text("El numero no puede ser menor que 0 o mayor que 100");
                }
            }else{
                if(valor >= 0){
                     $(input.target).removeClass("is-invalid");
                    $(input.target).addClass("is-valid");
                    $(input.target).next().css("display","none");
                }else{
                    $(input.target).removeClass("is-valid");
                    $(input.target).addClass("is-invalid");
                    $(input.target).next().css("display","block").text("El número no puede ser negativo");
                }
            }
            this.validarForm();
        },
        validarStatus(input){
            if(this.idEstatus != 0){
                this.successInput(input);
            }else{
                this.errorInput(input, 'Por favor escoga un estatus');
            }
            this.validarForm();
        },
        validarFecha(input){
            let f1, f2;
            if(input.target.value != ''){
                if(this.inicio != '' && this.fin != ''){
                    try {
                        f1  = new Date (this.inicio);
                    } catch (error) {
                        this.errorInput(input,'Fecha Inicio Invalida');
                        return;
                    }
                    try {
                        f2 = new Date(this.fin);
                    } catch (error) {
                        this.errorInput(input,'Fecha Fin Invalida');
                        return;
                    }
                    
                    if(f2 >= f1){
                        this.successInput(input);
                    }else{
                        this.errorInput(input, 'la fecha fin debe ser posterior a la fecha inicio');
                    }
                }else{
                    if(this.inicio != ''){
                        try {
                            f1  = new Date (this.inicio);
                            this.successInput(input);
                        } catch (error) {
                            this.errorInput(input,'Fecha Invalida');
                        }
                        
                    }

                }
            }else{
                this.errorInput(input, "Por favor escoga una fecha valida");
            }
            this.validarForm();
        },
        errorInput(input, mensaje){
            $(input.target).removeClass("is-valid");
            $(input.target).addClass("is-invalid");
            $(input.target).next().css("display","block").text(mensaje);
        },
        successInput(input){
            $(input.target).removeClass("is-invalid");
            $(input.target).addClass("is-valid");
            $(input.target).next().css("display","none");
        },
        limpiarForm(){
            $("#formActividad input, select").removeClass("is-valid");
            $("#formActividad input, select").removeClass("is-invalid");
        },
        cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre= '';
                this.descripcion= '';
                this.avance= 0;
                this.costoEstimado= 0;
                this.costoReal= 0;
                this.inicio= '';
                this.fin= '';
                this.idActiTemp= 0;

                this.limpiarForm();
        },
        abrirModal(modelo,accion,actividad = []){
                switch(modelo){
                    case "actividad":
                    {
                        switch(accion)
                        {
                            case "registrar":
                            {
                                this.modal = 1;
                                this.tipoAccion = 1;
                                this.tituloModal = 'Registrar Actividad';
                                this.nombre= '';
                                this.descripcion= '';
                                this.avance= 0;
                                this.costoEstimado= 0;
                                this.costoReal= 0;
                                this.inicio= '';
                                this.fin= '';
                                this.idEstatus = 0,
                                this.idActiTemp = 0;
                                break;
                            }
                            case "actualizar":
                            {
                                this.modal = 1;
                                this.tipoAccion = 2;
                                this.tituloModal = 'Actualizar Actividad';
                                 this.nombre= actividad.nombre;
                                this.descripcion= actividad.descripcion;
                                this.avance= actividad.avance;
                                this.costoEstimado= actividad.costoEstimado;
                                this.costoReal = actividad.costoReal;
                                this.inicio= actividad.inicio;
                                this.fin= actividad.fin;
                                this.idActiTemp = actividad.id;
                                this.idEstatus = actividad.idEstatus;
                                this.limpiarForm();
                                break;
                            }
                        }
                    }
                }
                //this.selectEncargado();
            }
    },
    mounted() {
      this.listarActividades() 
    },
    
}
</script>

