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

Route::get('/user/facebook', 'FacebookController@index');

Route::get('/user/instagram', 'InstagramController@index');

Route::get('/user/twitter', 'TwitterController@index');

Route::post('/user/facebook/updateuserinfo', 'FacebookController@updateUserInfo');

Route::post('/user/facebook/friendRequest', 'FacebookController@friendRequest');

Route::post('/user/twitter/updatestatus', 'TwitterController@updateStatus');

Route::post('/user/twitter/updateaccountstatus', 'TwitterController@updateAccountStatus');

Route::post('/user/instagram/modifyrelationship', 'InstagramController@modifyRelationship');

Route::post('/user/instagram/like', 'InstagramController@like');