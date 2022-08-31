<template>
    <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <i class="icon-note"></i> Solicitud de Partida
                    <br />
                  </div>
                  <div class="card-body">Luego de llenar la solicitud debe de ir a retirar la información en la Alcaldía 
                  	Municipal.
                    <hr>
                    <div class="row">
                      <div class="col-md-6">
                        <form id="frmSolicitud">
                          <div class="form-group">
                            <label class="col-form-label" for="nombre">Nombre (*)</label>
                            <input class="form-control" v-model="nombre" id="nombre" type="text" maxlength="100" placeholder="Nombre completo">
                          </div>
                          <div class="form-group">
                            <label>DUI (*)</label>
                            <div class="input-group">
                              <span class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="fa fa-id-card-o"></i>
                                </span>
                              </span>
                                <input class="form-control" v-model="dui" id="dui" maxlength="10" type="text">
                            </div>
                            <small class="text-muted">ej. 03478783-5</small>
                          </div>
                          <div class="form-group">
                            <label class="col-form-label" for="email">Email</label>
                            <input class="form-control" v-model="email" id="email" type="text" maxlength="50" placeholder="Email">
                          </div>
                          <div class="form-group">
                            <label>Teléfono (*)</label>
                            <div class="input-group">
                              <span class="input-group-prepend">
                                <span class="input-group-text">
                                  <i class="fa fa-phone"></i>
                                </span>
                              </span>
                                <input class="form-control" v-model="telefono" id="telefono" maxlength="9" type="text">
                            </div>
                            <small class="text-muted">ej. 2121-5555</small>
                          </div>
                          <div class="form-group">
                            <label>Documento a solicitar (*)</label>
                            <div class="input-group">
                              <select class="form-control" v-model="idtipodocumento">
                                <option value="0">--Sin selección--</option>
                                <option v-for="tipodocumento in arrayTipodocumento" :key="tipodocumento.id" :value="tipodocumento.id" v-text="tipodocumento.nombre">
                                </option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-form-label" for="anombrede">A nombre de (*)</label>
                            <input class="form-control" v-model="anombrede" id="nombre" type="text" maxlength="100" placeholder="Nombre registrado en la partida">
                          </div>
                          <div class="form-group">
                            <label>Fecha en que se realizó (*)</label>
                            <div class="input-group">
                              <input type="date" v-model="fecha_realizado"  class="form-control" placeholder="Fecha de la partida">
                            </div>
                          </div>
                          <div v-show="errorSolicitudPartida" class="form-group div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjSolicitudPartida" :key="error" v-text="error">
                                    </div>
                                </div>
                          </div>
                          <div class="form-group">
                              <button class="btn btn-primary" type="button" name="enviar" @click="registrarSolicitudPartida()">Enviar</button>
                          </div>
                        </form>
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    
</template>

<script>
import axios from 'axios'

    export default{
         data(){
            return{
                solicitudDocumento_id: 0,
                idtipodocumento:0,
                nombre: '',
                dui: '',
                email: '',
                telefono: '',
                anombrede: '',
                fecha_realizado: '',
                errorSolicitudPartida: 0,
                errorMostrarMsjSolicitudPartida: [],
                arrayTipodocumento:[]
            };
        },
         methods : {
             selectTipodocumento(){
                let me = this;
                var url = '/tipodocumento/selectTipodocumento';
                axios.get(url).then(function (response) {
                   var respuesta = response.data;
                   me.arrayTipodocumento = respuesta.tipodocumentos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarSolicitudPartida(){

                if (this.validarSolicitudPartida()){
                   return;
                }
                
                let me = this;
                axios.post('/solicitudpartida/registrar',{
                    'nombre': this.nombre,
                    'dui':this.dui,
                    'email': this.email,
                    'telefono': this.telefono,
                    'anombrede': this.anombrede,
                    'fecha_realizado': this.fecha_realizado,
                    'idtipodocumento': this.idtipodocumento
                    
                }).then(function(response){
                    me.limpiarDatos();
                    me.mensajeExito();
                }).catch(function(error){
                    console.log(error);
                });
                
            },
            formatoFecha(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2) 
                    month = '0' + month;
                if (day.length < 2) 
                    day = '0' + day;

                return [year, month, day].join('-');
            },
            validarSolicitudPartida(){
                
                var fechaActual = new Date();
                fechaActual = this.formatoFecha(fechaActual);

                this.errorSolicitudPartida=0;
                this.errorMostrarMsjSolicitudPartida=[];
                
                if(!this.nombre) this.errorMostrarMsjSolicitudPartida.push("Nombre es inválido");
                if(this.errorMostrarMsjSolicitudPartida.length) this.errorSolicitudPartida = 1;
                
                if(this.errorSolicitudPartida===0){
                    if(!this.dui) this.errorMostrarMsjSolicitudPartida.push("DUI es inválido");
                    if(this.errorMostrarMsjSolicitudPartida.length) this.errorSolicitudPartida = 1;
                }
                
                if(this.errorSolicitudPartida===0){
                    if(!this.telefono) this.errorMostrarMsjSolicitudPartida.push("Teléfono es inválido");
                    if(this.errorMostrarMsjSolicitudPartida.length) this.errorSolicitudPartida = 1;
                }
                
                if(this.errorSolicitudPartida===0){
                    if(this.idtipodocumento===0) this.errorMostrarMsjSolicitudPartida.push("Tipo documento seleccionado es inválido");
                    if(this.errorMostrarMsjSolicitudPartida.length) this.errorSolicitudPartida = 1;
                }
                
                if(this.errorSolicitudPartida===0){
                    if(!this.anombrede) this.errorMostrarMsjSolicitudPartida.push("Partida a nombre de, es inválido");
                    if(this.errorMostrarMsjSolicitudPartida.length) this.errorSolicitudPartida = 1;
                }
               
                if(this.errorSolicitudPartida===0){
                   if(!this.fecha_realizado) this.errorMostrarMsjSolicitudPartida.push("Fecha en que se realizó es inválido");
                   if(this.errorMostrarMsjSolicitudPartida.length) this.errorSolicitudPartida = 1;
                }
                
                if(this.errorSolicitudPartida===0){
                    if(this.fecha_realizado > fechaActual) this.errorMostrarMsjSolicitudPartida.push("Fecha en que se realizó no puede ser mayor a la actual");
                    if(this.errorMostrarMsjSolicitudPartida.length) this.errorSolicitudPartida = 1;
                }
                 
                return this.errorSolicitudPartida;
            },
            limpiarDatos(){
                this.idtipodocumento = 0;
                this.nombre = '';
                this.dui = '';
                this.email = '';
                this.telefono = '';
                this.anombrede = '';
                this.fecha_realizado='';
            },
            mensajeExito(){
                swal.fire({
                title: 'Solicitud enviada correctamente',
                type: 'success',
                showCancelButton: false,
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
                }});
            }
        },
        mounted() {
           this.selectTipodocumento();
        }             
    }
</script>


     