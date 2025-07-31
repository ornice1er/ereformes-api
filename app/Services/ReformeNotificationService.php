<?php

namespace App\Services;
use App\Models\Reforme;
use App\Models\ReformeNotification;
use App\Models\ReformePublicationNotification;
use App\Models\User;
use App\Notifications\ReformeDeadlineNotification;
use App\Notifications\ReformePublishedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class ReformeNotificationService
{
    public function sendImmediateNotification(Reforme $reforme, string $notificationType)
    {
        $daysMap = [
            '10_days' => 10,
            '3_days' => 3,
            'today' => 0
        ];

        $days = $daysMap[$notificationType] ?? 0;
        $users = $this->getUsersForNotification();

        Notification::send($users, new ReformeDeadlineNotification($reforme, $days, $notificationType));

        // Enregistrer la notification
        ReformeNotification::create([
            'reforme_id' => $reforme->id,
            'notification_type' => $notificationType,
            'sent_at' => now(),
        ]);
    }

    public function getReformesNeedingNotification(int $days): \Illuminate\Database\Eloquent\Collection
    {
        $targetDate = Carbon::today()->addDays($days);
        $notificationType = $this->getNotificationType($days);

        return Reforme::whereDate('date_fin', $targetDate)
            ->whereNotIn('etat_mor', ['TERMINE', 'ANNULE'])
            ->whereDoesntHave('notifications', function ($query) use ($notificationType) {
                $query->where('notification_type', $notificationType);
            })
            ->get();
    }

    private function getNotificationType(int $days): string
    {
        switch ($days) {
            case 10: return '10_days';
            case 3: return '3_days';
            case 0: return 'today';
            default: return 'unknown';
        }
    }

    private function getUsersForNotification()
    {
        $targetRoles = ['saisie', 'validation', 'publication'];

        return User::whereIn('roles', $targetRoles)
            ->where('email_verified_at', '!=', null)
            ->get();
    }

    public function sendPublicationNotification(Reforme $reforme)
    {
        // Vérifier si déjà envoyée
        $existingNotification = ReformePublicationNotification::where('reforme_id', $reforme->id)
            ->first();

        if ($existingNotification) {
            return false; // Déjà envoyée
        }

        $users = $this->getUsersForPublishedNotification();

        Notification::send($users, new ReformePublishedNotification($reforme));

        // Enregistrer la notification
        ReformePublicationNotification::create([
            'reforme_id' => $reforme->id,
            'sent_at' => now(),
            'recipients' => $users->pluck('id')->toArray(),
        ]);

        return true;
    }

    public function getNewlyPublishedReformes(): \Illuminate\Database\Eloquent\Collection
    {
        $yesterday = Carbon::now()->subDay();

        return Reforme::where('is_Published', 1)
            ->where('updated_at', '>=', $yesterday)
            ->whereDoesntHave('publicationNotifications')
            ->get();
    }

    private function getUsersForPublishedNotification()
    {
        $targetRoles = ['saisie', 'validation'];

        return User::whereIn('roles', $targetRoles)
            ->where('email_verified_at', '!=', null)
            ->get();
    }
}

