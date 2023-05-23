<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use App\Models\User;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'number',
        'address',
        'health_status',
        'visits_one',
        'visits_two',
        'visits_three',
        'visits_four',
        'price',
        'x_rays',
        'note',
        'doctor_name',
    ];


public function doctor()
{
    return $this->belongsTo(Doctor::class);
}
}
