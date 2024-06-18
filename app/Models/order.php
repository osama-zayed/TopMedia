<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'slug',
        'user_id',
        'address_id',
        'total_amount',
        'payment_method',
        'status',
        'notes',
    ];
}
