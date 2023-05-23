<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'medicinal-materials',
        'count',
        'buy_price',
        'total_price',
        'note',
    ];
}
