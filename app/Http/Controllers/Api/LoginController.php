<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Tymon\JWTAuth\Facades\JWTAuth;

    class LoginController extends ResponseController
    {

        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login']]);
//            $this->middleware('checkBannedOrDeleted');
        }

//        public function login(Request $request)
//        {
//
//            $user = User::where(['email' => $request->email, 'password' => $request->password])->first();
//            if ($user) {
//                return $this->apiResponse($user->name, 'Hoş geldin.', 200);
//            } else {
//                return $this->apiResponse(null, 'Invalid credentials.', 401);
//            }
//
//        }

        public function login(Request $request)
        {
            $user = User::where(['email' => $request->email])->first();

            $credentials = [
                'email'    => $request->email,
                'password' => $request->password,
            ];

            if (!$user) return $this->apiResponse(null, 'Email is incorrect', 403);

            if (!$token = JWTAuth::attempt($credentials)) return $this->apiResponse(null,
                                                                                    'Email or Password is incorrect',
                                                                                    403);

            if ($user->userType == 'Banned') return $this->apiResponse(null, 'Your account is banned', 405);

            if ($user->userType == 'Deleted') return $this->apiResponse(null, 'Your account is deleted', 406);

//                return $user->password;
            return $this->respondWithToken($token);

            //            if (!$token = JWTAuth::attempt($credentials)) {
            //                return $this->apiResponse(null, 'You need to confirm your email', 403, $user->eMailVerify);
            //            }


        }

        public function guard()
        {
            return Auth::guard('api');
        }

        private function respondWithToken($token)
        {
            return $this->apiResponse([
                                          'access_token' => $token,
                                          'token_type'   => 'bearer',
                                          'expires_in'   => auth()->factory()->getTTL() * 60*24,
                                          //                                             'eMailVerify'  => auth()->user()->eMailVerify == 1,
                                      ], 'Login successfully', 200);

        }

//        public static function logout()
//        {
//
//            auth()->logout();
//            JWTAuth::getToken(); // Ensures token is already loaded.
//            $forever = true;
//            JWTAuth::invalidate($forever);
//            return app(ApiController::class)->apiResponse(null, 'Successfully logged out', 200);
//        }

    }
