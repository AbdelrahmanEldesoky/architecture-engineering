<?php

namespace App\Http\Livewire\Managements\Tables;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Hr\Department;
use App\Models\Hr\Management;
use Livewire\Component;

class Managements extends BasicTable
{


    protected $listeners = ['confirmDelete'];

    public $branch_id;

    public function mount($branch_id ) {
        $this->branch_id = $branch_id;

    }
    public function render()
    {
        return view('livewire.managements.tables.managements',[
            'managements' => Management::where('branch_id',$this->branch_id)->withCount('departments'
            )->get()
        ]);
    }

    public function confirmDelete($id , $type)
    {
        if($type == 1)
        {
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
        }
        else
        {
            $department = Department::findOrFail($id);
            if(count($department->workers) >= 1) {
                $this->dispatchBrowserEvent('toastr',
                    ['type' => 'warning', 'message'
                    =>__('message.still-has',['model'=>__('names.department'),'relation'=>__('names.employees')])]);
                return ;
            } else{
                $department->delete();
                $this->dispatchBrowserEvent('toastr',
                    ['type' => 'success', 'message' =>__('message.deleted',['model'=>__('names.department')])]);
            }

            $this->emitSelf('$refresh');
        }

        // Refresh the component to reflect the updated data
        $this->emitSelf('$refresh');
    }
}
