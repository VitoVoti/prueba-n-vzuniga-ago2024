<script setup>
import { ref } from 'vue';
const emit = defineEmits(['cambio-en-filtros']);
import InputDeTexto from '@/Components/Layout/InputDeTexto.vue';

const props = defineProps({
    tags: Array,
})

const filtros_actuales = ref({
    tags: [],
    nombre: "",
    fecha_inicio: "",
    fecha_fin: "",
});

</script>

<template>
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-full md:col-span-6">
            <label for="nombre" class="sr-only">Buscar por nombre</label>
            <InputDeTexto
                id="nombre"
                type="text"
                class="mt-1 block w-full"
                placeholder="Buscar por nombre"
                v-model="filtros_actuales.nombre"
                @keyup.enter="emit('cambio-en-filtros', filtros_actuales)"
            />
        </div>

        <div class="col-span-full flex flex-col gap-y-1">
            <label for="tags">Tags</label>
            <div class="flex flex-wrap gap-4 justify-start">
                <div
                    v-for="tag of props.tags" 
                    :key="'tag_' + tag.id" 
                    class="flex items-center"
                >
                            
                    <label :for="tag.key">
                        <Checkbox v-model="filtros_actuales['tags']" :inputId="tag.key" name="tags" :value="tag.id" class="mr-2" />
                        {{ tag.name }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-span-full md:col-span-6">
            <label for="fecha_inicio">Fecha de inicio</label>
            <InputDeTexto
                id="fecha_inicio"
                type="date"
                class="mt-1 block w-full"
                placeholder="Fecha de inicio"
                v-model="filtros_actuales.fecha_inicio"
                
            />
        </div>

        <div class="col-span-full md:col-span-6">
            <label for="fecha_fin">Fecha de fin</label>
            <InputDeTexto
                id="fecha_fin"
                type="date"
                class="mt-1 block w-full"
                placeholder="Fecha de fin"
                v-model="filtros_actuales.fecha_fin"
                
            />
        </div>

        <div class="col-span-full md:col-span-6">
            <Button
                type="button"
                @click="emit('cambio-en-filtros', filtros_actuales)"
            >
                Filtrar
            </Button>
        </div>
    </div>

</template>