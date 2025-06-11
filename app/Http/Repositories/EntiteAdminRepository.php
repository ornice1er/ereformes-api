<?php

namespace App\Http\Repositories;

use App\Models\EntiteAdmin;
use App\Traits\Repository;

class EntiteAdminRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var EntiteAdmin
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(EntiteAdmin::class);
    }

    /**
     * Check if EntiteAdmin exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all EntiteAdmins with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = EntiteAdmin::ignoreRequest(['per_page', 'categorie', 'role'])
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
     * Get a specific EntiteAdmin by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new EntiteAdmin
     */
  public function makeStore(array $data): EntiteAdmin
{


    // Création de l'utilisateur
    $EntiteAdmin = EntiteAdmin::create($data);

    return $EntiteAdmin;
}


    /**
     * Update an existing EntiteAdmin
     */
  public function makeUpdate($id, array $data): EntiteAdmin
{
    $model = EntiteAdmin::findOrFail($id);



    // Mise à jour des données utilisateur
    $model->update($data);


    return $model;
}


    /**
     * Delete a EntiteAdmin
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest EntiteAdmins
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
     * Search for EntiteAdmins by name, email, or code
     */
    public function search($term)
    {
        $query = EntiteAdmin::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
