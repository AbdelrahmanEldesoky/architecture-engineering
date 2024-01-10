<?php

namespace App\Http\Livewire\Employee;

use App\Http\Livewire\Basic\BasicForm;
use App\Http\Livewire\Basic\Modal;
use App\Models\Employee\EmployeeAcademic;
use App\Models\Employee\EmployeeCourse;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeExperience;
use App\Models\Hr\University;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use App\Models\Hr\JobName;

class StepTwo extends BasicForm
{

    use WithFileUploads;

    protected $rules = [
        'academic.*.id' => 'nullable',
        'academic.*.university_id' => 'required|exists:universities,id',
        'academic.*.qualification' => 'required',
        'academic.*.specialization' => 'required',
        'academic.*.qualification_date'=>'date|required',
        'academic.*.qualification_photo'=>'nullable',
    ];
    public $academic = [];
    public $employee_id;
    public $qualification_photo = null;
    public $univs = [];
    public $course , $experince = null;
    public $experinces = [];
    public $courses = [];
    public $isloading = false;
    public $employee;
    public $updated = false;
    public $jobNames = [];
    public function mount($employee_id = null) {
        $this->employee = Employee::whereId($employee_id)->first();
        if(empty($this->employee)) {
            abort(404);
        }
        $this->employee_id = $employee_id;
        $this->univs = University::active()->latest()->get(['id','name','name_ar']);

        $acadmic = EmployeeAcademic::where('employee_id', $this->employee_id)->select('university_id','qualification','specialization','qualification_date','qualification_photo','id')->get();

        if(count($acadmic) >= 1) {
            foreach($acadmic as $line) {
                 $this->academic[] = ['id' => $line->id,'university_id' => $line->university_id, 'qualification' => $line->qualification, 'specialization' => $line->specialization,'qualification_date' => $line->qualification_date,'qualification_photo' => $line->qualification_photo];
            }
        } else {
            array_push($this->academic,['employee_id' => $this->employee_id ,'university_id' => null, 'qualification' =>
            null, 'specialization' => null,'qualification_date' => null,'qualification_photo' => null]) ;
        }
        $this->updateExperinces();
        $this->updateCourses();

        $this->jobNames = JobName::pluck('name','id')->toArray();
    }
    public function render()
    {
        return view('livewire.employee.step-two');
    }


    public function updateExperinces() {
       
        $this->experinces = [];
         
         $exs = EmployeeExperience::where('employee_id',$this->employee_id)->with('jobName')->get();
        if(count($exs) >= 1)
        {
               foreach($exs as $exp) {
                    $this->experinces[] = [
                        'id' => $exp->id,
                        'company_name' => $exp->company_name,
                        'job_name_id' => $exp->job_name_id,
                        'job_name' => $exp->jobName?->name,
                        'from_date' => $exp->from_date ,
                        'to_date' => $exp->to_date,
                         'no_of_years' => $exp->no_of_years,
                         'photo' => $exp->photo
                        ];
               }
        }
    }

    public function updateCourses() {
         $this->courses = EmployeeCourse::where('employee_id',$this->employee_id)->get();
    }

    public function save() {

        
        if($this->employee_id)


        $validated = $this->validate();

        $EmpService = new EmployeeService();
        foreach($validated['academic'] as $key=>$line) {
            if(array_key_exists('id', $line)) {
                $line['employee_id'] = $this->employee_id;
                  $stored = $EmpService->storeAcademice($line, $line['id']);

                  if(!empty($line['qualification_photo']) && gettype($line['qualification_photo']) != "string") {
                    $uploadedImage =
                    uploadFile($line['qualification_photo'],"employees",$this->employee_id,"acadmic_".$key);
                    $stored->qualification_photo = $uploadedImage;
                  }
                 $stored->save();
            } else {


                $line['employee_id'] = $this->employee_id;
                $stored = $EmpService->storeAcademice($line);
                if(!empty($line['qualification_photo']) && array_key_exists("qualification_photo", $line) &&
                gettype($line['qualification_photo']) != "string")
                {
                    $uploadedImage =
                    uploadFile($line['qualification_photo'],"employees",$this->employee_id,"acadmic_".$key);
                    $stored->qualification_photo = $uploadedImage;
                    $stored->save();
                }
            }
        }

          $this->dispatchBrowserEvent('toastr',
                ['type' => 'success',  'message' => __('message.saved-success')]);
        return redirect()->route('admin.custom.create',['employee_id' => $this->employee_id, 'step' => 3]);
    }

    public function addMoreGrades() {
        $this->academic[] = ['id' => '' ,'university_id' => '', 'qualification' => '', 'specialization' => '','qualification_date' => '','qualification_photo' => ''];
    }

