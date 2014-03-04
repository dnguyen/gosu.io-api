<?php

class Playlist extends Eloquent {
    protected $table = 'playlists';
    public $timestamps = false;

    public static function getAll() {
        $playlists = DB::table('playlists')->select('*')->get();

        return $playlists;
    }

    public static function getById($id) {
        $playlist = DB::table('playlists')->select('*')->where('id', '=', $id)->get();

        return $playlist[0];
    }

    public static function getTracks($id) {
        $playlist = DB::table('playlists')
        ->select(
            array(
                'playlists.id',
                'playlist_tracks.id',
                'playlist_tracks.playlistid',
                'playlist_tracks.trackid',
                'playlist_tracks.order',
                'tracks.id',
                'tracks.artist',
                'tracks.videoId',
                'tracks.title',
                'artists.name'
            )
        )
        ->join('playlist_tracks', 'playlists.id', '=', 'playlist_tracks.playlistid')
        ->join('tracks', 'playlist_tracks.trackid', '=', 'tracks.id')
        ->join('artists', 'tracks.artist', '=', 'artists.id')
        ->where('playlists.id', '=', $id)
        ->orderBy('playlist_tracks.order', 'desc')->get();

        return $playlist;
    }

    public static function insert($data) {
        DB::table('playlists')
        ->insert(
            array(
                'id' => hash('crc32b', uniqid()),
                'name' => $data['name'],
                'public' => $data['private'] == 0 ? 1 : 0
            )
        );
    }
}