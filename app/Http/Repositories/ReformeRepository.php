<?php

namespace App\Http\Repositories;
use App\Exceptions\JsonResponseException;
use App\Models\Reforme;
use App\Models\Media;
use App\Models\Affectation;
use App\Models\Parcours;
use App\Traits\Repository;
use Exception;
use Illuminate\Support\Facades\Auth;
use DB;

class ReformeRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Reforme
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Reforme::class);
    }

    /**
     * Check if reforme exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all reformes with filtering, pagination, and sorting
     */
    public function getAll($request)
    {

    try {
        $per_page = 10;

        // Logique de filtrage par rôle
        if (Auth::user()->roles()->first()->name == "admin" ||
            Auth::user()->roles()->first()->name == "saisie central" ||
            Auth::user()->roles()->first()->name == "publication") {

            $req = Reforme::with(["objectifs.results"])
                ->ignoreRequest(['per_page', 'categorie', 'role'])
                ->filter(array_filter($request->all(), function ($k) {
                    return $k != 'page';
                }, ARRAY_FILTER_USE_KEY))
                ->orderBy('id', 'desc');
        } else {
            $req = Reforme::with(["objectifs.results.suiviResults"])
                ->where("structure_id", Auth::user()->structure->id)
                ->ignoreRequest(['per_page', 'categorie', 'role'])
                ->filter(array_filter($request->all(), function ($k) {
                    return $k != 'page';
                }, ARRAY_FILTER_USE_KEY))
                ->orderBy('id', 'desc');
        }

        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];
            return $req->paginate($per_page);
        } else {
            return $req->get();
        }

    } catch (Exception $e) {
        return $req->get();
    }
    }


    /**
     * Get a specific reforme by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new reforme
     */
    public function makeStore(array $data)
    {
        DB::beginTransaction();

        try {
            // Ajout des données automatiques
            $data['date_enreg'] = date_create(date("Y-m-d h:i:s"));
            $data['user_id'] = Auth::id();

            // Attribution de la structure selon le rôle
            if (in_array(Auth::user()->roles()->first()->name, ["saisie", "validation"])) {
                $data['structure_id'] = Auth::user()->structure?->id;
            }

            // Gestion des fichiers
            $files = isset($data['files']) ? $data['files'] : [];
            unset($data['files']);

            // Création de la réforme
            $reforme = Reforme::create($data);

            // Gestion des médias
            foreach ($files as $file) {
                Media::find($file['id'])->update(["projets_media_id" => $reforme->id]);
            }

            // Création de l'affectation
            Affectation::create([
                'unite_admin_up' => Auth::id(),
                'unite_admin_down' => Auth::id(),
                'reforme_id' => $reforme->id,
                'sens' => 1,
            ]);

            // Création du parcours
            Parcours::create([
                'libelle' => "Création de la réforme",
                'reforme_id' => $reforme->id
            ]);

            DB::commit();

            return $reforme;


        } catch (Exception $e) {
            DB::rollback();

            throw new JsonResponseException([
                'message' => $e->getMessage(),
                'success' => false,
                'data' => null,
                'warning' => null
            ], 401);
        }
    }

    public function makeUpdate($id, array $data)
    {
        try {
            // Recherche de la réforme
            $reforme = Reforme::findOrFail($id);

            // Gestion des fichiers
            $files = isset($data['files']) ? $data['files'] : [];
            unset($data['files']);

            // Mise à jour des données
            $reforme->update($data);

            // Gestion des médias
            foreach ($files as $file) {
                Media::find($file['id'])->update(["projets_media_id" => $reforme->id]);
            }

            // Récupération des données mises à jour
            $reforme = Reforme::find($id);

            return $reforme;

        } catch (QueryException $ex) {
            return $ex->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delete a reforme
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest reformes
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
     * Search for reformes by name, email, or code
     */
    public function search($term)
    {
        $query = Reforme::query(); // Start with an empty query
        $attrs = ['structure_id', 'user_id', 'nature_id', 'couverture_id', 'libref', 'typeref', 'objectif_glob', 'popul_cible', 'struct_impl', 'periodexe', 'date_debut', 'date_fin', 'date_enreg', 'cadreinst_mor', 'etat_mor', 'montant_prevu', 'montant_trealise', 'difficult', 'solution', 'perspective', 'isPublished']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
