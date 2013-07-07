<?php

class NewTrackReleasesController extends BaseController {
    public function index() {
        $count = Input::get('count', 12);
        return Response::json(Track::getRecentlyUploaded($count));
    }
}