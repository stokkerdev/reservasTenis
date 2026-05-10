<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Reservas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header con botón crear -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Tus Reservas</h3>
                        <Link href="/reservations/create" class="inline-flex items-center px-4 py-2 bg-tennis-green text-white font-semibold rounded-lg hover:bg-green-700 transition">
                            + Nueva Reserva
                        </Link>
                    </div>
                </div>

                <!-- Tabla de reservas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Cancha</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Inicio</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Fin</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Estado</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="reservation in reservations" :key="reservation.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ reservation.space?.name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(reservation.start_time) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ formatDateTime(reservation.end_time) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span :class="getStatusClass(reservation.status)">
                                            {{ getStatusLabel(reservation.status) }}
                                        </span>
                                    </td>
                                      <td class="px-6 py-4 text-sm">
                                          <div class="flex gap-2">
                                              <Link
                                                  v-if="['pending', 'confirmed'].includes(reservation.status)"
                                                  :href="`/reservations/${reservation.id}/edit`"
                                                  class="text-blue-600 hover:text-blue-900 font-semibold"
                                              >
                                                  Editar
                                              </Link>
                                              <button
                                                  v-if="['pending', 'confirmed'].includes(reservation.status)"
                                                  @click="deleteReservation(reservation.id)"
                                                  class="text-red-600 hover:text-red-900 font-semibold"
                                              >
                                                  Eliminar
                                              </button>
                                              <span v-else class="text-gray-400">-</span>
                                          </div>
                                      </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-if="reservations.length === 0" class="p-6 text-center text-gray-500">
                        No tienes reservas aún. ¡Crea una nueva!
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
    reservations: Array,
});

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('es-AR');
};

const getStatusClass = (status) => {
    const baseClass = 'px-3 py-1 rounded-full text-xs font-semibold';
    const statusClasses = {
        pending: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-green-100 text-green-800',
        cancelled: 'bg-gray-100 text-gray-800',
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

const cancelReservation = async (reservationId) => {
    if (confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
        try {
            await fetch(`/api/reservations/${reservationId}/cancel`, {
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

const deleteReservation = async (reservationId) => {
    if (confirm('¿Estás seguro de que deseas eliminar esta reserva? Esta acción no se puede deshacer.')) {
        try {
            await fetch(`/api/reservations/${reservationId}`, {
                method: 'DELETE',
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
