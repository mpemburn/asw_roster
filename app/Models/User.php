<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User -- for Laravel Auth.
 */
class User extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'id';

	public $timestamps = false;

    protected $fillable = [
        'id',
        'member_id',
        'name',
        'email',
        'password',
        'remember_token'
    ];

    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

}