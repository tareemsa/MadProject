<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof BaseApiException) {
            return response()->json([
                'status' => 0,
                'data' => [],
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }
    public function register(): void
{
    $this->renderable(function (MediaUploadException $e, $request) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 422);
    });
}

}
