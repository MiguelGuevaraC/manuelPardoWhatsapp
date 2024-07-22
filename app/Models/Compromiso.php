<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compromiso extends Model
{
    protected $fillable = [
        'id',
        'cuotaNumber',
        'paymentAmount',
        'expirationDate',
        'conceptDebt',
        'telephoneStudent',
        'lastMessageDate',
        'status',
        'state',
        'stateSend',
        'student_id',
        'created_at',
    ];
    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];
    /**
     * Obtener el estudiante asociado con el compromiso.
     */
    public function student()
    {
        return $this->belongsTo(Person::class);
    }
}
