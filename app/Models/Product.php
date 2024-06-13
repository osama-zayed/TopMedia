<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'id',
        'product_code',
        'product_name',
        'product_description',
        'categorie_id',
        'product_status',
        'total_price',
    ];
    public function Category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'product_units')
        ->withPivot(['product_price'])
            ->withTimestamps();
    }
}
