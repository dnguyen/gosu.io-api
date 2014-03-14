<?php
namespace Gosu\Repositories;

interface AuthRepositoryInterface {
    public function create($userid);
    public function exists($userid);
    public function find($token);
    public function update($userid);
    public function remove($token);
}