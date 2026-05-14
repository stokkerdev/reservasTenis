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
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <!-- Filtro por Cancha -->
                            <div class="flex-1">
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

                            <!-- Filtro por Fecha -->
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Fecha</label>
                                <input
                                    v-model="filterDate"
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                            </div>

                            <!-- Filtro por Estado -->
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Estado</label>
                                <select
                                    v-model="filterStatus"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Todos los estados</option>
                                    <option value="pending">Pendiente</option>
                                    <option value="confirmed">Confirmada</option>
                                    <option value="cancelled">Cancelada</option>
                                    <option value="rejected">Rechazada</option>
                                </select>
                            </div>

                            <!-- Botón Limpiar -->
                            <div class="flex-none">
                                <button 
                                    @click="clearFilters"
                                    class="w-full px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition"
                                >
                                    Limpiar Filtros
                                </button>
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
                                    <th class="px-6 py-3 text-left text-sm font-black text-gray-900 uppercase tracking-wider">Cancha</th>
                                    <th class="px-6 py-3 text-left text-sm font-black text-gray-900 uppercase tracking-wider">Jugador</th>
                                    <th class="px-6 py-3 text-left text-sm font-black text-gray-900 uppercase tracking-wider">Inicio</th>
                                    <th class="px-6 py-3 text-left text-sm font-black text-gray-900 uppercase tracking-wider">Fin</th>
                                    <th class="px-6 py-3 text-left text-sm font-black text-gray-900 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-sm font-black text-gray-900 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="reservation in filteredReservations" :key="reservation.id" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-bold">{{ reservation.space?.name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                        <div class="flex flex-col">
                                            <span>{{ reservation.user_name }}</span>
                                            <span class="text-xs text-gray-400">{{ reservation.user_email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(reservation.start_time) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(reservation.end_time) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span :class="getStatusClass(reservation.status)">
                                            {{ getStatusLabel(reservation.status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <button
                                                v-if="reservation.status !== 'confirmed'"
                                                @click="updateStatus(reservation.id, 'accept')"
                                                class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-xs font-black transition"
                                                title="Aprobar"
                                            >
                                                APROBAR
                                            </button>
                                            <button
                                                v-if="reservation.status !== 'pending'"
                                                @click="updateStatus(reservation.id, 'set-pending')"
                                                class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 text-xs font-black transition"
                                                title="Pendiente"
                                            >
                                                PENDIENTE
                                            </button>
                                            <button
                                                v-if="reservation.status !== 'cancelled'"
                                                @click="updateStatus(reservation.id, 'cancel')"
                                                class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-xs font-black transition"
                                                title="Cancelar"
                                            >
                                                CANCELAR
                                            </button>
                                            <button
                                                @click="deleteReservation(reservation.id)"
                                                class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 text-xs font-black transition"
                                                title="Eliminar"
                                            >
                                                ELIMINAR
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="filteredReservations.length === 0" class="p-12 text-center text-gray-500 bg-gray-50 border-t border-gray-100">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h4 class="text-lg font-bold text-gray-400">No se encontraron reservas</h4>
                        <p class="text-gray-400">Intenta cambiar los filtros de búsqueda.</p>
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
    spaces: Array,
});

const filterStatus = ref('');
const filterSpace = ref('');
const filterDate = ref('');

const filteredReservations = computed(() => {
    return props.reservations.filter(r => {
        const matchStatus = !filterStatus.value || r.status === filterStatus.value;
        const matchSpace = !filterSpace.value || r.space_id === parseInt(filterSpace.value);
        
        let matchDate = true;
        if (filterDate.value) {
            const reservationDate = new Date(r.start_time).toISOString().split('T')[0];
            matchDate = reservationDate === filterDate.value;
        }
        
        return matchStatus && matchSpace && matchDate;
    });
});

const clearFilters = () => {
    filterStatus.value = '';
    filterSpace.value = '';
    filterDate.value = '';
};

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
    const baseClass = 'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider';
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
            const response = await fetch(`/admin/reservations/${reservationId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
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
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
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
