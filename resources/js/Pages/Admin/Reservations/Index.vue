<template>
    <AppLayout title="Gestión de Reservas">
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
                                    <option value="pending">Pendiente</option>
                                    <option value="confirmed">Confirmada</option>
                                    <option value="rejected">Rechazada</option>
                                    <option value="cancelled">Cancelada</option>
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
                                        <div class="flex flex-wrap gap-2">
                                            <button
                                                v-if="reservation.status !== 'confirmed'"
                                                @click="updateStatus(reservation.id, 'accept')"
                                                class="px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 text-xs font-bold"
                                                title="Aprobar"
                                            >
                                                Aprobar
                                            </button>
                                            <button
                                                v-if="reservation.status !== 'pending'"
                                                @click="updateStatus(reservation.id, 'set-pending')"
                                                class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200 text-xs font-bold"
                                                title="Pendiente"
                                            >
                                                Pendiente
                                            </button>
                                            <button
                                                v-if="reservation.status !== 'rejected'"
                                                @click="updateStatus(reservation.id, 'reject')"
                                                class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-xs font-bold"
                                                title="Rechazar"
                                            >
                                                Rechazar
                                            </button>
                                            <button
                                                v-if="reservation.status !== 'cancelled'"
                                                @click="updateStatus(reservation.id, 'cancel')"
                                                class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs font-bold"
                                                title="Cancelar"
                                            >
                                                Cancelar
                                            </button>
                                            <button
                                                @click="deleteReservation(reservation.id)"
                                                class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-bold"
                                                title="Eliminar"
                                            >
                                                Eliminar
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
import { router } from '@inertiajs/vue3';

const props = defineProps({
    reservations: Array,
});

const filterStatus = ref('');

const filteredReservations = computed(() => {
    if (!filterStatus.value) return props.reservations;
    return props.reservations.filter(r => r.status === filterStatus.value);
});

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('es-AR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
};

const getStatusClass = (status) => {
    const baseClass = 'px-3 py-1 rounded-full text-xs font-semibold';
    const statusClasses = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
        'cancelled': 'bg-gray-100 text-gray-800',
    };
    return `${baseClass} ${statusClasses[status] || 'bg-blue-100 text-blue-800'}`;
};

const getStatusLabel = (status) => {
    const labels = {
        'pending': 'Pendiente',
        'confirmed': 'Confirmada',
        'rejected': 'Rechazada',
        'cancelled': 'Cancelada',
    };
    return labels[status] || status;
};

const updateStatus = async (reservationId, action) => {
    const confirmMessages = {
        'accept': '¿Estás seguro de que quieres APROBAR esta reserva?',
        'reject': '¿Estás seguro de que quieres RECHAZAR esta reserva?',
        'set-pending': '¿Estás seguro de que quieres poner esta reserva en PENDIENTE?',
        'cancel': '¿Estás seguro de que quieres CANCELAR esta reserva?',
    };

    if (confirm(confirmMessages[action])) {
        try {
            // Usamos las rutas web definidas en web.php
            const response = await fetch(`/admin/reservations/${reservationId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });

            if (response.ok) {
                router.reload();
            } else {
                const data = await response.json();
                alert(data.message || 'Error al actualizar el estado de la reserva');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de red al intentar actualizar la reserva');
        }
    }
};

const deleteReservation = async (reservationId) => {
    if (confirm('¿Estás seguro de que quieres ELIMINAR permanentemente esta reserva? Esta acción no se puede deshacer.')) {
        try {
            const response = await fetch(`/admin/reservations/${reservationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });

            if (response.ok) {
                router.reload();
            } else {
                const data = await response.json();
                alert(data.message || 'Error al eliminar la reserva');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de red al intentar eliminar la reserva');
        }
    }
};
</script>
