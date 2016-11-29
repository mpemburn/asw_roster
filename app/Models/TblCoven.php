<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblCoven
 */
class TblCoven extends Model
{
    protected $table = 'tblCovens';

    protected $primaryKey = 'Coven';

	public $timestamps = false;

    protected $fillable = [
        'CovenFullName',
        'Wheel',
        'Element',
        'Tool'
    ];

    protected $guarded = [];

        
}