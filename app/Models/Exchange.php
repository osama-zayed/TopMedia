<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;
    protected $table = 'exchanges';
    protected $fillable = [
        'id',
        'invoice_number',
        'total_amount',
        'exchange_name',
        'inventory_id',
        'exchange_date',
        'notes',
    ];

    public function exchangeDetails()
    {
        return $this->hasMany(ExchangeDetails::class);
    }
    public function Inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}