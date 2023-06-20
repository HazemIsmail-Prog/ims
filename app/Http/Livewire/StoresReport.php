<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use Livewire\Component;

class StoresReport extends Component
{
    public $items = [];
    public $stores = [];
    public $data = [];
    public $selected_store_id = '';
    public $selected_store;
    public $selected_items_ids = [];

    public function mount()
    {
        $this->stores = Store::where('type', 'store')->get();
        $this->items = Item::all();
    }
    public function updated($key, $val)
    {

        // dd($key, $val);
        switch ($key) {
            case 'selected_store_id':
            case 'selected_items_ids':
                $this->selected_store = Store::find($this->selected_store_id);
                if ($this->selected_store_id) {
                    $this->data = Item::whereIn('id', $this->selected_items_ids)->get();
                }
                break;
        }
    }


    public function render()
    {
        return view('livewire.stores-report');
    }
}
