<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Reservas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filtros -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Estado</label>
                                <select
                                    v-model="filterStatus"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="confirmada">Confirmada</option>
                                    <option value="rechazada">Rechazada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de reservas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Cancha</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jugador</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Inicio</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Fin</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Estado</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="reservation in filteredReservations" :key="reservation.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ reservation.space?.name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ reservation.user_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(reservation.start_time) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(reservation.end_time) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span :class="getStatusClass(reservation.status)">
                                            {{ getStatusLabel(reservation.status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex gap-2">
                                            <button
                                                v-if="reservation.status === 'pendiente'"
                                                @click="acceptReservation(reservation.id)"
                                                class="text-green-600 hover:text-green-900 font-semibold"
                                            >
                                                Aprobar
                                            </button>
                                            <button
                                                v-if="reservation.status === 'pendiente'"
                                                @click="rejectReservation(reservation.id)"
                                                class="text-red-600 hover:text-red-900 font-semibold"
                                            >
                                                Rechazar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="filteredReservations.length === 0" class="p-6 text-center text-gray-500">
                        No hay reservas que coincidan con los filtros
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    reservations: Array,
});

const filterStatus = ref('');

const filteredReservations = computed(() => {
    if (!filterStatus.value) return props.reservations;
    return props.reservations.filter(r => r.status === filterStatus.value);
});

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('es-AR');
};

const getStatusClass = (status) => {
    const baseClass = 'px-3 py-1 rounded-full text-xs font-semibold';
    const statusClasses = {
        pending: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-green-100 text-green-800',
        canceled: 'bg-gray-100 text-gray-800',
    };
    return `${baseClass} ${statusClasses[status] || ''}`;
};

const getStatusLabel = (status) => {
    const labels = {
        pending: 'Pendiente',
        confirmed: 'Confirmada',
        cancelled: 'Cancelada',
    };
    return labels[status] || status;
};

const acceptReservation = async (reservationId) => {
    if (confirm('¿Confirmar esta reserva?')) {
        try {
            await fetch(`/api/reservations/${reservationId}/accept`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
            window.location.reload();
        } catch (error) {
            console.error('Error:', error);
        }
    }
};

const rejectReservation = async (reservationId) => {
    if (confirm('¿Rechazar esta reserva?')) {
        try {
            await fetch(`/api/reservations/${reservationId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
            window.location.reload();
        } catch (error) {
            console.error('Error:', error);
        }
    }
};
</script>
