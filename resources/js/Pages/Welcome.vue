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

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

</script>

<template>
    <Head title="Neering - Bienvenido/a" />
        <GuestLayout>
            <div class="flex flex-col w-full p-5 md:p-0 md:max-w-[500px] justify-center">
                    <!-- SVG neering logo -->
                    <div class="w-[50px] h-[50px]">
                        <img
                            src="../../../resources/images/neering_logo_no_text.svg"
                            class="w-full h-auto"
                        />
                        
                        
                    </div>
                    <div>
                        <h1 class="text-h1">¡Bienvenidos a Neering!</h1>
                        <p>Desarrolla esta plataforma a la perfección</p>
                    </div>
                    <div v-if="$page.props.auth.user == null">
                        <Tabs value="0">
                            <TabList>
                                <Tab value="0">Iniciar Sesión</Tab>
                                <Tab value="1">Registrarse</Tab>
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
                                Log Out
                            </Button>
                        </Link>
                    </div>
            </div>
                    
        </GuestLayout>
</template>
