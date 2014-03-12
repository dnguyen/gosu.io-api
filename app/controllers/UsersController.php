<?php

class UsersController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $username = Input::get('username');
        $password = Input::get('password');
        $email = Input::get('email');
        $responseObj = new stdClass();

        if (!User::exists($username)) {
            $token = User::insert(array(
                'username' => $username,
                'password' => $password,
                'email' => $email
            ));

            if (!is_null($token)) {
                $responseObj->token = $token;

                return Response::json($responseObj, 200);
            } else {
                return Response::json(NULL, 400);
            }
        } else {
            $responseObj->message = "Username is not available.";
            return Response::json($responseObj, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}