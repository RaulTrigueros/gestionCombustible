import axios from 'axios';
<template>
    <div class="container">
        <h1>ANUNCIOS</h1>
        <hr>
        <h3 v-if="listaAnuncio.length === 0">No Hay anuncios disponibles</h3>
    <h3 v-else></h3>
    
    <div class="card-columns">
    <div class="card" v-for="(anuncio, index) in listaAnuncio" :key="anuncio.id" v-bind:class="[index + 1 % 2 == 0 ? 'p-3':'' ]">
        <img v-if="anuncio.imagen" class="card-img-top" v-bind:src="anuncio.imagen" alt="">
        <img v-else class="card-img-top" src="img/competencia.png" alt="">
        <div class="card-title">
            <h3 class="card-title" v-text="anuncio.titulo"></h3>
            <h6 >{{anuncio.fecha_anuncio | fecha}}</h6>
        </div>
        <div class="card-body">
            <p class="card-text" v-text="anuncio.descripcion">
            </p>
        </div>
        <div class="card-footer" align="right">
             Autor:
            <p class="card-footer" v-text="anuncio.autor" align="right"></p>
            
        </div>
    </div>
    </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            listaAnuncio: [],
            titulo: "",
            autor: "",
            descripcion: "",
            fecha_anuncio: "",
            imagen: "",
            id: 0
        }
    },
    methods: {
        listarAnuncios(){
           // let me = this;
            axios.get("/get-anuncios").then( (respuesta) =>{
                    this.listaAnuncio = respuesta.data;
            }).catch( (error) => console.log(error));
        },
        
      
    }, 
    mounted() {
        this.listarAnuncios();
    }
}
</script>
