<?php
class Vote extends Eloquent {
    protected $table = 'votes';
    public $timestamps = false;

    /**
     * Gets a users vote for a track
     * @param $userid
     * @param $trackid
     *
     * @return stdClass
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

    /**
     * Inserts a new vote
     * @param $data
     *
     * @return void
     */
    public static function insert($data) {
        DB::table('votes')
            ->insert(array(
                'userid' => $data['userid'],
                'trackid' => $data['trackid'],
                'liked' => $data['liked'],
                'created_on' => date("Y-m-d H:i:s")
            ));
    }

    /**
     * Removes a vote by user id and track id
     * @param $data
     *
     * @return void
     */
    public static function remove($data) {
        DB::table('votes')
            ->where('userid', '=', $data['userid'])
            ->where('trackid', '=', $data['trackid'])
            ->delete();
    }

    /**
     * Updates a vote by user id and track id
     * @param $data
     *
     * @return void
     */
    public static function updateLiked($data) {
        DB::table('votes')
            ->where('userid', '=', $data['userid'])
            ->where('trackid', '=', $data['trackid'])
            ->update(array(
                'liked' => $data['liked'],
                'created_on' => date("Y-m-d H:i:s")
            ));
    }

    /**
     * Checks if a vote exists for a user id and track id
     * @param $userid
     * @param $trackid
     *
     * @return bool
     */
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
