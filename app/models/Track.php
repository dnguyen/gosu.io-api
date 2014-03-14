<?php

class Track extends Eloquent {
    protected $table = 'tracks';
    public $timestamps = false;

    public function artist() {
        return $this->belongsTo('Artist', 'id');
    }

    public static function getAll() {
        return Track::all();
    }

    public static function getTrack($id) {
        $track = DB::table('tracks')
        ->select(array('tracks.id AS trackId', 'tracks.title', 'tracks.videoId', 'tracks.uploaded', 'tracks.viewCount', 'artists.name AS artistName', 'artists.id AS artistId'))
        ->join('artists', 'tracks.artist', '=', 'artists.id')
        ->where('tracks.id', '=', $id)
        ->get();

        return $track;
    }

    // Gets all tracks given a sort and order
    public static function getAllSorted($sortType, $order) {
        if ($sortType === 'artistName' || $sortType === 'title') {

            if ($sortType === 'artistName')
                $sortType = 'name';

            if ($order === 'desc')
                $order = '';
            if ($order === 'ascd')
                $order = 'desc';
        } else {
            if ($order === 'ascd')
                $order = '';
        }

        $tracks = DB::table('tracks')
        ->select(array('tracks.id AS trackId', 'tracks.title', 'tracks.videoId', 'tracks.uploaded', 'tracks.viewCount', 'artists.name AS artistName', 'artists.id AS artistId'))
        ->join('artists', 'tracks.artist', '=', 'artists.id')
        ->where('tracks.published', '=', '1')
        ->orderBy($sortType, $order)->get();

        return $tracks;
    }

    public static function getTracksForPage($page, $sorts = array()) {
        if ($sorts['type'] === 'artistName' || $sorts['type'] === 'title') {

            if ($sorts['type'] === 'artistName')
                $sorts['type'] = 'name';

            if ($sorts['order'] === 'desc')
                $sorts['order'] = '';
            if ($sorts['order'] === 'ascd')
                $sorts['order'] = 'desc';
        } else {
            if ($sorts['order'] === 'ascd')
                $sorts['order'] = '';
        }

        $start = ($page - 1) * 24;
        $end = $page * 24 - (($page - 1) * 24);

        $tracks = DB::table('tracks')
        ->select(array('tracks.id AS trackId', 'tracks.title', 'tracks.videoId', 'tracks.uploaded', 'tracks.viewCount', 'artists.name AS artistName', 'artists.id AS artistId'))
        ->join('artists', 'tracks.artist', '=', 'artists.id')
        ->where('tracks.published', '=', '1')
        ->orderBy($sorts['type'], $sorts['order'])
        ->skip($start)->take($end)->get();

        return $tracks;

    }

    public static function getTracksTotalPageCount() {
        $totalPagecount = ceil(DB::table('tracks')->count() / 25);

        return $totalPagecount;
    }

    public static function getMostViewed($count) {
        $tracks = DB::table('tracks')
        ->select(array(
                'tracks.id AS trackId',
                'tracks.title',
                'tracks.videoId',
                'tracks.uploaded',
                'tracks.viewCount',
                'artists.name AS artistName',
                'artists.id AS artistId'))
        ->join('artists', 'tracks.artist', '=', 'artists.id')
        ->orderBy('viewCount', 'DESC')
        ->take($count)->get();

        return $tracks;
    }

    public static function getRecentlyUploaded($count) {
        $tracks = DB::table('tracks')
        ->select(array(
                'tracks.id AS trackId',
                'tracks.title',
                'tracks.videoId',
                'tracks.uploaded',
                'tracks.viewCount',
                'artists.name AS artistName',
                'artists.id AS artistId'))
        ->join('artists', 'tracks.artist', '=', 'artists.id')
        ->orderBy('uploaded', 'DESC')
        ->take($count)->get();

        return $tracks;
    }

    public static function filter($filterOptions) {
        if (!isset($filterOptions['sort'])) {
            $filterOptions['sort'] = 'uploaded';
        }

        if (!isset($filterOptions['order'])) {
            $filterOptions['order'] = 'DESC';
        }

        if (!isset($filterOptions['count'])) {
            $filterOptions['count'] = 25;
        }

        $tracks = DB::table('tracks')
            ->select(array(
                'tracks.id AS trackId',
                'tracks.title',
                'tracks.videoId',
                'tracks.uploaded',
                'tracks.viewCount',
                'artists.name AS artistName',
                'artists.id AS artistId'))
            ->join('artists', 'tracks.artist', '=', 'artists.id')
            ->orderBy($filterOptions['sort'], $filterOptions['order'])
            ->take($filterOptions['count'])->get();

        return $tracks;
    }

    public static function getComingSoon($count) {
        $tracks = DB::table('coming_soon_tracks')
        ->select(array(
               'coming_soon_tracks.*',
               'artists.name AS artistName'
               ))
        ->join('artists', 'coming_soon_tracks.artistId', '=', 'artists.id')
        ->orderBy('date', 'DESC')
        ->take($count)->get();

        return $tracks;
    }

}