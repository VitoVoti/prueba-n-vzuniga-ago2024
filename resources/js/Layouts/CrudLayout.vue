<script setup>
import ModalCrearEditar from '@/Components/Layout/ModalCrearEditar.vue';
import ModalEliminar from '@/Components/Layout/ModalEliminar.vue';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import uFuzzy from '@leeoniya/ufuzzy';
import FiltrosDeCrudLayout from '@/Components/Layout/FiltrosDeCrudLayout.vue';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import axios from 'axios';
import {parseISOLocal, formatISOLocal} from '@/Helpers/date_helpers.js';
import Popover from 'primevue/popover';

const confirm = useConfirm();
const toast = useToast();

const props = defineProps({
    title: String,
    resources: Array,
    columns: Array,
    tags: Array,
    acciones: Array,
    permitir_filtros: Boolean,
})
const key_de_filtros = ref(123);
const resources_actual = ref(props.resources);

// Variables
const elemento_a_editar = ref(null);
const elemento_a_eliminar = ref(null);
const comenzar_a_mostrar_modal_editar = ref(false);
const comenzar_a_mostrar_modal_crear = ref(false);

const filtros_popover = ref(false);
const form = useForm({});


// Métodos
function cerrarModalesYReiniciarFiltros() {
    comenzar_a_mostrar_modal_crear.value = false;
    comenzar_a_mostrar_modal_editar.value = false;
    resources_actual.value = props.resources;
    key_de_filtros.value = key_de_filtros.value + 1;
}

const mostrarModalCrear = (elemento) => {
    comenzar_a_mostrar_modal_crear.value = true;
}

const mostrarModalEditar = (elemento) => {
    comenzar_a_mostrar_modal_editar.value = false;
    elemento_a_editar.value = elemento;
    comenzar_a_mostrar_modal_editar.value = true;
}

const mostrarModalEliminar = (elemento) => {
    elemento_a_eliminar.value = elemento;
    confirm_de_eliminacion();
}

function tipo_de_elemento_para_ruta(){
    if (props.title == 'Artículos') {
        return 'articles';
    }
    else if (props.title == 'Repos') {
        return 'repos';
    }
    else if (props.title == 'Tags') {
        return 'tags';
    }
}

const title_singular = () => {
    return props.title.substring(0, props.title.length - 1);
}

const toggle_filtros_popover = (event) => {
    filtros_popover.value.toggle(event);
}

function filtrar(filtros){

    let elementos_filtrados = JSON.parse(JSON.stringify(props.resources)) // [ ...props.resources ]

    // Por nombre, usamos fuzzy matching con uFuzzy
    // Ignoramos caracteres especiales
    let filtro_de_nombre = filtros["nombre"] ? filtros["nombre"].replace(/[^a-zA-Z]/g, "") : "";
    if(filtro_de_nombre){
        let campo_a_filtrar = "name"
        if(props.title == "Artículos"){
            campo_a_filtrar = "title"
        }
        let todos_los_nombres = elementos_filtrados.map(r => r[campo_a_filtrar]);
        let ufuzzy = new uFuzzy({});

        // pre-filter, idx dará los índices del array que coinciden con el string buscado
        let [idxs, info, order] = ufuzzy.search(todos_los_nombres, filtro_de_nombre, 0, 1e3)

        if(idxs.length == 0){
            elementos_filtrados = [];
        }

        elementos_filtrados = elementos_filtrados.filter((elemento, index) => idxs.includes(index))

    }

    // Filtro por fechas, son strings en formayo YYYY-MM-DD por lo que filtramos alfabeticamente
    // Primero pasamos cada fecha a ISO string desfasado a hora local
    // Para fecha fin le agregamos 1 día, para que la búsqueda incluya el día elegido.
    if(filtros["fecha_inicio"]){
        let fecha_inicio_a_usar = filtros["fecha_inicio"]
        fecha_inicio_a_usar = parseISOLocal(fecha_inicio_a_usar)
        fecha_inicio_a_usar = fecha_inicio_a_usar.toISOString();
        elementos_filtrados = elementos_filtrados.filter(elemento => {
            return elemento["created_at"] >= fecha_inicio_a_usar
        })
    }
    if(filtros["fecha_fin"]){
        let fecha_fin_a_usar = filtros["fecha_fin"]
        fecha_fin_a_usar = parseISOLocal(fecha_fin_a_usar)
        let fecha_fin_a_usar_fin_del_dia = new Date(fecha_fin_a_usar.getFullYear(), fecha_fin_a_usar.getMonth(), fecha_fin_a_usar.getDate() + 1);
        fecha_fin_a_usar_fin_del_dia = fecha_fin_a_usar_fin_del_dia.toISOString();
        elementos_filtrados = elementos_filtrados.filter(elemento => {
            return elemento["created_at"] <= fecha_fin_a_usar_fin_del_dia
        })
    }


    // Finalmente filtramos por los que tengan los tags seleccionados
    if(filtros["tags"] && filtros["tags"].length > 0){
        elementos_filtrados = elementos_filtrados.filter(elemento => {
            let ids_de_tags_de_este_elemento = elemento["tags"].map(e => e.id)
            return filtros["tags"].every(tag => ids_de_tags_de_este_elemento.includes(tag))
        })
    }

    resources_actual.value = elementos_filtrados;
    //toggle_filtros_popover(null); // Cerramos popover
}

