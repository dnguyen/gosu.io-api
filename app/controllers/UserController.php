<?php

class UserController extends BaseController {

    public function getPlaylists() {
        $token = Input::get('token');

        $user = AuthToken::auth($token);
        if (!is_null($user)) {
            $playlists = Playlist::getAllForUser($user->id);

            return Response::json($playlists, 200);
        } else {
            return Response::json(NULL, 404);
        }
    }
}