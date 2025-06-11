<?php
namespace App\Http\Repositories;

use App\Models\Dossier;
use App\Traits\Repository;

class DossierRepository
{
    use Repository;

    protected $model;

    public function __construct()
    {
        $this->model = app(Dossier::class);
    }

    public function getAll($request)
    {
        return Dossier::with(['regleConservation', 'createur', 'dossierParent'])
            ->filter($request->all())
            ->orderBy('nom')
            ->paginate($request->per_page ?? 10);
    }

    public function get($id)
    {
        return $this->findOrFail($id);
    }

    public function makeStore($data)
    {
         $dossier= Dossier::create($data);
         $dossier->emplacement = $dossier->buildPath();
         $dossier->save();
         return $dossier;
    }

    public function makeUpdate($id, $data)
    {
        $dossier = Dossier::findOrFail($id);
        $dossier->update($data);
        $dossier->updateEmplacementRecursif();
        return $dossier;
    }

    public function makeDestroy($id)
    {
        return Dossier::findOrFail($id)->delete();
    }

    public function search($term)
    {
        return Dossier::where('nom', 'like', "%$term%")->get();
    }
}
