<script setup>
import ModalEditar from '@/Components/Layout/ModalEditar.vue';
import ModalEliminar from '@/Components/Layout/ModalEliminar.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import uFuzzy from '@leeoniya/ufuzzy';
import FiltrosDeCrudLayout from '@/Components/Layout/FiltrosDeCrudLayout.vue';

const props = defineProps({
    title: String,
    resources: Array,
    columns: Array,
    tags: Array,
    acciones: Array
})

const resources_actual = ref(props.resources);
console.log("resources_actual.value es", props.resources);

// Variables
const elemento_a_editar = ref(null);
const elemento_a_eliminar = ref(null);
const comenzar_a_mostrar_modal_editar = ref(false);
const comenzar_a_mostrar_modal_eliminar = ref(false);

// Metodos
const mostrarModalEditar = (elemento) => {
    comenzar_a_mostrar_modal_editar.value = false;
    elemento_a_editar.value = elemento;
    comenzar_a_mostrar_modal_editar.value = true;
}

const mostrarModalEliminar = (elemento) => {
    comenzar_a_mostrar_modal_eliminar.value = false;
    elemento_a_eliminar.value = elemento;
    comenzar_a_mostrar_modal_eliminar.value = true;
}

function filtrar(filtros){
    console.log("filtrar, filtros es", filtros);

    let elementos_filtrados = props.resources

    // Por nombre, usamos fuzzy matching con uFuzzy
    if(filtros["nombre"]){
        let campo_a_filtrar = "name"
        if(props.title == "Articulos"){
            campo_a_filtrar = "title"
        }
        let todos_los_nombres = elementos_filtrados.map(r => r[campo_a_filtrar]);
        let ufuzzy = new uFuzzy({});
        // pre-filter
        let [idxs, info, order] = ufuzzy.search(todos_los_nombres, filtros["nombre"], 0, 1e3)

        console.log("idxs es", idxs, "info es", info, "order es", order);
    }

    // Filtro por fechas, son strings en formayo YYYY-MM-DD por lo que filtramos alfabeticamente

    if(filtros["fecha_inicio"]){
        elementos_filtrados = elementos_filtrados.filter(elemento => {
            e["created_at"] >= filtros["fecha_inicio"]
        })
    }
    if(filtros["fecha_fin"]){
        elementos_filtrados = elementos_filtrados.filter(elemento => {
            e["created_at"] <= filtros["fecha_fin"]
        })
    }

    // Finalmente filtramos por los que tengan los tags seleccionados
    if(filtros["tags"]){
        elementos_filtrados = elementos_filtrados.filter(elemento => {
            return elemento.tags.some(tag => filtros["tags"].includes(tag.id))
        })
    }

    props.resources.value = elementos_filtrados
}

</script>

<template>
    <Head :title="title" />
    <div class="flex flex-col">

        <div class="mb-4 flex justify-between">
            <h1 class="text-3xl font-bold">Listado de {{ title }}</h1>
            <div class="flex">
                <Panel header="Filtros" toggleable collapsed>
                    <FiltrosDeCrudLayout :tags="tags" v-on:cambio-en-filtros="filtrar" />
                </Panel>
                
                <Button link class="ms-2">Agregar</Button>
            </div>
        </div>

        <DataTable :value="resources_actual" :paginator="true" :rows="10">
            <Column 
                v-for="c in props.columns"
                :key="c.field"
                :field="c.field"
                :header="c.label"
            >
                <template #body="slotProps">
                    <span v-if="c.field == 'tags'">
                        <span v-for="tag in slotProps.data.tags" :key="tag.id" class="inline-block bg-yellow-200 rounded-xl px-3 py-1 text-sm mr-2 mb-2">
                            {{ tag.name }}
                        </span>
                    </span>
                    <span v-else>
                        {{ slotProps.data[c.field] }}
                    </span>
                </template>    
            </Column>
            <Column header="Acciones">
                <template #body="slotProps">
                    <div class="flex flex-row gap-x-2">
                        <Button v-if="acciones.includes('editar')" type="button" severity="primary" rounded @click="mostrarModalEditar(slotProps.data)" icon="pi pi-pencil" aria-label="Editar">
                        </Button>
                        <Button v-if="acciones.includes('eliminar')" type="button" severity="danger" rounded @click="mostrarModalEliminar(slotProps.data)" icon="pi pi-trash" aria-label="Eliminar">
                        </Button>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>

    <div v-if="comenzar_a_mostrar_modal_editar" >
        <ModalEditar 
            :elemento_a_editar="elemento_a_editar" 
            :tipo_de_elemento="title" 
            v-on:cerrar-modal-editar="comenzar_a_mostrar_modal_editar = false"
            :tags_existentes="tags"
        />
    </div>

    <div v-if="comenzar_a_mostrar_modal_eliminar" >
        <ModalEliminar :show="comenzar_a_mostrar_modal_eliminar" :elemento_a_eliminar="elemento_a_eliminar" :tipo_de_elemento="title"></ModalEliminar>
    </div>
</template>

