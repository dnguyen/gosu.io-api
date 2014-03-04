<?php
class AuthToken extends Eloquent {
    protected $table = 'auth_tokens';
    public $timestamps = false;

    public static function insert($userid) {
        $generatedToken = md5(uniqid(mt_rand(), true));

        DB::table('auth_tokens')->insert(
            array(
                'userid' => $userid,
                'token' => $generatedToken
            )
        );

        return $generatedToken;
    }

    // Checks if a user already has a token in the database
    public static function exists($userid) {
        $query = DB::table('auth_tokens')->select('userid')->where('userid', '=', $userid);
        if ($query->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Authorizes a token. Returns userid, username, and token
    public static function auth($token) {
        $user = DB::table('auth_tokens')
        ->select(
            array(
                'users.id',
                'users.username',
                'auth_tokens.token'
            )
        )
        ->join('users', 'users.id', '=', 'auth_tokens.userid')
        ->where('auth_tokens.token', '=', $token)->get();

        return $user[0];
    }

    public static function updateToken($userid) {
        $generatedToken = md5(uniqid(mt_rand(), true));
        DB::table('auth_tokens')->where('userid', '=', $userid)->update(array('token' => $generatedToken));

        return $generatedToken;
    }

    public static function remove($token) {
        return DB::table('auth_tokens')->where('token', '=', $token)->delete();
    }
}