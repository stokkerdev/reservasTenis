<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    /**
     * Dashboard de estadísticas del cliente autenticado.
     */
    public function clientDashboard()
    {
        $userId = Auth::id();

        // Estados activos (soporte para inglés y español)
        $activeStatuses = ['confirmed', 'confirmada', 'pending', 'pendiente', 'finalized', 'finalizada'];

        // Total de reservas del usuario
        $totalReservations = Reservation::where('user_id', $userId)->count();

        // Total de horas jugadas (solo reservas no canceladas/rechazadas)
        $hoursPlayed = Reservation::where('user_id', $userId)
            ->whereNotIn('status', ['cancelled', 'cancelada', 'rechazada', 'rejected'])
            ->get()
            ->sum(function ($r) {
                return Carbon::parse($r->start_time)->diffInMinutes(Carbon::parse($r->end_time)) / 60;
            });

        // Canchas favoritas (top 5 por cantidad de reservas)
        $favoriteCourts = Reservation::where('user_id', $userId)
            ->whereNotIn('status', ['cancelled', 'cancelada', 'rechazada', 'rejected'])
            ->select('space_id', DB::raw('COUNT(*) as total'))
            ->groupBy('space_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('space:id,name')
            ->get()
            ->map(fn($r) => [
                'name'  => $r->space ? $r->space->name : 'Desconocida',
                'total' => (int) $r->total,
            ]);

        // Horarios preferidos (distribución por hora de inicio)
        $preferredHours = Reservation::where('user_id', $userId)
            ->whereNotIn('status', ['cancelled', 'cancelada', 'rechazada', 'rejected'])
            ->get()
            ->groupBy(fn($r) => Carbon::parse($r->start_time)->format('H:00'))
            ->map(fn($group, $hour) => ['hour' => $hour, 'total' => $group->count()])
            ->values()
            ->sortBy('hour')
            ->values();

        // Reservas por mes (últimos 6 meses)
        $reservationsByMonth = Reservation::where('user_id', $userId)
            ->where('start_time', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(start_time, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'month' => $r->month,
                'total' => (int) $r->total,
            ]);

        // Estado de reservas del usuario
        $statusBreakdown = Reservation::where('user_id', $userId)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(fn($r) => [
                'status' => $r->status,
                'total'  => (int) $r->total,
            ]);

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalReservations' => $totalReservations,
                'hoursPlayed'       => round($hoursPlayed, 1),
                'favoriteCourts'    => $favoriteCourts,
                'preferredHours'    => $preferredHours,
                'reservationsByMonth' => $reservationsByMonth,
                'statusBreakdown'   => $statusBreakdown,
            ],
        ]);
    }

    /**
     * Dashboard de estadísticas del administrador.
     */
    public function adminDashboard()
    {
        // Canchas más reservadas (top 5)
        $mostBookedCourts = Reservation::whereNotIn('status', ['cancelled', 'cancelada', 'rechazada', 'rejected'])
            ->select('space_id', DB::raw('COUNT(*) as total'))
            ->groupBy('space_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('space:id,name')
            ->get()
            ->map(fn($r) => [
                'name'  => $r->space ? $r->space->name : 'Desconocida',
                'total' => (int) $r->total,
            ]);

        // Horarios más solicitados (distribución por hora)
        $peakHours = Reservation::whereNotIn('status', ['cancelled', 'cancelada', 'rechazada', 'rejected'])
            ->get()
            ->groupBy(fn($r) => Carbon::parse($r->start_time)->format('H:00'))
            ->map(fn($group, $hour) => ['hour' => $hour, 'total' => $group->count()])
            ->values()
            ->sortBy('hour')
            ->values();

        // Ingresos totales por cancha
        $revenueBySpace = Reservation::whereNotIn('status', ['cancelled', 'cancelada', 'rechazada', 'rejected'])
            ->with('space:id,name,price_per_hour')
            ->get()
            ->groupBy('space_id')
            ->map(function ($reservations, $spaceId) {
                $space = $reservations->first()->space;
                $totalHours = $reservations->sum(function ($r) {
                    return Carbon::parse($r->start_time)->diffInMinutes(Carbon::parse($r->end_time)) / 60;
                });
                return [
                    'name'    => $space ? $space->name : 'Desconocida',
                    'revenue' => round($totalHours * ($space ? $space->price_per_hour : 0), 2),
                    'hours'   => round($totalHours, 1),
                ];
            })
            ->values()
            ->sortByDesc('revenue')
            ->values();

        // Ingresos totales globales
        $totalRevenue = $revenueBySpace->sum('revenue');

        // Reservas por mes (últimos 6 meses)
        $reservationsByMonth = Reservation::where('start_time', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("DATE_FORMAT(start_time, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn($r) => [
                'month' => $r->month,
                'total' => (int) $r->total,
            ]);

        // Distribución de estados
        $statusBreakdown = Reservation::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(fn($r) => [
                'status' => $r->status,
                'total'  => (int) $r->total,
            ]);

        // Totales generales
        $totalReservations = Reservation::count();
        $totalUsers = \App\Models\User::where('role', 'user')->count();
        $totalSpaces = Space::where('is_active', true)->count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'mostBookedCourts'   => $mostBookedCourts,
                'peakHours'          => $peakHours,
                'revenueBySpace'     => $revenueBySpace,
                'totalRevenue'       => round($totalRevenue, 2),
                'reservationsByMonth' => $reservationsByMonth,
                'statusBreakdown'    => $statusBreakdown,
                'totalReservations'  => $totalReservations,
                'totalUsers'         => $totalUsers,
                'totalSpaces'        => $totalSpaces,
            ],
        ]);
    }
}
