<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use App\Models\Transaction;
use App\Services\TransactionsServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use Livewire\Component;

class TransactionForm extends Component
{
    protected $listeners = [
        'showingModal' => 'showingModal',
        'item_selected' => 'item_selected',
    ];

    public $modalTitle;
    public $showModal = false;
    public $date;
    public $notes;
    public $stores;
    public $source_stores;
    public $items = [];
    public $rows = [];
    public $transaction;

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
            $this->transaction = Transaction::with('details')->find($transaction_id);
            $this->date = $this->transaction->date->format('Y-m-d');
            $this->notes = $this->transaction->notes;

            foreach ($this->transaction->details as $index => $row) {
                $this->rows[$index]['source_stores'] = $this->source_stores;
                $this->rows[$index]['source_store_id'] = $row->source_store_id;
                $this->updatedRows($row->source_store_id, $index . '.source_store_id');
                $this->rows[$index]['destination_store_id'] = $row->destination_store_id;
                $this->rows[$index]['item_id'] = $row->item_id;
                $this->updatedRows($row->item_id, $index . '.item_id');
                $this->rows[$index]['available'] = $this->rows[$index]['available'] + $row->quantity;
                $this->rows[$index]['quantity'] = $row->quantity;
            }
        } else {
            //Create
            $this->modalTitle = __('messages.add_transaction');
            $this->date = date('Y-m-d');
            $this->add_row();
        }
        $this->showModal = true;
    }

    public function item_selected($id,$index){
        $this->rows[$index]['item_id'] = $id;
        $this->updatedRows($id,$index.'.item_id');
    }

    public function add_row()
    {
        $this->rows[] = [
            'source_stores' => $this->source_stores,
            'source_store_type' => '',
            'destination_stores' => [],
            'source_store_id' => '',
            'destination_store_id' => '',
            'items' => [],
            'item_id' => '',
            'available' => 0,
            'quantity' => 0,
        ];
    }

    public function updatedRows($val, $key)
    {
        $index = explode('.', $key)[0];
        $model = explode('.', $key)[1];
        if ($model == 'source_store_id') {
            $selected_source_store = $this->stores->where('id', $val)->first();
            $this->rows[$index]['source_store_type'] = $selected_source_store->type;
            switch ($selected_source_store->type) {
                case 'supplier':
                case 'adjustment':
                    $this->rows[$index]['destination_stores'] = $this->stores->where('id', '!=', $selected_source_store->id)->whereIn('type', ['store']);
                    break;
                case 'store':
                    $this->rows[$index]['destination_stores'] = $this->stores->where('id', '!=', $selected_source_store->id)->whereIn('type', ['store', 'adjustment', 'depreciation']);
                    break;
            }
            $this->rows[$index]['destination_store_id'] = '';
            $this->rows[$index]['items'] =
                Item::query()
                ->when($selected_source_store->type == 'store', function ($q) use ($selected_source_store) {
                    $q->whereHas('stores', function ($q) use ($selected_source_store) {
                        $q->where('store_id', $selected_source_store->id);
                    });
                })
                ->get()->toArray();
            $this->rows[$index]['item_id'] = '';
            $this->rows[$index]['available'] = 0;
            $this->rows[$index]['quantity'] = 0;
        }

        if ($model == 'item_id' && $this->rows[$index]['source_store_type'] == 'store') {
            $this->rows[$index]['available'] = Item::find($val)->availablePerStore($this->rows[$index]['source_store_id']);
        }
    }
    protected $rules = [
        'rows' => 'required',
        'rows.*.source_store_id' => ['required'],
        'rows.*.destination_store_id' => 'required',
    ];


    public function unset_row($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function validateDuplicatedRows()
    {
        $this->resetErrorBag();
        $new_list = $this->rows;
        foreach ($new_list as $index => $row) {
            $new_list[$index]['index'] = $index;
        }

        $duplicates = collect($new_list)->groupBy(function ($item) {
            return $item['source_store_id'] . '-' . $item['destination_store_id'] . '-' . $item['item_id'];
        })->filter(function ($group) {
            return $group->count() > 1;
        })->flatMap(function ($group) {
            return $group->pluck('index');
        });

        if ($duplicates->count() > 0) {
            foreach ($duplicates as $index) {
                // $this->addError('rows.' . $index . '.duplicated', 'rows.' . $index . '.duplicated');
                throw ValidationException::withMessages(['rows.' . $index . '.duplicated' => 'Merge duplicate entry in one line']);
            }
        }
    }

    public function duplicate_row($index)
    {
        $this->rows[] = $this->rows[$index];
    }

    public function save()
    {
        $this->validateDuplicatedRows();
        // if ($this->getErrorBag()->count() > 0) {
        //     return false;
        // }
        $this->validate();
        $data = [
            'date' => $this->date,
            'notes' => $this->notes ?? null,
            'user_id' => auth()->id(),
        ];

        $details_data = [];
        foreach ($this->rows as $row) {
            $details_data[] = [
                'source_store_id' => $row['source_store_id'],
                'destination_store_id' => $row['destination_store_id'],
                'item_id' => $row['item_id'],
                'quantity' => $row['quantity'],
            ];
        }

        if (!$this->transaction) {
            //create
            DB::beginTransaction();
            try {
                $transaction = Transaction::create($data);
                $transaction->details()->createMany($details_data);
                (new TransactionsServices())->syncItemStoreTable();
                $this->showModal = false;
                $this->emit('TransactionsDataChanged');
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $this->addError('quantity_error', $e->getMessage());
            }
        } else {
            //edit
            DB::beginTransaction();
            try {
                $this->transaction->update($data);
                $this->transaction->details()->delete();
                $this->transaction->details()->createMany($details_data);
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
}
