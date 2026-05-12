<script setup>
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, Link } from '@inertiajs/vue3';
import { Bar, Doughnut, Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
    PointElement,
    LineElement,
    Filler,
} from 'chart.js';

ChartJS.register(
    Title, Tooltip, Legend,
    BarElement, CategoryScale, LinearScale,
    ArcElement, PointElement, LineElement, Filler
);

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalReservations: 0,
            hoursPlayed: 0,
            favoriteCourts: [],
            preferredHours: [],
            reservationsByMonth: [],
            statusBreakdown: [],
        }),
    },
    spaces: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const showStats = ref(false);
const filterType = ref('');

// ─── Filtrado de espacios ───────────────────────────────────────────────────
const filteredSpaces = computed(() => {
    if (!filterType.value) return props.spaces;
    return props.spaces.filter(s => s.type === filterType.value);
});

// ─── Formato de precio ──────────────────────────────────────────────────────
const formatPrice = (price) => {
    return new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(price);
};

// ─── Obtener imagen de espacio ──────────────────────────────────────────────
const getSpaceImage = (path) => {
    return path ? `/storage/${path}` : '/images/default-court.jpg';
};

// ─── Canchas favoritas (Bar) ─────────────────────────────────────────────────
const favoriteCourtsData = computed(() => ({
    labels: props.stats.favoriteCourts.map(c => c.name),
    datasets: [{
        label: 'Reservas',
        data: props.stats.favoriteCourts.map(c => c.total),
        backgroundColor: [
            'rgba(34, 197, 94, 0.8)',
            'rgba(6, 182, 212, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(249, 115, 22, 0.8)',
        ],
        borderColor: [
            'rgb(34, 197, 94)',
            'rgb(6, 182, 212)',
            'rgb(59, 130, 246)',
            'rgb(168, 85, 247)',
            'rgb(249, 115, 22)',
        ],
        borderWidth: 2,
        borderRadius: 6,
    }],
}));

// ─── Horarios preferidos (Bar horizontal) ────────────────────────────────────
const preferredHoursData = computed(() => ({
    labels: props.stats.preferredHours.map(h => h.hour),
    datasets: [{
        label: 'Reservas por hora',
        data: props.stats.preferredHours.map(h => h.total),
        backgroundColor: 'rgba(6, 182, 212, 0.75)',
        borderColor: 'rgb(6, 182, 212)',
        borderWidth: 2,
        borderRadius: 4,
    }],
}));

// ─── Reservas por mes (Line) ──────────────────────────────────────────────────
const reservationsByMonthData = computed(() => ({
    labels: props.stats.reservationsByMonth.map(m => m.month),
    datasets: [{
        label: 'Reservas',
        data: props.stats.reservationsByMonth.map(m => m.total),
        borderColor: 'rgb(34, 197, 94)',
        backgroundColor: 'rgba(34, 197, 94, 0.15)',
        fill: true,
        tension: 0.4,
        pointBackgroundColor: 'rgb(34, 197, 94)',
        pointRadius: 5,
    }],
}));

// ─── Estado de reservas (Doughnut) ───────────────────────────────────────────
const statusLabels = {
    confirmada: 'Confirmada', confirmed: 'Confirmada',
    pendiente: 'Pendiente',   pending: 'Pendiente',
    cancelada: 'Cancelada',   cancelled: 'Cancelada',
    rechazada: 'Rechazada',   rejected: 'Rechazada',
    finalizada: 'Finalizada', finalized: 'Finalizada',
};
const statusColors = {
    confirmada: 'rgba(34, 197, 94, 0.85)',   confirmed: 'rgba(34, 197, 94, 0.85)',
    pendiente: 'rgba(234, 179, 8, 0.85)',     pending: 'rgba(234, 179, 8, 0.85)',
    cancelada: 'rgba(239, 68, 68, 0.85)',     cancelled: 'rgba(239, 68, 68, 0.85)',
    rechazada: 'rgba(249, 115, 22, 0.85)',    rejected: 'rgba(249, 115, 22, 0.85)',
    finalizada: 'rgba(99, 102, 241, 0.85)',   finalized: 'rgba(99, 102, 241, 0.85)',
};

