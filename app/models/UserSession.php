<?php

class UserSession {
    public static function getAllData() {
        return Session::flush();
    }

    public static function isLoggedIn() {
        if (!Session::has('loggedin')) {
            Session::put('loggedin', false);
        }
        return Session::get('loggedin');
    }
}