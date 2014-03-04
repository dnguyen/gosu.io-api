<?php
class User extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    public $timestamps = false;

    public static function insert($username, $password) {
        $userId = DB::table('users')->insertGetId(
            array(
                'username' => $username,
                'password' => Hash::make($password)
            )
        );

        $generatedToken = md5(uniqid(mt_rand(), true));

        DB::table('auth_tokens')->insert(
            array(
                'userid' => $userId,
                'token' => $generatedToken
            )
        );

        return $generatedToken;

    }

    public static function exists($username) {
        $query = DB::table('users')->select('username')->where('username', '=', $username);
        $count = $query->count();

        if ($count >= 1)
            return true;
        else
            return false;
    }

    public static function getByUserName($username) {
        $query = DB::table('users')->select('*');
        $query->where('username', '=', $username);

        $user = $query->get();

        return $user[0];
    }

}