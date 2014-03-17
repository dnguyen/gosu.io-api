<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// TODO:
//      Remove ::resource routes, and just create routes manually because
//      we don't need all the routes the resource provides.
Route::get('/', function() {
    App::abort(404, 'Page not found');
});

Route::get('tracks/search/{terms}', 'TracksController@search');
Route::get('tracks/filter', 'TracksController@filter');
Route::get('tracks/comingsoon', 'TracksController@comingSoon');
Route::get('tracks/{id}', 'TracksController@show');
Route::get('tracks', 'TracksController@index');

Route::post('votes', 'VotesController@insert');

Route::resource('artists', 'ArtistsController');
Route::get('artists/search/{terms}','ArtistsController@search');

Route::resource('auth', 'AuthController');
Route::delete('auth', 'AuthController@logout');

// User data for an authenticated user
//Route::resource('user', 'UserController');
Route::get('user/playlists', 'UserController@getPlaylists');

Route::resource('users', 'UsersController');

Route::resource('playlists', 'PlaylistsController');