<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ErrorDeFormulario from '@/Components/Layout/ErrorDeFormulario.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputDeTexto from '@/Components/Layout/InputDeTexto.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    github_username: '',
    position: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nombre" class="hidden" />

                <InputDeTexto
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nombre"
                    :class="{ 'border-1 border-red-400': form.errors['name']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" class="hidden" />

                <InputDeTexto
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                    placeholder="Email"
                    :class="{ 'border-1 border-red-400': form.errors['email']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="github_username" value="Nombre de usuario de GitHub" class="hidden" />

                <InputDeTexto
                    id="github_username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.github_username"
                    required
                    autofocus
                    autocomplete="github_username"
                    placeholder="Nombre de usuario de GitHub"
                    :class="{ 'border-1 border-red-400': form.errors['github_username']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.github_username" />
            </div>

            <div class="mt-4">
                <InputLabel for="position" value="Cargo" class="hidden" />

                <InputDeTexto
                    id="position"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.position"
                    required
                    autofocus
                    autocomplete="position"
                    placeholder="Cargo"
                    :class="{ 'border-1 border-red-400': form.errors['position']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.position" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Contraseña" class="hidden" />

                <InputDeTexto
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Contraseña"
                    :class="{ 'border-1 border-red-400': form.errors['password']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirme su contraseña" class="hidden" />

                <InputDeTexto
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirme su contraseña"
                    :class="{ 'border-1 border-red-400': form.errors['password']  }"
                />

                <ErrorDeFormulario class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">

                <Button severity="primary" type="submit" class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrarse
                </Button>
            </div>
        </form>

</template>
