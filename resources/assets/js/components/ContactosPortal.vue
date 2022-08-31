import axios from 'axios';
<template>
    <div class="container" style="background-color:#1abc9c; border: indigo 5px solid;">
        <h1 align="center" style="color:white;">CONTACTOS</h1>
        <hr>
        <div v-if="listaContacto.length === 0">
            <h3>No hay contactos disponibles</h3>
        </div>
        <div v-else>
            <div class="card-rows">
                <div class="card" v-for="contacto in listaContacto" :key="contacto.id">   
                    <div class="card-body">
                        <div style="align=center;" >
                           <b>Nombre  :</b> <label v-text="contacto.nombre"></label><br/>
                           <b>E-mail  :</b> <label v-text="contacto.email"></label><br/>
                           <b>Teléfono:</b> <label v-text="contacto.telefono"></label><br/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
    </div>
</template>
<script>
export default {
    data(){
        return {
            listaContacto: [],
            nombre: "",
            descripcion: "",
            telefono: "0",
            id: 0
        }
    },
    methods: {
        listarContactos(){
            let me = this;
            axios.get("/getcontactos").then(function (response) {
                var respuesta = response.data;
                me.listaContacto = respuesta.contactos.data;
            }).catch(function (error) {
                console.log(error)
            });
        },
        
      
    }, 
    mounted() {
        this.listarContactos();
    }
}
</script>
