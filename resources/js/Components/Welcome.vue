<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <ApplicationLogo class="block h-16 w-auto" />
                <div>
                    <h1 class="text-3xl font-bold text-tennis-green">
                        Club de Tenis - Panel de Control
                    </h1>
                    <p class="text-gray-500">Bienvenido al Deporte Blanco. Gestiona tus reservas y entrenamientos.</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 bg-opacity-50 grid grid-cols-1 md:grid-cols-3 gap-6 p-6 lg:p-8">
            <!-- Card de Reservas -->
            <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-tennis-green hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-tennis-green/10 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-tennis-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="ms-3 text-xl font-bold text-gray-800">Mis Reservas</h2>
                </div>
                <p class="text-gray-600 text-sm mb-4">Consulta tus próximas partidas y el estado de tus canchas reservadas.</p>
                <Link :href="route('reservations.user.index')" class="inline-flex items-center text-tennis-green font-semibold hover:underline">
                    Ver calendario →
                </Link>
            </div>

            <!-- Card de Canchas / Gestión -->
            <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-tennis-cyan hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-tennis-cyan/20 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="ms-3 text-xl font-bold text-gray-800">
                        {{ $page.props.auth.user.role === 'admin' ? 'Gestión de Canchas' : 'Canchas Disponibles' }}
                    </h2>
                </div>
                <p class="text-gray-600 text-sm mb-4">
                    {{ $page.props.auth.user.role === 'admin' ? 'Administra las canchas, bloquea horarios y configura disponibilidad.' : 'Explora las canchas de césped, arcilla y cemento listas para jugar.' }}
                </p>
                <Link 
                    :href="$page.props.auth.user.role === 'admin' ? route('admin.spaces.index') : route('reservations.create')"
                    class="inline-flex items-center text-blue-600 font-semibold hover:underline"
                >
                    {{ $page.props.auth.user.role === 'admin' ? 'Configurar espacios →' : 'Reservar ahora →' }}
                </Link>
            </div>

            <!-- Card de Perfil -->
            <div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-tennis-yellow hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-tennis-yellow/20 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-tennis-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="ms-3 text-xl font-bold text-gray-800">Mi Nivel</h2>
                </div>
                <p class="text-gray-600 text-sm mb-4">Sigue tus estadísticas de juego y mejora tu ranking en el club.</p>
                <a href="#" class="inline-flex items-center text-tennis-green font-semibold hover:underline">
                    Ver estadísticas →
                </a>
            </div>
        </div>

        <!-- Banner Decorativo -->
        <div class="relative bg-tennis-green h-48 flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <line x1="0" y1="50" x2="100" y2="50" stroke="white" stroke-width="0.5" />
                    <line x1="50" y1="0" x2="50" y2="100" stroke="white" stroke-width="0.5" />
                </svg>
            </div>
            <div class="z-10 text-center">
                <h3 class="text-white text-2xl font-bold italic italic">"El tenis es un deporte mental. Ganas o pierdes el partido antes de salir a la cancha."</h3>
                <p class="text-tennis-cyan mt-2">- Venus Williams</p>
            </div>
        </div>
    </div>
</template>
