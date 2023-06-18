<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use App\Models\TransactionDetail;
use App\Services\TransactionsServices;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransactionDetailForm extends Component
{
    protected $listeners = [
        'showingModal' => 'showingModal',
    ];

    public $modalTitle;
    public $showModal = false;
    public $stores;
    public $source_stores = [];
    public $destination_stores = [];
    public $items = [];
    public $row = [];
    public $transaction;

    public function mount(){
        $this->row = [
            'item_id' => '',
            'source_store_type' => '',
            'available' => 0,
        ];
    }

    public function showingModal($transaction_id)
    {
        $this->reset();
        $this->resetValidation();
        //get all Stores in one query
        $this->stores = Store::with('items')->get();
        $this->source_stores = $this->stores
            ->where('id', '!=', 1) // id 1 for depretiation
            ->filter(fn ($store) => $store->items->count() > 0 || $store->type == 'supplier' || $store->type == 'adjustment');
        if ($transaction_id) {
            //Edit
            $this->modalTitle =  __('messages.edit_transaction');
            $this->transaction = TransactionDetail::find($transaction_id);

            $this->row['source_stores'] = $this->source_stores;
            $this->row['source_store_id'] = $this->transaction->source_store_id;
            $this->updatedRow($this->transaction->source_store_id, 'source_store_id');
            $this->row['destination_store_id'] = $this->transaction->destination_store_id;
            $this->row['item_id'] = $this->transaction->item_id;
            $this->updatedRow($this->transaction->item_id, 'item_id');
            $this->row['available'] = $this->row['available'] + $this->transaction->quantity;
            $this->row['quantity'] = $this->transaction->quantity;
        }
        $this->showModal = true;
    }

    public function updatedRow($val, $key)
    {
        if ($key == 'source_store_id') {
            $selected_source_store = $this->stores->where('id', $val)->first();
            $this->row['source_store_type'] = $selected_source_store->type;
            switch ($selected_source_store->type) {
                case 'supplier':
                case 'adjustment':
                    $this->destination_stores = $this->stores->where('id', '!=', $selected_source_store->id)->whereIn('type', ['store']);
                    break;
                case 'store':
                    $this->destination_stores = $this->stores->where('id', '!=', $selected_source_store->id)->whereIn('type', ['store', 'adjustment', 'depreciation']);
                    break;
            }
            $this->row['destination_store_id'] = '';
            $this->items =
                Item::query()
                ->when($selected_source_store->type == 'store', function ($q) use ($selected_source_store) {
                    $q->whereHas('stores', function ($q) use ($selected_source_store) {
                        $q->where('store_id', $selected_source_store->id);
                    });
                })
                ->get();
            $this->row['item_id'] = '';
            $this->row['available'] = 0;
            $this->row['quantity'] = 0;
        }

        if ($key == 'item_id' && $this->row['source_store_type'] == 'store') {
            $this->row['available'] = Item::find($val)->availablePerStore($this->row['source_store_id']);
        }
    }
    protected $rules = [
        'row.source_store_id' => 'required',
        'row.destination_store_id' => 'required',
    ];

    public function save()
    {
        $this->validate();

        $details_data = [
            'source_store_id' => $this->row['source_store_id'],
            'destination_store_id' => $this->row['destination_store_id'],
            'item_id' => $this->row['item_id'],
            'quantity' => $this->row['quantity'],
        ];

        //edit
        DB::beginTransaction();
        try {
            $this->transaction->update($details_data);
            (new TransactionsServices())->syncItemStoreTable();
            $this->showModal = false;
            $this->emit('TransactionsDataChanged');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->addError('quantity_error', $e->getMessage());
        }
    }
}
