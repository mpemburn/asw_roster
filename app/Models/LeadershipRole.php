<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblLeadershipRole
 */
class LeadershipRole extends Model
{
    protected $table = 'tblLeadershipRoles';

    protected $primaryKey = 'RoleID';

	public $timestamps = false;

    protected $fillable = [
        'Role',
        'Description',
        'GroupName'
    ];

    protected $guarded = [];

        
}