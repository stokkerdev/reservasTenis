<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Bloqueo por Mantenimiento</h2>
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
                                    Actualizar Bloqueo
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
import { reactive, ref, watch, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    blockedSlot: Object,
    spaces: Array,
});

const form = reactive({
    space_id: props.blockedSlot.space_id,
    start_time: props.blockedSlot.start_time,
    end_time: props.blockedSlot.end_time,
    reason: props.blockedSlot.reason,
});

const errors = ref({});
const selectedDate = ref(props.blockedSlot.start_time.split(' ')[0]);
const availableBlocks = ref([]);
const selectedBlock = ref(null);

onMounted(() => {
    fetchAvailableBlocks(true);
});

const fetchAvailableBlocks = async (isInitial = false) => {
    if (!form.space_id || !selectedDate.value) {
        availableBlocks.value = [];
        selectedBlock.value = null;
        return;
    }

    try {
        // Incluimos el ID del bloqueo actual para que no se marque como conflicto consigo mismo
        const response = await fetch(`/api/spaces/${form.space_id}/available-time-blocks?date=${selectedDate.value}&exclude_blocked_slot_id=${props.blockedSlot.id}`);
        if (!response.ok) {
            throw new Error('Failed to fetch available time blocks');
        }
        const data = await response.json();
        availableBlocks.value = data;
        
        if (isInitial) {
            // Encontrar el bloque actual en la lista de disponibles
            const currentStart = props.blockedSlot.start_time;
            selectedBlock.value = data.find(b => b.start_time === currentStart) || null;
        } else {
            selectedBlock.value = null;
        }
    } catch (error) {
        console.error('Error fetching available blocks:', error);
        availableBlocks.value = [];
        selectedBlock.value = null;
    }
};

// Watch for changes in space_id or selectedDate to fetch available blocks
watch([() => form.space_id, selectedDate], ([newSpace, newDate], [oldSpace, oldDate]) => {
    if (newSpace !== oldSpace || newDate !== oldDate) {
        fetchAvailableBlocks();
    }
});

// Watch for changes in selectedBlock to update form.start_time and form.end_time
watch(selectedBlock, (newBlock) => {
    if (newBlock) {
        form.start_time = newBlock.start_time;
        form.end_time = newBlock.end_time;
    }
});

const formatTime = (dateTime) => {
    return new Date(dateTime).toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit' });
};

const submitForm = () => {
    errors.value = {};
    if (!selectedBlock.value) {
        errors.value.block = 'Por favor, selecciona un bloque horario.';
        return;
    }

    router.put(`/admin/blocked-slots/${props.blockedSlot.id}`, {
        space_id: form.space_id,
        start_time: form.start_time,
        end_time: form.end_time,
        reason: form.reason,
    }, {
        onError: (err) => {
            errors.value = err;
        }
    });
};
</script>
