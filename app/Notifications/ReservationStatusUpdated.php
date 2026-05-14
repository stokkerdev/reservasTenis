<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusUpdated extends Notification implements ShouldQueue
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
        $statusLabel = $this->reservation->status === 'confirmed' ? 'CONFIRMADA' : 'RECHAZADA';
        $subject = 'Actualización de tu reserva: ' . $statusLabel;

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hola ' . $this->reservation->user_name . '!')
            ->line('El estado de tu reserva ha sido actualizado a: **' . $statusLabel . '**.');

        if ($this->reservation->status === 'confirmed') {
            $message->line('¡Excelente! Tu espacio está asegurado. Nos vemos en la cancha.');
        } else {
            $message->line('Lamentablemente tu reserva no pudo ser confirmada. Por favor, intenta con otro horario o contacta con administración.');
        }

        return $message
            ->line('Detalles:')
            ->line('Cancha: ' . $this->reservation->space->name)
            ->line('Fecha: ' . \Carbon\Carbon::parse($this->reservation->start_time)->format('d/m/Y'))
            ->action('Ver Detalles', url('/reservations'))
            ->line('Gracias por elegir nuestro Club de Tenis.');
    }
}
