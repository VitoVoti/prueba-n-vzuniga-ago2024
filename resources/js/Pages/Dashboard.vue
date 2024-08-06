<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-md">
                    <div class="p-8 text-gray-900">¡Bienvenido/a! Seleccione una de las opciones en el panel.</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
