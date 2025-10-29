<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseVehicleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\VehiclePurchaseService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserVehicleController extends Controller
{
    public function __construct(
        private readonly VehiclePurchaseService $purchaseService,
    ) {
    }

    /**
     * @throws \DomainException
     */
    public function store(PurchaseVehicleRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->purchaseService->purchase(
            $user,
            $request->integer('vehicle_id'),
            $request->role()
        );

        return response()->json(
            new UserResource($updatedUser),
            Response::HTTP_CREATED
        );
    }
}
