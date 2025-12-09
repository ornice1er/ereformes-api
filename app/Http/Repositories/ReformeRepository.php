<?php

namespace App\Http\Repositories;
use App\Exceptions\JsonResponseException;
use App\Models\Reforme;
use App\Models\Media;
use App\Models\Result;
use App\Models\Affectation;
use App\Models\Parcours;
use App\Traits\Repository;
use Exception;
use Illuminate\Support\Facades\Auth;
use DB,Pdf,Storage;
use App\Services\ReformeNotificationPublicationService;
use App\Notifications\ReformeUnpublishedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReformStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;


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


        $per_page = 10;

        // Logique de filtrage par rôle
        if (Auth::user()->roles()->first()->name == "admin" ||
            Auth::user()->roles()->first()->name == "saisie central" ||
            Auth::user()->roles()->first()->name == "publication") {

            $req = Reforme::with(["objectifs.results"])
                ->ignoreRequest(['per_page'])
                ->orderBy('id', 'desc');
        } else {
            $req = Reforme::with(["objectifs.results.suiviResults"])
                ->where("structure_id", Auth::user()->structure->id)
                ->ignoreRequest(['per_page'])
                ->orderBy('id', 'desc');
        }

        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];
            return $req->paginate($per_page);
        } else {
            return $req->get();
        }


    }

function getAllForPublic($request) {
    $req = Reforme::with(["objectifs.results.suiviResults"])
        ->ignoreRequest(['per_page'])
        ->where('isPublished', true)
        ->orderBy('id', 'desc');

    if ($request->has('per_page')) {
        $reformes= $req->paginate($request->per_page);
    }else{
    $reformes=$req->get();

    }


    $stats=array();
    $stats['total_reforme']=Reforme::count();
    $stats['total_reforme_admin']=Reforme::whereHas("nature",function($q){
        $q->where('libnature', 'like', 'admin%');
    })->count();
    $stats['total_reforme_ins']=Reforme::whereHas("nature",function($q){
        $q->where('libnature', 'like', 'ins%');
    })->count();

    return [
        "reformes"=>$reformes,
        "stats"=>$stats,
    ];
}
    /**
     * Get a specific reforme by id
     */
    public function get($id)
    {
        return $this->findOrFail($id)->load('objectifs.results.suiviResults');
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


    public function getSuiviResult($request)
    {

        $per_page = 10;

        $req=Result::with(['getLastSuiviResult','objectif.reforme.nature','suiviResults']);
        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];
            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }

    public function getMyList($request)
    {

        $per_page=10;

        $idStructure=Auth::id();

        $req=Reforme::where('user_id',Auth::id())->orderBy('id','desc');
        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];
            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }

    public function getByRole($request)
    {

        $idLevel=Auth::id();

        $req=Reforme::with(['objectifs.results','affectation','files'])->where('isPublished',false)->whereHas('affectations', function($q) use($idLevel) {
            $q->where('unite_admin_down',"=", $idLevel)->where('isLast',"=", true);
            })->orderBy('id','desc');

            if (array_key_exists('per_page', $request->all())) {
                $per_page = $request['per_page'];
                return $req->paginate($per_page);
            } else {
                return $req->get();
            }

    }


    //    public function publish($id)
    // {
    //     $reforme=Reforme::find($id);
    //     $reforme->affectation?->update(['isLast'=>true]);
    //     $reforme->update(['isPublished'=>true]);

    //     return [];
    // }


public function publish($id)
{
    $reforme = Reforme::find($id);

    if (!$reforme) {
        return response()->json(['message' => 'Réforme introuvable.'], 404);
    }

    // Marquer l'affectation comme la dernière si elle existe
    $reforme->affectation?->update(['isLast' => true]);

    // Publier la réforme
    $reforme->update([
        'isPublished' => true,
    ]);

    // Instancier et utiliser le service
    $notificationService = new ReformeNotificationPublicationService();
    $notificationService->notifyUsersOfPublication($reforme);

    return [];
}






    public function unpublish($id)
{
    $reforme = Reforme::find($id);

    if (!$reforme) {
        return response()->json(['message' => 'Réforme introuvable.'], 404);
    }

    // Mettre à jour l'affectation si elle existe
    if ($reforme->affectation) {
        $reforme->affectation->update(['isLast' => false]);
    }

    // Dépublier la réforme
    $reforme->update(['isPublished' => false]);

    // Récupérer les utilisateurs de la même structure avec les rôles ciblés
    $users = User::where('structure_id', $reforme->structure_id)
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['saisie', 'validation']);
        })
        ->get();

    // Envoyer la notification
    Notification::send($users, new ReformeUnpublishedNotification($reforme));

    return [];
}


public function updateStatut(Request $request, $id)
{// 1. Validation de la requête
    $request->validate([
        'statuts' => 'required|string|in:planification,exécution,évaluation', // adapte selon tes statuts
    ]);

    // 2. Récupération de la réforme
    $reforme = Reforme::findOrFail($id);
    $oldStatus = $reforme->statuts;
    $newStatus = $request->input('statuts');

    // 3. Vérification du changement de statut
    if ($oldStatus !== $newStatus) {
        $reforme->statuts = $newStatus;
        $reforme->save();

        // 4. Récupération des utilisateurs à notifier
        $user = User::find($reforme->user_id); // ou created_by, selon ta base

        if ($user) {
            $users = User::where('structure_id', $user->structure_id)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['saisie', 'validation']);
                })
                ->get();

            // 5. Envoi de la notification
            Notification::send($users, new ReformStatusChanged($reforme, $oldStatus, $newStatus));
        }
    }

    // 6. Réponse JSON
    return [];

}


function downloadPDF($id) {

        $reforme=Reforme::find($id)->load(["objectifs.results.suiviResults"]);

        $directory_exist=Storage::disk('public')->exists("temp");
        if(!$directory_exist) Storage::disk('public')->makeDirectory("temp");

        $filename = $reforme->libref . time() . '.pdf';
        $path = 'temp/' . $filename;

     Pdf::loadView('fiche_reformes',[
            "data"=> $reforme ])
            ->save(Storage::disk('public')->path($path));
      $downloadUrl = URL::temporarySignedRoute(
            'reformes.download', // nom de la route
            now()->addMinutes(10), // expiration
            ['filename' => $filename]
        );
        return  $downloadUrl;

}



}
