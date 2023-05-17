<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'available_quantity',
        'deprecated_quantity',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function getAvailableQuantityAttribute()
    {
        $inQuantity = $this->details()->whereHas('transaction',function($q){
            $q->where('type', 'in');
        })
        ->where('item_id', $this->id)->sum('quantity');
        $outQuantity = $this->details()->whereHas('transaction',function($q){
            $q->where('type', 'out');
        })
        ->where('item_id', $this->id)->sum('quantity');

        return $inQuantity - $outQuantity;
    }

    public function getDeprecatedQuantityAttribute()
    {
        return $this->details()->whereHas('transaction',function($q){
            $q->where('type', 'out');
            $q->where('destination_store_id', 1); // 1 for Depreciation Store ID
        })
        ->where('item_id', $this->id)->sum('quantity');
    }

}
