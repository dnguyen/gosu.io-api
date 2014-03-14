<?php

use Gosu\Repositories\MySQLAuthRepository;

class UserController extends BaseController {
    protected $AuthRepository;

    public function __construct(MySQLAuthRepository $authRepository) {
        $this->AuthRepository = $authRepository;
    }

    public function getPlaylists() {
        // Use token to get the user's id
        $token = Input::get('token');

        $user = $this->AuthRepository->find($token);

        if (!is_null($user)) {
            $playlists = Playlist::getAllForUser($user->id);

            return Response::json($playlists, 200);
        } else {
            return Response::json(NULL, 404);
        }
    }
}