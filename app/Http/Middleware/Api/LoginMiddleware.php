<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginMiddleware extends ResponseMiddleware
{

    public function validator(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        } else {
            return true;
        }
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->validator($request) !== true) {
            return $this->validator($request);
        } else {
            return $next($request);
        }

    }
}
