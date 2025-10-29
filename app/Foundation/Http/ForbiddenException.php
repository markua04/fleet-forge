<?php

namespace App\Foundation\Http;

use Illuminate\Http\Response;

class ForbiddenException extends HttpException
{
    protected array $response = [
        [
            'message' => 'This user can not access this part of the API.',
            'code' => 'forbidden',
        ],
    ];

    protected int $statusCode = Response::HTTP_FORBIDDEN;
}
