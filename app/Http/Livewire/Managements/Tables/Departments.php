<?php

namespace App\Http\Livewire\Managements\Tables;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use Livewire\Component;

class Departments extends BasicTable
{
    protected $listeners = ['confirmDelete'];

    public $management_id;
    public $branch_id;


    public function mount($branch_id,$management_id,$department_id=null) {
        $this->management_id = $management_id;
        $this->branch_id = $branch_id;

    }

    public function render()
    {
        return view('livewire.managements.tables.departments',[
            'managements' => Management::where('parent_id',$this->management_id)->withCount('departments' )->get()
        ]);
    }

     public function confirmDelete($id) {

         $management = Management::find($id);
         if ($management->departments()->exists()) {
             $this->dispatchBrowserEvent('toastr',
                 ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.management'),'relation'=>__('names.departments')])]);
             return ;
         } elseif($management->scopeNumberOfEmps() >= 1) {
             $this->dispatchBrowserEvent('toastr',
                 ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.management'),'relation'=>__('names.employees')])]);
             return ;
         } elseif($management->childrens()->exists()) {
             $this->dispatchBrowserEvent('toastr',
                 ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.management'),'relation'=>__('names.childrens')])]);
             return ;
         }else{
             $management->delete();
             $this->dispatchBrowserEvent('toastr',
                 ['type' => 'success',  'message' =>__('message.deleted',['model'=>__('names.management')])]);
         }
         // Refresh the component to reflect the updated data
         $this->emitSelf('$refresh');

    }
}
