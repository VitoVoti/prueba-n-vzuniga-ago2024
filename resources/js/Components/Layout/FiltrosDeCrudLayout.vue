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
    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-3 sm:col-span-2">
            <label for="nombre" class="sr-only">Buscar por nombre</label>
            <InputDeTexto
                id="nombre"
                type="text"
                class="mt-1 block w-full"
                placeholder="Buscar por nombre"
                v-model="filtros_actuales.nombre"
                
            />
        </div>

        <div class="col-span-3 sm:col-span-1">
            <label for="tags" class="sr-only">Tags</label>
            <Select
                id="tags"
                v-model="filtros_actuales.tags"
                multiple
                class="mt-1 block w-full"
                placeholder="Tags"
                
            >
                <option
                    v-for="tag in props.tags"
                    :key="tag.id"
                    :value="tag.id"
                >
                    {{ tag.name }}
                </option>
            </Select>
        </div>

        <div class="col-span-3 sm:col-span-1">
            <label for="fecha_inicio" class="sr-only">Fecha de inicio</label>
            <InputDeTexto
                id="fecha_inicio"
                type="date"
                class="mt-1 block w-full"
                placeholder="Fecha de inicio"
                v-model="filtros_actuales.fecha_inicio"
                
            />
        </div>

        <div class="col-span-3 sm:col-span-1">
            <label for="fecha_fin" class="sr-only">Fecha de fin</label>
            <InputDeTexto
                id="fecha_fin"
                type="date"
                class="mt-1 block w-full"
                placeholder="Fecha de fin"
                v-model="filtros_actuales.fecha_fin"
                
            />
        </div>

        <div class="col-span-3 sm:col-span-1">
            <Button
                type="button"
                @click="emit('cambio-en-filtros', filtros_actuales)"
            >
                Filtrar
            </Button>
        </div>
    </div>

</template>