<?php

namespace App\Models;

use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;
    protected $table = 'supplies';
    protected $fillable = [
        'id',
        'invoice_number',
        'supply_date',
        'inventory_id',
        'supplier_name',
        'total_amount',
        'notes',
    ];
    public function SupplyDetails(){
        return $this->hasMany(SupplyDetails::class);
    }
    public function Inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
