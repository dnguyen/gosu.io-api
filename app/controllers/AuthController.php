<?php

use Gosu\Repositories\MySQLAuthRepository;

class AuthController extends BaseController {

    protected $AuthRepository;

    public function __construct(MySQLAuthRepository $authRepository) {
        $this->AuthRepository = $authRepository;
    }

    public function index() {

        $token = Input::get('token');

        if (!$token) {
            return Response::json(array(), 404);
        }

        $authed = $this->AuthRepository->find($token);

        if (!is_null($authed)) {
            return Response::json($authed, 200);
        } else {
            return Response::json($authed, 404);
        }
    }

    public function logout() {
        $response = new stdClass();

        if (Input::get('token')) {
            $this->AuthRepository->remove(Input::get('token'));
            return Response::json($response, 204);
        } else {
            return Response::json($response, 400);
        }
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
    public function store() {
        $username = Input::get('username');
        $password = Input::get('password');
        $response = new stdClass();

        if (User::exists($username)) {

            $user = User::getByUserName($username);

            if (Hash::check($password, $user->password)) {
                $token = '';

                if ($this->AuthRepository->exists($user->id)) {
                    $token = $this->AuthRepository->update($user->id);
                } else {
                    $token = $this->AuthRepository->create($user->id);
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