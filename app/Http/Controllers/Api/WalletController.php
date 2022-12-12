<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\wallet;
    use Exception;
    use Illuminate\Http\Request;

    class WalletController extends ResponseController
    {
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
        public function store(int $id)
        {

            $wallet          = new Wallet();
            $wallet->userId  = $id;
            $wallet->balance = 0.00;
            $isSuccess=$wallet->save();
            if ($isSuccess) {
                return true;
            } else {
                throw new Exception('An error occurred while creating the wallet! Please try again.');
            }





        }

        /**
         * Display the specified resource.
         *
         * @param \App\Models\wallet $wallet
         *
         * @return \Illuminate\Http\Response
         */
        public function show(wallet $wallet)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param \App\Models\wallet       $wallet
         *
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, wallet $wallet)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param \App\Models\wallet $wallet
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy(wallet $wallet)
        {
            //
        }


    }
