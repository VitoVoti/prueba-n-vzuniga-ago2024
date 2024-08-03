<script setup>
import Login from '@/Components/Auth/Login.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <GuestLayout>
        <Login :canResetPassword="canResetPassword" :status="status" />
    </GuestLayout>
</template>