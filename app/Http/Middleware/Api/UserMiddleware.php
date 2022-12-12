<?php

    namespace App\Http\Middleware\Api;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class UserMiddleware extends ResponseMiddleware
    {
        /**
         * Handle an incoming request.
         *
         * @param \Illuminate\Http\Request $request
         * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
         *
         * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
         */

        public function validator(Request $request)
        {
            $validator = Validator::make($request->toArray(), [
                'name'        => 'required',
                'surname'     => 'required',
                'email'       => 'required|email',
                'userName'    => 'required',
                'tagline'     => 'required',
                'region'      => 'required',
                'phoneNumber' => 'required|unique:users',
                'password'    => 'required',
            ]);
            if ($validator->fails()) {
                return $this->apiResponse(null, $validator->errors(), 400);
            } else {
                return true;
            }
        }

        public function handle(Request $request, Closure $next)
        {
            $isValid = $this->validator($request);
            if ($isValid !== true) {
                return $isValid;
            } else {
                return $next($request);
            }

        }
    }
