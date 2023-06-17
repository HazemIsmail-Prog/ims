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

    public function stores()
    {
        return $this->belongsToMany(Store::class)->withPivot('quantity');;
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->stores()->whereNotIn('store_id',[1,2])->sum('quantity'); // 1 for Depreciation Store ID and 2 for Adjustment Store ID
    }

    public function availablePerStore($store_id)
    {
        return $this->stores()->where('store_id',$store_id)->sum('quantity');
    }
    
    public function getDeprecatedQuantityAttribute()
    {
        return $this->stores()->where('store_id',1)->sum('quantity'); // 1 for Depreciation Store ID
    }

}
