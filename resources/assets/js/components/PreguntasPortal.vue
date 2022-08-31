import axios from 'axios';
<template>
    <div class="container" style="background-color:#1abc9c; border: indigo 5px solid;">
        <h1 align="center" style="color:white;">PREGUNTAS FRECUENTES</h1>
        <hr>
        <div v-if="listaPregunta.length === 0">
            <h3>No hay preguntas frecuentes disponibles</h3>
        </div>
        <div v-else>
            <div class="card-rows">
                <div class="card" v-for="pregunta in listaPregunta" :key="pregunta.id">   
                    <div class="card-body">
                        <div style="align=center;" >
                           <b><label v-text="pregunta.pregunta"></label></b><br/>
                           <label v-text="pregunta.respuesta"></label><br/>
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
            listaPregunta: []
        }
    },
    methods: {
        listarPreguntas(){
            let me = this;
            axios.get("/getallpreguntas").then(function (response) {
                var respuesta = response.data;
                me.listaPregunta = respuesta.preguntas;
            }).catch(function (error) {
                console.log(error)
            });
        },
        
      
    }, 
    mounted() {
        this.listarPreguntas();
    }
}
</script>
