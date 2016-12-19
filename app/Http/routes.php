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

Route::get('member', [
    'middleware' => ['auth'],
    'uses' => 'Member@index'
]);
Route::get('/member/details',[
    'middleware' => ['auth'],
    'uses' => 'Member@member_details'
]);
Route::get('/member/details/{id}',[
    'middleware' => ['auth'],
    'uses' => 'Member@member_details'
]);
Route::get('/member/missing',[
    'middleware' => ['auth'],
    'uses' => 'Member@missing_details'
]);
Route::get('/member/migrate','Member@migrate');
Route::post('member/{id}/update',[
    'middleware' => ['auth'],
    'as' => 'member.update',
    'uses' => 'Member@update'
]);

