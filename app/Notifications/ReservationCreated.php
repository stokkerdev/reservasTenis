<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCreated extends Notification
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
            ->subject('¡Nueva Reserva Creada! - Club de Tenis')
            ->greeting('Hola ' . $this->reservation->user_name . '!')
            ->line('Tu reserva ha sido registrada exitosamente en nuestro sistema.')
            ->line('Detalles de la reserva:')
            ->line('Cancha: ' . $this->reservation->space->name)
            ->line('Fecha: ' . \Carbon\Carbon::parse($this->reservation->start_time)->format('d/m/Y'))
            ->line('Horario: ' . \Carbon\Carbon::parse($this->reservation->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($this->reservation->end_time)->format('H:i'))
            ->action('Ver Mis Reservas', url('/reservations'))
            ->line('¡Te esperamos para disfrutar del juego!');
    }
}
