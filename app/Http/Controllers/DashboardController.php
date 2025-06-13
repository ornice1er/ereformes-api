<?php

namespace App\Http\Controllers;

use App\Http\Repositories\DashboardRepository;
use App\Utilities\Common;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController
{
    /**
     * The Dashboard repository being queried.
     *
     * @var DashboardRepository
     */
    protected $dashRepository;

    public function __construct(DashboardRepository $dashRepository)
    {
        $this->dashRepository = $dashRepository;
    }

    /** @OA\Get(
     *      path="/dashboard",
     *      operationId="Dashboard list",
     *      tags={"Dashboard"},
     *      summary="Return Dashboard data",
     *      description="Get all log",
     *
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="Can be used for filtering data by user id",
     *          required=false,
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(ref="#/components/schemas/Dashboard"),
     *
     *          @OA\XmlContent(ref="#/components/schemas/Dashboard")
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="Expired session"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error"
     *      )
     * )
     */
    public function index(Request $request)
    {
        try {
            $result = $this->dashRepository->index($request);

            return Common::success('Tableau de bord', $result);
        } catch (\Throwable $th) {
            return Common::error($th->getMessage(), []);
        }
    }


    public function storeFile(Request $request)
    {
        return $this->dashRepository->uploadFile($request);
    }

}
