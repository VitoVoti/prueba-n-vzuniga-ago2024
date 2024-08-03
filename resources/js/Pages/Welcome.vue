<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Login from '../Components/Auth/Login.vue';
import Register from '../Components/Auth/Register.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

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
                    <div>
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
            </div>
                    
        </GuestLayout>
</template>
