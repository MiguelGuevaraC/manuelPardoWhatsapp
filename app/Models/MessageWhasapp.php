<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageWhasapp extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'title',
        'block1',
        'block2',
        'block3',
        'responsable_id',

        'state',
        'created_at',

    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];
}
