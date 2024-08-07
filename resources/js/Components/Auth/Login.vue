<script setup>
//import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ErrorDeFormulario from '@/Components/Layout/ErrorDeFormulario.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputDeTexto from '@/Components/Layout/InputDeTexto.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Boton from '@/Components/Layout/Boton.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" class="hidden" />

                <InputDeTexto
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="Email"
                    :class="{ 'border-1 border-red-400': form.errors['email']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" class="hidden" />

                <InputDeTexto
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Contraseña"
                    :class="{ 'border-1 border-red-400': form.errors['password']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex flex-row justify-between mt-4">
                <label class="flex items-center">
                    <Checkbox 
                        name="remember" 
                        v-model="form.remember"
                        :binary="true"
                        icon="pi pi-circle-fill"
                     />
                    <span class="ms-2 text-sm text-gray-600">Recuerdame</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    ¿Olvidó su contraseña?
                </Link>
            </div>

            <div class="flex items-center justify-end mt-4">
                

                <Button severity="primary" type="submit" :class="{ 'opacity-25': form.processing, 'w-full': true, 'btn-primary': true }" :disabled="form.processing">
                    Ingresar
                </Button>
            </div>
        </form>

</template>
