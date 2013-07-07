<?php

class Artist extends Eloquent {
    protected $table = 'artists';
    public $timestamps = false;

    public function tracks() {
        return $this->hasMany('Track', 'artist');
    }
}