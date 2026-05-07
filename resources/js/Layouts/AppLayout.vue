<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-tennis-white">
            <nav class="bg-tennis-green border-b border-tennis-cyan/30 shadow-lg">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-20">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-12 w-auto" />
                                </Link>
                                <span class="ms-3 text-white font-bold text-xl tracking-wider hidden md:block">RESERVAS TENIS</span>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-white hover:text-tennis-cyan transition-colors">
                                    Dashboard
                                </NavLink>

                                <!-- Admin Links -->
                                <template v-if="$page.props.auth.user.role === 'admin'">
                                    <NavLink :href="route('spaces.index')" :active="route().current('spaces.index')" class="text-white hover:text-tennis-cyan transition-colors">
                                        Canchas
                                    </NavLink>
                                    <NavLink :href="route('blocked-slots.index')" :active="route().current('blocked-slots.index')" class="text-white hover:text-tennis-cyan transition-colors">
                                        Bloqueos
                                    </NavLink>
                                    <NavLink :href="route('reservations.admin.index')" :active="route().current('reservations.admin.index')" class="text-white hover:text-tennis-cyan transition-colors">
                                        Reservas
                                    </NavLink>
                                    <NavLink :href="route('availabilities.index')" :active="route().current('availabilities.index')" class="text-white hover:text-tennis-cyan transition-colors">
                                        Disponibilidad
                                    </NavLink>
                                </template>

                                <!-- User Links -->
                                <template v-else>
                                    <NavLink :href="route('reservations.user.index')" :active="route().current('reservations.user.index')" class="text-white hover:text-tennis-cyan transition-colors">
                                        Mis Reservas
                                    </NavLink>
                                </template>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-tennis-cyan rounded-full focus:outline-none focus:border-white transition">
                                            <img class="size-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-4 py-2 border border-tennis-cyan text-sm leading-4 font-medium rounded-full text-white bg-tennis-green hover:bg-green-800 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Mi Cuenta
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            Perfil
                                        </DropdownLink>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Cerrar Sesión
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-tennis-cyan hover:text-white hover:bg-green-800 focus:outline-none transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden bg-green-900">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-white">
                            Dashboard
                        </ResponsiveNavLink>

                        <template v-if="$page.props.auth.user.role === 'admin'">
                            <ResponsiveNavLink :href="route('spaces.index')" :active="route().current('spaces.index')" class="text-white">
                                Canchas
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('blocked-slots.index')" :active="route().current('blocked-slots.index')" class="text-white">
                                Bloqueos
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('reservations.admin.index')" :active="route().current('reservations.admin.index')" class="text-white">
                                Reservas
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('availabilities.index')" :active="route().current('availabilities.index')" class="text-white">
                                Disponibilidad
                            </ResponsiveNavLink>
                        </template>

                        <template v-else>
                            <ResponsiveNavLink :href="route('reservations.user.index')" :active="route().current('reservations.user.index')" class="text-white">
                                Mis Reservas
                            </ResponsiveNavLink>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-green-800">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                <img class="size-10 rounded-full object-cover border-2 border-tennis-cyan" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-white">{{ $page.props.auth.user.name }}</div>
                                <div class="font-medium text-sm text-tennis-cyan">{{ $page.props.auth.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')" class="text-white">
                                Perfil
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button" class="text-white">
                                    Cerrar Sesión
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
