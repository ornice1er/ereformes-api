<?php

namespace App\Notifications;

use App\Models\Reforme;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReformePublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reforme;

    public function __construct(Reforme $reforme)
    {
        $this->reforme = $reforme;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('📢 Nouvelle réforme publiée - ' . $this->reforme->libref)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle réforme vient d\'être publiée dans le système.')
            ->line('**Détails de la réforme :**')
            ->line('📋 **Titre :** ' . $this->reforme->libref)
            ->line('📅 **Date de début :** ' . $this->reforme->date_debut)
            ->line('📅 **Date de fin :** ' . $this->reforme->date_fin)
           ->line('🏷️ **Statut :** ' . ($this->reforme->isPublished ? 'Publiée' : 'Non publiée'))
            // ->line('Rôle : ' .$notifiable->roles->pluck('name')->implode(', '))
             ->line('🏢 **Structure :** ' . optional($notifiable->structure)->designation)
            ->action('Consulter la réforme', url('/reformes/' . $this->reforme->id))
            ->line('Cette réforme est maintenant disponible pour consultation et traitement.')
            ->line('Merci de prendre connaissance de son contenu.')
            ->salutation( null);
    }

}

