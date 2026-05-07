<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ space.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Información de la cancha -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Detalles principales -->
                    <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ space.name }}</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1"><strong>Tipo:</strong></p>
                                    <p class="text-lg text-gray-900">{{ space.type }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1"><strong>Capacidad:</strong></p>
                                    <p class="text-lg text-gray-900">{{ space.capacity }} personas</p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <p class="text-sm text-gray-600 mb-2"><strong>Descripción:</strong></p>
                                <p class="text-gray-700">{{ space.description }}</p>
                            </div>

                            <div class="bg-tennis-green/10 border border-tennis-green rounded-lg p-4">
                                <p class="text-3xl font-bold text-tennis-green">${{ space.price_per_hour }}/hora</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de reserva -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white text-center">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">¿Deseas reservar esta cancha?</h4>
                            <Link href="/reservations/create" class="block w-full bg-tennis-green text-white font-semibold py-3 px-4 rounded-lg hover:bg-green-700 transition mb-3">
                                Hacer Reserva
                            </Link>
                            <Link href="/" class="block w-full bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-lg hover:bg-gray-400 transition">
                                Volver
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Disponibilidad -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Horarios Disponibles</h3>
                        
                        <div v-if="space.availabilities && space.availabilities.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="availability in space.availabilities" :key="availability.id" class="border border-gray-200 rounded-lg p-4">
                                <p class="text-sm text-gray-600 mb-2"><strong>{{ getDayName(availability.day_of_week) }}</strong></p>
                                <p class="text-gray-900">{{ availability.start_time }} - {{ availability.end_time }}</p>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500 py-8">
                            No hay horarios disponibles registrados
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    space: Object,
});

const getDayName = (dayOfWeek) => {
    const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    return days[dayOfWeek] || dayOfWeek;
};
</script>
