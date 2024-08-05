<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-4 text-sm text-gray-600">
            Gracias por registrarse. En breve le llegará un correo para confirmar su cuenta. Si en unos minutos no llega, por favor haga click abajo en "Reenviar"
        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
            Un nuevo link de verificación ha sido enviado a su correo
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex flex-col gap-y-4 items-center justify-between">
                <Button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reenviar
                </Button>

                <Link
                    :href="route('logout')"
                    method="post"
                >
                    <Button severity="secondary" class="w-full">
                        Log Out
                    </Button>
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
