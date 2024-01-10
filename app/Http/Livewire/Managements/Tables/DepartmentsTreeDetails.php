<?php

namespace App\Http\Livewire\Managements\Tables;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use Livewire\Component;

class DepartmentsTreeDetails extends BasicTable
{
    protected $listeners = ['confirmDelete'];

    public $management_id;
    public $department_id;


    public function mount($management_id,$department_id) {
        $this->department_id = $department_id;
        $this->management_id = $management_id;

    }

    public function render()
    {
        return view('livewire.managements.tables.departments-tree-details',[
            'departments' => $this->department_id!=null ? Department::where('parent_id',$this->department_id)->get() : Department::where('management_id',$this->management_id)->get()
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
