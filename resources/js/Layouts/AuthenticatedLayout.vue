<script setup>
import { computed, ref } from 'vue';
import LogoEmpresa from '@/Components/Layout/LogoEmpresa.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const nav_items_inicial = [
    {
        Id: 1,
        label: 'Artículos',
        icon: 'pi pi-file',
        href: 'articles.index',
    },
    {
        id: 2,
        label: 'Repos',
        icon: 'pi pi-github',
        href: 'repos.index',
    },
    {
        id: 3,
        label: 'Tags',
        icon: 'pi pi-tags',
        href: 'tags.index',
    },
];

const page = usePage();
const roles = computed(() => page.props.auth.roles)

const nav_items = ref(roles.value.includes("admin") ? nav_items_inicial : nav_items_inicial.filter(item => item.id != 3));

</script>

<template>
    <div>
        <div class="min-h-screen relative">
            <nav class="bg-white border-b border-gray-100 relative z-20 bg-[#FFFFFF]">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto px-4 sm:px-6 lg:px-8 shadow-md">
                    <div class="flex md:justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')" class="flex flex-row items-center gap-x-4">
                                    <LogoEmpresa
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                    <h1 class="text-lg font-bold text-gray-800 hidden md:block">TEST NEERING</h1>
                                </Link>
                            </div>

                            
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden ml-4">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <span class="pi pi-bars"></span>
                            </button>
                        </div>

                        <h1 class="text-lg font-bold text-gray-800 inline-flex md:hidden self-center ml-6">TEST NEERING</h1>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <span v-if="$page.props.auth.user['profile_photo_path']">
                                                    <img
                                                        :src="'/storage/' + $page.props.auth.user['profile_photo_path']"
                                                        alt="profile photo"
                                                        class="h-8 w-8 rounded-full object-cover mr-2"
                                                    />
                                                </span>
                                                <span class="font-semibold">{{ $page.props.auth.user.name }}</span>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Mi Cuenta </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Cerrar Sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <!-- Navigation Links (Mobile) -->
                    <div class="pt-2 pb-3 space-y-1 flex flex-col">
                        <span v-for="item in nav_items" :key="item.id" class="">
                            <Link
                                :href="route(item.href)"
                                :active="route().current(item.href)"
                                severity="link"
                                
                            >
                                <Button link class="w-full">
                                    {{ item.label }}
                                </Button>
                            </Link>
                        </span>
                    </div>

                    <!-- Usuario -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Mi Cuenta </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Cerrar Sesión
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            
            
            <!-- Navigation Links (Desktop) -->
            <div class="px-4 py-6 pt-[100px] shadow-md w-[200px] hidden sm:flex absolute top-0 left-0 z-10 h-full grow flex-col justify-between">
                    <Menu :model="nav_items" unstyled class="h-full">
                        <template #item="{ item, props }">
                            <Link 
                                class="flex items-center p-4 gap-x-6 transition duration-150 ease-in-out group"
                                :class="{'text-prueba-n-menu-icons': route().current(item.href) }"
                                :href="route(item.href)"
                            >   
                                <div 
                                    class="rounded-full p-2 h-8 w-8 flex items-center justify-center text-gray-400 group-hover:text-white group-hover:bg-prueba-n-menu-icons"
                                    :class="{ 'bg-prueba-n-menu-icons': route().current(item.href) }"
                                >
                                    <span 
                                        class=" flex items-center justify-center" 
                                        :class="{ 'text-white': route().current(item.href) }"
                                    >
                                        <span style="font-size: 1.2rem;" :class="item.icon" />
                                    </span>
                                </div>
                                <span :class="{ 'font-bold': route().current(item.href), 'text-prueba-n-menu-text': route().current(item.href) }">{{ item.label }}</span>
                            </Link>
                        
                        </template>
                    </Menu> 
                    <div class="flex flex-row p-6 text-xs items-center gap-x-2 absolute bottom-0">
                        <LogoEmpresa class="w-8 h-8"/>
                        <p>Desarrollado <span class="line-through">por</span> para Neering SpA. por Víctor Zúñiga M.</p>
                    </div>
            </div>

            <!-- Page Content -->
            <div class="flex flex-row gap-2 relative min-h-[86vh]">
                <main class="p-5 sm:pl-64 w-full p-8">
                    <slot />
                </main>
            </div>
        </div>
    </div>

    <Toast position="bottom-right">
        <template #message="slotProps">
            <div class="flex flex-col items-start flex-auto">
                <div class="font-medium text-lg m-4">{{ slotProps.message.detail }}</div>
            </div>
        </template>
    </Toast>
    <ConfirmDialog></ConfirmDialog>
</template>
