<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReformeNotificationPublicationService;
use App\Models\Reforme;



class PublishReforme extends Controller
{
    public function __construct(private ReformeNotificationPublicationService $notificationService) {}

public function publish(Reforme $reforme)
{
    $reforme->isPublished = true;
    $reforme->published_at = now();
    $reforme->save();

    $this->notificationService->notifyUsersOfPublication($reforme);

    return response()->json(['message' => 'Réforme publiée et notification envoyée.']);
}

}
