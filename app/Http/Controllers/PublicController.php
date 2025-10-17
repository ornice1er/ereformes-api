<?php
namespace App\Http\Controllers;

use App\Http\Repositories\ReformeRepository;
use App\Services\LogService;
use App\Utilities\Common;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ReformeController extends Controller
{
    /**
     * The reforme repository being queried.
     *
     * @var ReformeRepository
     */
    protected $reformeRepository;

    protected $ls;

    public function __construct(ReformeRepository $reformeRepository, LogService $ls)
    {
        $this->reformeRepository = $reformeRepository;
        $this->ls = $ls;

        //$this->middleware('auth:api')->except(['getNotified', 'show']);

    }


      public function index(Request $request)
    {
        $message = 'Récupération de la liste des reformes';

        try {
            $result = $this->reformeRepository->getAllForPublic($request);
            $this->ls->trace(['action_name' => $message, 'description' => json_encode($request->all())]);

            return Common::success($message, $result);
        } catch (\Throwable $th) {
            $this->ls->trace(['action_name' => $message, 'description' => $th->getMessage()]);

            return Common::error($th->getMessage(), []);
        }
    }
}