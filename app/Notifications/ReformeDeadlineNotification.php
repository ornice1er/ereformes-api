<?php

namespace App\Notifications;

use App\Models\Reforme;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReformeDeadlineNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reforme;
    protected $daysRemaining;
    protected $notificationType;

    public function __construct(Reforme $reforme, int $daysRemaining, string $notificationType)
    {
        $this->reforme = $reforme;
        $this->daysRemaining = $daysRemaining;
        $this->notificationType = $notificationType;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $message = $this->getMessage();

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting . $notifiable->name)
            ->line($message)
            ->line('**Détails de la réforme :**')
            ->line('Titre : ' . $this->reforme->libref)
             ->line('📅 **Date de début :** ' . $this->reforme->date_debut)
            ->line('📅 **Date de fin :** ' . $this->reforme->date_fin)
            ->line('Statut : ' . $this->reforme->etat_mor)
            ->action('Voir la réforme', url('/reformes/' . $this->reforme->id))
            // ->line('Rôle : ' .$notifiable->roles->pluck('name')->implode(', '))
            ->line('Merci de prendre les mesures nécessaires.')
            ->salutation(null);
    }

    private function getSubject(): string
    {
        switch ($this->notificationType) {
            case '10_days':
                return '⚠️ Réforme expire dans 10 jours';
            case '3_days':
                return '🚨 Réforme expire dans 3 jours - URGENT';
            case 'today':
                return '🔥 Réforme expire AUJOURD\'HUI - ACTION IMMÉDIATE REQUISE';
            default:
                return 'Alerte échéance réforme';
        }
    }

    private function getGreeting(): string
    {
        switch ($this->notificationType) {
            case '10_days':
                return 'Bonjour,';
            case '3_days':
                return 'Attention,';
            case 'today':
                return 'URGENT,';
            default:
                return 'Bonjour,';
        }
    }

    private function getMessage(): string
    {
        switch ($this->notificationType) {
            case '10_days':
                return "La réforme '{$this->reforme->libref}' expire dans 10 jours. Il est temps de commencer les préparatifs finaux.";
            case '3_days':
                return "ATTENTION : La réforme '{$this->reforme->libref}' expire dans seulement 3 jours. Une action urgente est requise.";
            case 'today':
                return "CRITIQUE : La réforme '{$this->reforme->libref}' expire AUJOURD'HUI. Une action immédiate est nécessaire.";
            default:
                return "La réforme '{$this->reforme->libref}' approche de son échéance.";
        }
    }

}
