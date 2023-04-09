<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Order;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Http\Request;
    use App\Http\Enum\OrderStatus;


    class OrderController extends ResponseController
    {

        public function __construct()
        {
            $this->middleware('orderMiddleware')->only('store');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            //
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
            $order              = new Order();
            $order->recipientId = $request->recipientId;
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
        public function update(Request $request, $id)
        {
//            $order=Order::find($id);
//            $order->comp
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
