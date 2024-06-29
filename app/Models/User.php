<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema (
 * schema="User",
 *     title="User",
 *     type="object",
 *     required={"id", "username", "password", "worker_id", "typeofUser_id"},
 *     @OA\Property(property="id", type="number", example="1"),
 *     @OA\Property(property="username", type="string", example="johndoe"),
 *     @OA\Property(property="password", type="string", example="password123"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
 *     @OA\Property(
 *         property="worker_id",
 *         type="number",
 *         example="1",
 *         description="Foreign key referencing Worker"
 *     ),
 *     @OA\Property(
 *         property="typeofUser_id",
 *         type="number",
 *         example="1",
 *         description="Foreign key referencing TypeOfUser"
 *     ),
 *     @OA\Property(
 *         property="worker",
 *         ref="#/components/schemas/Worker"
 *     ),
 *     @OA\Property(
 *         property="TypeUser",
 *         ref="#/components/schemas/TypeUser"
 *     ),
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use SoftDeletes;

    protected $fillable = [
        'id',
        'username',

        'created_at',
        'worker_id',
        'typeofUser_id',
        'person_id',
    ];

    protected $hidden = [
        'password',
        'updated_at',
        'deleted_at',
    ];

    public function typeUser()
    {
        return $this->belongsTo(TypeUser::class, 'typeofUser_id');
    }
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

}
