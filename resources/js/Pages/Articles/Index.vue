<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CrudLayout from '@/Layouts/CrudLayout.vue';
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue';

const props = defineProps({
    articles: {
        type: Array,
    },
    tags: {
        type: Array,
    }
});

const page = usePage();
const roles = computed(() => page.props.auth.roles)

const columnas_a_usar = roles.value.includes("admin") ? [
    {id: 1, field: 'title', label: 'Título', sortable: true},
    {id: 2, field: 'body', label: 'Contenido'},
    {id: 3, field: 'tags', label: 'Tags'},
    {id: 4, field: 'user', label: 'Creador'},
    {id: 5, field: 'created_at', label: 'Creado', sortable: true},
    {id: 6, field: 'updated_at', label: 'Actualizado', sortable: true},
] :
    [
        {id: 1, field: 'title', label: 'Título', sortable: true},
        {id: 2, field: 'body', label: 'Contenido'},
        {id: 3, field: 'tags', label: 'Tags'},
        {id: 4, field: 'created_at', label: 'Creado', sortable: true},
        {id: 5, field: 'updated_at', label: 'Actualizado', sortable: true},
    ];
</script>

<template>
    <AuthenticatedLayout>
        <CrudLayout
            :resources="props.articles"
            title="Artículos"
            :columns="columnas_a_usar"
            :acciones="['editar', 'eliminar', 'crear']"
            :tags = "props.tags"
            :permitir_filtros="true"
        >
        </CrudLayout>
    </AuthenticatedLayout>
</template>