<?php
namespace Gosu\Repositories;

use AuthToken;

class MySQLAuthRepository implements AuthRepositoryInterface {
    protected $authToken;

    public function __construct(AuthToken $token) {
        $this->authToken = $token;
    }

    public function create($userid) {
        return $this->authToken->insert($userid);
    }

    public function exists($userid) {
        return $this->authToken->exists($userid);
    }

    public function find($token) {
        return $this->authToken->auth($token);
    }

    public function update($userid) {
        return $this->authToken->insert($userid);
    }
    public function remove($token) {
        return $this->authToken->remove($token);
    }
}