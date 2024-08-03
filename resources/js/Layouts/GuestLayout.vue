<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:grid-row-span-3 hidden md:block">
                <img
                    id="screenshot-container"
                    src="../../../resources/images/Grupo 761@2x.jpg"
                    class="w-full h-[100vh] object-cover"
                    @error="handleImageError"
                />
            </div>
            <div class="grid-row-span-1 md:grid-row-span-3 flex flex-col pt-24 items-center">
                <slot />
            </div>
        </div>
    </div>
</template>
