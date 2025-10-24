<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Traits\Repository;
use App\Utilities\FileStorage;
use App\Utilities\Mailer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var User
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(User::class);
    }

    /**
     * Check if user exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all users with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;
            $data = $request->except(['per_page', 'page']);

            $req = User::ignoreRequest($data)
                ->with('roles')
                ->orderByDesc('created_at');

        // if (array_key_exists('project_id', $request->all())) {
        //     $project_id = $request->project_id;
        //     $req->whereHas('userProjects', function ($q) use ($project_id) {
        //         $q->where('project_id', $project_id);
        //         if (request()->has('role')) {
        //             $role = request()->role;
        //             $q->whereHas('roles', function ($qu) use ($role) {
        //                 $qu->where('id', $role);
        //             });
        //         }
        //     });
        // }

        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];

            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }


    /**
     * Get a specific user by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new user
     */
  public function makeStore(array $data): User
{
    // Gestion de la photo
    if (request()->hasFile('photo')) {
        $filename = FileStorage::setFile(
            'public',
            request()->file('photo'),
            'avatars',
            Str::slug($data['lastname'] . '.' . $data['firstname'] . '.' . time())
        );
        $data['photo'] = 'avatars/' . $filename;
    }

    // Génération du mot de passe aléatoire
    $plainPassword = Str::random(8);
    $data['password'] = Hash::make($plainPassword);
    $roles=$data['roles'];
    unset($data['roles']);

    // Création de l'utilisateur
    $user = User::create($data);

    // Attribution du rôle (accepte ID ou tableau de rôles)
  // Mise à jour des rôles si fournis
    if (!empty($roles)) {
        $roleIds = is_array($roles) ? $roles: [$roles];
        $user->syncRoles($roleIds);
    }

    // Envoi de l'email avec les identifiants
    Mailer::sendSimple(
        'emails.new_account',
        ['user' => $user, 'password' => $plainPassword],
        'Identifiants de connexion à la plateforme',
        $user->name,
        $user->email
    );

    return $user;
}


    /**
     * Update an existing user
     */
  public function makeUpdate($id, array $data): User
{
    $model = User::findOrFail($id);

    // Mise à jour de la photo de profil
    if (request()->hasFile('photo')) {
        FileStorage::deleteFile('public', $model->photo);
        $filename = FileStorage::setFile(
            'public',
            request()->file('photo'),
            'avatars',
            Str::slug($data['lastname'] . '.' . $data['firstname'] . '.' . time())
        );
        $data['photo'] = 'avatars/' . $filename;
    }
    $roles=$data['roles'];
    unset($data['roles']);

    // Mise à jour des données utilisateur
    $model->update($data);

    // Mise à jour des rôles si fournis
    if (!empty($roles)) {
        $roleIds = is_array($roles) ? $roles: [$roles];
        $model->syncRoles($roleIds);
    }

    return $model;
}


    /**
     * Delete a user
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest users
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
     * Search for users by name, email, or code
     */
    public function search($term)
    {
        $query = User::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
