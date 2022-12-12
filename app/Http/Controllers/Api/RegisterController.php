<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use Exception;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;

    class RegisterController extends ResponseController
    {

        public function __construct()
        {
            $this->middleware('userMiddleware');
        }

        public function register(Request $request)
        {
            try {
                $user = app('App\Http\Controllers\Api\UserController')->store($request);
                return $this->apiResponse($user->getData()->data, 'User created successfully.', 200);
            } catch (Exception $e) {
                return $this->apiResponse(null, $e->getMessage(), 400);
            }catch (QueryException $e){
                return $this->apiResponse(null, $e->getMessage(), 400);
            }
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
