<?php

namespace App\Livewire;

use App\Models\Directory;
use Livewire\Attributes\On;
use Livewire\Component;

class EditRecord extends Component
{
    public $id;
    public $employeename;
    public $department;
    public $unit;
    public $position;
    public $extension;
    public $floor;

    public function render()
    {
        return view('livewire.edit-record');
    }
    
    #[On('show-edit-modal')]
    public function displayModal($id)
    {
        // dd($id);
        $this->resetValidation();
        $this->id = $id;
        $user = Directory::find($id);
        $this->employeename = $user->employee;
        $this->department = $user->department;
        $this->unit = $user->groupname;
        $this->position = $user->extname;
        $this->extension = $user->extno;
        $this->floor = $user->location;
        
        $this->dispatch('display-edit-modal');

    }

    public function editRecord(){

        // dd($this->employeename);
        if(Directory::extExists(trim($this->extension))){ //If extension exists, display error message
            $this->addError('extension', 'This extension already exists');
        }else{
            Directory::where('id', $this->id)->update([
                'employee' => $this->employeename,
                'department' => $this->department,
                'groupname' => $this->unit,
                'extname' => $this->position,
                'extno' => $this->extension,
                'location' => $this->floor,
            ]);
            
            $this->resetValidation();
            $this->dispatch('close-edit-modal');
            $this->dispatch('refresh-table');
            $this->dispatch('show-message', message: 'Record edited successfully');
        }
        

    }
}
