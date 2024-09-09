<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseJsonHelper
{
    public static function success($data, $message = 'Success', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function created($data, $message = 'Resource created successfully'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], 201);
    }

    public static function notFound($message = 'Resource not found'): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 404);
    }

    public static function error($error = null, $message = 'Something went wrong', $statusCode = 500): JsonResponse
    {
        $response = [
            'message' => $message,
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, $statusCode);
    }
}
