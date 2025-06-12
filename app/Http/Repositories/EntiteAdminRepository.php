<?php

namespace App\Http\Repositories;

use App\Models\EntiteAdmin;
use App\Traits\Repository;
use Validator;
use Exception;

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
    try {
        // Validation des données
        $validator = Validator::make($data, [
            'libelle' => 'required',
        ]);

        if ($validator->fails()) {
            // Retourner les erreurs de validation
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'status_code' => 404
            ];
        }

        // Création de l'entité admin
        $entiteAdmin = EntiteAdmin::create($data);

        return [
            'success' => true,
            'data' => $entiteAdmin,
            'message' => 'EntiteAdmin créée avec succès',
            'status_code' => 201
        ];

    } catch (QueryException $ex) {
        // Gestion des erreurs de base de données
        return [
            'success' => false,
            'error' => $ex->getMessage(),
            'status_code' => 500
        ];
    } catch (Exception $e) {
        // Gestion des autres exceptions
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'status_code' => 500
        ];
    }
}


    /**
     * Update an existing EntiteAdmin
     */
    public function makeUpdate($id, array $data)
    {
        try {
            // Recherche de l'entité admin
            $entiteAdmin = EntiteAdmin::findOrFail($id);

            // Mise à jour des données
            $entiteAdmin->update([
                'libelle' => $data['libelle'] ?? $entiteAdmin->libelle,
            ]);

            return [
                'success' => true,
                'data' => $entiteAdmin,
                'message' => 'EntiteAdmin mise à jour avec succès',
                'status_code' => 200
            ];

        } catch (QueryException $ex) {
            // Gestion des erreurs de base de données
            return [
                'success' => false,
                'error' => $ex->getMessage(),
                'status_code' => 500
            ];
        } catch (Exception $e) {
            // Gestion des autres exceptions (incluant ModelNotFoundException de findOrFail)
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'status_code' => 500
            ];
        }
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
