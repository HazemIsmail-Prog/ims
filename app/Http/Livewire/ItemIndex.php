<?php

namespace App\Http\Livewire;

use App\Models\Item;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;


class ItemIndex extends Component
{
    use WithPagination;

    protected $listeners = ['ItemsDataChanged' => '$refresh'];

    public $search;
    public $pagination = 9;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function pdf($item_id)
    {
        $item = Item::where('id', $item_id)->with('stores')->first();
        $view = view('pdf.item', compact('item'));
        $html = $view->render();
        Pdf::loadHTML($html)->save(public_path() . '/item.pdf');
        $this->redirect(asset('') . 'item.pdf');
    }

    public function delete($item)
    {
        Item::find($item['id'])->delete();
        $this->emit('ItemsDataChanged');
    }
    public function render()
    {
        return view('livewire.item-index', [
            'items' => Item::when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->paginate($this->pagination),
        ]);
    }
}
