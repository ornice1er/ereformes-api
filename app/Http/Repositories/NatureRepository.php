<?php

namespace App\Http\Repositories;

use App\Models\Nature;
use App\Traits\Repository;
use Exception;

class NatureRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Nature
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Nature::class);
    }

    /**
     * Check if nature exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all natures with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Nature::ignoreRequest(['per_page', 'categorie', 'role'])
            ->orderByDesc('created_at');


        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];

            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }


    /**
     * Get a specific nature by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new nature
     */
    public function makeStore(array $data)
    {

            // Création de la nature
            $nature = Nature::create($data);

            return $nature;

    }

    public function makeUpdate($id, array $data)
    {

            // Recherche et mise à jour de la nature
            $nature = Nature::findOrFail($id);
            $nature->update($data);

            // Récupération des données mises à jour
            $nature = Nature::find($id);


            return $nature;

    }


    /**
     * Delete a nature
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest natures
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
     * Search for natures by name, email, or code
     */
    public function search($term)
    {
        $query = Nature::query(); // Start with an empty query
        $attrs = ['libnature']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
