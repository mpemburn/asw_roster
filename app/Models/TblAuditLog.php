<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TblAuditLog
 */
class TblAuditLog extends Model
{
    protected $table = 'tblAuditLog';

    public $timestamps = false;

    protected $fillable = [
        'AuditID',
        'ChangeDate',
        'TableName',
        'OldTableName',
        'KeyField',
        'KeyValue',
        'OldKeyField',
        'OldKeyValue',
        'SubKeyField',
        'SubKeyValue',
        'FieldName',
        'FieldLabel',
        'ChangeFrom',
        'ChangeTo',
        'UserID',
        'Application'
    ];

    protected $guarded = [];

        
}