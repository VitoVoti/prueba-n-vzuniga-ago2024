<script setup>
import ErrorDeFormulario from '@/Components/Layout/ErrorDeFormulario.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ModalBreeze from '@/Components/ModalBreeze.vue';
import InputDeTexto from '@/Components/Layout/InputDeTexto.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Eliminar cuenta</h2>

            <p class="mt-1 text-sm text-gray-600">
                Esta acción no se puede deshacer.
            </p>
        </header>

        <Button severity="danger" @click="confirmUserDeletion">Eliminar mi cuenta</Button>

        <ModalBreeze :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Está seguro/a que desea eliminar su cuenta?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Ingrese su contraseña para confirmar la eliminación.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <InputDeTexto
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Contraseña"
                        @keyup.enter="deleteUser"

                    />

                    <ErrorDeFormulario :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <Button severity="secondary" outlined @click="closeModal"> Cancelar </Button>

                    <Button
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                        severity="danger"
                    >
                        Eliminar mi cuenta
                    </Button>
                </div>
            </div>
        </ModalBreeze>
    </section>
</template>
