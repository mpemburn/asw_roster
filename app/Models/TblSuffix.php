<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblSuffix
 */
class TblSuffix extends Model
{
    protected $table = 'tblSuffixes';

    public $timestamps = false;

    protected $fillable = [
        'SuffixID',
        'Suffix'
    ];

    protected $guarded = [];

        
}