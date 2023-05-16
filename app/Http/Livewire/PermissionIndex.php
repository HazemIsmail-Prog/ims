<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionIndex extends Component
{
    use WithPagination;

    protected $listeners = ['PermissionsDataChanged' => '$refresh'];

    public $search;
    public $pagination = 9;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($permission)
    {
        Permission::find($permission['id'])->delete();
        $max = DB::table('Permissions')->max('id') + 1;
        DB::statement("ALTER TABLE Permissions AUTO_INCREMENT =  $max");
        $this->emit('PermissionsDataChanged');
    }
    public function render()
    {
        return view('livewire.permission-index', [
            'permissions' => Permission::when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
                $q->orWhere('section_name', 'like', '%' . $this->search . '%');
                $q->orWhere('description', 'like', '%' . $this->search . '%');
            })->paginate($this->pagination),
        ]);
    }
}
