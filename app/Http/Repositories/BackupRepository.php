<?php

namespace App\Http\Repositories;

use App\Models\Backup;
use App\Traits\Repository;

class BackupRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Backup
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Backup::class);
    }

    /**
     * Check if backup exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all backups with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Backup::ignoreRequest(['per_page', 'categorie', 'role'])
            ->orderByDesc('created_at');

        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];

            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }


    /**
     * Get a specific backup by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new backup
     */
  public function makeStore(array $data): Backup
{

    // Création de l'backup
    $backup = Backup::create($data);


    return $backup;
}


    /**
     * Update an existing backup
     */
  public function makeUpdate($id, array $data): Backup
{
    $model = Backup::findOrFail($id);



    // Mise à jour des données backup
    $model->update($data);


    return $model;
}


    /**
     * Delete a backup
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest backups
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
     * Search for backups by name, email, or code
     */
    public function search($term)
    {
        $query = Backup::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
