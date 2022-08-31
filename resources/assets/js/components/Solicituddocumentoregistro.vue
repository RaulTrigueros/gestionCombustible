<template>
    <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <i class="icon-note"></i> Solicitud de Información
                    <br />
                  </div>
                  <div class="card-body">Luego de llenar la solicitud debe de ir a retirar la información en la Alcaldía
                  	Municipal, según el formato seleccionado.
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
                            <label class="col-form-label" for="informacion_solicitada">Información a solicitar (*)</label>
                            <textarea class="form-control" v-model="informacion_solicitada" id="informacion_solicitada" maxlength="250" rows="6"></textarea>
                          </div>
                          <div class="form-group row">
                             <label class="col-md-3 form-control-label" for="text-input">Formato (*)</label>
                             <div class="col-md-9">
                                 <select v-model="formato" class="form-control">
                                    <option value="">--Sin selección--</option>
                                    <option value="DIGITAL">DIGITAL</option>
                                    <option value="IMPRESO">IMPRESO</option>
                                </select>
                             </div>
                          </div>
                          <div v-show="errorSolicitudDocumento" class="form-group div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjSolicitudDocumento" :key="error" v-text="error">
                                    </div>
                                </div>
                          </div>
                          <div class="form-group">
                              <button class="btn btn-primary" type="button" name="enviar" @click="registrarSolicitudDocumento()">Enviar</button>
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
                nombre: '',
                dui: '',
                email: '',
                telefono: '',
                informacion_solicitada: '',
                formato: '',
                errorSolicitudDocumento: 0,
                errorMostrarMsjSolicitudDocumento: []
            };
        },
         methods : {
            registrarSolicitudDocumento(){

                if (this.validarSolicitudDocumento()){
                   return;
                }

                let me = this;
                axios.post('/solicituddocumento/registrar',{
                    'nombre': this.nombre,
                    'dui':this.dui,
                    'email': this.email,
                    'telefono': this.telefono,
                    'informacion_solicitada': this.informacion_solicitada,
                    'formato':this.formato

                }).then(function(response){
                    me.limpiarDatos();
                    me.mensajeExito();
                }).catch(function(error){
                    console.log(error);
                });

            },
            validarSolicitudDocumento(){
                this.errorSolicitudDocumento=0;
                this.errorMostrarMsjSolicitudDocumento=[];

                if(!this.nombre) this.errorMostrarMsjSolicitudDocumento.push("Nombre es inválido");
                if(this.errorMostrarMsjSolicitudDocumento.length) this.errorSolicitudDocumento = 1;

                if(this.errorSolicitudDocumento===0){
                    if(!this.dui) this.errorMostrarMsjSolicitudDocumento.push("DUI es inválido");
                    if(this.errorMostrarMsjSolicitudDocumento.length) this.errorSolicitudDocumento = 1;
                }

                if(this.errorSolicitudDocumento===0){
                    if(!this.telefono) this.errorMostrarMsjSolicitudDocumento.push("Teléfono es inválido");
                    if(this.errorMostrarMsjSolicitudDocumento.length) this.errorSolicitudDocumento = 1;
                }

                if(this.errorSolicitudDocumento===0){
                    if(!this.informacion_solicitada) this.errorMostrarMsjSolicitudDocumento.push("Información solicitada es inválido");
                    if(this.errorMostrarMsjSolicitudDocumento.length) this.errorSolicitudDocumento = 1;
                }

                if(this.errorSolicitudDocumento===0){
                    if(!this.formato) this.errorMostrarMsjSolicitudDocumento.push("Formato seleccionado es inválido");
                    if(this.errorMostrarMsjSolicitudDocumento.length) this.errorSolicitudDocumento = 1;
                }

                return this.errorSolicitudDocumento;
            },
            limpiarDatos(){
                this.nombre = '';
                this.dui = '';
                this.email = '';
                this.telefono = '';
                this.informacion_solicitada = '';
                this.formato='';
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
        }
    }
</script>


