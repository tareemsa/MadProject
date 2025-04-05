<?php

namespace App\Traits;

trait ApiResponseTrait
{
    public function successResponse($message, $data = [], $status = 200)
    {
        return response()->json(['status' => 'success', 'message' => $message, 'data' => $data], $status);
    }

    public function errorResponse($message, $status = 400)
    {
        return response()->json(['status' => 'error', 'message' => $message], $status);
    }
}

