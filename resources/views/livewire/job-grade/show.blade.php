<div class="container-fluid ">
    <div class="row my-3 d-flex">
        <div class="col-md-12 col d-flex flex-row-reverse">
            <button class="btn btn-primary  mx-2 d-flex align-items-center" wire:click="search">بحث</button>
            <input  type="text" wire:model.lazy="search" class="form-control "
                placeholder="{{ __('names.search') }}">
        </div>
    </div>
    @include('admin.jobs.nav', ['class' => 'job_grade'])
    <div>
        <div class="row section my-2">
            <div class="d-flex justify-content-between">
                
                @if (havePermissionTo('jobGrades.create'))
                <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                    wire:click="create()" data-bs-target="#JobGradeModal">
                    <i class='bx bx-plus-circle bx-sm'></i>
                    {{ __('message.create', ['model' => __('names.job-grade')]) }}
                </button>
                @endif
                 {{-- <button class="btn btn-primary mx-2 light d-flex align-items-center" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter" aria-expanded="false" aria-controls="filter">
                <i class='bx bx-filter-alt bx-sm'></i>
                {{ __('names.filter') }}
                </button>  --}}
            </div>
            <div class="row collapse" id="filter" wire:ignore>
                <div class="col-3 ">
                    <x-input-label value="نوع الوظيفة"></x-input-label>
                    <select class="form-select" wire:model="typeJob">
                        <option value="">
                            {{ __('message.select', ['Model' => 'نوع الوظيفة']) }}
                        </option>
                        @foreach ($JobTypes as $JobType)
                                <option value="{{$JobType->id}}">{{$JobType->name}}</option>    
                        @endforeach
                    </select>
                </div>
                
          
         
            </div>
        <table class="table table-borderless">
            <thead>
                <tr>
                <th>
                    درجة الوظيفة
                </th>
                <th>الاسم</th>
                <th>
                    نوع الموظفين
                </th>
                <th>
                    الحالة
                </th>
                <th>
                    الاعدادات
                </th>
            </tr>
            </thead>
            <tbody>
                @foreach ($jobGrades as $jobGrade)
                    <tr>
                        <td>
                          {{$jobGrade->jobType->jobGrades[0]->grade->name}}
                        </td>
            
                        <td style="text-align: right; padding-right: 13%;">
                            <img src="{{ isset($employee) && !empty($employee->info) ? asset('/storage/' . $employee->attachments?->where('type', 'personal_photo')?->last()?->path) : url('assets/images/normal.svg') }}" alt="" style="float: right;">
                            <span style="float: right;">{{ $jobGrade->employee->first_name . ' ' . $jobGrade->employee->second_name. ' ' . $jobGrade->employee->last_name}}</span>
                        </td>
                        <td>
                                {{$jobGrade->jobType->name}}
                        </td>
                            <td>
                                <div class="{{ $jobGrade->employee->draft == 1 ? 'status stopped' : 'status active' }}">
                                    {{ $jobGrade->employee->draft == 0 ? __('names.active') : __('names.in-active') }}
                                </div>
                            </td>
                            <td>
                                <div class=" limit-2">
                                    @if (havePermissionTo('employees.edit'))
                                        <a href="{{ route('admin.custom.create', ['employee_id' => $jobGrade->employee->id, 'step' => '1']) }}" class="px-1">
                                            <i class='bx bxs-edit bx-sm text-gray'></i>
                                        </a>
                                    @endif
            
                                    @if (havePermissionTo('clients.delete'))
                        
                                    <a href="#" class="px-1" wire:click.prevent="delete({{ $jobGrade->employee->id }})">
                                            <i class='bx bx-trash bx-sm text-danger'></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        @endforeach
            </tbody>
        </table>
    </div>
    @if (havePermissionTo('jobGrades.create'))
        <livewire:job-grade.job-grade-modal modal_id="JobGradeModal" />
    @endif
</div>
