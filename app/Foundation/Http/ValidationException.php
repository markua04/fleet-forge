<?php

namespace App\Foundation\Http;

use Illuminate\Http\Response;

class ValidationException extends HttpException
{
    protected int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
}
