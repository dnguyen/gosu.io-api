<?php

class Playlist extends Eloquent {
    protected $table = 'playlists';
    public $timestamps = false;

    /**
     * Gets all playlists from the database
     * @return array
     */
    public static function getAll() {
        //$playlists = DB::table('playlists')->select('*')->get();

        //return $playlists;
        //return Playlist::all();
        return Playlist::all();
    }

    /**
     * Gets a playlist by id
     * @param  string $id
     * @return object
     */
    public static function getById($id) {
        $playlist = DB::table('playlists')->select('*')->where('id', '=', $id)->get();

        return $playlist[0];
    }

    /**
     * Gets tracks for a playlist
     * @param  string $id
     * @return array
     */
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

    /**
     * Inserts a new playlist to the database.
     * @param  array $data
     * @return string
     */
    public static function insert($data) {

        $playlistid = hash('crc32b', uniqid());

        $user = AuthToken::auth($data['token']);

        DB::table('playlists')
        ->insert(
            array(
                'id' => $playlistid,
                'name' => $data['name'],
                'userid' => $user->id,
                'public' => $data['private'] == 0 ? 1 : 0,
                'createdon' => date("Y-m-d H:i:s")
            )
        );

        return $playlistid;
    }

    /**
     * Gets playlists for a user
     * @param  string $userid
     * @return array
     */
    public static function getAllForUser($userid) {
        return DB::table('playlists')->select('*')->where('userid', '=', $userid)->get();
    }
}