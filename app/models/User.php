<?php
class User extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    public $timestamps = false;

    /**
     * Inserts a new user into the database.
     *
     * @param array $data
     * @return string|null
     */
    public static function insert($data) {
        $validator = Validator::make($data,
            array(
                'username' => array('required', 'alpha_dash', 'min:3', 'max:20'),
                'password' => array('required', 'min:4'),
                'email' => array('email')
            )
        );

        if ($validator->passes()) {
            $userId = DB::table('users')->insertGetId(
                array(
                    'username' => $data['username'],
                    'password' => Hash::make($data['password']),
                    'email' => $data['email']
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
        } else {
            return NULL;
        }

    }

    /**
     * Checks if a user with a username already exists
     * @param  string $username
     * @return bool
     */
    public static function exists($username) {
        $query = DB::table('users')->select('username')->where('username', '=', $username);
        $count = $query->count();

        if ($count >= 1)
            return true;
        else
            return false;
    }

    /**
     * Gets a user from the database by username
     * @param  string $username
     * @return user
     */
    public static function getByUserName($username) {
        $query = DB::table('users')->select('*');
        $query->where('username', '=', $username);

        $user = $query->get();

        return $user[0];
    }

}