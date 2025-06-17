<?php

namespace App\Http\Repositories;

use App\Models\Affectation;
use App\Models\User;
use App\Models\Parcours;
use App\Models\Reforme;
use App\Mail\AlerteReforme;
use App\Traits\Repository;
use App\Utilities\FileStorage;
use App\Utilities\Mailer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Auth, Mail;

class AffectationRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Affectation
     */
    protected $model;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Affectation::class);
    }

    /**
     * Check if affectation exists
     */
    public function ifExist($id)
    {
        return $this->find($id);
    }

    /**
     * Get all affectations with filtering, pagination, and sorting
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Affectation::ignoreRequest(['per_page', 'categorie', 'role'])
            ->filter(array_filter($request->all(), function ($k) {
                return $k != 'page';
            }, ARRAY_FILTER_USE_KEY))
            ->orderByDesc('created_at');

        // if (array_key_exists('project_id', $request->all())) {
        //     $project_id = $request->project_id;
        //     $req->whereHas('affectationProjects', function ($q) use ($project_id) {
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
     * Get a specific affectation by id
     */
    public function get($id)
    {
        return $this->findOrFail($id);
    }



    /**
     * Store a new affectation
     */
  public function makeStore(array $data)
{
    $role=Auth::user()->roles()->first()->name;

    if($data['sens']==1){
        switch ($role) {
            case 'publication':
                $ua_up=Auth::user();
                $check= Affectation::where('reforme_id',$data['reforme_id'])->where('isLast',true)->first();

                $ua_down=User::role(Role::where('name','validation')->first())->where("structure_id",$check->reforme->structure->id)->first();
                if($check)$check->update(['isLast'=>false,"instruction"=>$data['instruction']]);

                $newReq=Reforme::find($data['reforme_id']);
                break;
                case 'validation':
                    $ua_up=Auth::user();
                    $ua_down=User::role(Role::where('name','saisie')->first())->where("structure_id",Auth::user()->structure->id)->first();
                    $check= Affectation::where('reforme_id',$data['reforme_id'])->where('isLast',true)->first();
                    if($check)$check->update(['isLast'=>false,"instruction"=>$data['instruction']]);

                    $newReq=Reforme::find($data['reforme_id']);
                    break;

            default:
                return null;

                break;
        }

        Affectation::create([
            'unite_admin_up'=>$ua_up->id,
            'unite_admin_down'=>$ua_down->id,
            'reforme_id'=>$data['reforme_id'],
            'sens'=>$data['sens'],
            'instruction'=>$data['instruction'],
           // 'delay'=>date_create($data['delay']),
            ]);
            Parcours::create(['libelle'=>"Retour de la réforme ".$newReq->libref." par le/la ".$ua_up->structure->designation." au/à la " .$ua_down->structure->designation ,'reforme_id'=>$newReq->id]);
             Mail::to($ua_down->email)->send(new AlerteReforme('Retour de réforme pour correction',[
                "user"=>$ua_down
            ]));

           return true;

    }else{

        switch ($role) {
            case 'saisie':
                $ua_up=Auth::user();
                $ua_down=User::role(Role::where('name','validation')->first())->where("structure_id",Auth::user()->structure->id)->first();
                $check= Affectation::where('reforme_id',$data['reforme_id'])->where('isLast',true)->first();
                if($check)$check->update(['isLast'=>false]);

                break;
                case 'validation':
                    $ua_up=Auth::user();
                    $ua_down=User::role(Role::where('name','publication')->first())->first();
                    $check= Affectation::where('reforme_id',$data['reforme_id'])->where('isLast',true)->first();
                    if($check)$check->update(['isLast'=>false]);

                    break;

            default:
            return response()->json(["Plus de niveau de transmission"], 500);
                break;
        }



        $newReq=Reforme::find($data['reforme_id']);

        Affectation::create([
            'unite_admin_up'=>$ua_up->id,
            'unite_admin_down'=>$ua_down->id,
            'reforme_id'=>$data['reforme_id'],
            'sens'=>$data['sens'],
            ]);
            Parcours::create(['libelle'=>"Transmission de la réforme par le/la ".$ua_up->structure->designation." au/à la " .$ua_down->structure->designation ,'reforme_id'=>$newReq->id]);

            Mail::to($ua_down->email)->send(new AlerteReforme('Transmission de réforme pour validation',[
                "user"=>$ua_down
            ]));
            return true;
        }

        
}


    /**
     * Update an existing affectation
     */
  public function makeUpdate($id, array $data): Affectation
{
    $model = Affectation::findOrFail($id);


    // Mise à jour des données affectation
    $model->update($data);


    return $model;
}


    /**
     * Delete a affectation
     */
    public function makeDestroy($id)
    {
        return $this->findOrFail($id)->delete();
    }

    /**
     * Get the latest affectations
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
     * Search for affectations by name, email, or code
     */
    public function search($term)
    {
        $query = Affectation::query(); // Start with an empty query
        $attrs = ['name', 'email', 'code']; // Attributes you want to search in

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        return $query->get(); // Return the search results
    }
}
