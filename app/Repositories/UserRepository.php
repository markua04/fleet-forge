<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    /**
     * Retrieve a user with eager-loaded relationship data and limited columns.
     *
     * @throws ModelNotFoundException
     */
    public function findWithVehicles(int $id): User
    {
        return User::query()
            ->select(['id', 'name', 'email', 'cash', 'created_at'])
            ->with([
                'vehicles' => fn ($query) => $query->select([
                    'vehicles.id',
                    'vehicles.uuid',
                    'vehicles.make',
                    'vehicles.model',
                    'vehicles.price',
                    'vehicles.status',
                ])->withPivot([
                    'role',
                    'assigned_at',
                    'created_at',
                    'updated_at',
                ]),
            ])
            ->findOrFail($id);
    }

    /**
     * Retrieve a user row for balance updates with a lock.
     *
     * @throws ModelNotFoundException
     */
    public function findForUpdate(int $id): User
    {
        return User::query()
            ->lockForUpdate()
            ->findOrFail($id);
    }
}
