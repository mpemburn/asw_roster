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

Route::get('/', function () {
    return view('home');
});

Route::get('/home', 'HomeController@index');
Route::auth();

Route::get('acl', [
    'middleware' => ['auth'],
    'uses' => 'AclController@index'
]);
Route::get('member', [
    'middleware' => ['auth'],
    'uses' => 'MembersController@index'
]);
Route::get('/member/missing', [
    'middleware' => ['auth'],
    'uses' => 'MembersController@missing_details'
]);
Route::get('/member/details', [
    'middleware' => ['auth'],
    'uses' => 'MembersController@member_details'
]);
Route::get('/member/details/{id}', [
    'middleware' => ['auth'],
    'uses' => 'MembersController@member_details'
]);
Route::get('/member/migrate', 'MembersController@migrate');
Route::post('member/{id}/update', [
    'middleware' => ['auth'],
    'as' => 'member.update',
    'uses' => 'MembersController@update'
]);

/* Roles, Permissions, and Users */
Route::group(['middleware' => 'web'], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::controller('roles', 'RolesController');
        Route::controller('permissions', 'PermissionsController');
        Route::controller('users', 'UsersController');
    });
});