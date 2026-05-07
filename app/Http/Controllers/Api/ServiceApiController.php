<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', description: 'BookEase API', title: 'BookEase API')]
class ServiceApiController extends Controller
{
    #[OA\Get(path: '/api/services', summary: 'Get all services', tags: ['Services'])]
    #[OA\Response(response: 200, description: 'Successful operation')]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Service::query()->with('category')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        return ServiceResource::collection($query->get());
    }
}
