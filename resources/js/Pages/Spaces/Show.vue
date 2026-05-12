<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    space: Object,
});

const selectedDate = ref(new Date().toISOString().split('T')[0]);
const availableBlocks = ref([]);
const loading = ref(false);

const fetchBlocks = async () => {
    loading.value = true;
    try {
        const response = await fetch(`/spaces/${props.space.id}/available-time-blocks?date=${selectedDate.value}`);
        if (response.ok) {
            availableBlocks.value = await response.json();
        }
    } catch (error) {
        console.error('Error fetching blocks:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchBlocks();
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(price);
};

const formatTime = (time) => {
    return new Date(time).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
};

const getSpaceImage = (path) => {
    return path ? `/storage/${path}` : '/images/default-court.jpg';
};
</script>

<template>
    <AppLayout :title="space.name">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ space.name }}
                </h2>
                <Link href="/" class="text-tennis-green font-bold hover:underline">
                    &larr; Volver al listado
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="grid lg:grid-cols-2">
                        <!-- Imagen y Detalles -->
                        <div class="p-8 border-r border-gray-100">
                            <div class="rounded-2xl overflow-hidden h-80 mb-6 shadow-md">
                                <img :src="getSpaceImage(space.image_path)" :alt="space.name" class="w-full h-full object-cover">
                            </div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="px-4 py-1 bg-tennis-cyan/30 text-tennis-green rounded-full font-bold text-sm uppercase tracking-wider">
                                    {{ space.type }}
                                </span>
                                <span class="text-gray-400">|</span>
                                <span class="text-gray-600 font-medium">Capacidad: {{ space.capacity }} personas</span>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Descripción</h3>
                            <p class="text-gray-600 leading-relaxed mb-8">
                                {{ space.description || 'Esta cancha profesional cuenta con todas las medidas reglamentarias y mantenimiento diario para garantizar la mejor experiencia de juego.' }}
                            </p>

                            <div class="bg-gray-50 p-6 rounded-2xl flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-500 font-bold uppercase">Precio por hora</p>
                                    <p class="text-3xl font-black text-tennis-green">{{ formatPrice(space.price_per_hour) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 font-bold uppercase">Ubicación</p>
                                    <p class="text-lg font-bold text-gray-800">{{ space.location || 'Sede Principal' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Disponibilidad y Reserva -->
                        <div class="p-8 bg-gray-50/50">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">¡Reserva esta cancha!</h3>
                            
                            <div class="mb-8">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Selecciona una fecha</label>
                                <input 
                                    v-model="selectedDate" 
                                    type="date" 
                                    @change="fetchBlocks"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-tennis-cyan focus:border-tennis-cyan transition"
                                >
                            </div>

                            <div class="mb-8">
                                <h4 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Horarios Disponibles</h4>
                                
                                <div v-if="loading" class="flex justify-center py-10">
                                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-tennis-green"></div>
                                </div>

                                <div v-else-if="availableBlocks.length > 0" class="grid grid-cols-2 gap-3">
                                    <div v-for="block in availableBlocks" :key="block.start_time" class="bg-white p-3 rounded-xl border border-gray-200 text-center shadow-sm">
                                        <p class="text-sm font-bold text-gray-800">{{ formatTime(block.start_time) }} - {{ formatTime(block.end_time) }}</p>
                                    </div>
                                </div>

                                <div v-else class="bg-yellow-50 border border-yellow-100 p-6 rounded-2xl text-center">
                                    <p class="text-yellow-700 font-medium">No hay horarios disponibles para esta fecha.</p>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-200">
                                <Link 
                                    :href="route('reservations.create', { space_id: space.id, date: selectedDate })"
                                    class="block w-full bg-tennis-green text-white text-center font-black py-4 rounded-2xl shadow-xl hover:bg-green-800 transition transform hover:-translate-y-1"
                                >
                                    CONTINUAR CON LA RESERVA
                                </Link>
                                <p class="text-center text-xs text-gray-400 mt-4">
                                    Al hacer clic, serás redirigido para completar los detalles de tu reserva.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
