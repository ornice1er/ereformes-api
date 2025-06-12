<?php

namespace App\Http\Repositories;

use App\Models\Result;
use App\Traits\Repository;

class ResultRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Result
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Result::class);
    }

    /**
     * Check if result exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all results with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Result::ignoreRequest(['per_page', 'categorie', 'role'])
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
     * Get a specific result by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new result
     */
  public function makeStore(array $data): Result
{


    // Création de l'utilisateur
    $result = Result::create($data);

    return $result;
}


    /**
     * Update an existing result
     */
  public function makeUpdate($id, array $data): Result
{
    $model = Result::findOrFail($id);



    // Mise à jour des données utilisateur
    $model->update($data);


    return $model;
}


    /**
     * Delete a result
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest results
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
     * Search for results by name, email, or code
     */
    public function search($term)
    {
        $query = Result::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
