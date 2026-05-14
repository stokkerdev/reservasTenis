<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Bienvenidos al Club de Tenis" />
    
    <div class="relative min-h-screen bg-tennis-white overflow-hidden">
        <!-- Fondo decorativo con temática de tenis -->
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <rect x="0" y="0" width="100" height="100" fill="#2e5a27" />
                <line x1="0" y1="50" x2="100" y2="50" stroke="white" stroke-width="0.5" />
                <line x1="50" y1="0" x2="50" y2="100" stroke="white" stroke-width="0.5" />
                <rect x="10" y="10" width="80" height="80" fill="none" stroke="white" stroke-width="0.5" />
            </svg>
        </div>

        <!-- Navegación superior -->
        <nav class="relative z-10 flex items-center justify-between p-6 lg:px-8 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
            <div class="flex items-center gap-3">
                <ApplicationLogo class="h-10 w-auto" />
                <span class="text-tennis-green font-bold text-xl tracking-tight">CLUB DE TENIS</span>
            </div>
            
            <div v-if="canLogin" class="flex gap-4">

                
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-4 py-2 text-tennis-green font-semibold hover:bg-tennis-green/5 rounded-lg transition"
                >
                    Tablero Principal
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-4 py-2 text-tennis-green font-semibold hover:bg-tennis-green/5 rounded-lg transition"
                    >
                        Iniciar Sesión
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-4 py-2 bg-tennis-green text-white font-bold rounded-lg shadow-md hover:bg-green-800 transition transform hover:-translate-y-0.5"
                    >
                        Registrarse
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 pt-20 pb-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block px-4 py-1.5 mb-6 text-sm font-bold tracking-wider text-tennis-green uppercase bg-tennis-cyan/30 rounded-full">
                        El Deporte Blanco te Espera
                    </span>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-gray-900 leading-tight mb-6">
                        Reserva tu cancha y <span class="text-tennis-green">domina el juego</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-10 leading-relaxed">
                        Tu mejor plataforma para gestionar tus partidos de tenis. Reserva en segundos y mejora tu nivel en nuestras canchas de clase mundial.
                    </p>
                    
                    <div class="flex flex-wrap gap-4">
                        <Link :href="route('register')" class="px-8 py-4 bg-tennis-green text-white text-lg font-bold rounded-xl shadow-xl hover:bg-green-800 transition transform hover:scale-105">
                            Comenzar Ahora
                        </Link>
                    
                    </div>
                </div>

                <div class="relative">
                    <!-- Imagen decorativa / Ilustración -->
                    <div class="bg-tennis-green rounded-3xl p-4 shadow-2xl rotate-3 transform hover:rotate-0 transition duration-500">
                        <div class="bg-white rounded-2xl p-8 aspect-square flex flex-col items-center justify-center text-center">
                            <ApplicationLogo class="w-32 h-32 mb-6" />
                            <h3 class="text-2xl font-bold text-tennis-green mb-2">Canchas Profesionales</h3>
                            <p class="text-gray-500">Césped, Arcilla y Cemento</p>
                            <div class="mt-8 flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-tennis-yellow"></div>
                                <div class="w-3 h-3 rounded-full bg-tennis-yellow/50"></div>
                                <div class="w-3 h-3 rounded-full bg-tennis-yellow/20"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Badge flotante -->
                    <div class="absolute -bottom-6 -left-6 bg-tennis-cyan p-6 rounded-2xl shadow-xl border-4 border-white">
                        <p class="text-tennis-green font-bold text-3xl">100%</p>
                        <p class="text-gray-700 font-medium">Disponibilidad</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 py-12 border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <ApplicationLogo class="h-6 w-auto" />
                    <span class="text-gray-900 font-bold">Reserva Tenis © 2026</span>
                </div>
                <div class="flex gap-8 text-gray-500 font-medium">
                    <a href="#" class="hover:text-tennis-green transition">Términos</a>
                    <a href="#" class="hover:text-tennis-green transition">Privacidad</a>
                    <a href="#" class="hover:text-tennis-green transition">Contacto</a>
                </div>
            </div>
        </footer>
    </div>
</template>
