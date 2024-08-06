<script setup>
import { ref } from 'vue';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import { useForm } from '@inertiajs/vue3';
import InputDeTexto from './InputDeTexto.vue';
import InputDeTextArea from './InputDeTextArea.vue';
import ErrorDeFormulario from './ErrorDeFormulario.vue';

const confirm = useConfirm();
const toast = useToast();

const props = defineProps({
    elemento_a_editar: {
        type: Object,
        default: {},
        required: false,
    },
    tipo_de_elemento: {
        type: String,
        default: "elemento",
        required: false,
    },
    tags_existentes: {
        type: Array,
        default: [],
        required: true,
    },
    modo: {
        type: String,
        default: "CREAR",
        required: false,
    }
});

const mostrar_modal_actual = ref(true);
const emit = defineEmits(['cerrar-modal-editar']);

function tipo_de_elemento_para_ruta(){
    if (props.tipo_de_elemento == 'Artículos') {
        return 'articles';
    }
    else if (props.tipo_de_elemento == 'Repos') {
        return 'repos';
    }
    else if (props.tipo_de_elemento == 'Tags') {
        return 'tags';
    }
}

function campos_en_formulario(){
    if (props.tipo_de_elemento == 'Artículos') {
        return [
            {id: 1, field: 'title', label: 'Título'},
            {id: 2, field: 'body', label: 'Contenido'},
            {id: 3, field: 'tags', label: 'Tags'},
        ];
    }
    else if (props.tipo_de_elemento == 'Tags') {
        return [
            {id: 1, field: 'name', label: 'Título'},
        ];
    } else if (props.tipo_de_elemento == 'Repos') {
        return [
            {id: 1, field: 'tags', label: 'Tags'},
        ];
    }
}

const formulario = useForm({
    title: props.elemento_a_editar.title ? props.elemento_a_editar.title : "",
    body: props.elemento_a_editar.body ? props.elemento_a_editar.body : "",
    name: props.elemento_a_editar.name ? props.elemento_a_editar.name : "",
    tags: props.elemento_a_editar.tags ? props.elemento_a_editar.tags.map(tag => tag.id) : [],
});

const submit = () => {
    if(props.modo == 'EDITAR') {
        
    
        formulario.patch(route(tipo_de_elemento_para_ruta() + '.update', props.elemento_a_editar.id), {
            onSuccess: () => {
                formulario.reset()
                emit('cerrar-modal-editar')
                toast.add({ severity: 'success', summary: 'Actualizado', detail: 'Se actualizó el registro correctamente', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo actualizar el registro. Verifique que todos los datos estén correctos.', life: 3000 });
            }
        });
    }

    if(props.modo == 'CREAR') {

        formulario.post(route(tipo_de_elemento_para_ruta() + '.store'), {
            onSuccess: () => {
                formulario.reset()
                emit('cerrar-modal-editar')
                toast.add({ severity: 'success', summary: 'Creado', detail: 'Se creó el registro correctamente', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Error', detail: 'No se pudo crear el registro. Verifique que todos los datos estén correctos.', life: 3000 });
            }
        });
    }
};


</script>


<template>
    <Dialog 
    v-model:visible="mostrar_modal_actual" modal :closable="false" :draggable="false"
    :style="{ width: '50vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <template #header>
            <h3 v-if="modo == 'EDITAR'" class="text-lg font-medium text-gray-900">
                Editar {{ props.elemento_a_editar.name }}
            </h3>
            <h3 v-if="modo == 'CREAR'" class="text-lg font-medium text-gray-900">
                Crear {{ (props.tipo_de_elemento && props.tipo_de_elemento.endsWith('s')) ? props.tipo_de_elemento.slice(0, -1) : props.tipo_de_elemento }}
            </h3>
        </template>
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-4">
                
                <div v-for="c in campos_en_formulario()" :key="c.id" class="flex flex-col gap-y-3">
                    <label 
                        v-if="c.field == 'tags'"    
                        :for="c.field" 
                        class="block text-sm font-medium text-gray-700"
                    >
                        {{ c.label }}
                    </label>
                    <InputDeTextArea
                        v-if="c.field == 'body'"
                        :id="c.field"
                        :name="'article.' + c.field"
                        v-model="formulario[c.field]"
                        class="w-full"
                        :class="{ 'border-1 border-red-400': formulario.errors[c.field]  }"
                        :placeholder="c.label"
                    />
                    <InputDeTexto
                        v-else-if="c.field != 'tags'"
                        :id="c.field"
                        :name="'article.' + c.field"
                        v-model="formulario[c.field]"
                        class="w-full"
                        :class="{ 'border-1 border-red-400': formulario.errors[c.field]  }"
                        :placeholder="c.label"
                    />
                    <div
                        v-else-if="c.field == 'tags'" 
                        v-for="tag of tags_existentes" 
                        :key="'tag_' + tag.id" 
                        class="flex items-center w-full"
                    >
                        
                        <label :for="tag.key">
                            <Checkbox v-model="formulario['tags']" :inputId="tag.key" name="tags" :value="tag.id" class="mr-2" />
                            {{ tag.name }}
                        </label>
                    </div>

                    <ErrorDeFormulario :message="formulario.errors[c.field]" />

                    <span v-if="c.field == 'tags' && (!tags_existentes || !tags_existentes.length)">
                        No hay tags disponibles
                    </span>
                </div>

                
                
            </div>
        </form>
        <template #footer>
            <div class="w-full flex flex-row justify-end gap-2">
                <Button label="Cancelar" outlined @click="emit('cerrar-modal-editar')" />
                <Button label="Guardar" severity="primary" @click="() => submit()" />
            </div>
        </template>
    </Dialog>
</template>