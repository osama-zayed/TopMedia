<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;
    protected $table = 'product_units';
    protected $fillable = [
        'id',
        'product_id',
        'unit_id',
        'product_price',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
