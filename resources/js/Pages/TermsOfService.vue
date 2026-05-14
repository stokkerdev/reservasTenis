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
                <p class="text-gray-500 text-sm mb-8">Última actualización: {{ new Date().toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>

                <div class="prose prose-sm max-w-none text-gray-700 space-y-6">
                    <!-- Sección 1 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Aceptación de los Términos</h2>
                        <p>
                            Al acceder y utilizar la plataforma de reserva de canchas de tenis del Club de Tenis, aceptas estar vinculado por estos Términos de Servicio. Si no estás de acuerdo con alguna parte de estos términos, no debes utilizar nuestro servicio.
                        </p>
                    </section>

                    <!-- Sección 2 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Descripción del Servicio</h2>
                        <p>
                            Nuestro servicio permite a los usuarios registrados reservar canchas de tenis disponibles en nuestras instalaciones. Proporcionamos una plataforma digital para gestionar reservas, consultar disponibilidad y recibir confirmaciones por correo electrónico.
                        </p>
                    </section>

                    <!-- Sección 3 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Registro de Cuenta</h2>
                        <p>
                            Para utilizar nuestro servicio, debes crear una cuenta proporcionando información precisa y completa. Eres responsable de mantener la confidencialidad de tu contraseña y de toda la actividad que ocurra bajo tu cuenta. Debes notificarnos inmediatamente de cualquier uso no autorizado de tu cuenta.
                        </p>
                    </section>

                    <!-- Sección 4 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Reservas y Cancelaciones</h2>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Las reservas se confirman una vez que recibes una confirmación por correo electrónico.</li>
                            <li>Puedes cancelar tu reserva hasta 24 horas antes de la hora programada sin penalización.</li>
                            <li>Las cancelaciones realizadas menos de 24 horas antes pueden estar sujetas a cargos.</li>
                            <li>El Club se reserva el derecho de cancelar reservas por razones de mantenimiento o circunstancias imprevistas.</li>
                        </ul>
                    </section>

                    <!-- Sección 5 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Política de Pagos</h2>
                        <p>
                            Los precios de las reservas se muestran claramente en la plataforma. El pago debe realizarse según los términos especificados en el momento de la reserva. Nos reservamos el derecho de cambiar los precios con aviso previo.
                        </p>
                    </section>

                    <!-- Sección 6 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Conducta del Usuario</h2>
                        <p>
                            Al utilizar nuestro servicio, aceptas no:
                        </p>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Usar la plataforma de manera ilegal o no autorizada.</li>
                            <li>Interferir con el funcionamiento normal del servicio.</li>
                            <li>Compartir tu cuenta con terceros.</li>
                            <li>Usar lenguaje ofensivo o abusivo en la plataforma.</li>
                            <li>Intentar acceder a áreas restringidas sin autorización.</li>
                        </ul>
                    </section>

                    <!-- Sección 7 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Limitación de Responsabilidad</h2>
                        <p>
                            El Club de Tenis no será responsable por daños indirectos, incidentales, especiales o consecuentes que resulten del uso o la imposibilidad de usar nuestro servicio, incluso si hemos sido informados de la posibilidad de tales daños.
                        </p>
                    </section>

                    <!-- Sección 8 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Modificaciones de los Términos</h2>
                        <p>
                            Nos reservamos el derecho de modificar estos Términos de Servicio en cualquier momento. Los cambios entrarán en vigencia cuando se publiquen en la plataforma. Tu uso continuado del servicio constituye tu aceptación de los términos modificados.
                        </p>
                    </section>

                    <!-- Sección 9 -->
                    <section>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Contacto</h2>
                        <p>
                            Si tienes preguntas sobre estos Términos de Servicio, por favor <Link href="/contacto" class="text-tennis-green font-semibold hover:underline">contáctanos</Link>.
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
