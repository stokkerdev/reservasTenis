<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reserva Cancelada - Club de Tenis')
            ->greeting('Hola ' . $this->reservation->user_name . '!')
            ->line('Te confirmamos que tu reserva ha sido cancelada correctamente.')
            ->line('Detalles de la reserva cancelada:')
            ->line('Cancha: ' . $this->reservation->space->name)
            ->line('Fecha: ' . \Carbon\Carbon::parse($this->reservation->start_time)->format('d/m/Y'))
            ->line('Si fue un error, puedes realizar una nueva reserva en cualquier momento.')
            ->action('Nueva Reserva', url('/dashboard'))
            ->line('¡Esperamos verte pronto de nuevo!');
    }
}
