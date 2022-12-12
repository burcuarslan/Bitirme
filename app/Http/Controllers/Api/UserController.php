<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\user;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\App;

    class UserController extends ResponseController
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
        public function store(Request $request)
        {

            $getByPuuId=\app('App\Http\Controllers\Api\ValorantApiController')->getByPuuId($request);
            if ($getByPuuId){
                $user              = new User();
                $user->name        = $request->name;
                $user->surname     = $request->surname;
                $user->userName    = $request->userName;
                $user->puuId       = $getByPuuId;
                $user->tagline     = $request->tagline;
                $user->region      = $request->region;
                $user->email       = $request->email;
                $user->password    = $request->password;
                $user->phoneNumber = $request->phoneNumber;
                $isSuccess         = $user->save();
                if ($isSuccess) {
                    $wallet     = \app('App\Http\Controllers\Api\WalletController')->store($user->id);
                    $userDetail = \app('App\Http\Controllers\Api\ValorantApiController')->getUserDetail($user);
                    if ($wallet && $userDetail) {
                        return $this->apiResponse($user, 'User created successfully.', 200);
                    }

                } else {
                    throw new Exception('Failed to create user! Please try again.');
                }
            }


        }

        /**
         * Display the specified resource.
         *
         * @param \App\Models\user $user
         *
         * @return \Illuminate\Http\Response
         */
        public function show(user $user)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param \App\Models\user         $user
         *
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, user $user)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param \App\Models\user $user
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy(user $user)
        {
            //
        }
    }
