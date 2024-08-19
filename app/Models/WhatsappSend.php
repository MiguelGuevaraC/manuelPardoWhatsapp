<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsappSend extends Model
{
    use  HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'number',
        'userResponsability',
        'namesStudent',
        'dniStudent',
        'namesParent',
        'infoStudent',
        'telephone',
        'description',
        'conceptSend',
        'paymentAmount',
        'expirationDate',
        'costSend',

        'cuota',
        'status',
        'created_at',
        'student_id',
        'user_id',
        'comminment_id',
    ];
    

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function student()
    {
        return $this->belongsTo(Person::class, 'student_id');
    }
    public function conminmnet()
    {
        return $this->belongsTo(Compromiso::class, 'comminment_id');
    }

}
