<?php
namespace App\Http\Repositories;
use App\Models\User;
use App\Models\Structure;
use App\Models\Media;
use App\Models\Reforme;
use App\Traits\Repository;
use Illuminate\Support\Facades\Auth;

class DashboardRepository
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
      //  $this->model = app(Log::class);
    }

    /**
     * Get all users with filtering, pagination, and sorting
     */
    public function getDash($request)
    {


        return [];

    }

      public function index($request)
    {
        $data=[];
        $role=$request->role;
        switch ($role) {
            case 'admin':
                $data['users']=User::count();
                $data['total_structures']=Structure::count();
                $data['total_reforme']=Reforme::count();
                $data['total_reforme_admin']=Reforme::whereHas("nature",function($q){
                    $q->where('libnature', 'like', 'admin%');
                })->count();
                $data['total_reforme_ins']=Reforme::whereHas("nature",function($q){
                    $q->where('libnature', 'like', 'ins%');
                })->count();
                break;

                case 'saisie central':
                    $data['total_reforme']=Reforme::count();
                    $data['total_reforme_admin']=Reforme::whereHas("nature",function($q){
                        $q->where('libnature', 'like', 'admin%');
                    })->count();
                    $data['total_reforme_ins']=Reforme::whereHas("nature",function($q){
                        $q->where('libnature', 'like', 'ins%');
                    })->count();
                    $data['total_structures']=Structure::count();
                    $data['total_pending']=Reforme::where('isPublished',false)->where("user_id",Auth::id())->count();
                    break;
                    case 'saisie':
                        $idLevel=Auth::id();

                        $data['total_pending']=Reforme::where('isPublished',false)->whereHas('affectations', function($q) use($idLevel) {
                            $q->where('unite_admin_down',"=", $idLevel)->where('isLast',"=", true);
                            })->count();

                        $data['total_reforme']=Reforme::count();
                        $data['total_reforme_admin']=Reforme::whereHas("nature",function($q){
                            $q->where('libnature', 'like', 'admin%');
                        })->count();
                        $data['total_reforme_ins']=Reforme::whereHas("nature",function($q){
                            $q->where('libnature', 'like', 'ins%');
                        })->count();
                      //  $data['total_pending']=Structure::count();

                        break;
                        case 'validation':
                            $data['total_reforme']=Reforme::count();
                            $data['total_reforme_admin']=Reforme::whereHas("nature",function($q){
                                $q->where('libnature', 'like', 'admin%');
                            })->count();
                            $data['total_reforme_ins']=Reforme::whereHas("nature",function($q){
                                $q->where('libnature', 'like', 'ins%');
                            })->count();
                            $idLevel=Auth::id();

                            $data['total_pending']=Reforme::where('isPublished',false)->whereHas('affectations', function($q) use($idLevel) {
                                $q->where('unite_admin_down',"=", $idLevel)->where('isLast',"=", true);
                                })->count();
                            break;
                            case 'publication':
                                $data['total_reforme']=Reforme::count();
                                $data['total_reforme_admin']=Reforme::whereHas("nature",function($q){
                                    $q->where('libnature', 'like', 'admin%');
                                })->count();
                                $data['total_reforme_ins']=Reforme::whereHas("nature",function($q){
                                    $q->where('libnature', 'like', 'ins%');
                                })->count();
                                $idLevel=Auth::id();

                                $data['total_pending']=Reforme::where('isPublished',false)->whereHas('affectations', function($q) use($idLevel) {
                                    $q->where('unite_admin_down',"=", $idLevel)->where('isLast',"=", true);
                                    })->count();
                                break;

            default:
                # code...
                break;
        }


        return $data;
    }


}
