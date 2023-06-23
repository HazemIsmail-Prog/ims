<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;

class SingleSearchableSelect extends Component
{
    public $list;
    public $filtered_list;
    public $selected_id;
    public $selected_name;
    public $index;
    public $search;

    public function mount($selected_id)
    {
        if($selected_id){
            $this->selected_name = Item::find($selected_id)->name;
        }
        $this->filtered_list = $this->list;
    }
    
    public function updatedSearch()
    {
        if ($this->search) {
            $this->filtered_list = array_filter(collect($this->list)->toArray(), function ($item) {
                $term = strtolower($this->search);
                return preg_match("/$term/", strtolower($item['name']));
            });
        } else {
            $this->filtered_list = $this->list;
        }
    }
    
    public function select($id) {
        $this->emit('item_selected', $id , $this->index);
    }
    public function render()
    {
        return view('livewire.single-searchable-select');
    }
}
