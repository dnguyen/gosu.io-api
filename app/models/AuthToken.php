<?php
class AuthToken extends Eloquent {
    protected $table = 'auth_tokens';
    public $timestamps = false;

    /**
     * Insert a new authentication token for a user
     * @param  string $userid
     * @return string
     */
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

    /**
     * Checks if a user already has an authentication token in the database.
     * @param  string $userid
     * @return bool
     */
    public static function exists($userid) {
        $query = DB::table('auth_tokens')->select('userid')->where('userid', '=', $userid);
        if ($query->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Authorizes a token. If the token exists, then the user is authorized.
     * @param  string $token
     * @return user|null
     */
    public static function auth($token) {
        $user = DB::table('auth_tokens')
        ->select(
            array(
                'users.id',
                'users.username',
                'users.permissions',
                'auth_tokens.token'
            )
        )
        ->join('users', 'users.id', '=', 'auth_tokens.userid')
        ->where('auth_tokens.token', '=', $token)->get();

        if (count($user) > 0) {
            return $user[0];
        } else {
            return NULL;
        }
    }

    /**
     * Regenerates a user's authentication token.
     * @param   $userid
     * @return string
     */
    public static function updateToken($userid) {
        $generatedToken = md5(uniqid(mt_rand(), true));
        DB::table('auth_tokens')->where('userid', '=', $userid)->update(array('token' => $generatedToken));

        return $generatedToken;
    }

    /**
     * Deletes an authentication token from the database
     * @param  string $token
     * @return
     */
    public static function remove($token) {
        return DB::table('auth_tokens')->where('token', '=', $token)->delete();
    }
}