<?php

class UserSession {
    public static function getAllData() {
        return Session::all();
    }

    public static function isLoggedIn() {
        if (!Session::has('loggedin')) {
            Session::put('loggedin', false);
        }
        return Session::get('loggedin');
    }

    public static function setLogin() {
        Session::put('loggedin', true);
    }

    public static function setUsername($username) {
        Session::put('username', $username);
    }

    public static function destroy() {
        Session::flush();
    }
}