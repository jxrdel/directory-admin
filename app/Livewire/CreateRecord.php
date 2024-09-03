<?php

namespace App\Livewire;

use App\Models\Directory;
use Livewire\Component;

class CreateRecord extends Component
{
    public $employeename;
    public $department;
    public $unit;
    public $position;
    public $extension;
    public $floor;

    public function render()
    {
        return view('livewire.create-record');
    }

    public function createRecord(){
        // dd($this->floor);
        if(Directory::extExists(trim($this->extension))){ //If extension exists, display error message
            $this->addError('extension', 'This extension already exists');
        }else{
            Directory::create([
                'employee' => $this->employeename,
                'department' => $this->department,
                'groupname' => $this->unit,
                'extname' => $this->position,
                'extno' => $this->extension,
                'location' => $this->floor,
            ]);
            
            $this->employeename = null;
            $this->department = null;
            $this->unit = null;
            $this->position = null;
            $this->extension = null;
            $this->floor = null;
            $this->resetValidation();
            $this->dispatch('close-create-modal');
            $this->dispatch('refresh-table');
            $this->dispatch('show-message', message: 'Record entered successfully');
        }
    }
}
