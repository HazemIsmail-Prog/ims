<?php

namespace App\Http\Livewire;

use App\Models\Permission;
use Livewire\Component;

class PermissionForm extends Component
{
    public $permission;
    public $modalTitle;
    public $showModal = false;

    protected $listeners = [
        'showingModal' => 'showingModal',
    ];

    public function rules()
    {
        if (isset($this->permission['id'])) {
            return [
                'permission.name' => ['required', 'unique:permissions,name,' . $this->permission['id'] . ''],
                'permission.description' => ['required'],
                'permission.section_name' => ['required'],
            ];
        } else {
            return [
                'permission.name' => ['required', 'unique:permissions,name'],
                'permission.description' => ['required'],
                'permission.section_name' => ['required'],
            ];
        }
    }

    public function save()
    {
        $this->validate();
        if (isset($this->permission['id'])) {
            $permission = Permission::find($this->permission['id']);
            $permission->update($this->permission);
            $this->showModal = false;
            $this->emit('PermissionsDataChanged');
        } else {
            Permission::create($this->permission);
            $this->showModal = false;
            $this->emit('PermissionsDataChanged');
        }
    }

    public function showingModal($permission_id)
    {
        $this->reset();
        $this->resetValidation();
        if ($permission_id) {
            $this->modalTitle =  __('messages.edit_permission');
            $permission = Permission::find($permission_id);
            $this->permission = $permission->toArray();
        } else {
            $this->modalTitle = __('messages.add_permission');
        }
        $this->showModal = true;
    }
}
