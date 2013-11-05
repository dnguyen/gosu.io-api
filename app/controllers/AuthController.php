<?php

class AuthController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $username = Input::get('username');
        $password = Input::get('password');

        $response = new stdClass();
        if (User::exists($username)) {
            $user = User::getByUserName($username);
            if (Hash::check($password, $user->password)) {
                $response->status = true;
                $response->message = "Logged in";

                UserSession::setLogin();
                UserSession::setUsername($username);
            } else {
                $response->status = false;
                $response->message = "The username or password is incorrect.";
            }
        } else {
            $response->status = false;
            $response->message = "The username or password is incorrect.";
        }

        return Response::json($response);
    }

    public function logout() {
        UserSession::destroy();

        $response = new stdClass();
        $response->status = true;
        $response->message = "Logged out";

        return Response::json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

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
    }

}