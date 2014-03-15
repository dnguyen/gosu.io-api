<?php

class VotesController extends BaseController {
    public function insert() {
        $token = Input::get('token');
        $liked = Input::get('liked');
        $user = AuthToken::auth($token);
        $vote = Vote::get($user->id, Input::get('trackid'));

        // Make sure user is authenticated and the vote doesn't already exist
        if (!is_null($user)) {
            // If vote doesn't exist insert a new vote,
            // If it does already exist, just update the users vote.
            if (is_null($vote)) {
                Vote::insert(array(
                    'trackid' => Input::get('trackid'),
                    'userid' => $user->id,
                    'liked' => Input::get('liked')
                ));

            } else {
                // Delete the vote from the database if user voting for the same thing.
                // note: probably doesn't scale very well...will probably just want to add votes with liked = 0
                //       to some kind of queue that will be cleared every once in awhile.
                if ($liked == $vote->liked) {
                    Vote::remove(array(
                        'userid' => $user->id,
                        'trackid' => Input::get('trackid')
                    ));
                } else {
                    // Only update the vote if the vote has changed.
                    Vote::updateLiked(array(
                        'userid' => $user->id,
                        'trackid' => Input::get('trackid'),
                        'liked' => Input::get('liked')
                    ));
                }
            }

            return Response::json(NULL, 204);
        } else {
            return Response::json(NULL, 400);
        }
    }
}