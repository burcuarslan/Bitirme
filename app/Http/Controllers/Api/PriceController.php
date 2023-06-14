<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Price;
    use Exception;
    use Illuminate\Http\Request;
    use Namshi\JOSE\JWT;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Tymon\JWTAuth\Facades\JWTAuth;

    class PriceController extends ResponseController
    {

        public function __construct()
        {
            $this->middleware('priceMiddleware')->only('store');
        }
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            try {
                $jwt = JWTAuth::parseToken()->authenticate();

            } catch (JWTException $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }

            return $this->apiResponse(Price::meWithoutAllPrices($jwt->id), 'Prices listed successfully', 200);
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
            $price             = new Price();
            $price->providerId = $jwt->id;
            $price->categoryId = $request->categoryId;

            $price->price          = $request->price? $request->price : 0;
            $isSuccess             = $price->save();
            if ($isSuccess) {
                return $this->apiResponse($price, 'Price created successfully', 200);
            } else {
                return throw new Exception('Price could not be created.');
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
            //
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
