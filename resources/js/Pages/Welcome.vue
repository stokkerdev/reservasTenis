<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    spaces: Array,
});

const filterType = ref('');

const filteredSpaces = computed(() => {
    if (!filterType.value) return props.spaces;
    return props.spaces.filter(s => s.type === filterType.value);
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(price);
};

const getSpaceImage = (path) => {
    return path ? `/storage/${path}` : '/images/default-court.jpg';
};
</script>

<template>
    <Head title="Bienvenidos al Club de Tenis" />
    
    <div class="relative min-h-screen bg-gray-50 overflow-hidden">
        <!-- Navegación superior -->
        <nav class="relative z-10 flex items-center justify-between p-6 lg:px-8 bg-white border-b border-gray-100 shadow-sm">
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
                        class="px-4 py-2 bg-tennis-green text-white font-bold rounded-lg shadow-md hover:bg-green-800 transition"
                    >
                        Registrarse
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="bg-tennis-green py-16 px-6 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Nuestras Canchas</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto">
                Reserva en las mejores instalaciones profesionales. Césped, arcilla o cemento, tú eliges.
            </p>
        </header>

        <!-- Filtros y Listado -->
        <main class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <!-- Filtros -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-12">
                <div class="flex gap-2">
                    <button 
                        @click="filterType = ''"
                        :class="filterType === '' ? 'bg-tennis-green text-white' : 'bg-white text-gray-700 border border-gray-200'"
                        class="px-6 py-2 rounded-full font-semibold transition shadow-sm"
                    >
                        Todas
                    </button>
                    <button 
                        @click="filterType = 'cesped'"
                        :class="filterType === 'cesped' ? 'bg-tennis-green text-white' : 'bg-white text-gray-700 border border-gray-200'"
                        class="px-6 py-2 rounded-full font-semibold transition shadow-sm"
                    >
                        Césped
                    </button>
                    <button 
                        @click="filterType = 'arcilla'"
                        :class="filterType === 'arcilla' ? 'bg-tennis-green text-white' : 'bg-white text-gray-700 border border-gray-200'"
                        class="px-6 py-2 rounded-full font-semibold transition shadow-sm"
                    >
                        Arcilla
                    </button>
                    <button 
                        @click="filterType = 'cemento'"
                        :class="filterType === 'cemento' ? 'bg-tennis-green text-white' : 'bg-white text-gray-700 border border-gray-200'"
                        class="px-6 py-2 rounded-full font-semibold transition shadow-sm"
                    >
                        Cemento
                    </button>
                </div>
                <p class="text-gray-500 font-medium">{{ filteredSpaces.length }} canchas disponibles</p>
            </div>

            <!-- Grid de Canchas -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="space in filteredSpaces" :key="space.id" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 flex flex-col">
                    <!-- Imagen -->
                    <div class="relative h-56 overflow-hidden">
                        <img :src="getSpaceImage(space.image_path)" :alt="space.name" class="w-full h-full object-cover transform hover:scale-110 transition duration-500">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-tennis-green font-bold text-sm shadow-sm">
                            {{ space.type.toUpperCase() }}
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ space.name }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ space.description || 'Sin descripción disponible.' }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Precio por hora</p>
                                <p class="text-xl font-extrabold text-tennis-green">{{ formatPrice(space.price_per_hour) }}</p>
                            </div>
                            <Link 
                                :href="route('spaces.show', { space: space.slug })" 
                                class="bg-tennis-cyan text-tennis-green font-bold px-4 py-2 rounded-lg hover:bg-cyan-200 transition"
                            >
                                Ver Detalles
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredSpaces.length === 0" class="text-center py-20">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">No se encontraron canchas</h3>
                <p class="text-gray-500">Intenta con otro filtro o vuelve más tarde.</p>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-12 border-t border-gray-100 bg-white mt-20">
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
