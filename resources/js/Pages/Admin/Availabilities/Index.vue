<template>
    <AppLayout title="Gestión de Disponibilidades">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Disponibilidades</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filtros -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Cancha</label>
                                <select
                                    v-model="filterSpace"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Todas las canchas</option>
                                    <option v-for="space in spaces" :key="space.id" :value="space.id">
                                        {{ space.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Día</label>
                                <select
                                    v-model="filterDay"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Todos los días</option>
                                    <option v-for="(day, index) in dayNames" :key="index" :value="index">
                                        {{ day }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex-none">
                                <Link :href="route('admin.availabilities.create')" class="inline-flex items-center bg-tennis-green hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-lg shadow transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Añadir Disponibilidad
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div v-if="filteredAvailabilities.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Cancha</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Día de la Semana</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Hora de Inicio</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Hora de Fin</th>
                                        <th scope="col" class="relative px-6 py-3 text-xs font-black text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="availability in filteredAvailabilities" :key="availability.id" class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ availability.space.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ getDayName(availability.day_of_week) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ formatTime(availability.start_time) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ formatTime(availability.end_time) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('admin.availabilities.edit', availability.id)" class="text-indigo-600 hover:text-indigo-900 font-bold mr-4">Editar</Link>
                                            <button @click="confirmDelete(availability.id)" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="p-10 text-center text-gray-500 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h4 class="text-lg font-bold text-gray-400">No hay disponibilidades que coincidan</h4>
                            <p class="text-gray-400">Ajusta los filtros o añade una nueva disponibilidad.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    availabilities: Array,
    spaces: Array,
});

const filterSpace = ref('');
const filterDay = ref('');

const dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

const filteredAvailabilities = computed(() => {
    return props.availabilities.filter(a => {
        const matchSpace = !filterSpace.value || a.space_id === parseInt(filterSpace.value);
        const matchDay = filterDay.value === '' || a.day_of_week === parseInt(filterDay.value);
        return matchSpace && matchDay;
    });
});

const getDayName = (dayOfWeek) => {
    return dayNames[dayOfWeek];
};

const formatTime = (time) => {
    if (!time) return '';
    // Asegurarse de mostrar HH:mm
    return time.substring(0, 5);
};

const confirmDelete = (id) => {
    if (confirm('¿Estás seguro de que quieres eliminar esta disponibilidad?')) {
        useForm({}).delete(route('admin.availabilities.destroy', id));
    }
};
</script>
