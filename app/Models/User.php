<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User -- for Laravel Auth.
 */
class User extends Model
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

        
}