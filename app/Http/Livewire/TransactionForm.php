<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use App\Models\Transaction;
use Livewire\Component;

class TransactionForm extends Component
{

    public string $pageTitle;
    public $route;
    public $source_stores;
    public $destination_stores;
    public $transaction;
    public $items = [];
    public $selected_items = [];
    public $search = '';


    public function mount()
    {
        $this->route = explode('.',  request()->route()->getName())[0];
        $this->pageTitle = __('messages.add_' . $this->route);
        $this->transaction['date'] = date('Y-m-d');
        $this->transaction['source_store_id'] = '';
        $this->transaction['destination_store_id'] = '';
        switch ($this->route) {
            case 'stockin':
                $this->source_stores = Store::whereIn('type', ['supplier', 'adjustment'])->get();
                $this->destination_stores = Store::where('type', 'store')->get();
                break;
            case 'stockout':
                $this->source_stores = Store::where('type', 'store')->get();
                $this->destination_stores = Store::whereIn('type', ['depreciation', 'adjustment'])->get();
                break;
            case 'transfer':
                $this->source_stores = Store::where('type', 'store')->get();
                break;
        }
    }

    public function updatedTransaction($val, $key)
    {
        if ($key == 'source_store_id' && $this->route == 'transfer') {
            $this->transaction['destination_store_id'] = '';
            $this->destination_stores = Store::where('type', 'store')->where('id', '!=', $val)->get();
        }

        $this->selected_items = [];
        $this->getItems();
    }

    public function getItems()
    {
        switch ($this->route) {
            case 'stockin':

                $this->items = Item::query()
                    ->when($this->search, function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->whereNotIn('id', collect($this->selected_items)->pluck('item_id'))
                    ->get()->toArray();
                break;


            case 'stockout':
            case 'transfer':

                $items = Item::query()
                    ->withSum(['details as total_in' => function ($q) {
                        $q->whereHas('transaction', function ($q) {
                            $q->whereIn('type', ['in', 'transfer']);
                            $q->where('destination_store_id', $this->transaction['source_store_id']);
                        });
                    }], 'quantity')

                    ->withSum(['details as total_out' => function ($q) {
                        $q->whereHas('transaction', function ($q) {
                            $q->whereIn('type', ['out', 'transfer']);
                            $q->where('source_store_id', $this->transaction['source_store_id']);
                        });
                    }], 'quantity')
                    ->when($this->search, function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->whereNotIn('id', collect($this->selected_items)->pluck('item_id'))
                    ->get()->toArray();


                $this->items = array_filter($items , function($item){
                        return $item['total_in'] - $item['total_out'] > 0;
                    });
                break;
        }
    }

    public function updatedSearch()
    {
        $this->getItems();
    }

    public function unset_item($index)
    {
        unset($this->selected_items[$index]);
        $this->selected_items = array_values($this->selected_items);
        $this->getItems();
    }

    public function add_to_selected_items($index)
    {
        $this->selected_items[] = [
            'item_id' => $this->items[$index]['id'],
            'item_name' => $this->items[$index]['name'],
            'item_unit' => $this->items[$index]['unit'],
            'total_in' => $this->items[$index]['total_in'] ?? 0,
            'total_out' => $this->items[$index]['total_out'] ?? 0,
            'quantity' => 0,
        ];
        // dd($this->selected_items);
        $this->getItems();
    }

    public function save()
    {
        switch ($this->route) {
            case 'stockin':
                $type = 'in';
                break;
            case 'stockout':
                $type = 'out';
                break;
            case 'transfer':
                $type = 'transfer';
                break;
        }
        $data = [
            'date' => $this->transaction['date'],
            'source_store_id' => $this->transaction['source_store_id'],
            'destination_store_id' => $this->transaction['destination_store_id'],
            'type' => $type,
            'notes' => $this->transaction['notes'] ?? null,
        ];
        $transaction = Transaction::create($data);

        $details_data = [];
        foreach ($this->selected_items as $row) {
            $details_data[] = [
                'item_id' => $row['item_id'],
                'quantity' => $row['quantity'],
            ];
        }

        $transaction->details()->createMany($details_data);

        dd('Done');
    }

    public function render()
    {
        return view('livewire.transaction-form');
    }
}
