<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'units';
    protected $fillable = ['id', 'unit_name'];

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'product_units')
    //         ->withTimestamps();
    // }
    public function unit()
    {
        return $this->hasMany(Unit::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_units')
            ->withPivot(['product_price'])
            ->withTimestamps();
    }
}
