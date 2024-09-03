<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class CreateUserModal extends Component
{
    public $firstname;
    public $lastname;
    public $username;

    public function render()
    {
        return view('livewire.create-user-modal');
    }

    public function createUser(){
        
        User::create([
            'Firstname' => $this->firstname,
            'Lastname' => $this->lastname,
            'Username' => $this->username
        ]);
        
        $this->dispatch('close-create-modal');
        $this->dispatch('refresh-table');
        $this->dispatch('show-message', message: 'User created successfully');
        $this->reset();
    }

    public function updatedLastname(){
        if($this->firstname){
            $this->username = strtolower($this->firstname . '.' . $this->lastname);
        }
    }

    public function updatedFirstname(){
        if($this->lastname){
            $this->username = strtolower($this->firstname . '.' . $this->lastname);
        }
    }
}
