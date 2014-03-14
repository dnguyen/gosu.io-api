<?php
namespace Gosu\Repositories;

use Track;

class MySQLTracksRepository implements TracksRepositoryInterface {
    protected $track;

    public function __construct(Track $track) {
        $this->track = $track;
    }

    public function all() {
        return $this->track->all()->toArray();
    }

    public function allSorted($sortType, $order) {
        return $this->track->getAllSorted($sortType, $order);
    }

    public function find($id) {
        return $this->track->getTrack($id);
    }

    public function forPage($page, $settings) {
        return $this->track->getTracksForPage($page, $settings);
    }

    public function pageCount() {
        return $this->track->getTracksTotalPageCount();
    }

    public function filter($filterOptions) {
        return $this->track->filter($filterOptions);
    }
}