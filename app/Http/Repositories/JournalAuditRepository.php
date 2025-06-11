<?php
namespace App\Http\Repositories;

use App\Models\JournalAudit;
use App\Traits\Repository;

class JournalAuditRepository
{
    use Repository;

    protected $model;

    public function __construct()
    {
        $this->model = app(JournalAudit::class);
    }

    public function getAll($request)
    {
        return JournalAudit::with(['utilisateur', 'document'])
            ->filter($request->all())
            ->paginate($request->per_page ?? 10);
    }

    public function get($id)
    {
        return $this->findOrFail($id);
    }

    public function makeStore($data)
    {
        return JournalAudit::create($data);
    }

    public function makeUpdate($id, $data)
    {
        $regle = JournalAudit::findOrFail($id);
        $regle->update($data);
        return $regle;
    }

    public function makeDestroy($id)
    {
        return JournalAudit::findOrFail($id)->delete();
    }

    public function search($term)
    {
        return JournalAudit::where('nom', 'like', "%$term%")->get();
    }
}