// En documento se menciona que ciertos modelos tienen nombre en campo name y otros en title
// Por eso buscamos cual es el correcto
const nombre_de_elemento_a_eliminar = () => {
    if(elemento_a_eliminar.value){
        if(elemento_a_eliminar.value["name"]){
            return elemento_a_eliminar.value["name"];
        } else if (elemento_a_eliminar.value["title"]){
            return elemento_a_eliminar.value["title"];
        }
    }
    return "";
}



const confirm_de_eliminacion = () => {
    confirm.require({
        message: 'Seguro/a que desea eliminar este ' + title_singular() + '? (' + nombre_de_elemento_a_eliminar() + ')',
        header: 'Confirmación',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Cancelar',
        rejectProps: {
            label: 'Cancelar',
            severity: 'primary',
            outlined: true
        },
        acceptProps: {
            label: 'Eliminar',
            severity: 'danger'
        },
        accept: () => {
            form.delete(
                "/" + tipo_de_elemento_para_ruta() + '/' +  
                elemento_a_eliminar.value.id,{
                    onSuccess: () => {
                        elemento_a_eliminar.value = null;
                        toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Elemento eliminado', life: 3000 });
                        resources_actual.value = props.resources; // Actualizamos listado
                    },
                    onError: () => {
                        toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo eliminar el elemento', life: 3000 });
                    }
                }
            );
            
        },
    });
};

</script>

