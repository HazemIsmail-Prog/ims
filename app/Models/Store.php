<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot('quantity');
    }

    public function availablePerItem($item_id)
    {
        return $this->items()->where('item_id', $item_id)->sum('quantity');
    }
}
