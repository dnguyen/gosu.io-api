<?php
namespace Gosu\Repositories;

interface TracksRepositoryInterface {
    public function all();
    public function allSorted($sortType, $order);
    public function find($id);
    public function forPage($page, $settings);
    public function pageCount();
}