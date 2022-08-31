<template>
    <div v-if="imagenes.length > 0">
        <div id="carruselPortal" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li
                    data-target="#carruselPortal"
                    :data-slide-to="index"
                    v-for="(imagen, index) in imagenes"
                    :key="imagen.id"
                    :class="{ active: index == 0 }"
                ></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div
                    class="carousel-item "
                    :class="{ active: index == 0 }"
                    v-for="(imagen, index) in imagenes"
                    :key="imagen.id"
                >
                    <a
                        data-fancybox="carrusel"
                        :href="'/imagen_carrusel/img/' + imagen.imagen"
                        :data-caption="imagen.titulo +': '+ imagen.descripcion"
                    >
                        <img
                            class="d-block w-100"
                            data-src=""
                            alt="First slide [900x300]"
                            :src="'/imagen_carrusel/img/' + imagen.imagen"
                            data-holder-rendered="true"
                            width="100%"
                            height="auto"
                        />
                    </a>
                    <div
                        class="carousel-caption d-none d-md-block"
                        style="background: rgba(0,0,0,0.75); right: 0; left: 0"
                    >
                        <h3 v-text="imagen.titulo"></h3>
                        <p v-text="imagen.descripcion"></p>
                    </div>
                </div>
            </div>
            <a
                class="carousel-control-prev"
                href="#carruselPortal"
                role="button"
                data-slide="prev"
            >
                <span
                    class="carousel-control-prev-icon"
                    aria-hidden="true"
                ></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a
                class="carousel-control-next"
                href="#carruselPortal"
                role="button"
                data-slide="next"
            >
                <span
                    class="carousel-control-next-icon"
                    aria-hidden="true"
                ></span>
                <span class="sr-only">Siguiente</span>
            </a>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    data() {
        return {
            imagenes: [],
            carrusel: ""
        };
    },
    methods: {
        getImagenes() {
            const me = this;
            axios
                .get("/carrusel/1")
                .then(res => {
                    me.carrusel = res.data;
                    axios
                        .get(`/carrusel/${me.carrusel.id}/imagenes`)
                        .then(res => {
                            me.imagenes = res.data;
                        })
                        .catch(error => error);
                })
                .catch(error => error);
        }
    },
    mounted() {
        this.getImagenes();
    }
};
</script>
