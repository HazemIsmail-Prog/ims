<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class UserForm extends Component
{

    protected $listeners = ['showingModal'];

    public bool $showModal = false;
    public string $modalTitle = '';
    public array $user = [];
    public $roles = [];
    public $selected_roles = [];

    public function rules()
    {
        if (isset($this->user['id'])) {
            return [
                'user.name' => ['required'],
                'user.username' => ['required', 'unique:users,username,' . $this->user['id'] . ''],
                'selected_roles' => ['required'],
            ];
        } else {
            return [
                'user.name' => ['required'],
                'user.username' => ['required', 'unique:users,username'],
                'user.password' => ['required'],
                'selected_roles' => ['required'],
            ];
        }
    }

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showingModal($user_id)
    {
        $this->reset();
        $this->resetValidation();
        if($user_id){
            $user =  User::find($user_id);
            $this->user = $user->toArray();
            $this->selected_roles = $user->roles->pluck('id');
            $this->modalTitle = __('messages.edit_user');
        }else{
            $this->user['active'] = 1;
            $this->modalTitle = __('messages.add_user');
        }
        $this->showModal = true;
    }



    public function save()
    {
        $this->validate();
        if (isset($this->user['id'])) {
            $user = User::find($this->user['id']);
            $user->update($this->user);
            if (isset($this->user['password'])) {
                $user->password = bcrypt($this->user['password']);
                $user->save();
            }
            $user->roles()->sync($this->selected_roles);
            $this->showModal = false;
            $this->emit('UsersDataChanged');
        } else {
            $user = User::create($this->user);
            $user->password = bcrypt($this->user['password']);
            $user->save();
            $user->roles()->sync($this->selected_roles);
            $this->showModal = false;
            $this->emit('UsersDataChanged');
        }
    }

}
