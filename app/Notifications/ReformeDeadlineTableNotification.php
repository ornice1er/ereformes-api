<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class ReformeDeadlineTableNotification extends Notification
{
    use Queueable;

    protected $reformes;
    protected $structureId;

    public function __construct(Collection $reformes, $structureId)
    {
        $this->reformes = $reformes;
        $this->structureId = $structureId;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Vous pouvez ajouter d'autres canaux comme 'broadcast' pour les notifications en temps réel
    }

    // public function toMail($notifiable)
    // {
    //     $totalReformes = $this->reformes->count();
    //     $urgentCount = $this->reformes->where('days_remaining', 0)->count();
    //     $highPriorityCount = $this->reformes->where('days_remaining', 3)->count();
    //     $mediumPriorityCount = $this->reformes->where('days_remaining', 10)->count();

    //     $message = (new MailMessage)
    //         ->subject('📋 Tableau de bord des réformes - Échéances à venir')
    //         ->greeting('Bonjour,')
    //         ->line("Vous recevez ce récapitulatif en tant qu'utilisateur avec le rôle de publication.")
    //         ->line("**{$totalReformes} réforme(s)** arrivent à échéance dans les prochains jours :");

    //     if ($urgentCount > 0) {
    //         $message->line("🔴 **{$urgentCount} réforme(s) expirent AUJOURD'HUI**");
    //     }
    //     if ($highPriorityCount > 0) {
    //         $message->line("🟠 **{$highPriorityCount} réforme(s) expirent dans 3 jours**");
    //     }
    //     if ($mediumPriorityCount > 0) {
    //         $message->line("🟡 **{$mediumPriorityCount} réforme(s) expirent dans 10 jours**");
    //     }

    //     $message->line('### Détail des réformes :');

    //     // Grouper par jours restants pour un meilleur affichage
    //     $reformesByDays = $this->reformes->groupBy('days_remaining');

    //     // Traiter dans l'ordre de priorité : 0 jours, 3 jours, 10 jours
    //     foreach ([0, 3, 10] as $days) {
    //         if (isset($reformesByDays[$days])) {
    //             $priorityLabel = $this->getPriorityLabelByDays($days);
    //             $message->line("**{$priorityLabel}**");

    //             foreach ($reformesByDays[$days] as $reforme) {
    //                 $daysText = $reforme->days_remaining == 0 ? 'Aujourd\'hui' : "dans {$reforme->days_remaining} jour(s)";
    //                 $message->line("• **{$reforme->libref}** - Expire {$daysText} ({$reforme->date_fin->format('d/m/Y')})");
    //             }
    //             $message->line(''); // Ligne vide pour la séparation
    //         }
    //     }

    //     $message->line('Veuillez prendre les mesures nécessaires pour le suivi de ces réformes.')
    //         ->action('Consulter le tableau de bord', url('/reformes/dashboard'))
    //         ->line('Merci de votre attention.');

    //     return $message;
    // }


    public function toMail($notifiable)
    {
            return (new MailMessage)
                ->subject('📋 Réformes à échéance – Structure #' . $this->structureId)
                ->view('emails.tableRecap', [
                    'notifiable' => $notifiable,
                    'reformes' => $this->reformes,
                    'structureId' => $this->structureId,
                    'urgentCount' => $this->reformes->where('days_remaining', 0)->count(),
                    'highPriorityCount' => $this->reformes->where('days_remaining', 3)->count(),
                    'mediumPriorityCount' => $this->reformes->where('days_remaining', 10)->count(),
                ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'reformes_deadline_summary',
            'title' => 'Tableau de bord des réformes',
            'message' => "{$this->reformes->count()} réforme(s) arrivent à échéance",
            'structure_id' => $this->structureId,
            'reformes_count' => $this->reformes->count(),
            'urgent_count' => $this->reformes->where('days_remaining', 0)->count(),
            'high_priority_count' => $this->reformes->where('days_remaining', 3)->count(),
            'medium_priority_count' => $this->reformes->where('days_remaining', 10)->count(),
            'reformes' => $this->reformes->map(function ($reforme) {
                return [
                    'id' => $reforme->id,
                    'libref' => $reforme->libref,
                    'date_fin' => $reforme->date_fin->format('Y-m-d'),
                    'days_remaining' => $reforme->days_remaining,
                    'priority' => $this->calculatePriority($reforme->days_remaining),
                    'etat_mor' => $reforme->etat_mor,
                ];
            })->toArray(),
            'created_at' => now(),
        ];
    }

    private function getPriorityLabelByDays($days)
    {
        switch ($days) {
            case 0:
                return '🔴 URGENT - Expire aujourd\'hui';
            case 3:
                return '🟠 PRIORITÉ HAUTE - Expire dans 3 jours';
            case 10:
                return '🟡 PRIORITÉ MOYENNE - Expire dans 10 jours';
            default:
                return 'Autres';
        }
    }

    private function calculatePriority($days)
    {
        switch ($days) {
            case 0:
                return 'urgent';
            case 3:
                return 'high';
            case 10:
                return 'medium';
            default:
                return 'unknown';
        }
    }

    private function getPriorityLabel($priority)
    {
        switch ($priority) {
            case 'urgent':
                return '🔴 URGENT - Expire aujourd\'hui';
            case 'high':
                return '🟠 PRIORITÉ HAUTE - Expire dans 3 jours';
            case 'medium':
                return '🟡 PRIORITÉ MOYENNE - Expire dans 10 jours';
            default:
                return 'Autres';
        }
    }
}
