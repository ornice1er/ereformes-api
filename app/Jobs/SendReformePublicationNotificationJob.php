<?php

namespace App\Jobs;

use App\Models\Reforme;
use App\Models\ReformePublicationNotification;
use App\Models\User;
use App\Notifications\ReformePublishedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendReformePublicationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reforme;

    public function __construct(Reforme $reforme)
    {
        $this->reforme = $reforme;
    }

    public function handle()
    {
        // Vérifier si la notification n'a pas déjà été envoyée
        $existingNotification = ReformePublicationNotification::where('reforme_id', $this->reforme->id)
            ->first();

        if ($existingNotification) {
            return; // Notification déjà envoyée
        }

        // Récupérer les utilisateurs
        $users = User::whereIn('role', ['saisie', 'validation'])
            ->where('email_verified_at', '!=', null)
            ->get();

        if ($users->isNotEmpty()) {
            // Envoyer la notification
            Notification::send($users, new ReformePublishedNotification($this->reforme));

            // Enregistrer la notification
            ReformePublicationNotification::create([
                'reforme_id' => $this->reforme->id,
                'sent_at' => now(),
                'recipients' => $users->pluck('id')->toArray(),
            ]);
        }
    }
}
