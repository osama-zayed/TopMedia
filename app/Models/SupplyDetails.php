<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyDetails extends Model
{
    use HasFactory;
    protected $table = 'supply_details';
    protected $fillable = [
        'id',
        'supply_id',
        'product_id',
        'unit_id',
        'quantity',
        'unit_price',
        'total_price',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function Supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
