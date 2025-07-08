<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReformStatusChanged extends Notification
{
    protected $reform;
    protected $oldStatus;
    protected $newStatus;

    public function __construct($reform, $oldStatus, $newStatus)
    {
        $this->reform = $reform;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Changement de statut de la réforme')
            ->line("La réforme « {$this->reform->libref} » a changé de statut.")
            ->line("Ancien statut : {$this->oldStatus}")
            ->line("Nouveau statut : {$this->newStatus}")
            ->action('Voir la réforme', url("/reformes/{$this->reform->id}"))
            ->line('Merci d’utiliser notre plateforme.')
            ->salutation('');
    }
}
