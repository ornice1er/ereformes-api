<?php

namespace App\Http\Repositories;

use App\Models\Parcours;
use App\Traits\Repository;

class ParcoursRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Parcours
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Parcours::class);
    }

    /**
     * Check if parcours exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all parcourss with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Parcours::ignoreRequest(['per_page', 'categorie', 'role'])
            ->orderByDesc('created_at');


        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];

            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }


    /**
     * Get a specific parcours by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new parcours
     */
  public function makeStore(array $data): Parcours
{


    // Création de l'parcours
    $parcours = Parcours::create($data);

    return $parcours;
}


    /**
     * Update an existing parcours
     */
  public function makeUpdate($id, array $data): Parcours
{
    $model = Parcours::findOrFail($id);



    // Mise à jour des données parcours
    $model->update($data);


    return $model;
}


    /**
     * Delete a parcours
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest parcourss
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
     * Search for parcourss by name, email, or code
     */
    public function search($term)
    {
        $query = Parcours::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
