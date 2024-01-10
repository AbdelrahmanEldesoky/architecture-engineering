<?php

namespace App\Http\Livewire\JobType;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Hr\JobName;
use App\Models\Hr\JobType;
use App\Services\JobTypeService;
use Livewire\Component;

class Table extends BasicTable
{
    public $jobType;
    public $jobName;
    public $count;
    public $search;

    protected $listeners = ['refreshJopTypes' => '$refresh','confirmDelete'];

    public function search()
    {
     $service = new JobTypeService();

    $jobTypes = $service->search($this->search)
    ->when($this->jobType, function ($query) {

        $query->where('id', $this->jobType);
    })
    ->with(['jobNames' => fn ($query) => $query->withCount('employees')])
    // ->whereHas("jobNames", fn ($q) => $q->when($this->jobName, function ($query) {
    //     $query->where('id', $this->jobName);
    // }))
    ->withCount(['employees' => function ($query) {
        $query->selectRaw('COUNT(*)');
    }])
    ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
    ->paginate($this->perPage);

    return $jobTypes;

    }

    public function render()
    {
        $jobs = JobType::get();
        $jobTypes = $this->search();
        $jobNames = JobName::get();
        $counts = JobType::select('id', 'name')->withCount('employees')->groupBy('id', 'name')->get();
        return view('livewire.job-type.table',compact('jobTypes','jobs','jobNames','counts'));
    }

    public function create()
    {
        $this->emitTo('job-type.modal-form','createJobType',);
    }
    public function edit(int $jobTypeId)
    {
        $this->emitTo('job-type.modal-form','editJobType',$jobTypeId);
    }

    public function confirmDelete($id)
    {
        $jobType = JobType::find($id);
        if ($jobType->jobNames()->exists()) {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.job-type'),'relation'=>__('names.job-name')])]);
            return ;
        } elseif($jobType->jobGrades()->exists()) {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.job-type'),'relation'=>__('names.job-grade')])]);
            return ;
        }else{
            $jobType->delete();
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success',  'message' =>__('message.deleted',['model'=>__('names.job-type')])]);
        }
        // Refresh the component to reflect the updated data
        $this->emitSelf('$refresh');
    }
}
