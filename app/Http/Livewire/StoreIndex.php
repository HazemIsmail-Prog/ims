<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;


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

    public function pdf($store_id)
    {
        $store = Store::where('id', $store_id)->with('items')->first();
        $view = view('pdf.store', compact('store'));
        $html = $view->render();
        Pdf::loadHTML($html)->save(public_path() . '/store.pdf');
        $this->redirect(asset('') . 'store.pdf');
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
