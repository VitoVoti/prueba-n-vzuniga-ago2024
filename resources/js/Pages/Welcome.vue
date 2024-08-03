<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Login from '../Components/Auth/Login.vue';
import Register from '../Components/Auth/Register.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Boton from '@/Components/Layout/Boton.vue';

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
                        <h1>Bienvenidos a Neering!</h1>
                        <p>Desarrolla esta plataforma a la perfección</p>
                    </div>
                    <div v-if="$page.props.auth.user == null">
                        <div role="tablist" class="tabs tabs-bordered">
                            <input 
                                type="radio" 
                                name="welcome_page_tabs" 
                                role="tab" 
                                class="tab mr-2" 
                                aria-label="Iniciar Sesión" 
                            />
                            <div role="tabpanel" class="tab-content p-10">
                                <Login canResetPassword="true"/>
                            </div>

                            <input
                                type="radio"
                                name="welcome_page_tabs"
                                role="tab"
                                class="tab"
                                aria-label="Registrarse"
                                checked="checked" />
                            <div role="tabpanel" class="tab-content p-10">
                                <Register />
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <h4>Bienvenido de vuelta, {{ $page.props.auth.user.name }}</h4>
                        <Link :href="route('dashboard')">
                            <Boton>
                                Ir al Dashboard
                            </Boton>
                        </Link>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >Log Out</Link
                        >
                    </div>
            </div>
                    
        </GuestLayout>
</template>
