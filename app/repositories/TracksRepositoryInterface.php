<?php
namespace Gosu\Repositories;

interface TracksRepositoryInterface {
    public function all();
    public function allSorted($sortType, $order);
    public function find($id);
    public function forPage($page, $settings);
    public function pageCount();
    public function filter($filterOptions);
    public function getStats($trackid);
    public function comingSoon($count);
    public function updateData($trackid);
}