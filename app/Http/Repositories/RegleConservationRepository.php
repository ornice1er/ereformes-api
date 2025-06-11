<?php
namespace App\Http\Repositories;

use App\Models\RegleConservation;
use App\Traits\Repository;

class RegleConservationRepository
{
    use Repository;

    protected $model;

    public function __construct()
    {
        $this->model = app(RegleConservation::class);
    }

    public function getAll()
    {
        return RegleConservation::orderBy('nom')->get();
    }

    public function get($id)
    {
        return $this->findOrFail($id);
    }

    public function makeStore($data)
    {
        return RegleConservation::create($data);
    }

    public function makeUpdate($id, $data)
    {
        $regle = RegleConservation::findOrFail($id);
        $regle->update($data);
        return $regle;
    }

    public function makeDestroy($id)
    {
        return RegleConservation::findOrFail($id)->delete();
    }

    public function search($term)
    {
        return RegleConservation::where('nom', 'like', "%$term%")->get();
    }
}
