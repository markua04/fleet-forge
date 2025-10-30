<?php

declare(strict_types=1);

use App\Console\Commands\AddCashForUser;
use App\Exceptions\Handler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        AddCashForUser::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptionHandler = new Handler();
        $exceptions->render(
            fn (\Throwable $throwable, Request $request): JsonResponse => $exceptionHandler->render($throwable, $request),
        );
    })->create();
