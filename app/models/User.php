<?php
class User extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	public $timestamps = false;

	public static function createNew($username, $password) {
		$query = DB::table('users')->insert(
			array(
				'username' => $username,
				'password' => Hash::make($password)
			)
		);

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