<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Bloqueos por Mantenimiento</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header con botón crear -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Horarios Bloqueados</h3>
                        <Link href="/admin/blocked-slots/create" class="inline-flex items-center px-4 py-2 bg-tennis-green text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            + Nuevo Bloqueo
                        </Link>
                    </div>
                </div>

                <!-- Tabla de bloqueos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Cancha</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Inicio</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Fin</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Razón</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="slot in blockedSlots" :key="slot.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ slot.space?.name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(slot.start_time) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(slot.end_time) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ slot.reason }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex gap-3">
                                            <Link :href="`/admin/blocked-slots/${slot.id}/edit`" class="text-tennis-cyan hover:text-cyan-700 font-semibold">
                                                Editar
                                            </Link>
                                            <button @click="deleteBlockedSlot(slot.id)" class="text-red-600 hover:text-red-900 font-semibold">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="blockedSlots.length === 0" class="p-6 text-center text-gray-500">
                        No hay bloqueos registrados
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    blockedSlots: Array,
});

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('es-AR');
};

const deleteBlockedSlot = (slotId) => {
    if (confirm('¿Estás seguro de que deseas eliminar este bloqueo?')) {
        router.delete(`/admin/blocked-slots/${slotId}`);
    }
};
</script>
