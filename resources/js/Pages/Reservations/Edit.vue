<template>
    <AppLayout title="Editar Reserva">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Reserva</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div v-if="errors.general" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ errors.general }}</span>
                            </div>

                            <!-- Cancha -->
                            <div>
                                <label for="space_id" class="block text-sm font-medium text-gray-700 mb-2">Selecciona una Cancha</label>
                                <select
                                    v-model="form.space_id"
                                    id="space_id"
                                    required
                                    @change="handleSpaceChange"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Seleccionar cancha</option>
                                    <option v-for="space in spaces" :key="space.id" :value="space.id">
                                        {{ space.name }} - ${{ space.price_per_hour }}/hora
                                    </option>
                                </select>
                                <p v-if="errors.space_id" class="text-red-600 text-sm mt-1">{{ errors.space_id }}</p>
                            </div>

                            <!-- Fecha de la reserva -->
                            <div>
                                <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha de la Reserva</label>
                                <input
                                    v-model="form.date"
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
                            <div v-else-if="form.space_id && form.date" class="p-4 text-center text-gray-500 bg-gray-50 rounded-lg">
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

                            <!-- Botones -->
                            <div class="flex gap-4 pt-6">
                                <button
                                    type="submit"
                                    class="flex-1 bg-tennis-green text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition disabled:opacity-50"
                                    :disabled="!selectedBlock || isSubmitting"
                                >
                                    {{ isSubmitting ? 'Actualizando...' : 'Actualizar Reserva' }}
                                </button>
                                <Link :href="route('reservations.user.index')" class="flex-1 bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition text-center">
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
import { reactive, ref, onMounted, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    reservation: Object,
    spaces: Array,
});

const form = reactive({
    space_id: props.reservation.space_id,
    date: props.reservation.start_time.split(' ')[0],
    notes: props.reservation.notes || '',
});

const errors = ref({});
const availableBlocks = ref([]);
const selectedBlock = ref(null);
const isSubmitting = ref(false);

const handleSpaceChange = () => {
    fetchAvailableBlocks();
};

const fetchAvailableBlocks = async () => {
    if (!form.space_id || !form.date) return;

    try {
        const response = await fetch(`/spaces/${form.space_id}/available-time-blocks?date=${form.date}&exclude_reservation_id=${props.reservation.id}`, {
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

        // Si la fecha y espacio son los originales, intentar preseleccionar el bloque actual
        const currentStartTime = props.reservation.start_time;
        const matchingBlock = availableBlocks.value.find(b => b.start_time === currentStartTime);
        if (matchingBlock) {
            selectedBlock.value = matchingBlock;
        } else {
            selectedBlock.value = null;
        }
    } catch (error) {
        console.error('Error fetching blocks:', error);
    }
};

const formatTime = (dateTime) => {
    return new Date(dateTime).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
};

const submitForm = async () => {
    isSubmitting.value = true;
    errors.value = {};

    try {
        const response = await fetch(`/reservations/${props.reservation.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                space_id: form.space_id,
                start_time: selectedBlock.value.start_time,
                end_time: selectedBlock.value.end_time,
                notes: form.notes,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            errors.value = data.errors || { general: data.message };
        } else {
            window.location.href = route('reservations.user.index');
        }
    } catch (error) {
        errors.value = { general: 'Error de red al intentar actualizar la reserva.' };
    } finally {
        isSubmitting.value = false;
    }
};

onMounted(() => {
    fetchAvailableBlocks();
});
</script>
