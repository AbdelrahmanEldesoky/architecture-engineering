<?php

namespace App\Http\Livewire\Managements\Forms;

use App\Http\Livewire\Basic\BasicForm;
use App\Models\CMS\Service;
use App\Models\Employee\Employee;
use App\Models\Hr\Department;
use App\Models\Tag;
use App\Models\User;
use Livewire\Component;

class Departments extends BasicForm
{
    public $name;
    public $manager_id;
    public $services;
    public $service;
    public $servicesStore = [];
    public $types = [];
    public $parent_id;
    public $type;
    public $managers = [];
    public $management_id;
    public $department_id;
    public $parents = [];
    public $parent_type;
    public $tags = [];
    public $tags_query = '';
    public $searchTags = [];
    public $department;

    protected $rules = [
        'name' => 'required',
        'type' => 'required',
        'management_id' => 'required',
        'parent_id' => 'required_if:type,sub',
        'manager_id' => 'required',
        'service' => 'nullable',

    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($management_id, $department_id = null, $parent_type = null)
    {

        $this->services = Service::website()->pluck('name', 'id')->toArray();


        $this->management_id = $management_id;
        if ($department_id != NULL && $parent_type == 2) {

            $this->parent_id = $department_id;
            $this->department_id = $department_id;
            $department_id = null;
        }


        $this->types = [
            "" => __('names.select'),
            "main" => __('names.main'),
            "sub" => __('names.sub')
        ];
        $this->parent_type = $parent_type;
        $this->parents = Department::where(['management_id' => $management_id, 'type' => 'main'])->pluck('name', 'id')->toArray();

        if ($department_id != null) {
            $this->department = Department::findOrFail($department_id);
            $this->name = $this->department->name;
            $this->parent_id = $this->department->parent_id;
            $this->type = $this->department->type;
            $this->manager_id = $this->department->manager_id;
            foreach ($this->department->departmentServices as $service)

                $this->tags[] = $service->service;
        }

        $depsHavePermission = User::whereHas('roles', function ($query) {
            $query->whereHas('permissions', function ($sub) {
                $sub->where('name', 'managers.departments');
            });
        })->select('id')->pluck('id')->toArray();

        $this->managers = Employee::whereIn('user_id', $depsHavePermission)->get();
    }

    public function addTag($tagId = null)
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
        $this->tags[$tag->id] = $tag->name;
        $this->tags_query = '';
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
    }

    public function save()
    {

        $validated['management_id'] = $this->management_id;
        if ($this->department != null) {
            $validated = $this->validate();
            $this->department->update($validated);
            if (count($this->tags)) {
                if ($this->department->departmentServices != null)
                    $this->department->departmentServices()->delete();
                foreach ($this->tags as $tag)
                    $this->department->departmentServices()->create(['service' => $tag]);

            }
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success', 'message' => __('message.create', ['model' => __('names.departments')])]);

        } else {
            $this->type = 'main';
            if ($this->parent_type == 1) {
                $this->type = 'main';
            } elseif ($this->parent_type == 2) {
                $this->type = 'sub';
            }
            $validated = $this->validate();
            $dapartment = Department::create($validated);
            if (count($this->tags))
                foreach ($this->tags as $tag)
                    $dapartment->departmentServices()->create(['service' => $tag]);
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'success', 'message' => __('message.create', ['model' => __('names.departments')])]);
            $this->reset('type', 'name', 'manager_id', 'services', 'service');
        }
    }

    public function render()
    {
        return view('livewire.managements.forms.departments', ['services' => Service::website()->pluck('name', 'id')->toArray()]);
    }


}
