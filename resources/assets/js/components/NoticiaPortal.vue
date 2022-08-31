import axios from 'axios';
<template>
    <div class="container">
        <h1 v-if="listaNoticia.length === 0">No Hay Noticias disponibles</h1>
    <h1 v-else>Noticias</h1>
    <hr>
    <div class="card-columns">
    <div class="card my-3" v-for="noticia in listaNoticia">
        <div class="card">
            <img  v-if="noticia.imagen" class="card-img-top" v-bind:src="noticia.imagen" alt="">
            <img  v-else class="card-img-top" src="img/noticiaSinFoto.jpg" alt="">
        <div class="card-title">
            <h2 class="card-title" v-text="noticia.titulo"></h2>
        </div>
        <div class="card-body">
            <h6>FECHA:</h6>
            <hr>
            <p>{{noticia.fecha_noticia | fecha}}</p>
            <hr>
            <p class="card-text" v-text="noticia.cuerpo">
            </p>
        </div>
        <div class="card-footer">
            <footer>
            <h6>Escrito por</h6>
            <hr>
            <p v-text="noticia.autor" ></p>
            </footer>
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
            listaNoticia: [],
        
        }
    },
    methods: {
        listarNoticias(){
            let me = this;
            axios.get("/get-noticias").then( (respuesta) =>{
                    me.listaNoticia = respuesta.data;
            }).catch( (error) => console.log(error));
        },
        
      
    }, 
    mounted() {
        this.listarNoticias();
    }
}
</script>