<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CrudLayout from '@/Layouts/CrudLayout.vue';

defineProps({
    articles: {
        type: Array,
    },
    tags: {
        type: Array,
    }
});
</script>

<template>
    <AuthenticatedLayout>
        <CrudLayout
            :resources="articles"
            title="Artículos"
            :columns="[
                {id: 1, field: 'title', label: 'Título'},
                {id: 2, field: 'body', label: 'Contenido'},
                {id: 3, field: 'tags', label: 'Tags'},
            ]"
            :acciones="['editar', 'eliminar', 'crear']"
            :tags = "tags"
        >
        </CrudLayout>
    </AuthenticatedLayout>
</template>