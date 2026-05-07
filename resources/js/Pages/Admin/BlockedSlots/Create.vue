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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Seleccionar cancha</option>
                                    <option v-for="space in spaces" :key="space.id" :value="space.id">
                                        {{ space.name }}
                                    </option>
                                </select>
                                <p v-if="errors.space_id" class="text-red-600 text-sm mt-1">{{ errors.space_id }}</p>
                            </div>

                            <!-- Fecha y hora de inicio -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Inicio del Bloqueo</label>
                                <input
                                    v-model="form.start_time"
                                    type="datetime-local"
                                    id="start_time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.start_time" class="text-red-600 text-sm mt-1">{{ errors.start_time }}</p>
                            </div>

                            <!-- Fecha y hora de fin -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Fin del Bloqueo</label>
                                <input
                                    v-model="form.end_time"
                                    type="datetime-local"
                                    id="end_time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.end_time" class="text-red-600 text-sm mt-1">{{ errors.end_time }}</p>
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
import { reactive, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    spaces: Array,
});

const form = reactive({
    space_id: '',
    start_time: '',
    end_time: '',
    reason: '',
});

const errors = ref({});

const submitForm = async () => {
    try {
        // Convertir datetime-local a formato ISO
        const startTime = new Date(form.start_time).toISOString().slice(0, 19).replace('T', ' ');
        const endTime = new Date(form.end_time).toISOString().slice(0, 19).replace('T', ' ');

        const response = await fetch('/api/blocked-slots', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify({
                ...form,
                start_time: startTime,
                end_time: endTime,
            }),
        });

        if (!response.ok) {
            const data = await response.json();
            errors.value = data.errors || {};
        } else {
            window.location.href = '/admin/blocked-slots';
        }
    } catch (error) {
        console.error('Error:', error);
    }
};
</script>
