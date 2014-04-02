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

    public static function getArtistsForPage($page, $filters = array(), $sorts = array()) {
        // Get number of results
        $artistsMatchedCountQuery = DB::table('artists')->select('*');

        if ($filters['gender'] != 'all')
            $artistsMatchedCountQuery->where('gender', '=', $filters['gender']);
        if ($filters['type'] != 'all')
            $artistsMatchedCountQuery->where('type', '=', $filters['type']);

        $artistsMatchedCountQuery->orderBy('name', '');
        $artistsMatchedCount = $artistsMatchedCountQuery->count();

        $start = ($page - 1) * 32;
        $end = $page * 32 - (($page - 1) * 32);

        $artistsQuery = DB::table('artists')->select('*');

        if ($filters['gender'] != 'all')
            $artistsQuery->where('gender', $filters['gender']);
        if ($filters['type'] != 'all')
            $artistsQuery->where('type', $filters['type']);

        if ($sorts['order'] === 'desc')
            $sorts['order'] = "";
        else
            $sorts['order'] = "desc";

        $artistsQuery->orderBy($sorts['type'], $sorts['order']);

        if ($artistsMatchedCount > 32)
            $artistsQuery->skip($start)->take($end);

        $artists = $artistsQuery->get();

        return $artists;
    }

    public static function getArtistsTotalPageCount() {
        $totalPagecount = ceil(DB::table('artists')->count() / 32);

        return $totalPagecount;
    }
}