<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CrudLayout from '@/Layouts/CrudLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    article: {
        type: Object,
    },
    tag_names: {
        type: Array,
    },
    author_name: {
        type: String,
    }
});

const fecha_formateada = computed(() => {
    const date = new Date(props.article.created_at);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('es-ES', options);
});

</script>

<template>
    <AuthenticatedLayout>
        <div class="rounded-xl p-6 shadow-lg text-center flex flex-col gap-y-4 min-h-[300px]">
            <h1 class="text-3xl">{{ article.title }}</h1>
            <p class="text-sm">Escrito por <span class="font-semibold">{{ author_name }}</span> el <span class="font-semibold">{{ fecha_formateada }}</span></p>
            <div>
                <span v-for="tag in tag_names" :key="tag" class="inline-block bg-yellow-200 rounded-xl px-3 py-1 text-sm mr-2 mb-2">
                    {{ tag }}
                </span>
            </div>
            <div>
                <p>{{ article.body }}</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>