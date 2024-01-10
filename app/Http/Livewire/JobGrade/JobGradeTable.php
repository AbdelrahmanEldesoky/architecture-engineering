<?php

namespace App\Http\Livewire\JobGrade;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\Hr\Grade;
use App\Models\Hr\JobGrade;
use App\Models\Hr\JobName;
use App\Models\Hr\JobType;
use App\Services\JobGradeService;
use App\Services\JobTypeService;
use Livewire\Component;

class JobGradeTable extends BasicTable
{

    protected $listeners = ['refreshJopGrades' => '$refresh','confirmDelete'];
    public $gradeId;
    public $typeJob;
    public function search()
    {
        $service = new JobGradeService();
        
        $jobGrades = $service->search($this->search)
        ->with('jobType','grade','jobType.employees')
        ->when($this->typeJob, function ($query) {
            $query->where('job_type_id', $this->typeJob);
        })
        ->when($this->gradeId, function ($query) {

            $query->where('grade_id', $this->gradeId);
        })
        ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
        ->paginate($this->perPage);
        
        return $jobGrades;
    }
    
    
    
    public function render()
    {
        $service = new JobTypeService();

        $jobTypes_count = $service->search($this->search)
        ->with(['jobNames' => fn ($query) => $query->withCount('employees')])
        ->withCount(['employees' => function ($query) {
            $query->selectRaw('COUNT(*)');
        }])
        ->get();
    
    
        
        $JobTypes = JobType::get();
        $jobGrades = $this->search();
        $grades = Grade::get();
 
        return view('livewire.job-grade.job-grade-table', compact('JobTypes','jobGrades','grades','jobTypes_count'));
        
        
    }

    public function create()
    {
        $this->emitTo('job-grade.job-grade-modal','createJobGrade',);
    }
    public function edit(int $jobGradeId)
    {
        $this->emitTo('job-grade.job-grade-modal','editJobGrade',$jobGradeId);
    }


    public function confirmDelete($id)
    {
        $jobName = JobGrade::find($id);
        if ($jobName->employees()->exists()) {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.job-grade'),'relation'=>__('names.employees')])]);
            return ;
        }else{
            $jobName->delete();
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success',  'message' =>__('message.deleted',['model'=>__('names.job-grade')])]);
        }
        
        $this->emitSelf('$refresh');
    }
}
