<script setup>
import { ref } from 'vue';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const confirm = useConfirm();
const toast = useToast();

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    elemento_a_eliminar: {
        type: Object,
        default: null,
        required: true,
    },
    tipo_de_elemento: {
        type: String,
        default: "elemento",
        required: false,
    },
});

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


const confirm2 = () => {
    confirm.require({
        message: 'Seguro/a que desea eliminar este ' + props.tipo_de_elemento + '?',
        header: 'Confirmación',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Cancelar',
        rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Eliminar',
            severity: 'danger'
        },
        accept: () => {
            Inertia.delete(
                route(tipo_de_elemento_para_ruta(props.tipo_de_elemento) + '.destroy', 
                props.elemento_a_eliminar.id)
            );
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Elemento eliminado', life: 3000 });
        },
    });
};
</script>


<template>
    <ConfirmDialog></ConfirmDialog>
    <form class="hidden">
        <input type="text" v-model="elemento_a_eliminar.id" />
    </form>
</template>