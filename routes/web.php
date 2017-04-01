<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/auth/{oauthProvider}', 'Auth\AuthController@redirectToProvider');

Route::get('/auth/{oauthProvider}/oauthcallback', 'Auth\AuthController@handleProviderCallback');

Route::get('/user/profile', 'ProfileController@profile');

Route::get('/user/disassociate/{oauthProvider}', 'ProfileController@disassociateProvider');