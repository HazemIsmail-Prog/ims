<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $guarded = [];



    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function source_store()
    {
        return $this->belongsTo(Store::class,'source_store_id');
    }

    public function destination_store()
    {
        return $this->belongsTo(Store::class,'destination_store_id');
    }

}
