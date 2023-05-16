<?php

namespace App\Http\Livewire;

use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class StoreIndex extends Component
{
    use WithPagination;
    protected $listeners = ['StoresDataChanged' => '$refresh'];

    public $search;
    public $pagination = 9;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($store)
    {
        Store::find($store['id'])->delete();
        $this->emit('StoresDataChanged');
    }

    public function render()
    {
        return view('livewire.store-index', [
            'stores' => Store::when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
                ->paginate($this->pagination),
        ]);
    }
}
