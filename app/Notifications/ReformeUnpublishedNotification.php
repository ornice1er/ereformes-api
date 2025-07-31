<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Reforme;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReformeUnpublishedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     */

      protected $reforme;

    public function __construct(Reforme $reforme)
    {
        $this->reforme = $reforme;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Réforme dépubliée - ' . $this->reforme->libref)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('La réforme suivante a été dépubliée du système :')
            ->line('📋 **Titre :** ' . $this->reforme->libref)
            ->line('📅 **Date de début :** ' . $this->reforme->date_debut)
            ->line('📅 **Date de fin :** ' . $this->reforme->date_fin)
            ->line('🏢 **Structure :** ' . optional($notifiable->structure)->designation)
            // ->line('Rôle : ' . $notifiable->roles->pluck('name')->implode(', '))
            ->line('Elle n’est plus disponible pour consultation ou traitement.')
            ->line('Merci de votre compréhension.')
             ->salutation(null);

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
