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
    public $record;

    public function render()
    {
        return view('livewire.edit-record');
    }
    
    #[On('show-edit-modal')]
    public function displayModal($id)
    {
        // dd($id);
        $this->resetValidation();
        $this->record = Directory::find($id);
        $this->employeename = $this->record->employee;
        $this->department = $this->record->department;
        $this->unit = $this->record->groupname;
        $this->position = $this->record->extname;
        $this->extension = $this->record->extno;
        $this->floor = $this->record->location;
        
        $this->dispatch('display-edit-modal');

    }

    public function editRecord(){
        // dd($this->record->extno);
        $this->validate([
            'extension' => 'unique:directory,extno,' . $this->record->id,
        ]);

        Directory::where('id', $this->record->id)->update([
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
