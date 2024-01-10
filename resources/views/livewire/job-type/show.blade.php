<div class="container-fluid ">
    <div class="row my-3 d-flex">
        <div class="col-md-12 col d-flex flex-row-reverse">
            <button class="btn btn-primary  mx-2 d-flex align-items-center" wire:click="search">بحث</button>
            <input  type="text" wire:model.lazy="search" class="form-control "
                placeholder="{{ __('names.search') }}">
        </div>
    </div>
    @include('admin.jobs.nav', ['class' => 'job_type'])
<div>
   
        <div class="row section my-2">
            <div class="d-flex justify-content-between">
                @if (havePermissionTo('jobTypes.create'))
                    <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                        wire:click="create()" data-bs-target="#JobTypeModal">
                        <i class='bx bx-plus-circle bx-sm'></i>
                        {{ __('message.create', ['model' => __('names.job-type')]) }}
    
                    </button>
                @endif
              
            </div>   
                  
        <table class="table table-borderless">
            <thead>
                <th class="text-end">
                </th>
                <th class="text-end">
                    الاسم
                </th>
            <th>
                نوع الموظفين
            </th>
            <th>
                المسمي الوظيفي
            </th>
            <th>
                الحالة
            </th>
            <th>
                الاعدادات
            </th>
            </thead>
            <tbody>
                @foreach ($jobNames as $jobName)
                    <tr>
                        <td>
                            <img src="{{ isset($employee) && !empty($employee->info) ? asset('/storage/' . $employee->attachments?->where('type', 'personal_photo')?->last()?->path) : url('assets/images/normal.svg') }}" alt="">
                        </td>
                        <td class="text-end">
                            <span>{{ $jobName->employee->first_name . ' ' . $jobName->employee->second_name. ' ' . $jobName->employee->last_name}}</span>
                        </td>
                        <td>
                          {{$jobName->jobType->name}}
                        </td >
                            <td>
                                {{$jobName->jobName->name}}
                            </td>
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
    @if (havePermissionTo('jobTypes.create'))
    <livewire:job-type.modal-form modal_id="JobTypeModal" />
@endif
</div>
