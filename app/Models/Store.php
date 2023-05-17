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

    public function store_items()
    {
        return Item::query()
            ->withSum(['details as total_in' => function ($q) {
                $q->whereHas('transaction', function ($q) {
                    $q->whereIn('type', ['in', 'transfer']);
                    $q->where('destination_store_id', $this->id);
                });
            }], 'quantity')

            ->withSum(['details as total_out' => function ($q) {
                $q->whereHas('transaction', function ($q) {
                    $q->whereIn('type', ['out', 'transfer']);
                    $q->where('source_store_id', $this->id);
                });
            }], 'quantity')
            ->get();
    }
}
