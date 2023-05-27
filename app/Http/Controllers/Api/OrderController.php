<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Order;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Http\Request;
    use App\Http\Enum\OrderStatus;
    use PhpParser\Node\Expr\List_;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Tymon\JWTAuth\Facades\JWTAuth;


    class OrderController extends ResponseController
    {

        public function __construct()
        {
            $this->middleware('orderMiddleware')->only('store');
//            $this->middleware('checkUserforOrderUpdate')->only('updateOrder');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
                $user = JWTAuth::parseToken()->authenticate();
                $orders = Order::where('recipientId', $user->id)->with('user')->with('price')->get();
                return $this->apiResponse($orders, 'Orders listed successfully', 200);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            try {
                $jwt = JWTAuth::parseToken()->authenticate();

            } catch (JWTException $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }
            $order              = new Order();
            $order->recipientId = $jwt->id;
            $order->providerId  = $request->providerId;
            $order->priceId     = $request->priceId;
            $order->status      = OrderStatus::PENDING;
            $order->createdAt   = Carbon::now();
            $order->description = $request->description;
            $isSuccess          = $order->save();

            if ($isSuccess) {

                return $this->apiResponse($order, 'Order created successfully', 200);
            } else {
                return throw new Exception('Order could not be created.');
            }
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param int                      $id
         *
         * @return \Illuminate\Http\Response
         */
        public function updateOrder(Request $request)
        {
            try {
                $user          = JWTAuth::parseToken()->authenticate();
                $order         = Order::find($request->id);
                $order->status = $request->status;
                $order->save();
                return $this->apiResponse($order, 'Order updated successfully', 200);

            } catch (JWTException $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }


        }

        public function ordersRequest(Request $request)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            }
            catch (JWTException $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }

            $orders = Order::where('providerId', $user->id)->with('user')->with('price')->get();
            return $this->apiResponse($orders, 'Orders listed successfully', 200);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            //
        }
    }
