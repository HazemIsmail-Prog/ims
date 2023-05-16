<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Illuminate\Support\Arr;
use Livewire\Component;

class ItemForm extends Component
{
    public $item;
    public $modalTitle;
    public $showModal = false;

    protected $listeners = [
        'showingModal' => 'showingModal',
    ];

    public function rules()
    {
        if (isset($this->item['id'])) {
            return [
                'item.name' => ['required', 'unique:items,name,' . $this->item['id'] . ''],
                'item.unit' => ['required'],
            ];
        } else {
            return [
                'item.name' => ['required', 'unique:items,name'],
                'item.unit' => ['required'],
            ];
        }
    }

    public function save()
    {
        $this->validate();
        if (isset($this->item['id'])) {
            $item = Item::find($this->item['id']);
            // array_except($this->item, ['available_quantity','deprecated_quantity']);
            // $userArray = Arr::except($this->item, ['available_quantity', 'deprecated_quantity']);

            $item->update(Arr::except($this->item, ['available_quantity', 'deprecated_quantity']));
            $this->showModal = false;
            $this->emit('ItemsDataChanged');
        } else {
            Item::create($this->item);
            $this->showModal = false;
            $this->emit('ItemsDataChanged');
        }
    }

    public function showingModal($item_id)
    {
        $this->reset();
        $this->resetValidation();
        if ($item_id) {
            $this->modalTitle =  __('messages.edit_item');
            $item = Item::find($item_id);
            $this->item = $item->toArray();
        } else {
            $this->modalTitle = __('messages.add_item');
        }
        $this->showModal = true;
    }
}
