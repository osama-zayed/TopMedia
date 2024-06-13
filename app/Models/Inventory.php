<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'location',
        'description',
    ];
    public function Supply()
    {
        return $this->hasMany(Supply::class);
    }
    public function Exchange()
    {
        return $this->hasMany(Exchange::class);
    }
}