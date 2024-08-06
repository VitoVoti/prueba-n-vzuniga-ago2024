<script setup>
import TituloPrincipal from '@/Components/Layout/TituloPrincipal.vue';

</script>

<template>
    <div class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:grid-row-span-3">
                <img
                    src="../../../resources/images/Grupo 761@2x.jpg"
                    class="hidden md:block w-full object-cover h-[340px] md:h-[100vh]"
                />
                <img
                    src="../../../resources/images/Grupo 938@2x.png"
                    class="block md:hidden w-full object-cover h-[340px] md:h-[100vh]"
                />
                <div class="block md:hidden absolute top-[150px] w-full">
                    <TituloPrincipal class="block md:hidden flex flex-col" logo_blanco />
                </div>
            </div>
            <div class="grid-row-span-1 md:grid-row-span-3 flex flex-col pt-0 md:pt-24 items-center">
                <slot />
            </div>
        </div>
    </div>
    <Toast position="bottom-right">
        <template #message="slotProps">
            <div class="flex flex-col items-start flex-auto">
                <div class="font-medium text-lg m-4">{{ slotProps.message.detail }}</div>
            </div>
        </template>
    </Toast>
    <ConfirmDialog></ConfirmDialog>
</template>
