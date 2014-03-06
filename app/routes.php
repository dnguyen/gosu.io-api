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

Route::get('/', function()
{
	//return View::make('hello');
    App::abort(404, 'Page not found');
});
Route::resource('tracks', 'TracksController');
Route::get('tracks/search/{terms}', array('as' => 'tracks.search' , 'uses' => 'TracksController@search'));

Route::get('NewTrackReleases', array('uses' => 'MetaTracksController@recentlyUploaded'));
Route::get('MostViewedTracks', array('uses' => 'MetaTracksController@mostViewed'));
Route::get('ComingSoonTracks', array('uses' => 'MetaTracksController@comingSoon'));

Route::resource('artists', 'ArtistsController');
Route::get('artists/search/{terms}', array('uses' => 'ArtistsController@search'));

Route::resource('session', 'SessionController');
Route::resource('auth', 'AuthController');
Route::delete('auth', array('uses' => 'AuthController@logout'));

// User data for an authenticated user
//Route::resource('user', 'UserController');
Route::get('user/playlists', array('uses' => 'UserController@getPlaylists'));

Route::resource('users', 'UsersController');

Route::resource('playlists', 'PlaylistsController');