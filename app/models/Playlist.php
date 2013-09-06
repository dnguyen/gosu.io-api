<?php

class Playlist extends Eloquent {
    protected $table = 'playlists';
    public $timestamps = false;

    public static function getAll() {
        $playlists = DB::table('playlists')->select('*')->get();

        return $playlists;
    }

    public static function getById($id) {
        $playlist = DB::table('playlists')->select('*')->where('uniqueid', '=', $id)->get();

        return $playlist[0];
    }

    public static function getTracks($id) {
        $playlist = DB::table('playlists')
        ->select(
            array(
                'playlists.uniqueid',
                'playlist_tracks.playlistTrackId',
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
        ->join('playlist_tracks', 'playlists.uniqueid', '=', 'playlist_tracks.playlistid')
        ->join('tracks', 'playlist_tracks.trackid', '=', 'tracks.id')
        ->join('artists', 'tracks.artist', '=', 'artists.id')
        ->where('playlists.uniqueid', '=', $id)
        ->orderBy('playlist_tracks.order', 'desc')->get();

        return $playlist;
    }
}