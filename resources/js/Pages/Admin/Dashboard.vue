<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
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
            mostBookedCourts: [],
            peakHours: [],
            revenueBySpace: [],
            totalRevenue: 0,
            reservationsByMonth: [],
            statusBreakdown: [],
            totalReservations: 0,
            totalUsers: 0,
            totalSpaces: 0,
        }),
    },
});

// ─── Canchas más reservadas (Bar) ────────────────────────────────────────────
const mostBookedData = computed(() => ({
    labels: props.stats.mostBookedCourts.map(c => c.name),
    datasets: [{
        label: 'Reservas',
        data: props.stats.mostBookedCourts.map(c => c.total),
        backgroundColor: [
            'rgba(34, 197, 94, 0.85)',
            'rgba(6, 182, 212, 0.85)',
            'rgba(59, 130, 246, 0.85)',
            'rgba(168, 85, 247, 0.85)',
            'rgba(249, 115, 22, 0.85)',
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

// ─── Horarios más apetecidos (Bar) ───────────────────────────────────────────
const peakHoursData = computed(() => ({
    labels: props.stats.peakHours.map(h => h.hour),
    datasets: [{
        label: 'Reservas',
        data: props.stats.peakHours.map(h => h.total),
        backgroundColor: 'rgba(6, 182, 212, 0.75)',
        borderColor: 'rgb(6, 182, 212)',
        borderWidth: 2,
        borderRadius: 4,
    }],
}));

// ─── Ingresos por cancha (Bar horizontal) ────────────────────────────────────
const revenueData = computed(() => ({
    labels: props.stats.revenueBySpace.map(s => s.name),
    datasets: [{
        label: 'Ingresos ($)',
        data: props.stats.revenueBySpace.map(s => s.revenue),
        backgroundColor: 'rgba(34, 197, 94, 0.8)',
        borderColor: 'rgb(34, 197, 94)',
        borderWidth: 2,
        borderRadius: 6,
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
    confirmada: 'rgba(34, 197, 94, 0.85)',  confirmed: 'rgba(34, 197, 94, 0.85)',
    pendiente: 'rgba(234, 179, 8, 0.85)',   pending: 'rgba(234, 179, 8, 0.85)',
    cancelada: 'rgba(239, 68, 68, 0.85)',   cancelled: 'rgba(239, 68, 68, 0.85)',
    rechazada: 'rgba(249, 115, 22, 0.85)',  rejected: 'rgba(249, 115, 22, 0.85)',
    finalizada: 'rgba(99, 102, 241, 0.85)', finalized: 'rgba(99, 102, 241, 0.85)',
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
    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
};

const revenueBarOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` $${ctx.parsed.y.toFixed(2)}` } },
    },
    scales: { y: { beginAtZero: true } },
};

const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} reserva(s)` } },
    },
    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
};

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { position: 'bottom' } },
};

// Formato moneda
const formatCurrency = (val) =>
    new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(val);
</script>

<template>
    <AppLayout title="Panel de Estadísticas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Estadísticas
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <!-- Encabezado -->
                <div class="bg-gradient-to-r from-tennis-green to-green-700 rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="text-2xl font-bold mb-1">Panel de Administración</h3>
                    <p class="text-green-100">Resumen general de la actividad del sistema de reservas.</p>
                </div>

                <!-- KPIs principales -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total reservas -->
                    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100 border-width-10" >
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-tennis-green/10 rounded-full p-3">
                                <svg class="w-6 h-6 text-tennis-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Total Reservas</p>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ stats.totalReservations }}</p>
                    </div>

                    <!-- Ingresos totales -->
                    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-green-100 rounded-full p-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Ingresos Totales</p>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">${{ stats.totalRevenue.toFixed(2) }}</p>
                    </div>

                    <!-- Usuarios activos -->
                    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Usuarios</p>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ stats.totalUsers }}</p>
                    </div>

                    <!-- Canchas activas -->
                    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-tennis-cyan/10 rounded-full p-3">
                                <svg class="w-6 h-6 text-tennis-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Canchas Activas</p>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ stats.totalSpaces }}</p>
                    </div>
                </div>

                <!-- Fila 1: Canchas más reservadas + Estado de reservas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Canchas más reservadas -->
                    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                        <h4 class="text-base font-semibold text-gray-700 mb-4">Canchas Más Reservadas</h4>
                        <div v-if="stats.mostBookedCourts.length > 0" class="h-64">
                            <Bar :data="mostBookedData" :options="barOptions" />
                        </div>
                        <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                    </div>

                    <!-- Estado de reservas -->
                    <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                        <h4 class="text-base font-semibold text-gray-700 mb-4">Distribución por Estado</h4>
                        <div v-if="stats.statusBreakdown.length > 0" class="h-64">
                            <Doughnut :data="statusBreakdownData" :options="doughnutOptions" />
                        </div>
                        <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                    </div>
                </div>

                <!-- Fila 2: Horarios más apetecidos -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                    <h4 class="text-base font-semibold text-gray-700 mb-4">Horarios Más Solicitados</h4>
                    <div v-if="stats.peakHours.length > 0" class="h-64">
                        <Bar :data="peakHoursData" :options="barOptions" />
                    </div>
                    <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                </div>

                <!-- Fila 3: Ingresos por cancha -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-base font-semibold text-gray-700">Ingresos por Cancha</h4>
                        <span class="text-sm text-gray-500">Total: <strong class="text-tennis-green">${{ stats.totalRevenue.toFixed(2) }}</strong></span>
                    </div>
                    <div v-if="stats.revenueBySpace.length > 0">
                        <div class="h-64 mb-6">
                            <Bar :data="revenueData" :options="revenueBarOptions" />
                        </div>
                        <!-- Tabla de ingresos -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3 rounded-l-lg">Cancha</th>
                                        <th class="px-4 py-3 text-right">Horas</th>
                                        <th class="px-4 py-3 text-right rounded-r-lg">Ingresos</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="space in stats.revenueBySpace" :key="space.name" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ space.name }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ space.hours }} h</td>
                                        <td class="px-4 py-3 text-right font-semibold text-tennis-green">${{ space.revenue.toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                </div>

                <!-- Fila 4: Actividad mensual -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-100">
                    <h4 class="text-base font-semibold text-gray-700 mb-4">Actividad Mensual (últimos 6 meses)</h4>
                    <div v-if="stats.reservationsByMonth.length > 0" class="h-64">
                        <Line :data="reservationsByMonthData" :options="lineOptions" />
                    </div>
                    <p v-else class="text-gray-400 text-sm text-center py-10">Sin datos disponibles</p>
                </div>

                

            </div>
        </div>
    </AppLayout>
</template>
