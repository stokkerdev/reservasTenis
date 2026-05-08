<template>
    <AppLayout title="Gestión de Disponibilidades">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Disponibilidades</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Disponibilidades Registradas</h3>
                            <Link :href="route('admin.availabilities.create')" class="bg-tennis-green hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Añadir Disponibilidad
                            </Link>
                        </div>

                        <div v-if="availabilities.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancha</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día de la Semana</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora de Inicio</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora de Fin</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="availability in availabilities" :key="availability.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ availability.space.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ getDayName(availability.day_of_week) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ availability.start_time }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ availability.end_time }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('admin.availabilities.edit', availability.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</Link>
                                            <button @click="confirmDelete(availability.id)" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="p-4 text-center text-gray-500 bg-gray-50 rounded-lg">
                            No hay disponibilidades registradas. ¡Añade una ahora!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    availabilities: Array,
});

const getDayName = (dayOfWeek) => {
    const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    return days[dayOfWeek];
};

const confirmDelete = (id) => {
    if (confirm('¿Estás seguro de que quieres eliminar esta disponibilidad?')) {
        useForm({}).delete(route('admin.availabilities.destroy', id));
    }
};
</script>
