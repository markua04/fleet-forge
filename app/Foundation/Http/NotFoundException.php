<?php

namespace App\Foundation\Http;

use Illuminate\Http\Response;

class NotFoundException extends HttpException
{
    protected array $response = [
        [
            'message' => 'This endpoint or entity does not exist.',
            'code' => 'not_found',
        ],
    ];

    protected int $statusCode = Response::HTTP_NOT_FOUND;
}
