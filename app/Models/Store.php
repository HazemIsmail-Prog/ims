<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function details()
    {
        return $this->hasManyThrough(TransactionDetail::class,Transaction::class,'destination_store_id');
    }
}
