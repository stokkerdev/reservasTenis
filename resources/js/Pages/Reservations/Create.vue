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
                                    @change="fetchAvailableBlocks"
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

                            <!-- Fecha de la reserva -->
                            <div>
                                <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de la Reserva</label>
                                <input
                                    v-model="selectedDate"
                                    type="date"
                                    id="reservation_date"
                                    required
                                    @change="fetchAvailableBlocks"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.date" class="text-red-600 text-sm mt-1">{{ errors.date }}</p>
                            </div>

                            <!-- Bloques horarios disponibles -->
                            <div v-if="availableBlocks.length > 0">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona un Bloque Horario</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <div v-for="block in availableBlocks" :key="block.start_time" class="relative">
                                        <input
                                            type="radio"
                                            :id="`block-${block.start_time}`"
                                            :value="block"
                                            v-model="selectedBlock"
                                            class="hidden peer"
                                        />
                                        <label
                                            :for="`block-${block.start_time}`"
                                            class="block w-full p-3 border border-gray-300 rounded-lg text-center cursor-pointer peer-checked:bg-tennis-green peer-checked:text-white peer-checked:border-tennis-green hover:bg-gray-50 transition"
                                        >
                                            {{ formatTime(block.start_time) }} - {{ formatTime(block.end_time) }}
                                        </label>
                                    </div>
                                </div>
                                <p v-if="errors.block" class="text-red-600 text-sm mt-1">{{ errors.block }}</p>
                            </div>
                            <div v-else-if="form.space_id && selectedDate" class="p-4 text-center text-gray-500 bg-gray-50 rounded-lg">
                                No hay bloques horarios disponibles para la fecha y cancha seleccionadas.
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
                            <div v-if="selectedSpace && selectedBlock" class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Resumen de tu Reserva</h4>
                                <p class="text-sm text-gray-600 mb-1"><strong>Cancha:</strong> {{ selectedSpace.name }}</p>
                                <p class="text-sm text-gray-600 mb-1"><strong>Inicio:</strong> {{ formatDateTime(selectedBlock.start_time) }}</p>
                                <p class="text-sm text-gray-600 mb-1"><strong>Fin:</strong> {{ formatDateTime(selectedBlock.end_time) }}</p>
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
                                    :disabled="!selectedBlock"
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
import { reactive, ref, watch } from 'vue';
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
const selectedDate = ref('');
const availableBlocks = ref([]);
const selectedBlock = ref(null);

// Watch for changes in space_id or selectedDate to fetch available blocks
watch([() => form.space_id, selectedDate], () => {
    if (form.space_id && selectedDate.value) {
        fetchAvailableBlocks();
    } else {
        availableBlocks.value = [];
        selectedBlock.value = null;
    }
});

// Watch for changes in selectedBlock to update form.start_time and form.end_time
watch(selectedBlock, (newBlock) => {
    if (newBlock) {
        form.start_time = newBlock.start_time;
        form.end_time = newBlock.end_time;
    } else {
        form.start_time = '';
        form.end_time = '';
    }
});

const fetchAvailableBlocks = async () => {
        selectedSpace.value = props.spaces.find(s => s.id === parseInt(form.space_id)) || null;
        if (!form.space_id || !selectedDate.value) {
            availableBlocks.value = [];
            selectedBlock.value = null;
            return;
        }

        try {
            const response = await fetch(`/spaces/${form.space_id}/available-time-blocks?date=${selectedDate.value}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });
            if (!response.ok) {
                throw new Error('Failed to fetch available time blocks');
            }
            const data = await response.json();
            availableBlocks.value = data;
            selectedBlock.value = null;
        } catch (error) {
            console.error('Error fetching available blocks:', error);
            availableBlocks.value = [];
            selectedBlock.value = null;
        }
    };

const formatDateTime = (dateTime) => {
    return new Date(dateTime).toLocaleString('es-AR');
};

const formatTime = (dateTime) => {
    return new Date(dateTime).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
};

const calculateDuration = () => {
    if (!selectedBlock.value) return 0;
    const start = new Date(selectedBlock.value.start_time);
    const end = new Date(selectedBlock.value.end_time);
    return ((end - start) / (1000 * 60 * 60)).toFixed(1);
};

const calculateTotal = () => {
    if (!selectedSpace.value || !selectedBlock.value) return 0;
    const duration = calculateDuration();
    return (selectedSpace.value.price_per_hour * duration).toFixed(2);
};

const submitForm = async () => {
        errors.value = {};
        if (!selectedBlock.value) {
            errors.value.block = 'Por favor, selecciona un bloque horario.';
            return;
        }

        try {
            const response = await fetch(route('reservations.store'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    space_id: parseInt(form.space_id),
                    start_time: form.start_time,
                    end_time: form.end_time,
                    notes: form.notes,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                if (response.status === 409) {
                    errors.value.general = data.errors?.general || data.message;
                } else {
                    errors.value = data.errors || {};
                }
            } else {
                window.location.href = route('reservations.user.index');
            }
        } catch (error) {
            console.error('Error:', error);
            errors.value.general = 'Ocurrió un error al procesar tu reserva.';
        }
    };
</script>
