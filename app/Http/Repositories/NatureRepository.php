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
        try {
            // Validation des données
            $validator = Validator::make($data, [
                "libnature" => "required|unique:nature"
            ],
            [
                'libnature.required' => 'Le libellé est requis',
                'libnature.unique' => 'Le libellé doit être unique',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'errors' => $validator->errors(),
                    'status_code' => 500
                ];
            }

            // Création de la nature
            $nature = Nature::create($data);

            return [
                'success' => true,
                'data' => $nature,
                'message' => "Enregistrement d'un nature",
                'status_code' => 200
            ];

        } catch (QueryException $ex) {
            return [
                'success' => false,
                'error' => $ex->getMessage(),
                'status_code' => 500
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
    }

    public function makeUpdate($id, array $data)
    {
        try {
            // Validation des données avec exclusion de l'ID actuel pour la règle unique
            $validator = Validator::make($data, [
                "libnature" => "required|unique:nature,libnature," . $id
            ],
            [
                'libnature.required' => 'Le libellé est requis',
                'libnature.unique' => 'Le libellé doit être unique',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'errors' => $validator->errors(),
                    'status_code' => 500
                ];
            }

            // Recherche et mise à jour de la nature
            $nature = Nature::findOrFail($id);
            $nature->update($data);

            // Récupération des données mises à jour
            $nature = Nature::find($id);

            return [
                'success' => true,
                'data' => $nature,
                'message' => "Modification d'un nature",
                'status_code' => 200
            ];

        } catch (QueryException $ex) {
            return [
                'success' => false,
                'error' => $ex->getMessage(),
                'status_code' => 500
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
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
