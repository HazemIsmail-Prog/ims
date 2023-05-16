<?php

namespace App\Http\Livewire;

use App\Models\Store;
use Livewire\Component;

class StoreForm extends Component
{

    protected $listeners = ['showingModal'];

    public bool $showModal = false;
    public string $modalTitle = '';
    public array $store = [];

    public function rules()
    {
        if (isset($this->store['id'])) {
            return [
                'store.name' => ['required', 'unique:stores,name,' . $this->store['id'] . ''],
                'store.type' => ['required'],
            ];
        } else {
            return [
                'store.name' => ['required', 'unique:stores,name'],
                'store.type' => ['required'],
            ];
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showingModal($store_id)
    {
        $this->reset();
        $this->resetValidation();
        if ($store_id) {
            $store =  Store::find($store_id);
            $this->store = $store->toArray();
            $this->modalTitle = __('messages.edit_store');
        } else {
            $this->store['active'] = 1;
            $this->store['type'] = '';
            $this->modalTitle = __('messages.add_store');
        }
        $this->showModal = true;
    }



    public function save()
    {
        $this->validate();
        if (isset($this->store['id'])) {
            $store = Store::find($this->store['id']);
            $store->update($this->store);
            $this->showModal = false;
            $this->emit('StoresDataChanged');
        } else {
            $store = Store::create($this->store);
            $this->showModal = false;
            $this->emit('StoresDataChanged');
        }
    }
}
