<?php

    namespace App\Http\Middleware\Api;

    use App\Http\Controllers\Api\ResponseController;
    use Closure;
    use Illuminate\Http\Request;

    class ValorantApiMiddleware extends ResponseMiddleware
    {

        private function userDetailValidator(Request $request)
        {
            $rules = [
                'userId'              => 'required|unique:users,id',
                'rank'                => 'required',
                'currentTier'         => 'required',
                'rankingInTier'       => 'required',
                'mmrChangeToLastGame' => 'required',
                'elo'                 => 'required',
                'old'                 => 'required',
            ];

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

            return $next($request);
        }
    }
