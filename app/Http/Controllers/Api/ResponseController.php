<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function checkJwtToken(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user) {
                return $user;
            } else {
                return false;
            }
        } catch (JWTException $e) {
            return $this->apiResponse(null, $e->getMessage(), 400);
        }
    }
}
