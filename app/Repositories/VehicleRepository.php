<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Foundation\Http\ValidationException;
use App\Models\Vehicle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VehicleRepository
{
    /** @throws ValidationException */
    public function findForPurchase(int $id): Vehicle
    {
        $vehicle = Vehicle::query()
            ->available()
            ->whereKey($id)
            ->lockForUpdate()
            ->first();

        if ($vehicle === null) {
            throw new ValidationException([
                [
                    'message' => 'Vehicle is no longer available for purchase.',
                    'code' => 'vehicle_unavailable',
                ],
            ]);
        }

        return $vehicle;
    }

    public function paginateAvailable(int $perPage = 9): LengthAwarePaginator
    {
        return Vehicle::query()
            ->available()
            ->select([
                'id',
                'uuid',
                'make',
                'model',
                'year',
                'type',
                'price',
                'vin',
                'status',
            ])
            ->orderBy('make')
            ->orderBy('model')
            ->paginate($perPage)
            ->withQueryString();
    }
}
