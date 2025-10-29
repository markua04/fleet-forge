<?php

namespace App\Exceptions;

use App\Foundation\Http\BadRequestException;
use App\Foundation\Http\ForbiddenException;
use App\Foundation\Http\HttpException;
use App\Foundation\Http\MethodNotAllowedException;
use App\Foundation\Http\NotFoundException;
use App\Foundation\Http\UnauthorizedException;
use App\Foundation\Http\ValidationException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException as LaravelException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnexpectedValueException;

final class Handler
{
    public function render(\Throwable $e, Request $request): JsonResponse
    {
        if ($e instanceof HttpException) {
            return $this->jsonResponse($e);
        } elseif ($e instanceof LaravelException) {
            return $this->jsonResponse(new ValidationException([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'code' => 'validation_error',
            ]));
        } elseif ($e instanceof NotFoundHttpException) {
            return $this->jsonResponse(new NotFoundException);
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return $this->jsonResponse(new MethodNotAllowedException);
        } elseif ($e instanceof UnexpectedValueException) {
            return $this->jsonResponse(new BadRequestException);
        } elseif ($e instanceof ClientException) {
            return $this->getClientException($e);
        } elseif ($e instanceof AuthenticationException) {
            return $this->jsonResponse(new UnauthorizedException);
        } elseif ($e instanceof AccessDeniedHttpException) {
            return $this->jsonResponse(new ForbiddenException());
        } elseif ($e instanceof \DomainException) {
            return new JsonResponse(
                [
                    [
                        'message' => $e->getMessage(),
                        'code' => 'domain_error',
                    ],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        Log::error('Unhandled exception encountered.', [
            'exception' => $e,
        ]);

        return new JsonResponse(
            [
                [
                    'message' => 'An unexpected error occurred. Please try again later.',
                    'code' => 'error',
                ],
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }



    private function jsonResponse(HttpException $e): JsonResponse
    {
        return new JsonResponse($e->errors(), $e->getCode());
    }

    private function getClientException(ClientException $exception): JsonResponse
    {
        return match ($exception->getResponse()->getStatusCode()) {
            Response::HTTP_BAD_REQUEST => $this->jsonResponse(new BadRequestException([
                [
                    'message' => $exception->getResponse()->getBody()->getContents(),
                    'code' => 'bad_request',
                ]
            ])),
            default => new JsonResponse(
                [
                    [
                        'message' => $exception->getMessage(),
                        'code' => 'error',
                    ],
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            ),
        };
    }
}
