import axios from 'axios';
<template>
<div>
    <h1 v-if="listaCompetencia.length === 0">No Hay ninguna competencia disponible</h1>
    <h1 v-else>Competencias</h1>
    <hr>
    <div class="card-columns">
    <div class="card" v-for="(competencia, index) in listaCompetencia" :key="competencia.id" v-bind:class="[index + 1 % 2 == 0 ? 'p-3':'' ]">
        <img v-if="competencia.imagen" class="card-img-top" v-bind:src="competencia.imagen" alt="">
        <img v-else class="card-img-top" src="img/competencia.png" alt="">
        <div class="card-body">
            <h4 class="card-title" v-text="competencia.nombre"></h4>
            <p class="card-text" v-text="competencia.descripcion">
            </p>
            <a href="#!" class="btn btn-primary" style="width: 100%;" data-toggle="modal" v-bind:data-target="'#modal'+competencia.id">Inscribirme</a>
        </div>

        <div class="modal" v-bind:id="'modal'+competencia.id" tabindex="-1" role="dialog" v-bind:aria-labelledby="'label'+index" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="margin-top:230px">
                <div class="modal-header">
                    <h5 class="modal-title" v-bind:id="'label'+index" v-text="'Inscribirse en '+competencia.nombre"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" v-bind:id="'f'+competencia.id">
                        <div class="form-group">
                            <label for="nombre">Nombre (*)</label>
                            <input required name="nombre" v-model="nombre" type="text" class="form-control" id="nombre" placeholder="nombre" @keyup="validarNombreApellido($event)" @blur="validarNombreApellido($event)">
                            <div class="invalid-feedback" style="display:none">
                                Ingrese un nombre
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido (*)</label>
                            <input required name="apellido" v-model="apellido" type="text" class="form-control" id="apellido" placeholder="apellido" @keyup="validarNombreApellido($event)" @blur="validarNombreApellido($event)">
                            <div class="invalid-feedback" style="display:none">
                                Ingrese un apellido
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="sexo">Sexo (*)</label>
                          <select class="form-control" name="" id="" v-model="sexo" required @change="selectChange()">
                            <option value="Femenino">Femenino</option>
                            <option value="Masculino" >Masculino</option>
                          </select>
                        </div>
                        <div class="form-group">
                            <label for="comunidad">Comunidad (*)</label>
                            <select class="form-control" name="" id="" v-model="comunidad" required @change="selectChange()">
                                <option value="Belen">Belen</option>
                                <option value="Solares">Solares</option>
                                <option value="El Socorro">El Socorro</option>
                                <option value="San Antonio">San Antonio</option>
                                <option value="San José la Loma">San José la Loma</option>
                                <option value="San Antonio la Loma">San Antonio la Loma</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="edad">Edad (*)</label>
                          <input type="number" name="" id="" class="form-control" placeholder="" aria-describedby="helpId" required v-model="edad" @keyup="validadEdad($event)">
                          <div class="invalid-feedback" style="display:none">
                                Edad invalida
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="dui">dui</label>
                          <input type="number" name="" id="" class="form-control" placeholder="numero de dui" aria-describedby="helpId" v-model="dui">
                          <div class="invalid-feedback" style="display:none">
                                Dui invalido
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="telefono">Telefono (*)</label>
                            <input required name="telefono" v-model="telefono" type="text" class="form-control" id="telefono" placeholder="ej: 70707070" @keyup="validarTelefono($event)" @blur="validarTelefono($event)">
                            <div class="invalid-feedback" style="display:none">
                                Telefono no valido
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo Electronico (*)</label>
                            <input required name="correo" v-model="correo" type="email" class="form-control" id="correo" placeholder="ejemplo@ejemplo.com" @keyup="validarCorreo($event)" @blur="validarCorreo($event)">
                            <div class="invalid-feedback" style="display:none">
                                Correo invalido
                            </div>
                        </div>
                        <small  class="form-text text-warning my-3">(*) Son campos obligatorios</small>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="inscribir(competencia.id)" disabled id="btnInscribir">Inscribir</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

    </div>

    </div>
</div>
    
