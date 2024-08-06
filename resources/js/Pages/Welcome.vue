<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Login from '../Components/Auth/Login.vue';
import Register from '../Components/Auth/Register.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Boton from '@/Components/Layout/Boton.vue';
import TabList from 'primevue/tablist';
import Tabs from 'primevue/tabs';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import LogoEmpresa from '@/Components/Layout/LogoEmpresa.vue';
import TituloPrincipal from '@/Components/Layout/TituloPrincipal.vue';
import { ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const current_tab = ref('0');

</script>

<template>
    <Head title="Neering - Bienvenido/a" />
        <GuestLayout>
            <div class="flex flex-col w-full p-5 gap-y-3 md:p-0 md:max-w-[400px] justify-center">
                    <!-- SVG neering logo -->
                    <TituloPrincipal class="hidden md:flex"/>
                    <div v-if="$page.props.auth.user == null">
                        <Tabs v-model:value="current_tab">
                            <TabList class="border-0">
                                <Tab value="0" :class="{ '!text-black !font-bold': current_tab === '0'}">Iniciar Sesión</Tab>
                                <Tab value="1" :class="{ '!text-black !font-bold': current_tab === '1'}">Registrarse</Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <Login :canResetPassword="true"/>
                                </TabPanel>
                                
                                <TabPanel value="1">
                                    <Register />
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>
                    <div v-else class="mt-4 flex flex-col gap-y-4">
                        <h4>Bienvenido de vuelta, {{ $page.props.auth.user.name }}</h4>
                        <Link :href="route('dashboard')">
                            <Button severity="primary" class="w-full">
                                Ir al Dashboard
                            </Button>
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            >
                            <Button severity="secondary" class="w-full">
                                Cerrar Sesión
                            </Button>
                        </Link>
                    </div>
            </div>
                    
        </GuestLayout>
</template>
