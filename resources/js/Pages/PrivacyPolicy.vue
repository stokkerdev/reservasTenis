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
                <p class="text-gray-500 text-sm mb-8">Última actualización: {{ new Date().toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>

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
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Información que Recopilamos</h2>
                        <p>
                            Recopilamos información que proporcionas directamente, incluyendo:
                        </p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Nombre completo</li>
                            <li>Correo electrónico</li>
                            <li>Número de teléfono</li>
                            <li>Dirección</li>
                            <li>Información de pago</li>
                            <li>Historial de reservas</li>
                            <li>Preferencias de horarios</li>
                        </ul>
                    </section>

                    <!-- Sección 3 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Cómo Usamos Tu Información</h2>
                        <p>
                            Utilizamos la información recopilada para:
                        </p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Procesar y confirmar tus reservas</li>
                            <li>Enviarte confirmaciones y notificaciones por correo electrónico</li>
                            <li>Procesar pagos</li>
                            <li>Mejorar nuestro servicio</li>
                            <li>Responder a tus consultas y solicitudes</li>
                            <li>Cumplir con obligaciones legales</li>
                            <li>Prevenir fraude y actividades ilícitas</li>
                        </ul>
                    </section>

                    <!-- Sección 4 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Compartir Tu Información</h2>
                        <p>
                            No vendemos, alquilamos ni compartimos tu información personal con terceros, excepto:
                        </p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Con proveedores de servicios que nos ayudan a operar la plataforma</li>
                            <li>Cuando sea requerido por ley</li>
                            <li>Para proteger nuestros derechos legales</li>
                            <li>Con tu consentimiento explícito</li>
                        </ul>
                    </section>

                    <!-- Sección 5 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Seguridad de Datos</h2>
                        <p>
                            Implementamos medidas de seguridad técnicas, administrativas y físicas para proteger tu información personal contra acceso no autorizado, alteración, divulgación o destrucción. Sin embargo, ningún método de transmisión por Internet es 100% seguro.
                        </p>
                    </section>

                    <!-- Sección 6 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Retención de Datos</h2>
                        <p>
                            Retenemos tu información personal durante el tiempo que sea necesario para proporcionar nuestros servicios y cumplir con nuestras obligaciones legales. Puedes solicitar la eliminación de tu cuenta en cualquier momento.
                        </p>
                    </section>

                    <!-- Sección 7 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Tus Derechos</h2>
                        <p>
                            Tienes derecho a:
                        </p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Acceder a tu información personal</li>
                            <li>Corregir información inexacta</li>
                            <li>Solicitar la eliminación de tu información</li>
                            <li>Optar por no recibir comunicaciones de marketing</li>
                            <li>Solicitar portabilidad de datos</li>
                        </ul>
                    </section>

                    <!-- Sección 8 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Cookies</h2>
                        <p>
                            Nuestro sitio utiliza cookies para mejorar tu experiencia. Las cookies son archivos pequeños almacenados en tu dispositivo que nos ayudan a recordar tus preferencias y personalizar tu experiencia. Puedes controlar las cookies a través de la configuración de tu navegador.
                        </p>
                    </section>

                    <!-- Sección 9 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Cambios en Esta Política</h2>
                        <p>
                            Podemos actualizar esta Política de Privacidad periódicamente. Te notificaremos sobre cambios significativos publicando la nueva política en nuestra plataforma. Tu uso continuado del servicio implica tu aceptación de los cambios.
                        </p>
                    </section>

                    <!-- Sección 10 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Contacto</h2>
                        <p>
                            Si tienes preguntas sobre esta Política de Privacidad o cómo manejamos tu información, por favor <Link href="/contacto" class="text-tennis-green font-semibold hover:underline">contáctanos</Link>.
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
