<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $users,
    ) {
    }

    /**
     * Get a user's profile along with their vehicles.
     */
    public function getUserProfile(int $userId): User
    {
        return $this->users->findWithVehicles($userId);
    }
}
