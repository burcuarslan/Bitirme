<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResponseMiddleware
{
    public function apiResponse($data, $message, $status): JsonResponse
    {
        $response = [];
        if ($data != null)
            $response['data'] = $data;
        if ($message != null)
            $response['message'] = $message;
        return response()->json($response, $status);
    }
}
