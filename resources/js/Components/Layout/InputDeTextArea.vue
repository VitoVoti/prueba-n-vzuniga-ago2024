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
    <textarea
        class="
            bg-gray-100
            border-transparent
            focus:border-fuchsia-500
            focus:ring-fuchsia-500
            rounded-0
            shadow-sm
        "
        v-model="model"
        ref="input"
    />
</template>
