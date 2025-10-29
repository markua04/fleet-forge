<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VehiclePurchaseService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly VehicleRepository $vehicles,
    ) {
    }

    /**
     * Attempt to purchase a vehicle for a user.
     *
     * @throws \DomainException
     */
    public function purchase(User $user, int $vehicleId, string $role = 'owner'): User
    {
        return DB::transaction(function () use ($user, $vehicleId, $role) {
            $lockedUser = $this->users->findForUpdate($user->id);
            try {
                $lockedVehicle = $this->vehicles->findForPurchase($vehicleId);
            } catch (ModelNotFoundException $exception) {
                throw new \DomainException('Vehicle is no longer available for purchase.', 0, $exception);
            }

            if ($lockedUser->cash < $lockedVehicle->price) {
                throw new \DomainException('You do not have enough cash to purchase this vehicle.');
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
