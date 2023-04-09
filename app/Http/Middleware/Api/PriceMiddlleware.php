<?php

    namespace App\Http\Middleware\Api;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class PriceMiddlleware extends ResponseMiddleware
    {

        private function priceValidator(Request $request)
        {
            $validator = Validator::make($request->toArray(), [
//                'providerId'     => 'required|integer|exists:users,id',
                'categoryId'     => 'required|integer|exists:match_categories,id',
                'pricePerMinute' => 'required|numeric:2',
                'priceMinute'    => 'required|numeric:2',

            ]);
            if ($validator->fails()) {
                return $validator->errors();
            } else {
                return true;
            }
        }

        /**
         * Handle an incoming request.
         *
         * @param \Illuminate\Http\Request                                                                          $request
         * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
         *
         * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
         */
        public function handle(Request $request, Closure $next)
        {

            $validator = self::priceValidator($request);
            return $validator === true ? $next($request) : $this->apiResponse(null, $validator, 400);

        }
    }
