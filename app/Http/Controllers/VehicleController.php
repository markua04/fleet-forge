<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\VehicleResource;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleController extends Controller
{
    public function __construct(private readonly VehicleRepository $vehicles)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = (int) $request->integer('per_page', 15);

        if ($perPage <= 0) {
            $perPage = 15;
        }

        $availableVehicles = $this->vehicles->paginateAvailable($perPage);

        return VehicleResource::collection($availableVehicles);
    }
}
