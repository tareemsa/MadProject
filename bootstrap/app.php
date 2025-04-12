<?php

use App\Exceptions\CustomException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckForAnyScope;
use Laravel\Sanctum\Http\Middleware\CheckScopes;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'scopes' => CheckScopes::class,
            'scope' => CheckForAnyScope::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {

            $response = new class {
                use ApiResponseTrait;
            };

            if ($e instanceof NotFoundHttpException && $e->getPrevious() instanceof ModelNotFoundException) {
                return $response::Error([], 'User not found.', 404);
            }

            if ($e instanceof ModelNotFoundException) {
                return $response::Error([], 'User not found.', 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return $response::Error([], 'Route not found.', 404);
            }

            if ($e instanceof AuthenticationException) {
                return $response::Error([], 'Unauthenticated.', 401);
            }

           if ($e instanceof ValidationException) {
                return $response::Validation([], $e->errors());
           }

            if ($e instanceof QueryException) {
                return $response::Error([], 'Database error occurred.', 500);
            }

            if ($e instanceof CustomException) {
                return $response::Error([], $e->getMessage(), $e->getStatusCode());
            }
            return $response::Error([], $e->getMessage() ?: 'Something went wrong.', 500);
        });
    })->create();

