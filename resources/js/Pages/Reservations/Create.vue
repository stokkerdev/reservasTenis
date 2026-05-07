<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nueva Reserva</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Cancha -->
                            <div>
                                <label for="space_id" class="block text-sm font-medium text-gray-700 mb-2">Selecciona una Cancha</label>
                                <select
                                    v-model="form.space_id"
                                    id="space_id"
                                    required
                                    @change="updateSpaceInfo"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Seleccionar cancha</option>
                                    <option v-for="space in spaces" :key="space.id" :value="space.id">
                                        {{ space.name }} - ${{ space.price_per_hour }}/hora
                                    </option>
                                </select>
                                <p v-if="errors.space_id" class="text-red-600 text-sm mt-1">{{ errors.space_id }}</p>
                            </div>

                            <!-- Información de la cancha seleccionada -->
                            <div v-if="selectedSpace" class="bg-tennis-cyan/10 border border-tennis-cyan rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ selectedSpace.name }}</h4>
                                <p class="text-sm text-gray-600 mb-1"><strong>Tipo:</strong> {{ selectedSpace.type }}</p>
                                <p class="text-sm text-gray-600 mb-1"><strong>Capacidad:</strong> {{ selectedSpace.capacity }} personas</p>
                                <p class="text-sm text-gray-600"><strong>Descripción:</strong> {{ selectedSpace.description }}</p>
                            </div>

                            <!-- Fecha de inicio -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Inicio</label>
                                <input
                                    v-model="form.start_time"
                                    type="datetime-local"
                                    id="start_time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.start_time" class="text-red-600 text-sm mt-1">{{ errors.start_time }}</p>
                            </div>

                            <!-- Fecha de fin -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora de Fin</label>
                                <input
                                    v-model="form.end_time"
                                    type="datetime-local"
                                    id="end_time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.end_time" class="text-red-600 text-sm mt-1">{{ errors.end_time }}</p>
                            </div>

                            <!-- Notas -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas Adicionales (Opcional)</label>
                                <textarea
                                    v-model="form.notes"
                                    id="notes"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                    placeholder="Ej: Necesito raquetas, pelotas, etc."
                                ></textarea>
                                <p v-if="errors.notes" class="text-red-600 text-sm mt-1">{{ errors.notes }}</p>
                            </div>

                            <!-- Resumen -->
                            <div v-if="selectedSpace && form.start_time && form.end_time" class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Resumen de tu Reserva</h4>
                                <p class="text-sm text-gray-600 mb-1"><strong>Cancha:</strong> {{ selectedSpace.name }}</p>
                                <p class="text-sm text-gray-600 mb-1"><strong>Inicio:</strong> {{ formatDateTime(form.start_time) }}</p>
                                <p class="text-sm text-gray-600 mb-1"><strong>Fin:</strong> {{ formatDateTime(form.end_time) }}</p>
                                <p class="text-sm text-gray-600 mb-2"><strong>Duración:</strong> {{ calculateDuration() }} horas</p>
                                <p class="text-lg font-semibold text-tennis-green">
                                    Total: ${{ calculateTotal() }}
                                </p>
                            </div>

                            <!-- Botones -->
                            <div class="flex gap-4 pt-6">
                                <button
                                    type="submit"
                                    class="flex-1 bg-tennis-green text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition"
                                >
                                    Crear Reserva
                                </button>
                                <Link href="/reservations" class="flex-1 bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition text-center">
                                    Cancelar
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { reactive, ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    spaces: Array,
});

const form = reactive({
    space_id: '',
    start_time: '',
    end_time: '',
    notes: '',
});

const errors = ref({});
const selectedSpace = ref(null);

const updateSpaceInfo = () => {
    selectedSpace.value = props.spaces.find(s => s.id === parseInt(form.space_id)) || null;
};

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('es-AR');
};

const calculateDuration = () => {
    if (!form.start_time || !form.end_time) return 0;
    const start = new Date(form.start_time);
    const end = new Date(form.end_time);
    return ((end - start) / (1000 * 60 * 60)).toFixed(1);
};

const calculateTotal = () => {
    if (!selectedSpace.value) return 0;
    const duration = calculateDuration();
    return (selectedSpace.value.price_per_hour * duration).toFixed(2);
};

const submitForm = async () => {
    try {
        const startTime = new Date(form.start_time).toISOString().slice(0, 19).replace('T', ' ');
        const endTime = new Date(form.end_time).toISOString().slice(0, 19).replace('T', ' ');

        const response = await fetch('/api/reservations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                space_id: parseInt(form.space_id),
                start_time: startTime,
                end_time: endTime,
                notes: form.notes,
            }),
        });

        if (!response.ok) {
            const data = await response.json();
            errors.value = data.errors || {};
        } else {
            window.location.href = '/reservations';
        }
    } catch (error) {
        console.error('Error:', error);
    }
};
</script>
