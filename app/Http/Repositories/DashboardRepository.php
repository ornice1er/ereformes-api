<?php
namespace App\Http\Repositories;

use App\Models\Dossier;
use App\Models\Document;
use App\Models\JournalAudit;
use App\Traits\Repository;

class DashboardRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var User
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
      //  $this->model = app(Log::class);
    }

    /**
     * Get all users with filtering, pagination, and sorting
     */
    public function getDash($request)
    {


        return [
            'dossiers' => Dossier::count(),
            'documents' => Document::count(),
            'audits' =>JournalAudit::with(['utilisateur', 'document'])->take(10)->get()
        ];
       
    }


}
