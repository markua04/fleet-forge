<?php

namespace App\Foundation\Http;

use Illuminate\Http\Response;

class MethodNotAllowedException extends HttpException
{
    protected array $response = [
        [
            'message' => 'Method not allowed.',
            'code' => 'method_not_allowed',
        ],
    ];

    protected int $statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
}
