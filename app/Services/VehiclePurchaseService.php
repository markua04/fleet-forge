<?php

declare(strict_types=1);

namespace App\Services;

use App\Foundation\Http\ValidationException;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VehiclePurchaseService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly VehicleRepository $vehicles,
    ) {
    }

    /** @throws ValidationException */
    public function purchase(User $user, int $vehicleId, string $role = 'owner'): User
    {
        return DB::transaction(function () use ($user, $vehicleId, $role) {
            $lockedUser = $this->users->findForUpdate($user->id);
            $lockedVehicle = $this->vehicles->findForPurchase($vehicleId);

            if ($lockedUser->cash < $lockedVehicle->price) {
                throw new ValidationException([
                    [
                        'message' => 'You do not have enough cash to purchase this vehicle.',
                        'code' => 'insufficient_funds',
                    ],
                ]);
            }

            $lockedUser->cash = $lockedUser->cash - $lockedVehicle->price;
            $lockedUser->save();

            $lockedVehicle->status = 'active';
            $lockedVehicle->sold_at = Carbon::now();
            $lockedVehicle->save();

            $lockedUser->vehicles()->syncWithoutDetaching([
                $lockedVehicle->id => [
                    'role' => $role,
                    'assigned_at' => Carbon::now(),
                ],
            ]);

            return $this->users->findWithVehicles($lockedUser->id);
        });
    }
}
