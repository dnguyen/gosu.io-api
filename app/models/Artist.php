<?php

class Artist extends Eloquent {
    protected $table = 'artists';
    public $timestamps = false;

    public function tracks() {
        return $this->hasMany('Track', 'artist');
    }

    public static function getAll() {
        $artists = DB::table('artists')->select('*')->get();

        return $artists;
    }

    public static function getArtistsForPage($page, $filters = array()) {
        $artistsMatchedCountQuery = DB::table('artists')->select('*');

        if ($filters['gender'] != 'all')
            $artistsMatchedCountQuery->where('gender', '=', $filters['gender']);
        if ($filters['type'] != 'all')
            $artistsMatchedCountQuery->where('type', '=', $filters['type']);

        $artistsMatchedCountQuery->orderBy('name', '');
        $artistsMatchedCount = $artistsMatchedCountQuery->count();

        $start = ($page - 1) * 12;
        $end = $page * 12 - (($page - 1) * 12);

        $artistsQuery = DB::table('artists')->select('*');

        if ($filters['gender'] != 'all')
            $artistsQuery->where('gender', $filters['gender']);
        if ($filters['type'] != 'all')
            $artistsQuery->where('type', $filters['type']);

        $artistsQuery->orderBy('name', '');

        if ($artistsMatchedCount > 12)
            $artistsQuery->skip($start)->take($end);

        $artists = $artistsQuery->get();

        return $artists;
    }
}