<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class VehicleDashboardController extends Controller
{
    public function __construct(
        private readonly UserService $users,
    ) {
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(): View
    {
        $userId = Auth::id();

        abort_unless(is_int($userId), 403);

        $user = $this->users->getUserProfile($userId);

        return view('vehicles.index', [
            'user' => $user,
            'vehicles' => $user->vehicles,
        ]);
    }
}
