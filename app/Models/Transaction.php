<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
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
