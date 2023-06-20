<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use Livewire\Component;

class ItemsReport extends Component
{
    public $items = [];
    public $stores = [];
    public $data = [];
    public $selected_item_id = '';
    public $selected_item;
    public $selected_stores_ids = [];

    public function mount()
    {
        $this->items = Item::all();
        $this->stores = Store::where('type', 'store')->get();
    }
    public function updated($key, $val)
    {

        // dd($key, $val);
        switch ($key) {
            case 'selected_item_id':
                case 'selected_stores_ids':
                $this->selected_item = Item::find($this->selected_item_id);
                if ($this->selected_item_id) {
                    $this->data = Store::whereIn('id', $this->selected_stores_ids)->get();
                }
                break;
            }
    }


    public function render()
    {
        return view('livewire.items-report');
    }
}
