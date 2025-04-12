<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return self::Error(
                $exception->errors(),
                'Validation failed.',
                422
            );
        }

        if ($exception instanceof AuthenticationException) {
            return self::Error(null, 'Unauthenticated.', 401);
        }

        if ($exception instanceof ModelNotFoundException) {
            return self::Error(null, 'Resource not found.', 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return self::Error(null, 'Route not found.', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return self::Error(null, 'HTTP method not allowed.', 405);
        }

        if ($exception instanceof CustomException) {
            return self::Error(
                null,
                $exception->getMessage(),
                $exception->getStatusCode()
            );
        }

        // Fallback: unexpected error
        return self::Error(null, 'Server Error: ' . $exception->getMessage(), 500);
    }

    public function register(): void
    {
        //
    }
}
