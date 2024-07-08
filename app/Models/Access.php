<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{

/**
 * @OA\Schema(
 *     schema="Access",
 *     title="Access",
 *     type="object",
 *     @OA\Property(property="id", type="number", example="1"),
 *     @OA\Property(property="typeuser_id", type="number", example="1"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *       @OA\Property(
 *         property="optionmenu",
 *         ref="#/components/schemas/OptionMenu"
 *     ),
 *     @OA\Property(
 *         property="typeuser",
 *         ref="#/components/schemas/TypeUser"
 *     ),
 * )
 */

    protected $fillable = [
        'optionmenu_id',
        'typeuser_id',
        'created_at',
    ];

    protected $hidden = [
        'updated_at',

    ];

    public function optionMenu()
    {
        return $this->belongsTo(Optionmenu::class, 'optionmenu_id');
    }

    public function typeUser()
    {
        return $this->belongsTo(TypeUser::class, 'typeuser_id');
    }
}
