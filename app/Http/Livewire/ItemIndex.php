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
        $item = Item::where('id', $item_id)->first();
        $all_stores = Store::all();
        $stores = [];
        foreach($all_stores as $store){
            $items = $store->store_items()->where('id',$item->id)->toArray();
            $items = array_filter($items, function ($item) {
                return $item['total_in'] - $item['total_out'] > 0;
            });
            if($items){
                $stores[] = [
                    'store_name' => $store->name,
                    'available' => array_values($items)[0]['total_in'] - array_values($items)[0]['total_out'],
                ];
            }
        }
        $view = view('pdf.item', compact('item','stores'));
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
