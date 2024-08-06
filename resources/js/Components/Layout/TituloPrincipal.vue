<script setup>
import LogoEmpresa from './LogoEmpresa.vue';
import LogoEmpresaBlanco from './LogoEmpresaBlanco.vue';

const titulo_principal = "¡Bienvenidos a Neering!";
const subtitulo_principal = "Desarrolla esta plataforma a la perfección";

const props = defineProps({
    logo_blanco: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <div class="text-white md:text-black flex flex-col items-center md:items-start gap-y-2">
                    <div class="w-[50px] h-[50px]">
                        <LogoEmpresa
                            v-if="!logo_blanco"
                            class="w-full h-auto"
                        />
                        <LogoEmpresaBlanco
                            v-else-if="logo_blanco"
                            class="w-full h-auto"
                        />
                    </div>
                    <h1 class="text-2xl">{{  titulo_principal }}</h1>
                    <p>{{ subtitulo_principal }}</p>
                    
    </div>
</template>