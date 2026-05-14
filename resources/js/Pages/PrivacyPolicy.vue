<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <Head title="Política de Privacidad" />

    <div class="min-h-screen bg-gray-50">
        <!-- Navegación -->
        <nav class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-4xl mx-auto px-6 lg:px-8 py-4 flex items-center justify-between">
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
        <main class="max-w-4xl mx-auto px-6 lg:px-8 py-12">
            <div class="bg-white rounded-lg shadow-md p-8 md:p-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Política de Privacidad</h1>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-6">
                    <!-- Sección 1 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introducción</h2>
                        <p>
                            En el Club de Tenis, nos comprometemos a proteger tu privacidad. Esta Política de Privacidad explica cómo recopilamos, usamos, divulgamos y salvaguardamos tu información cuando utilizas nuestra plataforma de reserva de canchas.
                        </p>
                    </section>

                    <!-- Sección 2 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">2. No vendemos datos a google</h2>
                        <p>
                            Que se jodan los monopolios 
                        </p>
                       
                    </section>

                    <!-- Sección 3 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Tampoco a facebook</h2>
                        <p>
                            Esos si que los odio
                        </p>
                        
                    </section>

                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-12">
            <div class="max-w-4xl mx-auto px-6 lg:px-8 py-8 text-center text-gray-500 text-sm">
                <p>&copy; 2026 Club de Tenis. Todos los derechos reservados.</p>
            </div>
        </footer>
    </div>
</template>
