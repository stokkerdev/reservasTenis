<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Canchas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header con botón crear -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Canchas Registradas</h3>
                        <Link href="/admin/spaces/create" class="inline-flex items-center px-4 py-2 bg-tennis-green text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            + Nueva Cancha
                        </Link>
                    </div>
                </div>

                <!-- Tabla de canchas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nombre</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipo</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Capacidad</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Precio/Hora</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Estado</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="space in spaces" :key="space.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ space.name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ space.type }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ space.capacity }} personas</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">${{ space.price_per_hour }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span v-if="space.is_active" class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                            Activa
                                        </span>
                                        <span v-else class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                            Inactiva
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex gap-2">
                                            <Link :href="`/admin/spaces/${space.id}/edit`" class="text-blue-600 hover:text-blue-900 font-semibold">
                                                Editar
                                            </Link>
                                            <button @click="deleteSpace(space.id)" class="text-red-600 hover:text-red-900 font-semibold">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
    spaces: Array,
});

const deleteSpace = async (spaceId) => {
    if (confirm('¿Estás seguro de que deseas eliminar esta cancha?')) {
        try {
            await fetch(`/api/spaces/${spaceId}`, { method: 'DELETE' });
            window.location.reload();
        } catch (error) {
            console.error('Error al eliminar:', error);
        }
    }
};
</script>
