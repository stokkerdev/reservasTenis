<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Bloqueo por Mantenimiento</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Cancha -->
                            <div>
                                <label for="space_id" class="block text-sm font-medium text-gray-700 mb-2">Cancha</label>
                                <select
                                    v-model="form.space_id"
                                    id="space_id"
                                    required
                                    @change="fetchAvailableBlocks"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Seleccionar cancha</option>
                                    <option v-for="space in spaces" :key="space.id" :value="space.id">
                                        {{ space.name }}
                                    </option>
                                </select>
                                <p v-if="errors.space_id" class="text-red-600 text-sm mt-1">{{ errors.space_id }}</p>
                            </div>

                            <!-- Fecha del Bloqueo -->
                            <div>
                                <label for="blocked_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha del Bloqueo</label>
                                <input
                                    v-model="selectedDate"
                                    type="date"
                                    id="blocked_date"
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

                            <!-- Razón -->
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Razón del Bloqueo</label>
                                <textarea
                                    v-model="form.reason"
                                    id="reason"
                                    rows="4"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                    placeholder="Ej: Mantenimiento de césped, reparación de luces, etc."
                                ></textarea>
                                <p v-if="errors.reason" class="text-red-600 text-sm mt-1">{{ errors.reason }}</p>
                            </div>

                            <!-- Botones -->
                            <div class="flex gap-4 pt-6">
                                <button
                                    type="submit"
                                    class="flex-1 bg-tennis-green text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition"
                                    :disabled="!selectedBlock"
                                >
                                    Crear Bloqueo
                                </button>
                                <Link href="/admin/blocked-slots" class="flex-1 bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition text-center">
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
    reason: '',
});

const errors = ref({});
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
    if (!form.space_id || !selectedDate.value) {
        availableBlocks.value = [];
        selectedBlock.value = null;
        return;
    }

    try {
        const response = await fetch(`/spaces/${form.space_id}/available-time-blocks?date=${selectedDate.value}`);
        if (!response.ok) {
            throw new Error('Failed to fetch available time blocks');
        }
        const data = await response.json();
        availableBlocks.value = data;
        selectedBlock.value = null; // Reset selected block when space or date changes
    } catch (error) {
        console.error('Error fetching available blocks:', error);
        availableBlocks.value = [];
        selectedBlock.value = null;
    }
};

const formatTime = (dateTime) => {
    return new Date(dateTime).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
};

const submitForm = async () => {
    errors.value = {}; // Clear previous errors
    if (!selectedBlock.value) {
        errors.value.block = 'Por favor, selecciona un bloque horario.';
        return;
    }

    try {
        const response = await fetch('/admin/blocked-slots', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                space_id: parseInt(form.space_id),
                start_time: form.start_time,
                end_time: form.end_time,
                reason: form.reason,
            }),
        });

        if (!response.ok) {
            const data = await response.json();
            if (response.status === 409) {
                errors.value.general = data.message; // Conflict message
            } else {
                errors.value = data.errors || {};
            }
        } else {
            window.location.href = '/admin/blocked-slots';
        }
    } catch (error) {
        console.error('Error:', error);
        errors.value.general = 'Ocurrió un error al procesar el bloqueo.';
    }
};
</script>
