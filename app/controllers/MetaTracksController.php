<?php

class MetaTracksController extends BaseController {
    public function recentlyUploaded() {
        $count = Input::get('count', 12);
        return Response::json(Track::getRecentlyUploaded($count));
    }

    public function mostViewed() {
        $count = Input::get('count', 5);
        return Response::json(Track::getMostViewed($count));
    }
}