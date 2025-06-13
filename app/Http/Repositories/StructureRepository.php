<?php

namespace App\Http\Repositories;

use App\Models\Structure;
use App\Traits\Repository;

class StructureRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Structure
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Structure::class);
    }

    /**
     * Check if structure exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all structures with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Structure::ignoreRequest(['per_page', 'categorie', 'role'])
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
     * Get a specific structure by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new structure
     */
  public function makeStore(array $data): Structure
{


    // Création de l'structure
    $structure = Structure::create($data);

    return $structure;
}


    /**
     * Update an existing structure
     */
  public function makeUpdate($id, array $data): Structure
{
    $model = Structure::findOrFail($id);



    // Mise à jour des données structure
    $model->update($data);


    return $model;
}


    /**
     * Delete a structure
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest structures
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
     * Search for structures by name, email, or code
     */
    public function search($term)
    {
        $query = Structure::query(); // Start with an empty query
        $attrs = ['sector_id', 'sigl', 'designation', 'adresse_struct', 'telephone', 'email']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
