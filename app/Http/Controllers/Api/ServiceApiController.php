<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", description: "BookEase API", title: "BookEase API")]
class ServiceApiController extends Controller
{
    #[OA\Get(path: "/api/services", summary: "Get all services", tags: ["Services"])]
    #[OA\Response(response: 200, description: "Successful operation")]
    public function index()
    {
        return response()->json(Service::all());
    }
}
