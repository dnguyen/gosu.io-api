<?php
use Gosu\Repositories\MySQLAuthRepository;

class AuthTest extends TestCase {
    public function testGoodAuthToken() {
        $user = AuthToken::auth('fca20e7dc22f80519d580adb1719a696');
        $this->assertEquals($user->userid, 2);
    }
}