<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CrudLayout from '@/Layouts/CrudLayout.vue';

defineProps({
    repos: {
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
            :resources="repos"
            title="Repos"
            :columns="[
                {id: 1, field: 'title', label: 'Título'},
                {id: 2, field: 'tags', label: 'Tags'},
            ]"
            :acciones="['editar']"
            :tags = "tags"
            :permitir_filtros="false"
        >
        </CrudLayout>
    </AuthenticatedLayout>
</template>