</template>
<script>
export default {
    data(){
        return {
            listaCompetencia: [],
            nombre: "",
            apellido: "",
            sexo: "",
            comunidad: "",
            dui: "",
            edad: "",
            telefono: "",
            correo: "",
            idprograma: 0
        }
    },
    methods: {
        listarCompetencias(){
            let me = this;
            axios.get("/get-competencias").then( (respuesta) =>{
                    me.listaCompetencia = respuesta.data;
            }).catch( (error) => console.log(error));
        },
        limpiarForm(){
            let me = this;
            me.listaCompetencia.forEach(element => {
                document.getElementById("f"+element.id).reset();
            });
            $(".is-valid").removeClass("is-valid");
            $(".is-invalid").removeClass("is-invalid");
        },
        validarForm(){
            if(this.nombre.length != 0 &&
            this.apellido.length !=0 &&
            this.sexo.length !=0 &&
            this.comunidad.length != 0 &&
            this.edad.length != 0 &&
            this.telefono.length != 0 &&
            this.correo.length != 0){
                if(this.dui.length == 0) this.dui = 0;

                if($(".is-invalid").length == 0){
                    $("#btnInscribir").removeAttr('disabled');
                }else{
                    $("#btnInscribir").attr('disabled','disabled');
                }
            }
        },
        selectChange(){
            this.validarForm();
        },
        validadEdad(input){
            let valor = input.target.value;
            valor = Number(valor);
            if(valor > 2 && valor < 100){
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
        validarNombreApellido(input){
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
        validarTelefono(input){
            let valor = input.target.value;
            if(!isNaN(Number(valor))){
                valor = Number(valor);
                if((valor >= 20000000 && valor < 30000000) || 
                   (valor >= 60000000 && valor < 80000000)){
                    $(input.target).removeClass("is-invalid");
                    $(input.target).addClass("is-valid");
                    $(input.target).next().css("display","none");
                }else{
                    $(input.target).removeClass("is-valid");
                    $(input.target).addClass("is-invalid");
                    $(input.target).next().css("display","block");
                    $(input.target).next().text("Telefono invalido");
                }
            }else{
                $(input.target).removeClass("is-valid");
                $(input.target).addClass("is-invalid");
                $(input.target).next().css("display","block");
                $(input.target).next().text("Por favor, Solo números");
            }
            this.validarForm()
        },
        validarCorreo(input){
            let valor = input.target.value;
            let patron = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
            if(patron.test(valor)){
                $(input.target).removeClass("is-invalid");
                $(input.target).addClass("is-valid");
                $(input.target).next().css("display","none");
            }else{
                $(input.target).removeClass("is-valid");
                $(input.target).addClass("is-invalid");
                $(input.target).next().css("display","block");
                $(input.target).next().text("Correo Invalido");
            }
            this.validarForm()
        },
        cerrarModal(idmodal){
            $('#'+idmodal).modal('hide');
            $('.modal-backdrop').remove();
        },
        inscribir(id){
            let me = this;
            this.idprograma = id;

            

            axios.post("/inscribir",{
                'idprograma':this.idprograma,
                'nombre':this.nombre,
                'apellido':this.apellido,
                'telefono':this.telefono,
                'correo':this.correo,
                'edad':this.edad,
                'dui':this.dui,
                'comunidad':this.comunidad,
                'sexo':this.sexo
            }).then((respuesta) => {
                
                if(respuesta.data == 'Exito'){
                    me.nombre= "";
                    me.apellido= "";
                    me.telefono= "";
                    me.correo= "";
                    me.sexo = "";
                    me.dui = "";
                    me.comunidad = "";
                    me.edad = "";
                    me.idprograma = 0
                    me.limpiarForm();
                     $("#btnInscribir").attr('disabled','disabled');
                    me.cerrarModal('modal'+id);

                    Swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Inscripcion Exitosa',
                    showConfirmButton: false,
                    timer: 1500
                    })
                }else{
                    Swal.fire({
                    position: 'center',
                    type: 'error',
                    title: 'Ya no hay cupo',
                    showConfirmButton: true
                    })
                }
                
            })
            .catch((error) => {
                Swal.fire({
                position: 'center',
                type: 'error',
                title: 'No se pudo inscribir',
                showConfirmButton: true
                })
                console.log(error)
            });
        }
    }, 
    mounted() {
        this.listarCompetencias();
    }
}
</script>
