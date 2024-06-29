<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema (
 *     schema="OptionMenu",
 *     title="OptionMenu",
 *     type="object",
 *     required={"id","name", "icon","groupmenu_id"},
 *     @OA\Property(property="id", type="number", example="1"),
 *     @OA\Property(property="name", type="string", example="Principal"),
 *     @OA\Property(property="route", type="string", example="principal"),
 *     @OA\Property(property="icon", type="string", example="fas fa-user"),
 * @OA\Property(property="groupmenu_id", type="string", example="1"),
 *     @OA\Property(property="created_at", type="string", example="2024-03-27 01:42:21"),
 * )
 */
class Optionmenu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'route',
//        'icon',
        'groupmenu_id',
        'created_at',
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function groupmenu()
    {
        return $this->belongsTo(Person::class, 'groupmenu_id');
    }

    public function accesses()
    {
        return $this->hasMany(Access::class, 'optionmenu_id');
    }
}
