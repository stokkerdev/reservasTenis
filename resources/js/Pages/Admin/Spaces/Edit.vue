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
                                <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
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
                                <p v-if="form.errors.slug" class="text-red-600 text-sm mt-1">{{ form.errors.slug }}</p>
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
                                <p v-if="form.errors.type" class="text-red-600 text-sm mt-1">{{ form.errors.type }}</p>
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
                                <p v-if="form.errors.capacity" class="text-red-600 text-sm mt-1">{{ form.errors.capacity }}</p>
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
                                <p v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</p>
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
                                <p v-if="form.errors.price_per_hour" class="text-red-600 text-sm mt-1">{{ form.errors.price_per_hour }}</p>
                            </div>

                            <!-- Imagen -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Imagen de la Cancha</label>
                                <div v-if="space.image_path" class="mb-2">
                                    <img :src="`/storage/${space.image_path}`" alt="Imagen actual" class="w-32 h-32 object-cover rounded-lg border">
                                </div>
                                <input
                                    type="file"
                                    id="image"
                                    @input="form.image = $event.target.files[0]"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-cyan"
                                    accept="image/*"
                                />
                                <p v-if="form.errors.image" class="text-red-600 text-sm mt-1">{{ form.errors.image }}</p>
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
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    space: Object,
});

const form = useForm({
    _method: 'PUT', // Necesario para enviar archivos con PUT en Laravel
    name: props.space.name,
    slug: props.space.slug,
    type: props.space.type,
    capacity: props.space.capacity,
    description: props.space.description,
    price_per_hour: props.space.price_per_hour,
    is_active: props.space.is_active,
    image: null,
});

const submitForm = () => {
    // Usamos POST con _method: 'PUT' para que soporte la carga de archivos
    form.post(`/admin/spaces/${props.space.id}`);
};
</script>
