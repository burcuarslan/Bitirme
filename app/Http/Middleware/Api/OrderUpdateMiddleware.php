<?php

	namespace App\Http\Middleware\Api;

	use App\Http\Enum\OrderStatus;
    use App\Models\Order;
    use Closure;
    use Illuminate\Http\Request;
    use Tymon\JWTAuth\Facades\JWTAuth;

    class OrderUpdateMiddleware  extends ResponseMiddleware
	{
        private function checkIfUserIsProvider(Request $request)
        {
            $user = JWTAuth::parseToken()->authenticate();
            $order=Order::find($request->id);
            if ($user->id == $order->providerId) {
                $isTrue = $request->validate([
                                                 'status' => 'required|in:' .OrderStatus::COMPLETED, OrderStatus::CANCELED, OrderStatus::IN_PROGRESS
                                             ]);
                return true;
            } elseif ($user->id == $order->recipientId) {
                $request->validate([
                                                 'status' => 'required|in:' . OrderStatus::CANCELED
                                             ]);
                return true;
            } else {
                return false;
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
            $validator = self::checkIfUserIsProvider($request);
            return $validator === true ? $next($request) : $this->apiResponse(null, "Yetkiniz Bulunmamakta", 400);
        }
	}
