<?php

namespace App\Http\Livewire\Managements;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use Livewire\Component;

class DepartmentTree  extends BasicTable
{
    protected $listeners = ['confirmDelete'];

    public $management_id;
    public $branch_id;


    public function mount($branch_id,$management_id) {
        $this->management_id = $management_id;
        $this->branch_id = $branch_id;

    }

    public function render()
    {


        return view('livewire.managements.department-tree',[
            'departments' => Department::where('management_id',$this->management_id)->get()
        ]);
    }

    public function confirmDelete($id) {

        $department = Department::findOrFail($id);
        if(count($department->workers) >= 1) {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'warning', 'message'
                =>__('message.still-has',['model'=>__('names.department'),'relation'=>__('names.employees')])]);
            return ;
        }
        elseif(count($department->childrens)>=1)
            {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'warning', 'message'
                =>__('message.still-has',['model'=>__('names.department'),'relation'=>__('names.department')])]);
            return ;
        }
         else{
            $department->delete();
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success', 'message' =>__('message.deleted',['model'=>__('names.department')])]);
        }

        $this->emitSelf('$refresh');

    }
}