const statusBreakdownData = computed(() => ({
    labels: props.stats.statusBreakdown.map(s => statusLabels[s.status] || s.status),
    datasets: [{
        data: props.stats.statusBreakdown.map(s => s.total),
        backgroundColor: props.stats.statusBreakdown.map(s => statusColors[s.status] || 'rgba(156, 163, 175, 0.85)'),
        borderWidth: 2,
        borderColor: '#fff',
    }],
}));

// ─── Opciones de gráficos ─────────────────────────────────────────────────────
const barOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} reserva(s)` } },
    },
    scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 } },
    },
};

const hoursBarOptions = {
    ...barOptions,
    plugins: {
        ...barOptions.plugins,
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} reserva(s)` } },
    },
};

const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} reserva(s)` } },
    },
    scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 } },
    },
};

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'bottom' },
    },
};

const hasData = computed(() => props.stats.totalReservations > 0);
</script>

<template>
    <AppLayout title="Mis Estadísticas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Estadísticas
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <!-- Bienvenida -->
                <div class="bg-gradient-to-r from-tennis-green to-green-700 rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="text-2xl font-bold mb-1">¡Hola, {{ user.name }}!</h3>
                    <p class="text-green-100">Aquí tienes un resumen de tu actividad en las canchas.</p>
                </div>

                <!-- Accesos rápidos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <Link :href="route('reservations.create')"
                        class="flex items-center gap-3 bg-tennis-green text-white font-semibold px-6 py-4 rounded-xl hover:bg-green-700 transition shadow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva Reserva
                    </Link>
                    <Link :href="route('reservations.user.index')"
                        class="flex items-center gap-3 bg-white text-tennis-green font-semibold px-6 py-4 rounded-xl hover:bg-gray-50 transition shadow border border-tennis-green">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Ver Mis Reservas
                    </Link>
                </div>

                <!-- Botón Mira tus estadísticas -->
                <div class="flex justify-center">
                    <button @click="showStats = !showStats"
                        class="flex items-center gap-3 bg-tennis-cyan text-tennis-green font-bold px-8 py-3 rounded-full hover:bg-cyan-200 transition shadow-md border-2 border-tennis-green/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        {{ showStats ? 'Ocultar' : 'Mira tus' }} Estadísticas
                    </button>
                </div>

                <!-- Estadísticas (Colapsables) -->
                <transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <div v-if="showStats" class="space-y-8">
                        <!-- Tarjetas de resumen -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Total reservas -->
                            <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4 border border-gray-100">
                                <div class="bg-tennis-green/10 rounded-full p-4">
                                    <svg class="w-8 h-8 text-tennis-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Total de Reservas</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ stats.totalReservations }}</p>
                                </div>
                            </div>

                            <!-- Horas jugadas -->
                            <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4 border border-gray-100">
                                <div class="bg-tennis-cyan/10 rounded-full p-4">
                                    <svg class="w-8 h-8 text-tennis-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Horas Jugadas</p>
                                    <p class="text-3xl font-bold text-gray-800">{{ stats.hoursPlayed }}<span class="text-lg font-normal text-gray-400"> h</span></p>
                                </div>
                            </div>

                            <!-- Cancha favorita -->
                            <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4 border border-gray-100">
                                <div class="bg-yellow-100 rounded-full p-4">
                                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">Cancha Favorita</p>
                                    <p class="text-lg font-bold text-gray-800 truncate max-w-[160px]">
                                        {{ stats.favoriteCourts.length > 0 ? stats.favoriteCourts[0].name : 'Sin datos' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                                <h4 class="text-base font-semibold text-gray-700 mb-4">Canchas Reservadas</h4>
                                <div v-if="stats.favoriteCourts.length > 0" class="h-56">
                                    <Bar :data="favoriteCourtsData" :options="barOptions" />
                                </div>
                                <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                            </div>

                            <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                                <h4 class="text-base font-semibold text-gray-700 mb-4">Estado de mis Reservas</h4>
                                <div v-if="stats.statusBreakdown.length > 0" class="h-56">
                                    <Doughnut :data="statusBreakdownData" :options="doughnutOptions" />
                                </div>
                                <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                            <h4 class="text-base font-semibold text-gray-700 mb-4">Horarios Preferidos</h4>
                            <div v-if="stats.preferredHours.length > 0" class="h-56">
                                <Bar :data="preferredHoursData" :options="hoursBarOptions" />
                            </div>
                            <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                        </div>

                        <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                            <h4 class="text-base font-semibold text-gray-700 mb-4">Actividad Mensual (últimos 6 meses)</h4>
                            <div v-if="stats.reservationsByMonth.length > 0" class="h-56">
                                <Line :data="reservationsByMonthData" :options="lineOptions" />
                            </div>
                            <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                        </div>
                    </div>
                </transition>

                <!-- Sección de Canchas Disponibles (Presentación Bonita) -->
                <div class="space-y-6">
                    <h3 class="text-3xl font-extrabold text-gray-900 text-center">Nuestras Canchas</h3>
                    
                    <!-- Filtros -->
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <button 
                            @click="filterType = ''"
                            :class="filterType === '' ? 'bg-tennis-green text-white shadow-lg scale-105' : 'bg-white text-gray-700 border border-gray-200'"
                            class="px-8 py-2 rounded-full font-bold transition duration-300"
                        >
                            Todas
                        </button>
                        <button 
                            @click="filterType = 'cesped'"
                            :class="filterType === 'cesped' ? 'bg-tennis-green text-white shadow-lg scale-105' : 'bg-white text-gray-700 border border-gray-200'"
                            class="px-8 py-2 rounded-full font-bold transition duration-300"
                        >
                            Césped
                        </button>
                        <button 
                            @click="filterType = 'arcilla'"
                            :class="filterType === 'arcilla' ? 'bg-tennis-green text-white shadow-lg scale-105' : 'bg-white text-gray-700 border border-gray-200'"
                            class="px-8 py-2 rounded-full font-bold transition duration-300"
                        >
                            Arcilla
                        </button>
                        <button 
                            @click="filterType = 'cemento'"
                            :class="filterType === 'cemento' ? 'bg-tennis-green text-white shadow-lg scale-105' : 'bg-white text-gray-700 border border-gray-200'"
                            class="px-8 py-2 rounded-full font-bold transition duration-300"
                        >
                            Cemento
                        </button>
                    </div>

                    <!-- Grid de Canchas -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 pt-4">
                        <div v-for="space in filteredSpaces" :key="space.id" class="bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition duration-500 flex flex-col group border border-gray-100">
                            <!-- Imagen -->
                            <div class="relative h-64 overflow-hidden">
                                <img :src="getSpaceImage(space.image_path)" :alt="space.name" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                <div class="absolute top-5 right-5 bg-white/95 backdrop-blur px-4 py-1.5 rounded-2xl text-tennis-green font-black text-xs shadow-lg uppercase tracking-widest">
                                    {{ space.type }}
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div class="p-8 flex-1 flex flex-col">
                                <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-tennis-green transition duration-300">{{ space.name }}</h3>
                                <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-3">{{ space.description || 'Disfruta de una experiencia profesional en nuestras instalaciones de primer nivel.' }}</p>
                                
                                <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-[0.2em] mb-1">Precio / Hora</p>
                                        <p class="text-2xl font-black text-tennis-green">{{ formatPrice(space.price_per_hour) }}</p>
                                    </div>
                                    <Link 
                                        :href="route('spaces.show', { space: space.slug })" 
                                        class="bg-tennis-cyan text-tennis-green font-black px-6 py-3 rounded-2xl hover:bg-cyan-200 hover:shadow-lg transition duration-300 transform active:scale-95"
                                    >
                                        Ver Detalles
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="filteredSpaces.length === 0" class="text-center py-24 bg-white rounded-3xl shadow-inner border border-dashed border-gray-200">
                        <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-2">Sin canchas disponibles</h3>
                        <p class="text-gray-500 font-medium">No encontramos canchas de tipo "{{ filterType }}" en este momento.</p>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
