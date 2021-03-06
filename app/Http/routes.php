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

// Password Reset Routes...
Route::post('password/email', [
    'as' => 'auth.password.email',
    'uses' => 'Auth\PasswordController@sendResetLinkEmail'
]);

// All Auth protected routes
Route::group(['middlewareGroups' => 'web'], function () {

    Route::get('profile/password', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@resetProfilePassword'
    ]);

    Route::post('profile/reset', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@setNewPassword'
    ]);

    Route::get('member', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@index'
    ]);
    Route::get('/member/covens', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@listCovens'
    ]);
    Route::get('/member/details', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@memberDetails'
    ]);
    Route::get('/member/details/{id}', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@memberDetails'
    ]);
    Route::get('/member/missing', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@missingDetails'
    ]);
    Route::get('/member/search', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@memberSearch'
    ]);
    Route::get('/member/migrate', 'MembersController@migrate');
    Route::post('member/{id}/update', [
        'middleware' => ['auth'],
        'as' => 'member.update',
        'uses' => 'MembersController@update'
    ]);

    Route::get('/guild/manage/{guild_id}', [
        'middleware' => ['auth'],
        'uses' => 'GuildsController@manage'
    ]);
    Route::get('/guild/add', [
        'middleware' => ['auth'],
        'uses' => 'GuildsController@add'
    ]);
    Route::get('/guild/remove', [
        'middleware' => ['auth'],
        'uses' => 'GuildsController@remove'
    ]);

    /* Roles, Permissions, and Users */
    Route::group(['prefix' => 'admin'], function () {
        Route::controller('roles', 'RolesController');
        Route::controller('permissions', 'PermissionsController');
        Route::controller('users', 'UsersController');
    });


    Route::get('/debug', function () {
      $mem = new \App\Services\MembershipService();

      $mem->setPrimaryPhone(6);
    });

    /* TODO: Create RBAC admin interface
    Route::get('acl', [
        'middleware' => ['auth'],
        'uses' => 'RbacController@index'
    ]);
    Route::get('rbac/set_leaders', [
        'middleware' => ['auth'],
        'uses' => 'RbacController@setLeadershipRoles'
    ]);
    Route::get('rbac/set_perms', [
        'middleware' => ['auth'],
        'uses' => 'RbacController@setRolePermissions'
    ]);
    */

});