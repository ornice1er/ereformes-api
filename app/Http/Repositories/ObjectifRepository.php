<?php

namespace App\Http\Repositories;

use App\Models\Objectif;
use App\Traits\Repository;

class ObjectifRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Objectif
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Objectif::class);
    }

    /**
     * Check if objectif exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all objectifs with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Objectif::ignoreRequest(['per_page', 'categorie', 'role'])
            ->filter(array_filter($request->all(), function ($k) {
                return $k != 'page';
            }, ARRAY_FILTER_USE_KEY))
            ->orderByDesc('created_at');


        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];

            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }


    /**
     * Get a specific objectif by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new objectif
     */
  public function makeStore(array $data): Objectif
{


    // Création de l'objectif
    $objectif = Objectif::create($data);

    return $objectif;
}


    /**
     * Update an existing objectif
     */
  public function makeUpdate($id, array $data): Objectif
{
    $model = Objectif::findOrFail($id);



    // Mise à jour des données objectif
    $model->update($data);


    return $model;
}


    /**
     * Delete a objectif
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest objectifs
     */
    public function getLatest()
    {
        return $this->latest()->get();
    }

    public function setStatus($id, $status)
    {
        return $this->findOrFail($id)->update(['is_active' => $status]);
    }

    /**
     * Search for objectifs by name, email, or code
     */
    public function search($term)
    {
        $query = Objectif::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
