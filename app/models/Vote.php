<?php
class Vote extends Eloquent {
    protected $table = 'tracks';
    public $timestamps = false;

    /**
     * Gets a users vote for a track
     * @param $userid
     * @param $trackid
     *
     * returns vote
     */
    public static function get($userid, $trackid) {
        $vote = DB::table('votes')
            ->select('*')
            ->where('userid', '=', $userid)
            ->where('trackid', '=', $trackid)
            ->get();

        if (count($vote) > 0) {
            return $vote[0];
        } else {
            return NULL;
        }
    }

    public static function insert($data) {
        DB::table('votes')
            ->insert(array(
                'userid' => $data['userid'],
                'trackid' => $data['trackid'],
                'liked' => $data['liked']
            ));
    }

    public static function remove($data) {
        DB::table('votes')
            ->where('userid', '=', $data['userid'])
            ->where('trackid', '=', $data['trackid'])
            ->delete();
    }

    public static function updateLiked($data) {
        DB::table('votes')
            ->where('userid', '=', $data['userid'])
            ->where('trackid', '=', $data['trackid'])
            ->update(array(
                'liked' => $data['liked']
            ));
    }

    public static function exists($userid, $trackid) {
        $count = DB::table('votes')
            ->select('*')
            ->where('userid', '=', $userid)
            ->where('trackid', '=', $trackid)
            ->count();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }
}