<template>
    <Head :title="title" />
    <div class="flex flex-col">

        <div class="mb-4 grid grid-cols-1 xl:flex justify-between gap-y-4">
            <div class="col-span-1">
                <h1 class="text-3xl font-semibold">Listado de {{ title }}</h1>
            </div>
            <div class="col-span-1 flex p-2 flex-row gap-x-2 items-start">
                <!-- Filtros -->
                <div v-if="permitir_filtros" header="Filtrar por" toggleable collapsed class="grow">
                    <Button 
                        severity="primary"
                        outlined
                        iconPos="right"
                        class="w-full lg:w-[545px]"
                        type="button"
                        icon="pi pi-chevron-down text-red-600"
                        label="Filtrar por"
                        @click="toggle_filtros_popover" 
                    />
                    <Popover ref="filtros_popover">
                        <FiltrosDeCrudLayout :tags="tags" v-on:cambio-en-filtros="filtrar" :key="key_de_filtros" />
                    </Popover>
                </div>
                <!-- Botón para agregar -->
                <Button severity="primary" class="ms-2 w-[150px] rounded-none" @click="mostrarModalCrear()" :class="{'invisible': acciones.includes('crear') == false}">
                    Agregar
                </Button>
            </div>
        </div>
        <div class="p-4 shadow-md min-h-[300px]">
            <div v-if="!resources_actual || resources_actual.length == 0"
                class="flex justify-center pt-5"
            >
                <span>No hay elementos para mostrar</span>
            </div>
            <div class="w-100vw">
            <DataTable
                v-if="resources_actual && resources_actual.length > 0"
                :value="resources_actual"
                :paginator="true"
                :rows="10"
                scrollable
                scrollHeight="flex"
                :sortField="(title == 'Artículos') ? 'updated_at' : null"
                :sortOrder="-1"
            >
                <Column 
                    v-for="c in props.columns"
                    :key="c.field"
                    :field="c.field"
                    :header="c.label"
                    :sortable="c.sortable"
                    :style="{ width: (c.field == 'body') ? '25%' : 'auto' }"
                >
                    <!-- Según sea el modelo (title) y el campo (field) será cómo lo mostramos en la tabla -->
                    <template #body="slotProps">
                        <span v-if="c.field == 'tags'">
                            <span v-for="tag in slotProps.data.tags" :key="tag.id" class="inline-block bg-yellow-200 rounded-xl px-3 py-1 text-sm mr-2 mb-2">
                                {{ tag.name }}
                            </span>
                        </span>
                        <span v-else-if="c.field == 'title' && props.title == 'Repos'">
                            <a :href="slotProps.data.url" target="_blank" class="underline">
                                {{ slotProps.data.title }}
                            </a>
                        </span>
                        <span v-else-if="c.field == 'title' && props.title == 'Artículos'">
                            <Link :href="route('articles.show', slotProps.data.id)" target="_blank" class="underline">
                                {{ slotProps.data.title }}
                            </Link>
                        </span>
                        <span v-else-if="c.field == 'user' && props.title == 'Artículos'">
                            <div class="flex items-center">
                                <img
                                    :src="'/storage/' + slotProps.data.user['profile_photo_path']"
                                    alt="profile photo"
                                    class="h-8 w-8 rounded-full object-cover mr-2"
                                />
                                {{ slotProps.data.user.name }}
                            </div>
                        </span>
                        <span v-else-if="c.field == 'created_at' || c.field == 'updated_at'">
                            {{ new Date(slotProps.data[c.field]).toLocaleString('es-ES') }}
                        </span>
                        <span v-else-if="c.field == 'body' && props.title == 'Artículos'">
                            <span class="line-clamp-3" v-html="slotProps.data.body"></span>
                        </span>
                        <span v-else>
                            {{ slotProps.data[c.field] }}
                        </span>
                    </template>    
                </Column>
                <Column header="Acciones">
                    <template #body="slotProps">
                        <div class="flex flex-row gap-x-2">
                            <Button 
                                v-if="acciones.includes('editar')" 
                                type="button" severity="primary" rounded 
                                @click="mostrarModalEditar(slotProps.data)" 
                                icon="pi pi-pencil" 
                                aria-label="Editar"
                            >
                            </Button>
                            <Button 
                                v-if="acciones.includes('eliminar')" 
                                type="button" severity="danger" rounded 
                                @click="mostrarModalEliminar(slotProps.data)" 
                                icon="pi pi-trash" 
                                aria-label="Eliminar"
                            >
                            </Button>
                        </div>
                    </template>
                </Column>
            </DataTable>
            </div>
        </div>
    </div>

    <div v-if="comenzar_a_mostrar_modal_crear" >
        <ModalCrearEditar 
            modo="CREAR"
            :tipo_de_elemento="title" 
            v-on:cerrar-modal-editar="() => { cerrarModalesYReiniciarFiltros(); }"
            :tags_existentes="tags"
        />
    </div>

    <div v-if="comenzar_a_mostrar_modal_editar" >
        <ModalCrearEditar 
            modo="EDITAR"
            :elemento_a_editar="elemento_a_editar" 
            :tipo_de_elemento="title" 
            v-on:cerrar-modal-editar="() => { cerrarModalesYReiniciarFiltros(); }"
            :tags_existentes="tags"
        />
    </div>

</template>

