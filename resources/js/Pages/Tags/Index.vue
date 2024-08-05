<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CrudLayout from '@/Layouts/CrudLayout.vue';

defineProps({
    tags: {
        type: Array,
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <CrudLayout
            :resources="tags"
            title="Tags"
            :columns="[
                {id: 1, field: 'name', label: 'Título'},
            ]"
            :acciones="['editar', 'crear']"
        >
        </CrudLayout>
    </AuthenticatedLayout>
</template>