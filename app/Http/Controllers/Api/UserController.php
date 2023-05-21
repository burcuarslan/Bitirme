<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\Price;
    use App\Models\user;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\App;
    use Tymon\JWTAuth\Exceptions\JWTException;
    use Tymon\JWTAuth\Facades\JWTAuth;

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

            $getByPuuId = \app('App\Http\Controllers\Api\ValorantApiController')->getByPuuId($request);
            if ($getByPuuId) {
                $user              = new User();
                $user->name        = $request->name;
                $user->surname     = $request->surname;
                $user->userName    = $request->userName;
                $user->puuId       = $getByPuuId;
                $user->tagline     = $request->tagline;
                $user->region      = $request->region;
                $user->email       = $request->email;
                $user->password    = bcrypt($request->password);
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


        public function getUser(Request $request)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }
            $userDetail = [
                'name'        => $user->name,
                'surname'     => $user->surname,
                'userName'    => $user->userName,
                'tagline'     => $user->tagline,
                'region'      => $user->region,
                'email'       => $user->email,
                'phoneNumber' => $user->phoneNumber,
                'followers'   => $user->followers,
                'following'   => $user->following,
                'prices'      => Price::getByPriceCount($user->id),
                //                'puuId'=>$user->puuId,
                //                'wallet'=>$user->wallet,
                //                'userDetail'=>$user->userDetail,
            ];
            return $this->apiResponse($userDetail, 'User listed successfully', 200);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param \App\Models\user         $user
         *
         * @return \Illuminate\Http\Response
         *
         *
         */
        public function update(Request $request)
        {
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (JWTException $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }

            $user->name        = $request->name??$user->name;
            $user->email       = $request->email??$user->email;
            $user->phoneNumber = $request->phoneNumber??$user->phoneNumber;
            $user->userName    = $request->userName??$user->userName;
            $user->tagline     = $request->tagline??$user->tagline;
            $response=$user->save()?true:false;
            if ($response){
                return $this->apiResponse($user, 'User updated successfully', 200);
            }else{
                return $this->apiResponse(null, 'User not updated', 400);
            }


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