    public function addExperince() {
        
        if(empty($this->experince)) {
            $this->dispatchBrowserEvent('toastr',
            ['type' => 'error', 'message' => __('names.all-fields-required')]);
            return;
        }

       if(! array_key_exists("id", $this->experince) || $this->experince['id'] != '' || $this->experince['id'] != null) {
         $validated = $this->validate([
            'experince.id' => 'nullable',
            'experince.company_name' => 'string',
            'experince.job_name_id' => 'required',
            'experince.from_date' => 'date',
            'experince.to_date' => 'date|after:experince.from_date',
            'experince.no_of_years' => 'numeric',
            'experince.photo' =>'nullable',
        ]);
       } else {
         $validated = $this->validate([
            'experince.id' => 'nullable',
            'experince.company_name' => 'string',
            'experince.job_name_id' => 'required',
            'experince.from_date' => 'date',
            'experince.to_date' => 'date|after:experince.from_date',
            'experince.no_of_years' => 'numeric',
            'experince.photo' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);
       }


        $EmpService = new EmployeeService();
        $validated['experince']['employee_id'] = $this->employee_id;
     
            if(array_key_exists('photo',$validated['experince']) && gettype($validated['experince']['photo']) != "string" && $validated['experince']['photo'] != null) {
                $validated['experince']['photo'] = uploadFile($validated['experince']['photo'],'Employee', $this->employee_id, 'Experince');
           }
   
        
        $EmpService->storeExperinces($validated['experince']);

        $this->dispatchBrowserEvent('toastr',
                ['type' => 'success',  'message' =>  __('message.saved-success')]);

        $this->close("addExperince");
        $this->updateExperinces();
    }

    public function addCourse() {
         if(empty($this->course)) {
            $this->dispatchBrowserEvent('toastr',
            ['type' => 'error', 'message' => __('names.all-fields-required')]);
            return;
         }

         if( ! array_key_exists("id", $this->course) || $this->course['id'] == '' || $this->course['id'] == null) {

            $validated = $this->validate([
                    'course.name' => 'required|string',
                    'course.course_from' => 'required',
                    'course.duration' => 'required',
                    'course.date' => 'required|date',
                    'course.certificate_photo' => 'nullable|image|mimes:jpeg,png,jpg'
                ]);


        } else {

             $validated = $this->validate([
                    'course.id' => 'nullable',
                    'course.name' => 'required|string',
                    'course.course_from' => 'required',
                    'course.duration' => 'required',
                    'course.date' => 'required|date',
                    'course.certificate_photo' => 'nullable'
                ]);
        }

        $EmpService = new EmployeeService();
        $validated['course']['employee_id'] = $this->employee_id;
           
        if(array_key_exists('certificate_photo',$validated['course']) && gettype($validated['course']['certificate_photo']) != "string" &&$validated['course']['certificate_photo']!=null) {
            $validated['course']['certificate_photo'] = uploadFile($validated['course']['certificate_photo'],'Employee', $this->employee_id, 'Experince');
        }
 
       $course = $EmpService->storeCources($validated['course'], array_key_exists("id", $validated["course"]) ?
       $validated['course']['id'] : null);

        $this->dispatchBrowserEvent('toastr',
                ['type' => 'success',  'message' =>  __('message.saved-success')]);

        $this->close("addCourse");
        $this->updateCourses();
    }

    public function close($modal_id)
    {
        return redirect()->route('admin.custom.create',['employee_id' => $this->employee_id, 'step' => 2]);
        // $this->dispatchBrowserEvent('closeModal',['id'=>$modal_id]);
    }


    public function open($modal_id, $reset = false)
    {
        if($reset) {
            $this->course = $this->experince = null;
        }
        $this->dispatchBrowserEvent('openModal',['id'=>$modal_id]);
    }

    public function deleteGrades($id = null, $index = null) {
        if($id != null) {
            $empService = new EmployeeService();
            $empService->destroyAcadmic($id);
             unset( $this->academic[$index] );
             $this->dispatchBrowserEvent('toastr',
                ['type' => 'success', 'message' => __('message.deleted',['model' => __('names.academic-info')])]);
            return;
        }
        if($index != null) {
             unset( $this->academic[$index] );
              $this->dispatchBrowserEvent('toastr',
                ['type' => 'success', 'message' => __('message.deleted',['model' => __('names.academic-info')])]);
              return;
        }

    }

    public function deleteExperince($id) {
       $empService = new EmployeeService();
       $empService->destroyExperince($id);
       $this->updateExperinces();
        $this->dispatchBrowserEvent('toastr',['type' => 'success',  'message' => 'Deleted Successfully']);
    }

    public function destroyCourse($id) {
       $empService = new EmployeeService();
       $empService->destroyCourse($id);
       $this->updateCourses();
       $this->dispatchBrowserEvent('toastr',['type' => 'success',  'message' => __('message.deleted',['model' => __('names.academic-info')])]);
    }
    public function EditExperince($index) {
        $this->experince = $this->experinces[$index];
    }

    public function EditCourse($index) {
        $this->course = [
         'id' => $this->courses[$index]['id'],
         'employee_id' => $this->courses[$index]['employee_id'],
         'name' => $this->courses[$index]['name'],
         'course_from' => $this->courses[$index]['course_from'],
         'duration' => $this->courses[$index]['duration'],
         'date' => $this->courses[$index]['date'],
         'certificate_photo' => $this->courses[$index]['certificate_photo'],
        ];
    }
}
