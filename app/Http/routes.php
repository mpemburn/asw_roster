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
    return view('welcome');
});

Route::get('member', 'Member@index');
Route::post('member/{id}/update',['as' => 'member.update', 'uses' => 'Member@update']);

//Route::get('/','Front@index');
//Route::get('/member','Member@member');
Route::get('/member/details/{id}','Member@member_details');
//Route::get('/member/categories','Member@product_categories');
//Route::get('/member/brands','Member@product_brands');
//Route::get('/blog','Front@blog');
//Route::get('/blog/post/{id}','Front@blog_post');
//Route::get('/contact-us','Front@contact_us');
//Route::get('/login','Front@login');
//Route::get('/logout','Front@logout');
//Route::get('/cart','Front@cart');
//Route::get('/checkout','Front@checkout');
//Route::get('/search/{query}','Front@search');