<?php

namespace App\Http\Livewire\Settings\Shifts;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Employee\Employee;
use App\Models\Hr\Branch;
use App\Models\Hr\Shift;
use Livewire\Component;

class Table extends BasicTable
{
    protected $listeners = ['confirmDelete'];

    public $shifts;


    public function render()
    {
        $this->shifts = Shift::where('type','general')->latest()->get();
        return view('livewire.settings.shifts.table');
    }

    public function confirmDelete($id)
    {
        $shift  = Shift::whereId($id)->first();

        if($shift) {

            if(Employee::where('shift_id', $shift->id)->first()) {
                $this->dispatchBrowserEvent('toastr',
                ['type' => 'error',  'message' =>__('message.still-has',['model'=> __('names.shift'),'relation'=> __('names.employee')
                ])]);

                return 0;
            }


            if(Branch::where('shift_id', $shift->id)->first()) {
                $this->dispatchBrowserEvent('toastr',
                    ['type' => 'error',  'message' =>__('message.still-has',['model'=> __('names.shift'),'relation'=> __('names.branch')
                    ])]);

                return 0;
            }

        $shift->delete();
         $this->dispatchBrowserEvent('toastr',
                ['type' => 'success',  'message' =>__('message.deleted',['model'=> __('names.shift')])]);

        } else{

         $this->dispatchBrowserEvent('toastr',
                ['type' => 'danger',  'message' =>__('message.not-found',['model'=>'shift'])]);

        }
      return ;

    }
}
