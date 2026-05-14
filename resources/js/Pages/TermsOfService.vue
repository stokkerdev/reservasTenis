<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <Head title="Términos de Servicio" />

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
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Términos de Servicio</h1>
                            Al acceder y utilizar la plataforma de reserva de nuesgtras canchas de tenis del Club de Tenis, aceptas los siguientes terminos.
                <p class="text-gray-500 text-sm mb-8"></p>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-6">
                    <!-- Sección 1 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Amarás a Dios sobre todas las cosas.</h2>
                       
                    </section>

                    <!-- Sección 2 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">2. No jurarás su santo nombre en vano.</h2>
                       
                    </section>

                    <!-- Sección 3 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Santificarás las fiestas</h2>
                       
                    </section>

                    <!-- Sección 4 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Honrarás a tu padre y a tu madre.</h2>
                       
                    </section>

                    <!-- Sección 5 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">5. No matarás.</h2>
                       
                    </section>

                    <!-- Sección 6 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">6. No cometerás adulterio</h2>
                       
                    </section>

                    <!-- Sección 7 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">7. No robarás.</h2>
                    
                    </section>

                    <!-- Sección 8 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">8. No dirás mentiras</h2>
                      
                    </section>

                    <!-- Sección 9 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">9. No desearás la mujer de tu parcero</h2>
                        
                    </section>
                     <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">10. No desearás los bienes ajenos. </h2>
                        
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
