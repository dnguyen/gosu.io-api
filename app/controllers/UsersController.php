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
        $responseObj = new stdClass();

        /*
            Make sure username only contains alphanumeric characters, underscores, and dashes. And is
            between 3 and 20 characters long. And the username doesn't already exist.

            After inserting new user, log user in.
        */
        if (preg_match('/^[a-z0-9_-]{3,20}$/i', $username)) {
            if (!User::exists($username)) {
                $token = User::insert($username, $password);
                $responseObj->token = $token;

                return Response::json($responseObj, 200);
            } else {
                $responseObj->message = "Username is not available.";
                return Response::json($responseObj, 400);
            }
        } else {
            $responseObj->message = "Username must be between 3 and 20 characters and can only contain alphanumeric characters, underscores, and dashes.";
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