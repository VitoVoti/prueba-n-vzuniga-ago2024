<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="
            bg-gray-100 
            border-gray-100
            focus:border-fuchsia-800
            focus:ring-fuchsia-800
            rounded-0
            shadow-sm
            p-2
            h-12
            my-1
        "
        v-model="model"
        ref="input"
    />
</template>
