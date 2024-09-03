<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class EditUserModal extends Component
{
    public $id;
    public $firstname;
    public $lastname;
    public $username;

    public function render()
    {
        return view('livewire.edit-user-modal');
    }
    
    #[On('show-edit-modal')]
    public function displayModal($id)
    {
        // dd($id);
        $this->id = $id;
        $user = User::find($id);
        $this->firstname = $user->Firstname;
        $this->lastname = $user->Lastname;
        $this->username = $user->Username;
        
        $this->dispatch('display-edit-modal');

    }

    public function editUser(){

        // dd($this->employeename);
        
        User::where('UserID', $this->id)->update([
            'Firstname' => $this->firstname,
            'Lastname' => $this->lastname,
            'Username' => $this->username
        ]);
        
        $this->dispatch('close-edit-modal');
        $this->dispatch('refresh-table');
        $this->dispatch('show-message', message: 'Record edited successfully');

    }
}
