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
        'product_name',
        'product_description',
        'category_id',
        'product_status',
        'product_price',
        'image',
        'discount_percentage',
    ];
    protected $casts = [
        'image' =>"array"
    ];
    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function Favorit()
    {
        return $this->hasMany(Favorit::class);
    }
}
