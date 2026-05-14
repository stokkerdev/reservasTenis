<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
});

const submitForm = () => {
    form.post(route('contact.store'), {
        onSuccess: () => {
            form.reset();
            alert('Mensaje enviado correctamente. Nos pondremos en contacto pronto.');
        },
        onError: () => {
            alert('Error al enviar el mensaje. Por favor intenta de nuevo.');
        },
    });
};

const contactMethods = [
    {
        icon: '📞',
        title: 'Teléfono',
        value: '+34 XXX XXX XXX',
        description: 'Llámanos de lunes a viernes de 9:00 a 18:00',
    },
    {
        icon: '📧',
        title: 'Correo Electrónico',
        value: 'info@clubdetenis.com',
        description: 'Responderemos en 24 horas',
    },
    {
        icon: '📍',
        title: 'Ubicación',
        value: 'Calle Principal 123',
        description: 'Ciudad, País',
    },
];
</script>

<template>
    <Head title="Contacto" />

    <div class="min-h-screen bg-gray-50">
        <!-- Navegación -->
        <nav class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 py-4 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2 hover:opacity-80 transition">
                    <ApplicationLogo class="h-8 w-auto" />
                    <span class="text-tennis-green font-bold">CLUB DE TENIS</span>
                </Link>
                <Link href="/" class="text-gray-600 hover:text-tennis-green transition font-medium">
                    Volver al inicio
                </Link>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="max-w-6xl mx-auto px-6 lg:px-8 py-12">
            <!-- Encabezado -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Ponte en Contacto</h1>
                <p class="text-xl text-gray-600">¿Tienes preguntas? Nos encantaría escucharte. Envíanos un mensaje y te responderemos lo antes posible.</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 mb-12">
                <!-- Métodos de Contacto -->
                <div v-for="method in contactMethods" :key="method.title" class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="text-5xl mb-4">{{ method.icon }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ method.title }}</h3>
                    <p class="text-tennis-green font-semibold mb-2">{{ method.value }}</p>
                    <p class="text-gray-600 text-sm">{{ method.description }}</p>
                </div>
            </div>

            <!-- Formulario de Contacto -->
            <div class="bg-white rounded-lg shadow-md p-8 md:p-12 max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Envíanos un Mensaje</h2>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-green"
                            placeholder="Tu nombre"
                        />
                        <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-green"
                            placeholder="tu@email.com"
                        />
                        <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono (Opcional)</label>
                        <input
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-green"
                            placeholder="+34 XXX XXX XXX"
                        />
                        <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
                    </div>

                    <!-- Asunto -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Asunto</label>
                        <select
                            id="subject"
                            v-model="form.subject"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-green"
                        >
                            <option value="">Selecciona un asunto</option>
                            <option value="reserva">Problema con una reserva</option>
                            <option value="pago">Problema con el pago</option>
                            <option value="tecnico">Problema técnico</option>
                            <option value="sugerencia">Sugerencia o mejora</option>
                            <option value="otro">Otro</option>
                        </select>
                        <p v-if="form.errors.subject" class="text-red-500 text-sm mt-1">{{ form.errors.subject }}</p>
                    </div>

                    <!-- Mensaje -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Mensaje</label>
                        <textarea
                            id="message"
                            v-model="form.message"
                            required
                            rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tennis-green"
                            placeholder="Cuéntanos cómo podemos ayudarte..."
                        ></textarea>
                        <p v-if="form.errors.message" class="text-red-500 text-sm mt-1">{{ form.errors.message }}</p>
                    </div>

                    <!-- Botón Enviar -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-tennis-green text-white font-bold py-3 rounded-lg hover:bg-green-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Enviando...' : 'Enviar Mensaje' }}
                    </button>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-12">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 py-8 text-center text-gray-500 text-sm">
                <p>&copy; 2026 Club de Tenis. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>
</template>
