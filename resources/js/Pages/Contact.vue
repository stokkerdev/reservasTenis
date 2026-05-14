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
        value: '+57 312 261 3725',
        description: 'Me llaman el domingo por que entre semana descanso',
    },
    {
        icon: '📧',
        title: 'Correo Electrónico',
        value: 'stokkerma@gmail.com',
        description: 'Respondo apenas pueda.',
    },
    {
        icon: '📍',
        title: 'Ubicación',
        value: 'Universidad de Caldas',
        description: 'Caldas, Manizales',
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

         
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-12">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 py-8 text-center text-gray-500 text-sm">
                <p>&copy; 2026 Club de Tenis. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>
</template>
