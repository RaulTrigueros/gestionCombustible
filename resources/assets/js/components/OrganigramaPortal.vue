import axios from 'axios';
<template>
    <div class="container">
        <h1>ESTRUCTURA ORGANIZACIONAL</h1>
        <hr>
        <h3 v-if="listaOrganigrama.length === 0">No Hay Organigramas disponibles</h3>
    <h3 v-else></h3>
    
    <div >
    <div class="card" v-for="(organigrama, index) in listaOrganigrama" :key="organigrama.id" v-bind:class="[index + 1 % 2 == 0 ? 'p-3':'' ]">
        <img v-if="organigrama.imagen" class="card-img-top" v-bind:src="organigrama.imagen" alt="imagen de organigrama">
        <img v-else class="card-img-top" src="img/logo.jpg" alt="">
        <div class="card-body">
            <p class="card-text" v-text="organigrama.descripcion">
            </p>
        </div>
    </div>
    </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            listaOrganigrama: [],
            descripcion: "",
            imagen: "",
            id: 0
        }
    },
    methods: {
        listarOrganigramas(){
           // let me = this;
            axios.get("/get-organigramas").then( (respuesta) =>{
                    this.listaOrganigrama = respuesta.data;
            }).catch( (error) => console.log(error));
        },
        
      
    }, 
    mounted() {
        this.listarOrganigramas();
    }
}
</script>


