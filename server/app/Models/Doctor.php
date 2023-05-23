<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\User;


class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'age',
        'phone_number',
        'online_days',
        'online_hours',
        'balance',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

//     public function patients()
// {
//     return $this->hasMany(Patient::class);
// }

public function patients()
{
    return $this->hasMany(Patient::class);
}
public function user()
{
    return $this->belongsTo(User::class, 'uuid', 'uuid');
}

//     public function patients()
// {
//     return $this->hasMany(Patient::class, 'doctor_name', 'unique_code');
// }
}
