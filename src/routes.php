<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::controllers([
    'auth' => 'Yuyinitos\SocialAuthenticator\AuthController',
    'password' => 'Yuyinitos\SocialAuthenticator\PasswordController',
]);

Route::get(config('socialAuthenticator.login_page'), function() {
    return view('socialAuthenticator::login');
});

Route::get(config('socialAuthenticator.logout'), function() {
    return $this->app['authenticator']->logout();
});

Route::get(config('socialAuthenticator.login_redirect'), function() {
    $user = Yuyinitos\SocialAuthenticator\Models\User::find(\Auth::id());
    return view('socialAuthenticator::dashboard')->with('user', $user);
});

Route::get('socialAuth/{provider?}', function($provider = null) {
    return $this->app['authenticator']->login($provider);
});

Route::get('activate/{code}', 'Yuyinitos\SocialAuthenticator\AuthController@accountIsActive');
