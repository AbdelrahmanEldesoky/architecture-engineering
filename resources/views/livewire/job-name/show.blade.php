<div class="container-fluid ">
    <div class="row my-3 d-flex">
        <div class="col-md-12 col d-flex flex-row-reverse">
            <button class="btn btn-primary  mx-2 d-flex align-items-center" wire:click="search">بحث</button>
            <input  type="text" wire:model.lazy="search" class="form-control "
                placeholder="{{ __('names.search') }}">
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center">
    <h5> {{$job_name_title->name}}</h5>
    @if ($job_name_title->active)
        <span class="status active">
                {{ __('names.active') }}
            </span>
        @else
            <span class="status stopped">
                {{ __('names.in-active') }}
            </span>
        @endif
    </div>
    @include('admin.jobs.nav', ['class' => 'job_name'])
    <div>
        <div class="row section my-2">
            <div class="d-flex justify-content-between">
                @if (havePermissionTo('jobNames.create'))
                    <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                        wire:click="create()" data-bs-target="#JobNameModal">
                        <i class='bx bx-plus-circle bx-sm'></i>
    
                        {{ __('message.create', ['model' => __('names.job-name')]) }}
                    </button>
                @endif
                {{-- <button class="btn btn-primary mx-2 light d-flex align-items-center" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter" aria-expanded="false" aria-controls="filter">
                <i class='bx bx-filter-alt bx-sm'></i>
                {{ __('names.filter') }}
                </button> --}}
            </div>
            <div class="row collapse" id="filter" wire:ignore>
                <div class="col-4 ">
                    <x-input-label value="المسميات الوظيفية"></x-input-label>
                    <select class="form-select" wire:model="jobNameID">
                        <option value="">
                            {{ __('message.select', ['Model' => 'المسميات الوظيفية']) }}
                        </option>
                        @foreach ($jobNamesSelect as $jobName)
                                <option value="{{$jobName->id}}">{{$jobName->name}}</option>    
                        @endforeach
                    </select>
                </div>
                
          
         
            </div>
        <table class="table table-borderless">
            <thead>
                <tr>

                    <th>
                        نوع الموظفين
                    </th>
                    <th>
                        الاسم
            </th>
            {{-- <th>
                المسمي الوظيفي
            </th> --}}
            <th>
                الحالة
            </th>
            <th>
                الاعدادات
            </th>
        </tr>
            </thead>
            <tbody>
                @foreach ($jobNames as $jobName)
                    <tr>
                        <td>
                          {{$jobName->jobType->name}}
                        </td>
                        <td style="text-align: right; padding-right: 13%;">
                            <img src="{{ isset($employee) && !empty($employee->info) ? asset('/storage/' . $employee->attachments?->where('type', 'personal_photo')?->last()?->path) : url('assets/images/normal.svg') }}" alt="" style="float: right; margin-left: 10px;">
                            <span style="float: right;">{{ $jobName->employee->first_name . ' ' . $jobName->employee->second_name. ' ' . $jobName->employee->last_name}}</span>
                        </td>
                        {{-- <td>
                                {{$jobName->jobName->name}}
                        </td> --}}
                            <td>
                                <div class="{{ $jobName->employee->draft == 1 ? 'status stopped' : 'status active' }}">
                                    {{ $jobName->employee->draft == 0 ? __('names.active') : __('names.in-active') }}
                                </div>
                            </td>
                            <td>
                                <div class=" limit-2">
                                    @if (havePermissionTo('employees.edit'))
                                        <a href="{{ route('admin.custom.create', ['employee_id' => $jobName->employee->id, 'step' => '1']) }}" class="px-1">
                                            <i class='bx bxs-edit bx-sm text-gray'></i>
                                        </a>
                                    @endif
            
                                    @if (havePermissionTo('clients.delete'))
                        
                                    <a href="#" class="px-1" wire:click.prevent="delete({{ $jobName->employee->id }})">
                                            <i class='bx bx-trash bx-sm text-danger'></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        @endforeach
            </tbody>
        </table>
    </div>
    @if (havePermissionTo('jobNames.create'))
        <livewire:job-name.modal-form modal_id="JobNameModal" />
    @endif
</div>
