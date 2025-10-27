<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $users,
    ) {
    }

    public function show(User $user): JsonResponse
    {
        $user = $this->users->getUserProfile($user->id);

        return response()->json(
            new UserResource($user)
        );
    }
}
