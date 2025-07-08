<?php

namespace App\Observers;

use App\Models\Reforme;
use App\Jobs\SendReformePublicationNotificationJob;

class ReformeObserver
{
    public function updated(Reforme $reforme)
    {
        // Vérifier si le statut vient de changer vers "publié"
        if ($reforme->isDirty('isPublished') && $reforme->isPublished === 1){
            // Envoyer la notification immédiatement ou la programmer
            dispatch(new SendReformePublicationNotificationJob($reforme))->delay(now()->addMinutes(5));
        }
    }
}

