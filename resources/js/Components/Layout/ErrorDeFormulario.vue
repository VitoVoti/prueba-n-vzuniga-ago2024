<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class="bg-red-400 flex items-center p-2">
        <span class="pi pi-exclamation-circle mr-2 text-white"></span>
        <span class="text-sm text-white">
            {{ message }}
        </span>
    </div>
</template>
