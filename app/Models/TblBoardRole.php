<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblBoardRole
 */
class TblBoardRole extends Model
{
    protected $table = 'tblBoardRoles';

    protected $primaryKey = 'RoleID';

	public $timestamps = false;

    protected $fillable = [
        'BoardRole'
    ];

    protected $guarded = [];

        
}