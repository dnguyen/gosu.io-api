<?php

/*
    Entire authentication method needs to be refactored. REST APIs should be
    stateless. Use some kind of token instead of sessions.
*/
class AuthController extends BaseController {


    public function index() {

        $token = Input::get('token');

        if (!$token) {
            return Response::json(array(), 404);
        }

        $authed = AuthToken::auth($token);

        if ($authed) {
            return Response::json($authed, 200);
        } else {
            return Response::json($authed, 404);
        }
    }

    public function logout() {
        $response = new stdClass();

        if (Input::get('token')) {
            AuthToken::remove(Input::get('token'));
            return Response::json($response, 204);
        } else {
            return Response::json($response, 400);
        }
    }

    public function AuthToken() {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return 'post';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $username = Input::get('username');
        $password = Input::get('password');
        $response = new stdClass();

        if (User::exists($username)) {

            $user = User::getByUserName($username);

            if (Hash::check($password, $user->password)) {
                $token = '';

                if (AuthToken::exists($user->id)) {
                    $token = AuthToken::updateToken($user->id);
                } else {
                    $token = AuthToken::insert($user->id);
                }

                $response->token = $token;

                return Response::json($response, 200);
            } else {
                $response->message = "The username or password is incorrect.";
                return Response::json($response, 400);
            }
        } else {
            $response->message = "The username or password is incorrect.";
            return Response::json($response, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {

    }

}