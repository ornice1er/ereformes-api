<?php

namespace App\Console\Commands;

use App\Models\Reforme;
use App\Models\ReformeNotification;
use App\Models\User;
use App\Notifications\ReformeDeadlineNotification;
use App\Notifications\ReformeDeadlineTableNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendReformeNotifications extends Command
{
    protected $signature = 'reformes:send-notifications';
    protected $description = 'Envoie les notifications d\'échéance pour les réformes';

    public function handle()
    {
        $this->info('Début de l\'envoi des notifications...');

        $today = Carbon::today();

        // Notifications à 10 jours
        $this->sendNotificationsForDays(10, 'ten_days_before', $today);

        // Notifications à 3 jours
        $this->sendNotificationsForDays(3, 'three_days_before', $today);

        // Notifications pour aujourd'hui
        $this->sendNotificationsForDays(0, 'today', $today);

        $this->sendGroupedNotificationsToPublication($today);

        $this->info('Notifications envoyées avec succès !');
    }

    private function sendNotificationsForDays(int $days, string $type, Carbon $today)
    {
        $targetDate = $today->copy()->addDays($days);

        // Récupérer les réformes qui expirent à la date cible
        $reformes = Reforme::whereDate('date_fin', $targetDate)
            ->whereNotIn('etat_mor', ['TERMINÉ', 'ANNULÉ']) // Exclure les réformes terminées/annulées
            ->get();

        $notificationType = $this->getNotificationType($days);

        foreach ($reformes as $reforme) {
            // Vérifier si la notification n'a pas déjà été envoyée
            $existingNotification = ReformeNotification::where('reforme_id', $reforme->id)
                ->where('notification_type', $notificationType)
                ->first();

            if (!$existingNotification) {
                // Récupérer les utilisateurs avec les rôles appropriés
                $users = $this->getUsersForNotification($reforme->user_id);

                // Envoyer la notification
                Notification::send($users, new ReformeDeadlineNotification($reforme, $days, $notificationType));

                // Enregistrer que la notification a été envoyée
                // ReformeNotification::create([
                //     'reforme_id' => $reforme->id,
                //     'notification_type' => $notificationType,
                //     'sent_at' => now(),
                // ]);

                $this->info("Notification envoyée pour la réforme: {$reforme->libref} ({$days} jours)");
            } else {
                $this->info("Notification déjà envoyée pour la réforme: {$reforme->libref} ({$days} jours)");
            }
        }
    }


    private function sendGroupedNotificationsToPublication(Carbon $today)
    {
        // Récupérer toutes les réformes arrivant à échéance dans les 10 prochains jours
        $upcomingReformes = collect();

        // Réformes à 10 jours
        $reformes10Days = Reforme::whereDate('date_fin', $today->copy()->addDays(10))
            ->whereNotIn('etat_mor', ['TERMINÉ', 'ANNULÉ'])
            ->get()
            ->map(function ($reforme) {
                $reforme->days_remaining = 10;
                return $reforme;
            });

        // Réformes à 3 jours
        $reformes3Days = Reforme::whereDate('date_fin', $today->copy()->addDays(3))
            ->whereNotIn('etat_mor', ['TERMINÉ', 'ANNULÉ'])
            ->get()
            ->map(function ($reforme) {
                $reforme->days_remaining = 3;
                return $reforme;
            });

        // Réformes pour aujourd'hui
        $reformesToday = Reforme::whereDate('date_fin', $today)
            ->whereNotIn('etat_mor', ['TERMINÉ', 'ANNULÉ'])
            ->get()
            ->map(function ($reforme) {
                $reforme->days_remaining = 0;
                return $reforme;
            });

        // Combiner toutes les réformes
        $upcomingReformes = $upcomingReformes->concat($reformes10Days)
            ->concat($reformes3Days)
            ->concat($reformesToday);

        if ($upcomingReformes->isNotEmpty()) {
            // Grouper par structure pour envoyer des notifications ciblées
            $reformesByStructure = $upcomingReformes->groupBy(function ($reforme) {
                return User::find($reforme->user_id)->structure_id ?? 'unknown';
            });

            foreach ($reformesByStructure as $structureId => $reformes) {
                if ($structureId !== 'unknown') {
                    // Récupérer les utilisateurs publication de cette structure
                    $publicationUsers = $this->getUsersPublicationForNotification($structureId);

                    if ($publicationUsers->isNotEmpty()) {
                        // Vérifier si une notification groupée n'a pas déjà été envoyée aujourd'hui
                        $existingGroupNotification = ReformeNotification::where('notification_type', 'publication_summary')
                            ->whereDate('sent_at', $today)
                            ->where('reforme_id', $reformes->first()->id) // Utiliser la première réforme comme référence
                            ->first();

                        if (!$existingGroupNotification) {
                            // Envoyer la notification groupée
                            Notification::send($publicationUsers, new ReformeDeadlineTableNotification($reformes, $structureId));

                            // Enregistrer la notification groupée
                            // ReformeNotification::create([
                            //     'reforme_id' => $reformes->first()->id,
                            //     'notification_type' => 'publication_summary',
                            //     'sent_at' => now(),
                            //     // 'additional_data' => json_encode([
                            //     //     'structure_id' => $structureId,
                            //     //     'reformes_count' => $reformes->count(),
                            //     //     'reformes_ids' => $reformes->pluck('id')->toArray()
                            //     // ]),
                            // ]);

                            $this->info("Notification groupée envoyée aux utilisateurs publication de la structure {$structureId} ({$reformes->count()} réformes)");
                        } else {
                            $this->info("Notification groupée déjà envoyée aujourd'hui pour la structure {$structureId}");
                        }
                    }
                }
            }
        }
    }

    private function getNotificationType(int $days): string
    {
        switch ($days) {
            case 10:
                return '10_days';
            case 3:
                return '3_days';
            case 0:
                return 'today';
            default:
                return 'unknown';
        }
    }

    private function getUsersForNotification($id)
    {
        // $authors=User::where('structure_id',User::find($id)->structure_id)->get();
        $authors = User::where('structure_id', User::find($id)->structure_id)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['saisie', 'validation']);
                })
                ->get();

        return $authors;
    }

    private function getUsersPublicationForNotification($id)
{
    $user = User::find($id);

    if (!$user) {
        // Optionnel : tu peux enregistrer un log ou afficher une erreur explicite
        $this->warn("Utilisateur avec l'ID {$id} introuvable.");
        return collect(); // retourne une collection vide
    }

    $authors = User::where('structure_id', $user->structure_id)
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['publication']);
        })
        ->get();

    return $authors;
}
}
