<?php

namespace App\Http\Livewire\Managements\Forms;

use App\Http\Livewire\Basic\BasicForm;
use App\Models\Employee\Employee;
use App\Models\Hr\Department;
use App\Models\Hr\Management as HrManagement;
use App\Models\Tag;
use App\Services\BranchService;
use App\Models\User;

class Management extends BasicForm
{
    public $branch_id;
    public $branches = [];
    public $type;
    public $management_parents = [];
    public $parent_id;
    public $types = [];
    public $name;
    public $manager_id;
    public $parent_type;
    private $branchService;
    public $managers = [];
    public $departments = [];
    public $management;
    public $deps_managers = [];
    public $tags = [];
    public $tags_query = '';
    public $searchTags = [];

    protected $rules = [
        'name' => 'required',
        'branch_id' => 'required|exists:branches,id',
        'type' => 'required',
        'parent_id' => 'required_if:type,sub',
        'departments.*.name' => 'required',
        'departments.*.manager_id' => 'required',
        'manager_id' => 'required',
    ];

    public function mount($branch_id = null, $management_id, $parent_type = null)
    {
        $this->types = [
            "" => __('names.select'),
            "main" => __('names.main'),
            "sub" => __('names.sub')
        ];
        $this->branchService = new BranchService();
        if ($branch_id != null) {
            $this->branch_id = $branch_id;
        }
        if ($management_id != NULL && $parent_type == 2) {
            $this->parent_id = $management_id;
            $management_id = null;
        }
        if ($management_id != null) {
            $this->management = $management_id;
        }
        $this->parent_type = $parent_type;

        if ($management_id != null) {
            $management = HrManagement::where('id', $management_id)->with('departments')->first();
            $this->management = $management;
            $this->name = $management->name;
            $this->type = $management->type;
            $this->branch_id = $management->branch_id;
            $this->parent_id = $management->parent_id;
            $this->manager_id = $management->manager_id;
            if (count($management->departments) >= 1) {
                foreach ($management->departments as $department) {
                    foreach ($department->departmentServices as $service)
                    {
                        $this->tags[]=$service->service;

                    }

                    $this->departments[] = ['id' => $department->id, 'name' => $department->name, 'manager_id' => $department->manager_id, 'tags' => $this->tags
                    ];
                    $this->tags=[];
                }
            }
        }

        $this->branches = $this->branchService->fetchAsArray();
        $this->management_parents = HrManagement::where('branch_id', $this->branch_id)->pluck('name', 'id')->toArray();


        $usersHavePermission = User::whereHas('roles', function ($query) {
            $query->whereHas('permissions', function ($sub) {
                $sub->where('name', 'managers.managements');
            });
        })->select('id')->pluck('id')->toArray();

        $this->managers = Employee::whereIn('user_id', $usersHavePermission)->get();

        $depsHavePermission = User::whereHas('roles', function ($query) {
            $query->whereHas('permissions', function ($sub) {
                $sub->where('name', 'managers.departments');
            });
        })->select('id')->pluck('id')->toArray();

        $this->deps_managers = Employee::whereIn('user_id', $depsHavePermission)->get();
    }

    public function render()
    {
        return view('livewire.managements.forms.management');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function addTag($key ,$tagId = null)
    {
        if (!$tagId) {
            $tag = Tag::create([
                'name' => $this->tags_query,
                'type' => 'job_grades'
            ]);
        } else {
            $tag = Tag::findOrFail($tagId);
        }
        if (isset($this->tags[$tagId])) {
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'error', 'message' => 'Certificate Already Selected!']);
            return back();
        }
//        $this->tags->push($tag);
//        $this->tags[$tag->id] = $tag->name;
        $this->departments[$key]['tags'][$tag->id]=$tag->name;
        $this->tags_query = '';
    }

    public function removeTag($key ,$index)
    {
        unset( $this->departments[$key]['tags'][$index]);
    }


    public function save()
    {

        if ($this->management != null) {

            $validate = $this->validate();
            $this->management->update($validate);
            // update departments

            foreach ($this->departments as $department) {
                $service = $department['tags'];

                if (array_key_exists('id', $department)) {

                    $old = Department::where('id', $department['id'])->first();
                    $old->update($department);
                    if ($old->departmentServices != null)
                        $old->departmentServices()->delete();
                    if ($service) {

                        foreach ($service as $tag)
                        $old->departmentServices()->create(['service' => $tag]);

                    }
                } else {
                    $department['management_id'] = $this->management->id;
                    $department['type'] = "main";
                    $dep=Department::create($department);
                    foreach ($service as $tag)
                    $dep->departmentServices()->create(['service' => $tag]);

                }

            }
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success', 'message' => __('message.updated', ['model' => __('names.managements')])]);

        } else {


            $this->type = 'main';
            if ($this->parent_type == 1)
                $this->type = 'main';
            else if ($this->parent_type == 2)
                $this->type = 'sub';
            $validate = $this->validate();


            $management = new HrManagement($validate);


            $management->save();


            foreach ($this->departments as $department) {
                $department['management_id'] = $management->id;
                $department['type'] = "main";
                $department['manager_id'] = null;
                $dep=Department::create($department);
                foreach ($department['tags'] as $tag )
                    $dep->departmentServices()->create(['service' => $tag]);
            }

            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success', 'message' => __('message.created', ['model' => __('names.managements')])]);

            $this->reset('departments',  'name', 'type');
        }
    }

    public function addDepartment()
    {
        $this->departments[] = ['name' => '', 'manager_id' => '', 'tags' => []];
    }


}
