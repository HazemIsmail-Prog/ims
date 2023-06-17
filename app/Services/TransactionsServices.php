<?php

namespace App\Services;

use App\Models\ItemStore;
use App\Models\Store;
use App\Models\TransactionDetail;
use Illuminate\Validation\ValidationException;


class TransactionsServices
{
    public function syncItemStoreTable()
    {
        $transactions = TransactionDetail::all();
        $destination_stores = $transactions->pluck('destination_store_id')->toArray();
        $source_stores = $transactions->pluck('source_store_id')->toArray();
        $all_stores = array_merge($destination_stores, $source_stores);
        $stores = array_unique($all_stores);
        $items = $transactions->pluck('item_id');
        $items = $items->unique();
        ItemStore::query()->delete();
        foreach ($stores as $store) {
            $current_store = Store::find($store);
            foreach ($items as $item) {
                $all_in = $transactions->where('destination_store_id', $store)->where('item_id', $item)->sum('quantity');
                $all_out = $transactions->where('source_store_id', $store)->where('item_id', $item)->sum('quantity');
                $available = $all_in - $all_out;
                if ($available < 0 && $current_store->type == 'store') {
                    throw ValidationException::withMessages(['Quantity' => 'Quantity of ' . $current_store->name . ' Cannot be minus']);
                }
                if ($available > 0) {
                    $current_store->items()->attach($item, ['quantity' => $available]);
                }
            }
        }
    }
}
