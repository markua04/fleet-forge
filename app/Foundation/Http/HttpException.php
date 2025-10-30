<?php

namespace App\Foundation\Http;

use Exception;
use Illuminate\Http\Response;

class HttpException extends Exception
{
    protected array $response = [];
    protected int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
    protected ?array $context = null;

    public function __construct(?array $errors = [])
    {
        if (!empty($errors)) {
            $this->response = $errors;
        }

        parent::__construct(null, $this->statusCode);
    }

    public function errors(): array
    {
        return $this->response;
    }

    public function withContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }
}
