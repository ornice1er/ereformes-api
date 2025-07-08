<?php

namespace App\Console\Commands;

use App\Models\Reforme;
use App\Models\User;
use App\Notifications\ReformStatusChanged;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class SendReformStatusChangeNotifications extends Command
{
    protected $signature = 'reformes:notify-status-change';
    protected $description = 'Envoie des notifications pour les changements de statuts de réforme';

    public function handle()
    {
        $this->info('Début de la vérification des changements de statuts...');

        $reformes = Reforme::all();

        foreach ($reformes as $reforme) {
            $oldStatus = Cache::get('reform_status_' . $reforme->id);

            if (!is_null($oldStatus) && $oldStatus !== $reforme->statuts) {
                $this->sendStatusChangeNotification($reforme, $oldStatus);
            }

            // Mise à jour du cache
            Cache::put('reform_status_' . $reforme->id, $reforme->statuts, now()->addDays(1));
        }

        $this->info('Vérification terminée.');
    }

    private function sendStatusChangeNotification(Reforme $reforme, string $oldStatus)
    {
        $userId = $reforme->user_id; // ou created_by, selon ta base
        $users = $this->getUsersForStatusChange($userId);

        if ($users->isNotEmpty()) {
            Notification::send($users, new ReformStatusChanged($reforme, $oldStatus, $reforme->statuts));
            $this->info("Notification envoyée pour la réforme ID {$reforme->id} ({$oldStatus} → {$reforme->statutss})");
        } else {
            $this->warn("Aucun utilisateur trouvé pour notifier la réforme ID {$reforme->id}");
        }
    }

    private function getUsersForStatusChange($id)
    {
        $user = User::find($id);

        if (!$user) {
            $this->warn("Utilisateur avec l'ID {$id} introuvable.");
            return collect();
        }

        return User::where('structure_id', $user->structure_id)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['saisie', 'validation']);
            })
            ->get();
    }
}
