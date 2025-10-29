<?php

namespace App\Foundation\Http;

use Illuminate\Http\Response;

class BadRequestException extends HttpException
{
    protected array $response = [
        [
            'message' => 'Bad Request',
            'code' => 'bad_request',
        ],
    ];

    protected int $statusCode = Response::HTTP_BAD_REQUEST;
}
