<?php

namespace App\Http\Repositories;

use App\Models\SuiviResult;
use App\Models\SuivreResult;
use App\Traits\Repository;

class SuivreResultRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var SuivreResult
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(SuiviResult::class);
    }

    /**
     * Check if SuivreResult exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all SuivreResults with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = SuiviResult::ignoreRequest(['per_page', 'categorie', 'role'])
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
     * Get a specific SuivreResult by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new SuivreResult
     */
  public function makeStore(array $data): SuiviResult
{


    // Création de l'SuivreResult
    $SuivreResult = SuiviResult::create($data);

    return $SuivreResult;
}


    /**
     * Update an existing SuivreResult
     */
  public function makeUpdate($id, array $data): SuiviResult
{
    $model = SuiviResult::findOrFail($id);



    // Mise à jour des données SuivreResult
    $model->update($data);


    return $model;
}


    /**
     * Delete a SuivreResult
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest SuivreResults
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
     * Search for SuivreResults by name, email, or code
     */
    public function search($term)
    {
        $query = SuiviResult::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
