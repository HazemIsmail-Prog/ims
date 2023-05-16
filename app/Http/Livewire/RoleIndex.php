<?php

namespace App\Http\Livewire;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class RoleIndex extends Component
{
    use WithPagination;

    protected $listeners = ['RolesDataChanged' => '$refresh'];

    public $search;
    public $pagination = 9;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($role)
    {
        Role::find($role['id'])->delete();
        $max = DB::table('Roles')->max('id') + 1;
        DB::statement("ALTER TABLE Roles AUTO_INCREMENT =  $max");
        $this->emit('RolesDataChanged');
    }
    public function render()
    {
        return view('livewire.role-index', [
            'roles' => Role::when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
                // ->where('id', '!=', 1)
                ->paginate($this->pagination),
        ]);
    }
}
