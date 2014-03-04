<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        $statusCode = 204;
        $headers = [
            'Access-Control-Allow-Origin'      => 'http://dev.gosukpop.com',
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers'     => 'origin, content-type, accept, authorization, x-requested-with, enctype, Content-Disposition',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400'
        ];
        return Response::make(null, $statusCode, $headers);
    }
});


App::after(function($request, $response)
{
    $response->headers->set('Access-Control-Allow-Origin', 'http://dev.gosukpop.com');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept, authorization, x-requested-with, enctype, Content-Disposition');
    $response->headers->set('Access-Control-Allow-Credentials', 'true');
    $response->headers->set('Access-Control-Max-Age','86400');
    return $response;
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('auth.token', function() {
    // Get userid and token from input parameters (These should be stored in localStorage on the client)
    // Match the token with the userid in the database, and return that user
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});