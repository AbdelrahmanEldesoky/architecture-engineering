<?php

namespace App\Http\Livewire\JobName;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Hr\JobName;
use App\Models\Hr\JobType;
use App\Services\JobNameService;
use Livewire\Component;

class Table extends BasicTable
{
    public $typeJob;
    public $jobName;
    public $count;
    public $search;
    protected $listeners = ['refreshJopNames' => '$refresh','confirmDelete'];
    public function search()
    {
        $service = new JobNameService();

        $jobNames = $service->search($this->search)
                ->with('jobType')
                ->when($this->jobName,function ($query){
                    $query->where('id',$this->jobName);
                })
                ->whereHas("jobType", fn($q) => $q->when($this->typeJob,function ($query){
                    $query->where('id',$this->typeJob);
                }))
                // ->whereHas('employees', fn($q) => $q->when($this->count,function ($query){
                //     $query->havingRaw('COUNT(*) = ?', [$this->count]);
                // }))
                ->withCount(['employees' => function ($query) {
                    $query->selectRaw('COUNT(*)');
                }])
                ->withCount('employees')
                ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
                ->paginate($this->perPage);
        return $jobNames;
    }
    public function render()
    {
        $jobNames = $this->search();
        $jobNamesSelect = JobName::get();
        $JobTypes = JobType::get();
        return view('livewire.job-name.table',compact('jobNames','jobNamesSelect','JobTypes'));
    }

    public function create()
    {
        $this->emitTo('job-name.modal-form','createJobName',);
    }
    public function edit(int $jobNameId)
    {
        $this->emitTo('job-name.modal-form','editJobName',$jobNameId);
    }


    public function confirmDelete($id)
    {
        $jobName = JobName::find($id);
        if ($jobName->employees()->exists()) {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.job-name'),'relation'=>__('names.employee')])]);
            return ;
        }else{
            $jobName->delete();
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success',  'message' =>__('message.deleted',['model'=>__('names.job-name')])]);
        }
        // Perform the deletion action



        // Refresh the component to reflect the updated data

        $this->emitSelf('$refresh');
    }
}
