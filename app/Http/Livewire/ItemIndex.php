<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function delete($item)
    {
        Item::find($item['id'])->delete();
        $max = DB::table('Items')->max('id') + 1;
        DB::statement("ALTER TABLE Items AUTO_INCREMENT =  $max");
        $this->emit('ItemsDataChanged');
    }
    public function render()
    {
        return view('livewire.item-index', [
            'items' => Item::when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
                $q->orWhere('section_name', 'like', '%' . $this->search . '%');
                $q->orWhere('description', 'like', '%' . $this->search . '%');
            })->paginate($this->pagination),
        ]);
    }
}
