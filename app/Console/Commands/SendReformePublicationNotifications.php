<?php

namespace App\Console\Commands;

use App\Models\Reforme;
use App\Models\User;
use App\Notifications\ReformePublishedNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendReformePublicationNotifications extends Command
{
    protected $signature = 'reformes:send-publication-notifications';
    protected $description = 'Envoie les notifications pour les nouvelles réformes publiées';

    public function handle()
    {
        $this->info('Début de l\'envoi des notifications de publication...');

        // Récupérer les réformes publiées depuis la dernière exécution (dernières 24h)
        $yesterday = Carbon::now()->subDay();

        $newlyPublishedReformes = Reforme::where('isPublished', 1)
            ->get();

        if ($newlyPublishedReformes->isEmpty()) {
            $this->info('Aucune nouvelle réforme publiée trouvée.');
            return;
        }



        foreach ($newlyPublishedReformes as $reforme) {
            $users = $this->getUsersForNotification($reforme->user_id);

        if ($users->isEmpty()) {
            $this->warn('Aucun utilisateur trouvé pour recevoir les notifications.');
            return;
        }
            $this->sendNotificationForReforme($reforme, $users);
        }

        $this->info('Notifications de publication envoyées avec succès !');

        foreach ($users as $user) {
            $this->info($user->lastname . ' : ' . implode(', ', $user->roles->pluck('name')->toArray()));
        }


    }

    private function sendNotificationForReforme(Reforme $reforme, $users)
    {
        try {
            // Envoyer la notification à tous les utilisateurs concernés
            Notification::send($users, new ReformePublishedNotification($reforme));

            // Enregistrer que la notification a été envoyée
            // ReformePublicationNotification::create([
            //     'reforme_id' => $reforme->id,
            //     'sent_at' => now(),
            //     'recipients' => $users->pluck('id')->toArray(),
            // ]);

            $this->info("✅ Notification envoyée pour la réforme: {$reforme->libref} à {$users->count()} utilisateurs");

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'envoi pour la réforme {$reforme->libref}: " . $e->getMessage());
        }
    }

    private function getUsersForNotification($id)
    {
        // $authors=User::where('structure_id',User::find($id)->structure_id)->get();
        $authors = User::where('structure_id', User::find($id)->structure_id)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['saisie','validation']);
                })
                ->get();
        // Récupérer tous les utilisateurs avec les rôles appropriés
        // $targetRoles = ['saisie', 'validation'];

        // return User::whereIn('roles', $targetRoles)
        //     ->where('email_verified_at', '!=', null)
        //     ->get();
        return $authors;
    }
}
