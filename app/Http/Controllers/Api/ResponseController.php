<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResponseController extends Controller
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
