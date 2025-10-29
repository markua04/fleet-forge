<?php

namespace App\Foundation\Http;

use Illuminate\Http\Response;

class UnauthorizedException extends HttpException
{
    protected array $response = [
        [
            'message' => 'You are not authorized to access this part of the API.',
            'code' => 'unauthorized',
        ],
    ];

    protected int $statusCode = Response::HTTP_UNAUTHORIZED;
}
