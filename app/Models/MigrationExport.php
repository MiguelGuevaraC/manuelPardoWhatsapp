<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigrationExport extends Model
{
    protected $fillable = [
        'id',
        'number',
        'type',
        'comment',
        'routeExcel',
        'state',

        'created_at',

    ];
    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
