<template>
    <AppLayout title="Crear Disponibilidad">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nueva Disponibilidad</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="form.post(route('availabilities.store'))" class="space-y-6">
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
                                <p v-if="form.errors.space_id" class="text-red-600 text-sm mt-1">{{ form.errors.space_id }}</p>
                            </div>

                            <!-- Día de la Semana -->
                            <div>
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-2">Día de la Semana</label>
                                <select
                                    v-model="form.day_of_week"
                                    id="day_of_week"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="">Seleccionar día</option>
                                    <option value="1">Lunes</option>
                                    <option value="2">Martes</option>
                                    <option value="3">Miércoles</option>
                                    <option value="4">Jueves</option>
                                    <option value="5">Viernes</option>
                                    <option value="6">Sábado</option>
                                    <option value="0">Domingo</option>
                                </select>
                                <p v-if="form.errors.day_of_week" class="text-red-600 text-sm mt-1">{{ form.errors.day_of_week }}</p>
                            </div>

                            <!-- Hora de Inicio -->
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Hora de Inicio</label>
                                <input
                                    v-model="form.start_time"
                                    type="time"
                                    id="start_time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="form.errors.start_time" class="text-red-600 text-sm mt-1">{{ form.errors.start_time }}</p>
                            </div>

                            <!-- Hora de Fin -->
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Hora de Fin</label>
                                <input
                                    v-model="form.end_time"
                                    type="time"
                                    id="end_time"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="form.errors.end_time" class="text-red-600 text-sm mt-1">{{ form.errors.end_time }}</p>
                            </div>

                            <!-- Botones -->
                            <div class="flex gap-4 pt-6">
                                <button
                                    type="submit"
                                    class="flex-1 bg-tennis-green text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition"
                                    :disabled="form.processing"
                                >
                                    Crear Disponibilidad
                                </button>
                                <Link :href="route('availabilities.index')" class="flex-1 bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition text-center">
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
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    spaces: Array,
});

const form = useForm({
    space_id: '',
    day_of_week: '',
    start_time: '',
    end_time: '',
});
</script>
