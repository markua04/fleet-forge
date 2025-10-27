<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientFundsException;
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

    public function store(PurchaseVehicleRequest $request, User $user): JsonResponse
    {
        try {
            $updatedUser = $this->purchaseService->purchase(
                $user,
                $request->integer('vehicle_id'),
                $request->role()
            );
        } catch (InsufficientFundsException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(
            new UserResource($updatedUser),
            Response::HTTP_CREATED
        );
    }
}
