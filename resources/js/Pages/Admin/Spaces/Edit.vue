<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Cancha: {{ space.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Nombre -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Cancha</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    id="name"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug (URL)</label>
                                <input
                                    v-model="form.slug"
                                    type="text"
                                    id="slug"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.slug" class="text-red-600 text-sm mt-1">{{ errors.slug }}</p>
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cancha</label>
                                <select
                                    v-model="form.type"
                                    id="type"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                >
                                    <option value="cesped">Césped</option>
                                    <option value="arcilla">Arcilla</option>
                                    <option value="cemento">Cemento</option>
                                    <option value="indoor">Indoor</option>
                                </select>
                                <p v-if="errors.type" class="text-red-600 text-sm mt-1">{{ errors.type }}</p>
                            </div>

                            <!-- Capacidad -->
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Capacidad (personas)</label>
                                <input
                                    v-model.number="form.capacity"
                                    type="number"
                                    id="capacity"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.capacity" class="text-red-600 text-sm mt-1">{{ errors.capacity }}</p>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                <textarea
                                    v-model="form.description"
                                    id="description"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                ></textarea>
                                <p v-if="errors.description" class="text-red-600 text-sm mt-1">{{ errors.description }}</p>
                            </div>

                            <!-- Precio por hora -->
                            <div>
                                <label for="price_per_hour" class="block text-sm font-medium text-gray-700 mb-2">Precio por Hora ($)</label>
                                <input
                                    v-model.number="form.price_per_hour"
                                    type="number"
                                    id="price_per_hour"
                                    step="0.01"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                />
                                <p v-if="errors.price_per_hour" class="text-red-600 text-sm mt-1">{{ errors.price_per_hour }}</p>
                            </div>

                            <!-- Activa -->
                            <div class="flex items-center">
                                <input
                                    v-model="form.is_active"
                                    type="checkbox"
                                    id="is_active"
                                    class="h-4 w-4 text-tennis-green focus:ring-tennis-cyan border-gray-300 rounded"
                                />
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Cancha Activa</label>
                            </div>

                            <!-- Botones -->
                            <div class="flex gap-4 pt-6">
                                <button
                                    type="submit"
                                    class="flex-1 bg-tennis-green text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition"
                                >
                                    Guardar Cambios
                                </button>
                                <Link href="/admin/spaces" class="flex-1 bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-400 transition text-center">
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
import { reactive, ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    space: Object,
});

const form = reactive({
    name: '',
    slug: '',
    type: '',
    capacity: 4,
    description: '',
    price_per_hour: 0,
    is_active: true,
});

const errors = ref({});

onMounted(() => {
    Object.assign(form, props.space);
});

const submitForm = async () => {
    try {
        const response = await fetch(`/api/spaces/${props.space.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            },
            body: JSON.stringify(form),
        });

        if (!response.ok) {
            const data = await response.json();
            errors.value = data.errors || {};
        } else {
            window.location.href = '/admin/spaces';
        }
    } catch (error) {
        console.error('Error:', error);
    }
};
</script>
