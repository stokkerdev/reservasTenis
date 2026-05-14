<?php

namespace App\Notifications;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        protected Reservation $reservation,
        protected ?string $previousStatus = null,
        protected bool $updatedByAdmin = false,
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $status = $this->reservation->status;
        $statusLabel = $this->statusLabel($status);
        $config = $this->statusConfig($status);
        $spaceName = $this->reservation->space?->name ?? 'Cancha no disponible';
        $startTime = Carbon::parse($this->reservation->start_time);
        $endTime = Carbon::parse($this->reservation->end_time);
        $changedBy = $this->updatedByAdmin ? 'por el equipo de administración' : 'en nuestro sistema';

        $mail = (new MailMessage)
            ->subject($config['subject'])
            ->greeting('Hola ' . $this->reservation->user_name . '!')
            ->line($config['intro'])
            ->line('Tu reserva fue actualizada ' . $changedBy . ' y ahora se encuentra en estado: **' . $statusLabel . '**.');

        if ($this->previousStatus && $this->previousStatus !== $status) {
            $mail->line('Estado anterior: ' . $this->statusLabel($this->previousStatus) . '.');
        }

        $mail
            ->line($config['message'])
            ->line('Detalles de la reserva:')
            ->line('Cancha: ' . $spaceName)
            ->line('Fecha: ' . $startTime->format('d/m/Y'))
            ->line('Horario: ' . $startTime->format('H:i') . ' - ' . $endTime->format('H:i'));

        if (!empty($this->reservation->notes)) {
            $mail->line('Notas: ' . $this->reservation->notes);
        }

        return $mail
            ->action($config['action_text'], $config['action_url'])
            ->line($config['closing'])
            ->salutation('Club de Tenis');
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'confirmed' => 'CONFIRMADA',
            'pending' => 'PENDIENTE',
            'cancelled' => 'CANCELADA',
            'rejected' => 'RECHAZADA',
            default => strtoupper($status),
        };
    }

    protected function statusConfig(string $status): array
    {
        return match ($status) {
            'confirmed' => [
                'subject' => 'Reserva confirmada - Club de Tenis',
                'intro' => '¡Buenas noticias! La administración confirmó tu reserva.',
                'message' => 'Tu espacio está asegurado. Te recomendamos llegar unos minutos antes del horario reservado.',
                'action_text' => 'Ver Mis Reservas',
                'action_url' => url('/reservations'),
                'closing' => 'Gracias por elegir nuestro Club de Tenis. ¡Te esperamos en la cancha!',
            ],
            'pending' => [
                'subject' => 'Reserva pendiente de confirmación - Club de Tenis',
                'intro' => 'La administración actualizó tu reserva y la dejó pendiente de confirmación.',
                'message' => 'Revisaremos la disponibilidad y te notificaremos cuando exista una decisión definitiva. Por ahora, evita asumir el horario como confirmado.',
                'action_text' => 'Revisar Reserva',
                'action_url' => url('/reservations'),
                'closing' => 'Gracias por tu paciencia. Te avisaremos cualquier nueva actualización por este mismo medio.',
            ],
            'cancelled' => [
                'subject' => 'Reserva cancelada - Club de Tenis',
                'intro' => 'La administración canceló tu reserva.',
                'message' => 'El horario indicado ya no se encuentra reservado a tu nombre. Si necesitas otro horario, puedes crear una nueva reserva desde la plataforma.',
                'action_text' => 'Crear Nueva Reserva',
                'action_url' => url('/reservations/create'),
                'closing' => 'Lamentamos los inconvenientes y esperamos verte pronto nuevamente.',
            ],
            'rejected' => [
                'subject' => 'Reserva rechazada - Club de Tenis',
                'intro' => 'La administración no pudo aprobar tu reserva.',
                'message' => 'Por favor intenta seleccionar otro horario disponible o contacta con administración si necesitas más información.',
                'action_text' => 'Buscar Otro Horario',
                'action_url' => url('/reservations/create'),
                'closing' => 'Gracias por tu comprensión.',
            ],
            default => [
                'subject' => 'Actualización de reserva - Club de Tenis',
                'intro' => 'El estado de tu reserva fue actualizado.',
                'message' => 'Puedes revisar el detalle actualizado desde la plataforma.',
                'action_text' => 'Ver Mis Reservas',
                'action_url' => url('/reservations'),
                'closing' => 'Gracias por elegir nuestro Club de Tenis.',
            ],
        };
    }
}
