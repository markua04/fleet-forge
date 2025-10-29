<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticatedUser
{
    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $userId = Auth::id();

        if ($user === null || !is_int($userId)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
