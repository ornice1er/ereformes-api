<?php

namespace App\Services;

use App\Models\Reforme;
use App\Models\User;
use App\Notifications\ReformePublishedNotification;
use Illuminate\Support\Facades\Notification;

class ReformeNotificationPublicationService
{
    /**
     * Envoie une notification aux utilisateurs concernés lorsqu'une réforme est publiée.
     *
     * @param Reforme $reforme
     * @return void
     */
    public function notifyUsersOfPublication(Reforme $reforme): void
    {
        $users = $this->getUsersForNotification($reforme->user_id);

                    logger()->info(" la réforme publiée : {$reforme->user_id}");


        if ($users->isEmpty()) {
            logger()->warning("Aucun utilisateur trouvé pour la réforme publiée : {$reforme->id}");
            return;
        }

        try {
            Notification::send($users, new ReformePublishedNotification($reforme));
            logger()->info("Notification envoyée pour la réforme {$reforme->libref} à {$users->count()} utilisateurs.");
        } catch (\Exception $e) {
            logger()->error("Erreur lors de l'envoi de la notification pour la réforme {$reforme->id} : " . $e->getMessage());
        }
    }

    /**
     * Récupère les utilisateurs d'une même structure avec les rôles 'saisie' ou 'validation'.
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    private function getUsersForNotification(int $userId)
    {
        $author = User::find($userId);

        if (!$author) {
            return collect(); // retourne une collection vide si l'auteur n'existe pas
        }

        

        return User::where('structure_id', $author->structure_id)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['saisie', 'validation']);
            })
            ->get();
    }
}
