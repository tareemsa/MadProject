<?php

namespace App\Traits;

trait ApiResponseTrait

{
    public static function Success($data, $message, $code = 200)
    {
        return response()->json([
            'status' => 1,
            'data' => $data,
            'message' => $message
        ], $code);
    }

    public static function Error($data, $message, $code = 400)
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $message
        ], $code);
    }


    public static function Validation($data, $message, $code = 422)
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}