<?php

namespace App\Http\Repositories;

use App\Models\Sector;
use App\Traits\Repository;

class SectorRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Sector
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Sector::class);
    }

    /**
     * Check if sector exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all sectors with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Sector::ignoreRequest(['per_page', 'categorie', 'role'])
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
     * Get a specific sector by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new sector
     */
  public function makeStore(array $data): Sector
{

    // Création de l'sector
    $sector = Sector::create($data);

    return $sector;
}


    /**
     * Update an existing sector
     */
  public function makeUpdate($id, array $data): Sector
{
    $model = Sector::findOrFail($id);



    // Mise à jour des données sector
    $model->update($data);


    return $model;
}


    /**
     * Delete a sector
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest sectors
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
     * Search for sectors by name, email, or code
     */
    public function search($term)
    {
        $query = Sector::query(); // Start with an empty query
        $attrs = ['libsecteur']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
