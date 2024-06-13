<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeDetails extends Model
{
    use HasFactory;
    protected $table = 'exchange_details';
    protected $fillable = [
        'id',
        'exchange_id',
        'product_id',
        'unit_id',
        'quantity',
        'unit_price',
        'total_price',
    ];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
